<?php

/**
 * Extended  MySQL Driver Class.
 * @pupose        Perform audit trail operation on mysql database
 *
 * @filesource    \app\libraries\MY_DB_mysql_driver.php
 * @package        omegasoft
 * @subpackage    omegasoft.libraries.MY_DB_mysql_driver
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury, Mahbub Tito $
 * @lastmodified $Date: 2011-03-07 $
 */
class MY_DB_mysqli_driver extends CI_DB_mysqli_driver
//class MY_DB_mysqli_driver extends CI_DB_driver
{

    //table list for which audit trail will not be done
    private $audit_skip_tables = [
        'user_audit_trails' => TRUE,
        'user_login_attempts' => TRUE,
        'user_login_profiles' => TRUE
    ];
    private $debug = FALSE;
    private $CI = null;
    public $ar_where;

    function __construct($params)
    {
        parent::__construct($params);
        log_message('debug', 'Extended DB driver class instantiated!');
        $this->CI =& get_instance();
//        $this->session_table = $this->CI->config->item('sess_table_name');
//        $this->audit_skip_tables[$this->session_table] = TRUE;
    }

    //overriding the insert method
    function insert($table = '', $set = NULL, $escape = NULL)
    {
        //perform the operation
        $status = parent::insert($table, $set);
        //add audit log
        $this->_add_audit_log($status, 'insert', $table, $set);
        //return the operation status
        return $status;
    }

    //overriding the insert batch method
    function insert_batch($table = '', $set = NULL, $escape = NULL, $batch_size = 100)
    {
        //perform the operation
        $status = parent::insert_batch($table, $set);
        //add audit log
        $this->_add_audit_log($status, 'insert', $table, $set);
        //return the operation status
        return $status;
    }

    //overriding the update method
    function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
    {

        //getting the old values
        $old_value = null;
        //storing the initial conditions whatever is set
        $ar_where = $this->ar_where;

        $audit_enabled = TRUE;
        if (!empty($set)) {
            if (isset($set['skip_audit_trail'])) {
                $audit_enabled = FALSE;
                unset($set['skip_audit_trail']);
            }
        }

        if ($this->is_auditable($table) && $audit_enabled) {

            if (empty($where))
                $query = $this->get($table);
            else
                $query = $this->get_where($table, $where);

            $old_value = $query->result_array();
            //print_r($old_value);
            $query->free_result();
        }
        //restoring the conditions after executing the query
        $this->ar_where = $ar_where;

        //perform the operation
        $status = parent::update($table, $set, $where, $limit);

        if ($this->is_auditable($table) && $audit_enabled) {
            //add audit log
            $this->_add_audit_log($status, 'update', $table, $set, $old_value);
            //return the operation status
        }
        return $status;
    }

    //overriding the delete method
    function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE, $audit_enabled = TRUE)
    {

        //getting the old values
        $old_value = null;
        //storing the initial conditions whatever is set
        if (!$this->is_auditable($table)) {
            $audit_enabled = FALSE;
        }

        if ($audit_enabled) {
            $ar_where = $this->ar_where;
            if ($this->is_auditable($table)) {
                if (empty($where))
                    $query = $this->get($table);
                else
                    $query = $this->get_where($table, $where);

                $old_value = $query->result_array();

                $query->free_result();
            }
            //restoring the conditions after executing the query
            $this->ar_where = $ar_where;
        }
        //perform the operation
        $status = parent::delete($table, $where, $limit, $reset_data);
        //add audit log
        if ($audit_enabled) {
            $this->_add_audit_log($status, 'delete', $table, $where, $old_value);
        }
        //return the operation status
        return $status;
    }

    //adding audit trail functionality
    function _add_audit_log($status, $operation, $table, $set = NULL, $previous_values = NULL)
    {
        //if db operation failed, return false;
        if (!$status) return false;

        // if we skip audit trail insert
        if ($operation === 'insert' && IS_SKIP_INSERT_QUERY_ON_AUDIT_LOG === FALSE) return false;

        //If not for audit then return true
        if (!$this->is_auditable($table)) {
            if ($this->debug) echo("[Audit Trail] Skipped for $table; ");
            return true;
        }

        $reference_id = null;

        if ($operation == 'update') {
            if (isset($previous_values[0]['id']))
                $reference_id = $previous_values[0]['id'];
            $this->diff_on_update($previous_values, $set);
            //data has not been update, so return
            if (empty($previous_values) && empty($set))
                return TRUE;
        }

        $new_value = json_encode($set);
        //If edit or delete operation, adding the old values
        $old_value = null;
        if (!empty($previous_values)) {
            $old_value = json_encode($previous_values);
        }

        $user = $this->CI->session->userdata('data');

        if (count($previous_values) == 1) {
            if (isset($previous_values[0]['id']))
                $reference_id = $previous_values[0]['id'];
        }
        $success = TRUE;
        $audit_trail_data = array('time_stamp' => time(), 'user_id' => $user['id'], 'ip_address' => $this->CI->input->ip_address(), 'table_name' => $table, 'url' => $this->CI->uri->ruri_string(), 'action' => $operation, 'reference_id' => $reference_id, 'old_value' => $old_value, 'new_value' => $new_value);
        $success = parent::insert('user_audit_trails', $audit_trail_data);
        if (!$success) {
            log_message('error', "Error writing log for $operation operation on $table");
        }

        return $success;
    }

    function is_auditable($table_name)
    {
        //If AUDIT_TRAIL is disabled for command line use, return FALSE to skip auditing
        if (defined('AUDIT_TRAIL'))
            if (AUDIT_TRAIL == false) return false;

        return (!isset($this->audit_skip_tables[$table_name]));
    }


    function diff_on_update(&$old_value, &$new_value)
    {
        if (isset($old_value[0]))
            $old_value = $old_value[0];
        $old = array();
        $new = array();
        foreach ($new_value as $key => $val) {
            if (isset($new_value[$key])) {
                if (isset($old_value[$key])) {
                    if ($new_value[$key] != $old_value[$key]) {
                        $old[$key] = $old_value[$key];
                        $new[$key] = $new_value[$key];
                    }
                } else {
                    $old[$key] = '';
                    $new[$key] = $new_value[$key];
                }
            }
        }

        $old_value = $old;
        $new_value = $new;
    }
}
