<?php

/**
 * Model Class for User Audit Trail.
 * @pupose        Display User Audit Trail
 *
 * @filesource    ./system/application/models/User_audit_trail.php
 * @package        microfin
 * @subpackage    microfin.system.application.models.User_audit_trail
 * @version      $Revision: 1 $
 * @author       $Author: Saroj Roy $
 * @lastmodified $Date: 2011-03-07 $
 */
class User_audit_trail extends MY_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_list($offset, $limit, $cond)
    {
        $this->db->select('user_audit_trails.id,user_audit_trails.time_stamp,user_audit_trails.ip_address,user_audit_trails.table_name,user_audit_trails.action,user_id, users.login');
        $this->db->from('user_audit_trails');
        $this->db->join('users', 'users.id=user_audit_trails.user_id', 'left');
        $from_date = (isset($cond['from_date']) && !empty($cond['from_date'])) ? strtotime($cond['from_date']) : false;
        $to_date = (isset($cond['to_date']) && !empty($cond['to_date'])) ? strtotime($cond['to_date']) : false;
        if ($from_date && !$to_date) {
            $where = "( user_audit_trails.time_stamp >= '$from_date' )";
            $this->db->where($where);
        }
        if (!$from_date && $to_date) {
            $where = "( user_audit_trails.time_stamp <= '$to_date' )";
            $this->db->where($where);
        }
        if ($from_date && $to_date) {
            $where = "( user_audit_trails.time_stamp BETWEEN '$from_date' AND '$to_date' )";
            $this->db->where($where);
        }
        if (isset($cond['user_id']) and !empty($cond['user_id']) and $cond['user_id'] != -1) {
            $this->db->where('user_audit_trails.user_id', $cond['user_id']);
        }
        if (isset($cond['action']) and !empty($cond['action']) and $cond['action'] != -1) {
            $this->db->where('user_audit_trails.action', $cond['action']);
        }
        if (isset($cond['table']) and !empty($cond['table']) and $cond['table'] != -1) {
            $this->db->where('user_audit_trails.table_name', $cond['table']);
        }

        $this->db->order_by('time_stamp', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    function get_tables()
    {
        $tables = $this->db->query("SHOW TABLES;")->result_array();
        $returnArray = [];
        foreach ($tables as $row) {
            if ($row['Tables_in_' . $this->db->database] != 'years' || $row['Tables_in_' . $this->db->database] != 'time_sets') {
                $returnArray[$row['Tables_in_' . $this->db->database]] = $row['Tables_in_' . $this->db->database];
            }
        }
        return $returnArray;
    }

    function get_current_database()
    {
        return $this->db->database;
    }

    function row_count($cond)
    {
        $from_date = (isset($cond['from_date']) && !empty($cond['from_date'])) ? strtotime($cond['from_date']) : false;
        $to_date = (isset($cond['to_date']) && !empty($cond['to_date'])) ? strtotime($cond['to_date']) : false;
        if ($from_date && !$to_date) {
            $where = "( user_audit_trails.time_stamp >= '$from_date' )";
            $this->db->where($where);
        }
        if (!$from_date && $to_date) {
            $where = "( user_audit_trails.time_stamp <= '$to_date' )";
            $this->db->where($where);
        }
        if ($from_date && $to_date) {
            $where = "( user_audit_trails.time_stamp BETWEEN '$from_date' AND '$to_date' )";
            $this->db->where($where);
        }
        if (isset($cond['user_id']) and !empty($cond['user_id']) and $cond['user_id'] != -1) {
            $this->db->where('user_audit_trails.user_id', $cond['user_id']);
        }
        if (isset($cond['action']) and !empty($cond['action']) and $cond['action'] != -1) {
            $this->db->where('user_audit_trails.action', $cond['action']);
        }
        if (isset($cond['table']) and !empty($cond['table']) and $cond['table'] != -1) {
            $this->db->where('user_audit_trails.table_name', $cond['table']);
        }
        return $this->db->count_all_results('user_audit_trails');
    }

    function get_user_list($branch_id = null)
    {
        $condition = "";
        if (isset($branch_id)) {
            $condition = " where default_branch_id ='$branch_id' ";
        }
        $query_db = "  SELECT id, full_name FROM users " . $condition . "ORDER BY full_name ASC";

        $users = $this->db->query($query_db);
        return $users->result();
    }

    function get_detail($id)
    {
        $this->db->select('user_audit_trails.*,users.full_name as user_name');
        $this->db->from('user_audit_trails');
        $this->db->where('user_audit_trails.id', $id);
        $this->db->join('users', 'users.id=user_audit_trails.user_id', 'left');
        $query = $this->db->get()->row();
        if ($query->table_name == 'users' && $query->action == "update") {
            $user_details = $this->db->get_where('users', array('id' => $query->reference_id))->row();
            $old_value = json_decode($query->old_value);
            $old_value->full_name = $user_details->full_name;
            $old_value->login_id = $user_details->login;
            $old_value = json_encode($old_value);
            $query->old_value = $old_value;
        }
        return $query;
    }

}
