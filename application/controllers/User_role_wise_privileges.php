<?php

/**
 * User role wise privileges Controller Class.
 * @pupose        Manage User role wise privileges information
 *
 * @filesource    \app\controllers\user_role_wise_privileges.php
 * @package        microfin
 * @subpackage    microfin.controller.user_roles_controller
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @update        Anis Alamgir
 * @lastmodified $Date: 2014-01-04 $
 */
class User_role_wise_privileges extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form']);
        $this->load->model(['User_role', 'User_role_wise_privilege'], '', TRUE);
    }

    /**
     * Action for default role wise previleges view page
     * @uses     Creating default role wise previleges view page
     * @access    public
     * @param    int $role_id
     * @return    void
     * @author   Matin
     */
    function index($role_id)
    {
        $data = [];
        $data['title'] = $data['headline'] = 'Role wise privileges [' . $this->User_role->get_role_name_by_id($role_id) . ' ]';
        $data['role_privilege_resources'] = $this->User_role_wise_privilege->get_privileged_resources($role_id);
        $data['user_resources_array'] = $this->User_role_wise_privilege->get_all_resources_array();
        $data['role_id'] = $role_id;
        $data['role_name'] = $this->User_role->get_role_name_by_id($role_id);
        $this->layout('User_role_wise_privileges/index', $data);
    }

    /**
     * Adds data to user role privilege
     * @author  :   Amlan Chowdhury
     * @uses    :   To add data to user role privilege
     * @access  :   public
     * @param   :   void
     * @return  :   void
     */
    function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->_get_posted_data();
            if ($this->form_validation->run() === TRUE) {
                if ($this->User_role_wise_privilege->add($data)) {
                    $this->session->set_flashdata('message', 'User Role Privilege information has been added successfully');
                    redirect('/user_roles/');
                }
            }
        }
        $this->session->set_flashdata('warning', 'Data is not provided');
        redirect('/user_roles/');
    }

    /**
     * Set up validation rules
     * @author  :   Amlan Chowdhury
     * @uses    :   To sert up validations on various fiels
     * @access  :   public
     * @param   :   void
     * @return  :   void
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', 'Role anme', 'trim|required|xss_clean|is_natural_no_zero');
        $this->form_validation->set_rules('data[]', 'Action', 'xss_clean');
    }

    /**
     * Gets posted data
     * @author  :   Amlan Chowdhury
     * @uses    :   To get posted data
     * @access  :   private
     * @param   :   void
     * @return  :   array
     */
    function _get_posted_data()
    {
        $data = [];
        $user_resources = $this->User_role_wise_privilege->get_all_resources_array();
        $i = 0;
        foreach ($user_resources as $rows1) {
            foreach ($rows1 as $rows2) {
                $entity = 0;
                foreach ($rows2 as $key3 => $rows3) {
                    foreach ($rows3 as $key4 => $rows4) {
                        foreach ($rows4 as $key5 => $rows5) {
                            foreach ($rows5 as $rows6) {
                                if (isset($_POST['data'][$entity]["$key4"]["$key5"])) {
                                    $data['resources'][$i]['controller'] = $key4;
                                    $data['resources'][$i]['action'] = $rows6['name'];
                                    $data['resources'][$i]['role_id'] = $_POST['role_id'];
                                }
                                $i++;
                            }
                        }
                    }
                    $entity++;
                }
            }
        }
        $data['role_id'] = $_POST['role_id'];
        return $data;
    }
}
