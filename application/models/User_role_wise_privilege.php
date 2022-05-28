<?php

/**
 * User Role wise Privileges Model Class.
 * @pupose        Manage User Role wise Privileges information
 *
 * @filesource    \app\model\user_role_wise_privileges.php
 * @package        microfin
 * @subpackage    microfin.model.user_role_wise_privileges
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @lastmodified $Date: 2011-01-04 $
 */
class User_role_wise_privilege extends MY_Model
{

    var $title = '';
    var $content = '';
    var $date = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     * Generates a list of user wise privileges
     * @author  :   Amlan Chowdhury
     * @uses    :   To Generate a list of user wise privileges
     * @access  :   public
     * @param   :   int $offset, int $limit
     * @return  :   array
     */
    function get_list($offset, $limit)
    {
        $query = $this->db->get('user_role_wise_privileges', $offset, $limit);
        return $query->result();
    }

    /**
     * Counts number of rows of  user wise privileges table
     * @author  :   Amlan Chowdhury
     * @uses    :   To count number of rows of  user wise privileges table
     * @access  :   public
     * @return  :   int
     */
    function row_count()
    {
        return $this->db->count_all_results('user_role_wise_privileges');
    }

    /**
     * Adds data to user role wise privileges
     * @author  :   Amlan Chowdhury
     * @uses    :   To add data to user role wise privileges
     * @access  :   public
     * @param   :   array $data
     * @return  :   boolean
     */
    function add($data)
    {
        //echo '-------'.$data['role_id'].'=======';echo '<pre>';print_r($data);echo '</pre>';//die('model die');

        $this->db->trans_start();
        $this->db->delete('user_role_wise_privileges', array('role_id' => $data['role_id']));
        //$this->insert_rows('user_role_wise_privileges', $data['column_names'],$data['column_rows']);
        $this->db->insert_batch('user_role_wise_privileges', $data['resources']);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Updates data of user role wise privileges
     * @author  :   Amlan Chowdhury
     * @uses    :   To update data of user roles wise privileges
     * @access  :   public
     * @param   :   array $data
     * @return  :   boolean
     */
    function edit($data)
    {
        return $this->db->update('user_role_wise_privileges', $data, array('id' => $data['id']));
    }

    /**
     * Reads data of specific user role wise privilege
     * @author  :   Amlan Chowdhury
     * @uses    :   To  read data of specific user role wise privilege
     * @access  :   public
     * @param   :   int $user_role_wise_privileges_id
     * @return  :   boolean
     */
    function read($user_role_wise_privileges_id)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('id' => $user_role_wise_privileges_id));
        return $query->result();
    }

    /**
     * Gets data of user role wise privilege by role id
     * @author  :   Amlan Chowdhury
     * @uses    :   To  get data of user role wise privilege by role id
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   array
     */
    function get_by_role_id($role_id)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('role_id' => $role_id));
        return $query->result_array();
    }

    /**
     * Gets data of user role wise privilege by role id,controller name and action
     * @author  :   Amlan Chowdhury
     * @uses    :   To  get data of user role wise privilege by role id, controller name and action
     * @access  :   public
     * @param   :   int $role_id, string $controller, string $action
     * @return  :   array
     */
    function get_by_role_id_controller_action($role_id, $controller, $action)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('role_id' => $role_id, 'controller' => $controller, 'action' => $action));
        return $query->result_array();
    }

    /**
     * Gets data of user role wise privilege by controller name and action
     * @author  :   Amlan Chowdhury
     * @uses    :   To  get data of user role wise privilege by controller name and action
     * @access  :   public
     * @param   :   string $controller, string $action
     * @return  :   array
     */
    function get_by_controller_action($controller, $action)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('controller' => $controller, 'action' => $action));
        return $query->result_array();
    }

    /**
     * Gets data of user role wise privilege by controller name ,action and role id
     * @author  :   Amlan Chowdhury
     * @uses    :   To  get data of user role wise privilege by controller name, action and role id
     * @access  :   public
     * @param   :   string $controller, string $action, int $role_id
     * @return  :   array
     */
    function get_by_controller_action_role($controller, $action, $role_id)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('controller' => $controller, 'action' => $action, 'role_id' => $role_id));
        return $query->result_array();
    }

    /**
     * Gets data of user role wise privileged resourses by controller role id
     * @author  :   Amlan Chowdhury
     * @uses    :   To  get data of user role wise privileged resourses by role id
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   array
     */
    function get_privileged_resources($role_id)
    {
        $query = $this->db->get_where('user_role_wise_privileges', array('role_id' => $role_id));
        {
            $amlan_field_officers_reports_ccdas = array();
            $results = $query->result_array();
            foreach ($results as $result) {
                if ($result['controller'] == 'amlan_field_officers_reports') {
                    $result['id'] += 1000;
                    $result['controller'] = 'amlan_field_officers_reports_ccdas';
                    array_push($amlan_field_officers_reports_ccdas, $result);
                }
            }
            foreach ($amlan_field_officers_reports_ccdas as $amlan_field_officers_reports_ccda) {
                array_push($results, $amlan_field_officers_reports_ccda);
            }
            return $results;
        }
        return $query->result_array();
    }

    /**
     * Deletes data of specific user role wise privileges
     * @author  :   Amlan Chowdhury
     * @uses    :   To  delete data of specific user role wise privileges
     * @access  :   public
     * @param   :   int $user_role_wise_privileges_id
     * @return  :   boolean
     */
    function delete($user_role_wise_privileges_id)
    {
        return $this->db->delete('user_role_wise_privileges', array('id' => $user_role_wise_privileges_id));
    }

    /**
     * Deletes data of user role wise privileges by role id ,controller name and action
     * @author  :   Amlan Chowdhury
     * @uses    :   To  delete data of user role wise privileges by role id ,controller name and action
     * @access  :   public
     * @param   :   int $role_id,string $controller, string $action
     * @return  :   boolean
     */
    function delete_by_role_id_controller_action($role_id, $controller, $action)
    {
        return $this->db->delete('user_role_wise_privileges', array('role_id' => $role_id, 'controller' => $controller, 'action' => $action));
    }

    /**
     * Deletes data of specific user role wise privileges by role id
     * @author  :   Amlan Chowdhury
     * @uses    :   To  delete data of specific user role wise privileges by role id
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   boolean
     */
    function delete_by_role_id($role_id)
    {
        return $this->db->delete('user_role_wise_privileges', array('role_id' => $role_id));
    }

    /**
     * Checks permission of user role wise privileges
     * @author  :   Amlan Chowdhury
     * @uses    :   To  check permission of user role wise privileges
     * @access  :   public
     * @param   :   int $role_id, string $controller,string $action
     * @return  :   boolean
     */
    function check_permission($role_id, $controller, $action)
    {
        $controller = strtolower($controller);
        $action = strtolower($action);

        if ($this->is_globally_allowed_action($controller, $action)) { //if action is globally allowed, access is granted
            return true;
        } else {
            $check = $this->db->query("SELECT `id` FROM `user_role_wise_privileges` WHERE `controller` = '$controller' AND `action` = '$action' AND `role_id` = '$role_id'")->result_array();
            return !empty($check) ? true : false;
        }
    }

    /**
     * Checks if the action is globally dis-allowed or not
     * @author  :   Anis Alamgir
     * @uses    :   To check if the action is globally dis-allowed or not
     * @access  :   public
     * @param   :   string $controller,string $action
     * @return  :   boolean
     * @wiki    : http://203.188.255.195/tracker/projects/microfin360/wiki/Manage_User_Role#Only-For-Super-Admin
     */
    function is_globally_disallowed_action($controller, $action)
    {
        return isset($actions[$controller][$action]) ? true : false;
    }

    /**
     * Checks if the action is globally allowed or not
     * @author  :   Amlan Chowdhury
     * @uses    :   To check if the action is globally allowed or not
     * @access  :   public
     * @param   :   string $controller,string $action
     * @return  :   boolean
     */
    function is_globally_allowed_action($controller, $action)
    {
        $actions = [];
        $actions['pages']['access_denied'] = 1;
        $actions['pages']['enable_javascript'] = 1;
        $actions['pages']['no_data_found'] = 1;
        $actions['pages']['404'] = 1;
        $actions['pages']['index'] = 1;

        $actions['users']['change_password'] = 1;
        $actions['users']['reset_password'] = 1;
        $actions['auths']['login'] = 1;
        $actions['auths']['logout'] = 1;
        $actions['auths']['two_step_verification'] = 1;
        $actions['user_roles']['access_denied'] = 1;
        return isset($actions[$controller][$action]) ? true : false;
    }


    function get_all_resources_array()
    {
        define("VIEW", "View");
        define("ADD", "Add");
        define("EDIT", "Edit");
        define("DELETE", "Delete");
        //initializing
        $group_id = 0;
        $tmp = array();
        //Organization management group
        $group_name = 'Admin';
        $subgroup_name = 'Admin';
        $entity_name = 'User';
        $controller = 'Users';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);
        $CI = &get_instance();

        $entity_name = 'User Role';
        $controller = 'User_roles';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Change Password';
        $controller = 'Users';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, array('index'));
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD, array('save', 'batch_save', 'delete_all'));
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT, array('edit'));
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE, array('delete'));

        $entity_name = 'Audit Trail';
        $controller = 'User_audit_trails';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        //Organization management group
        $group_name = 'Configuration';
        $subgroup_name = 'Configuration';
        $entity_name = 'Unit';
        $controller = 'Units';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Disaggregate Tier';
        $controller = 'Disaggregate_tiers';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Disaggregate Group';
        $controller = 'Disaggregate_groups';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Disaggregate';
        $controller = 'Disaggregates';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Disaggregate Set';
        $controller = 'Disaggregates_sets';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Commodity';
        $controller = 'Commodities';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Indicator';
        $controller = 'Indicators';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Activity';
        $controller = 'Activites';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Activity Indicator';
        $controller = 'Project_indicators';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Activity Indicator Disaggregate Set';
        $controller = 'Project_indicator_disaggregate_sets';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        //Organization management group
        $group_name = 'Transaction';
        $subgroup_name = 'Transaction';
        $entity_name = 'Baseline Data';
        $controller = 'Transactions';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Target Data';
        $controller = 'Transactions';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Result';
        $controller = 'Transactions';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        //Organization management group
        $group_name = 'Report';
        $subgroup_name = 'Report';
        $entity_name = 'IP Wise Report';
        $controller = 'Reports';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'All Indicator Report';
        $controller = 'Reports';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Gross Margin Report';
        $controller = 'Reports';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        $entity_name = 'Increment Sales Report';
        $controller = 'Reports';
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, VIEW, 'index');
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, ADD);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, EDIT);
        $tmp = $this->create_resource($tmp, $group_name, $subgroup_name, $entity_name, $controller, DELETE);

        return $tmp;
    }

    function create_resource(&$tmp, $group_name, $subgroup_name, $entity_name, $controller, $action_title, $actions = null)
    {
        if (is_null($actions)) {
            $tmp[$group_name][$subgroup_name][$entity_name][$controller][$action_title][0]['name'] = strtolower($action_title);
        } elseif (is_string($actions)) {
            $tmp[$group_name][$subgroup_name][$entity_name][$controller][$action_title][0]['name'] = $actions;
        } elseif (is_array($actions)) {
            foreach ($actions as $key => $row) {
                $tmp[$group_name][$subgroup_name][$entity_name][$controller][$action_title][$key]['name'] = $row;
            }
        }

        return $tmp;
    }

}
