<?php

/**
 * Users Controller Class.
 * @pupose        Manage Users information
 *
 * @filesource    \app\controllers\users.php
 * @package        microfin
 * @subpackage    microfin.controller.users
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @lastmodified $Date: 2011-01-04 $
 */
class Users extends MY_Controller
{

    var $user_id;

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->model(['User', 'User_role', 'Activity']);
        $this->ci =& get_instance();
        $this->user_id = $this->ci->get_user_id();
    }

    /**
     * Action for default user list view page
     * @uses     Creating default user list view page
     * @access    public
     * @param   void
     * @return    void
     * @author  Amlan Chowdhury
     */
    function index()
    {
        $data = [];
//        echo '<pre>';
//        print_r($data);
//        die;
        $cond = [];
        $this->load->library('pagination');
        $total = $this->User->get_list(0, 0, $cond);
        $data['user_roles'] = $this->User_role->get_item();
        $data['projects'] = $this->Activity->get_item();
        $config['base_url'] = site_url('/users/index/');
        $config['per_page'] = ROW_PER_PAGE;
        $config['total_rows'] = $total;
        $this->pagination->initialize($config);
        $data['users'] = $this->User->get_list(ROW_PER_PAGE, (int)$this->uri->segment(3), $cond);
        $data['title'] = $data['headline'] = 'Manage User';
        $this->layout('Users/index', $data);
    }

    /**
     * Adds data to users
     * @author  :   Amlan Chowdhury
     * @uses    :   To add data to users
     * @access  :   public
     * @param   ;   void
     * @return  :   void
     */
    function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() == TRUE) {
                if ($this->User->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/users');
                }
            }
        }
        $data = [];
        $data['user_roles'] = $this->User_role->get_item();
        $data['action'] = 'add';
        $data['projects'] = $this->Activity->get_item();
        $data['title'] = $data['headline'] = 'Add User';
        $this->Layout('Users/save', $data);
    }

    /**
     * Updates data of user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To update data of user roles
     * @access  :   public
     * @param   :   int $user_id
     * @return  :   void
     */
    function edit($user_id = null)
    {
        $this->_prepare_validation();
        if ($_POST) {
            if ($this->form_validation->run() == TRUE) {
                $data = $this->input->post();
                if ($this->User->edit($data, $user_id)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/users');
                }
            }
        }
        $data = [];
        $data['action'] = 'edit/' . $user_id;
        $data['user_roles'] = $this->User_role->get_item();
        $data['projects'] = $this->Activity->get_item();
        $data['row'] = $this->User->read($user_id);
        $data['title'] = $data['headline'] = 'Edit User';
        $this->layout('Users/save', $data);
    }


    /**
     * Set up validation rules
     * @author  :   Amlan Chowdhury
     * @uses    :   To set up validations on various fiels
     * @access  :   private
     * @param   :   void
     * @return  :   void
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            $password_min_length = $this->config->item('password_min_length', 'auth');
            $password_max_length = $this->config->item('password_max_length', 'auth');
            $this->form_validation->set_rules('password', 'Password', "required|min_length[$password_min_length]|max_length[$password_max_length]");
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('login', 'Login', 'required|trim|max_length[50]|is_unique[users.login]');
        } else {
            $this->form_validation->set_rules('status', 'Current Status', 'required');
        }
        $this->form_validation->set_rules('role_id', 'Role', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    }

    /**
     * Changes password
     * @uses    To change password
     * @access    private
     * @param   void
     * @return    void
     * @author  Matin
     */
    function change_password()
    {
        $password_min_length = $this->config->item('password_min_length', 'auth');
        $password_max_length = $this->config->item('password_max_length', 'auth');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('old_password', 'Old Password', "required|callback_old_password_check|max_length[$password_max_length]");
        $this->form_validation->set_rules('password', 'New Password', "required|min_length[$password_min_length]|max_length[$password_max_length]");
        $this->form_validation->set_rules('verify_password', 'Verify New Password', 'required|matches[password]');
        if ($_POST) {
            if ($this->form_validation->run() == TRUE) {
                $data = [];
                $data['id'] = $this->user_id;
                $data['password'] = $this->input->post('password');
                if ($this->User->changePassword($data)) {
                    $this->session->set_flashdata('success', 'Password has been changed successfully');
                    redirect('/');
                }
            }
        }
        $data['row'] = $this->User->read($this->user_id);
        $data['title'] = $data['headline'] = 'Change Password';
        $this->layout('Users/change_password', $data);
    }

    /**
     * Checks old password
     * @uses    To check old password
     * @access    private
     * @param   string $old_password
     * @return    boolean
     * @author  Matin
     */
    function old_password_check($old_password)
    {
        if ($this->User->checkPassword($this->user_id, $old_password)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('old_password_check', 'The %s field is not correct');
            return FALSE;
        }
    }

    public function reset_password()
    {
        $data['title'] = 'User Reset Password';
        if ($_POST) {
            $post_data = $this->input->post();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == TRUE) {
                $data = [];
                $user_info = $this->User->checkEmail($post_data['email']);
                if ($user_info) {
                    $data['password'] = $user_info['login'] . rand(10, 100);
                    $data['id'] = $user_info['id'];
                    $data['skip_audit_trail'] = true;
                    if ($this->User->changePassword($data)) {
                        $this->load->library('email');
                        $email_config = $this->config->item('email_config');
                        $this->email->initialize($email_config);
                        $this->email->from('saiful.physics@gmail.com', 'Info');
                        $this->email->to($user_info['email']);
                        $this->email->subject('New Password');
                        $this->email->message('Your Password successfully reset. Your new password is ' . $data['password']);
                        if ($this->email->send()) {
                            $this->session->set_flashdata('message', 'Password has been changed successfully. Please Check your email.');
                            redirect('/');
                        } else {
                            $this->session->set_flashdata('message', $this->email->print_debugger());
                            redirect('/');
                        }
                    }
                }
            }
            $this->session->set_flashdata('message', 'This user email not found');
            redirect('/');
        }
        $this->load->view('Auths/reset_password', $data);
    }


    /**
     * Prepares data for combo
     * @uses    Prepares data for combo
     * @access    private
     * @param   void
     * @return    array
     * @author  Matin
     */

}
