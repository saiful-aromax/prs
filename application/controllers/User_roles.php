<?php

/**
 * User roles Controller Class.
 * @pupose        Manage User roles information
 *
 * @filesource    \app\controllers\user_roles.php
 * @package        microfin
 * @subpackage    microfin.controller.user_roles
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @lastmodified $Date: 2011-01-04 $
 */
class User_roles extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form']);
        $this->load->model(['User_role', 'User_role_wise_privilege'], '', TRUE);
    }

    /**
     * Action for default user role view page
     * @uses     Creating default user role view page
     * @access    public
     * @param    void
     * @return    void
     * @author   Amlan Chowdhury
     */
    function index()
    {
        $data = [];
        $data['user_roles'] = $this->User_role->index_tree_data();
        $roles = $this->User_role->get_list($this->get_role_id());
        $data['role_list'] = [];
        if (!empty($roles)) {
            foreach ($roles as $row) {
                $data['role_list'][$row['id']] = $row['id'];
            }
        }
        $data['total'] = count($data['user_roles']);
        $data['title'] = 'Manage User Role';
        $data['headline'] = "User's Role Information";
        $this->layout('User_roles/index', $data);
    }

    /**
     * Adds data to user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To add data to user roles
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
                $data['id'] = $this->User_role->get_new_id('user_roles', 'id');
                if ($this->User_role->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/user_roles');
                }
            }
        }
        $data = $this->_load_combo_data();
        $data['title'] = 'Add User Role';
        $data['headline'] = 'Add User Role';
        $this->layout('User_roles/add', $data);
    }

    /**
     * Updates data of user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To update data of user roles
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   void
     */
    function edit($role_id = null)
    {
        //If ID is not provided, redirecting to index page
        if (empty($role_id) && !$_POST) {
            $this->session->set_flashdata('error', 'User Role ID is not provided');
            redirect('/user_roles/index/');
        }
        $this->_prepare_validation();

        $data = $this->_load_combo_data();
        //If the form is posted, perform the validation. is_posted is a hidden input used to detect if the form is posted
        if ($_POST) {
            $role_id = $this->input->post('role_id');
            //Perform the Validation
            if ($this->form_validation->run() == TRUE) {
                $data = $this->_get_posted_data();
                $data['id'] = $this->input->post('role_id');
                //Validation is OK. So, add this data and redirect to the index page
                if ($this->User_role->edit($data)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/user_roles/index/');
                }
            }
        }
        //Load data from database
        $data['row'] = $this->User_role->read($role_id);
        $data['title'] = 'Edit User Role';
        $data['headline'] = 'Edit User Role';
        //echo "<pre>";print_r($data);
        //If data is not posted or validation fails, the add view is displayed
        $this->layout('User_roles/edit', $data);
    }

    /**
     * Deletes data of specific user role
     * @author  :   Amlan Chowdhury
     * @uses    :   To  delete data of specific user role
     * @access  :   public
     * @param   :   int $role_id
     * @return  :    boolean
     */
    function delete($role_id = null)
    {
        if (empty($role_id)) {
            $this->session->set_flashdata('warning', 'User Role ID is not provided');
            redirect('/user_roles/index/');
        }
        $has_user_entry = $this->User_role->is_dependency_found('users', ['role_id' => $role_id]);
        if ($has_user_entry) {
            $this->session->set_flashdata('warning', DEPENDENT_DATA_FOUND);
            redirect('/user_roles/index/');
        } else {
            if ($this->User_role->delete($role_id)) {
                $this->session->set_flashdata('success', DELETE_MESSAGE);
            } else {
                $this->session->set_flashdata('warning', "Could not delete this role.");
            }
            redirect('/user_roles/index/');
        }
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
        $data = array();
        // if edit
        if (!is_numeric($this->input->post('role_id'))) {
            $data['parent_id'] = $this->input->post('cbo_parent');
            if ($data['parent_id'] == '') {
                $data['parent_id'] = $this->input->post('txt_parent');
            }
        }
        $data['role_name'] = $this->input->post('txt_role_name');
        $data['role_description'] = $this->input->post('txt_role_description');
        return $data;
    }

    /**
     * Set up validation rules
     * @author  :   Amlan Chowdhury
     * @uses    :   To sert up validations on various fiels
     * @access  :   private
     * @param   :   void
     * @return  ;   void
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_role_name', 'Name', 'required|trim|unique[user_roles.role_name.id.role_id]|max_length[100]');
        $this->form_validation->set_rules('cbo_parent', 'Parent', 'callback_check_valid_user_role');
    }

    function check_valid_user_role($role_id = '')
    {
        $action = $this->uri->segment(2);
        if ($action == "add") {
            if ($role_id == '' || !is_numeric($role_id)) {
                $this->form_validation->set_message('check_valid_user_role', "Parent is required");
            }
        } else {
            $role_id = $this->input->post('cbo_parent');
            if ($role_id == '') {
                $role_id = $this->input->post('txt_parent');
            }
        }
        $user_role = $this->User_role->read($role_id);
        if (!empty($user_role)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Loads data to combo box
     * @author  :   Amlan Chowdhury
     * @uses    :   To load data of user role to combo box
     * @access  :   private
     * @param   :   void
     * @return  :   array
     */
    function _load_combo_data()
    {
        $current_role_data = $this->User_role->read($this->get_role_id());
        $data['parent_list'] = $this->User_role->get_list($this->get_role_id());
        if ($this->uri->segment(2) == "edit") {
            $data['parent_list'][] = $this->User_role->read($current_role_data['parent_id']);
        }
        return $data;
    }

}
