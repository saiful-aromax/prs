<?php

/**
 * Extended Controller Class.
 * @pupose        Perform some operation which is common to all control class
 *
 * @filesource    \app\libraries\MY_Controller.php
 * @version      $Revision: 1 $
 * @author       $Author:  Saiful Islam $
 * @lastmodified $Date: 2017-05-22 $
 */
class MY_Controller extends CI_Controller
{

    public $is_access_denied = true;

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_role_wise_privilege', '', TRUE);
        $this->load->library('auth');

        $controller = strtolower($this->router->class);
        $action = empty($this->router->method) ? 'index' : $this->router->method;
        $is_logged_in = $this->auth->is_logged_in();

        if ($controller == 'users' && $action == "reset_password") {
            $this->is_access_denied = true;
        } elseif (!$is_logged_in && !(($controller == 'auths' && $action == "login"))) {
            redirect('/auths/login');
        } elseif ($is_logged_in) {
            //check ACL ---
            if (!$this->User_role_wise_privilege->check_permission($this->get_role_id(), $controller, $action)) {

                if (!$this->is_super_admin()) {
                    $this->is_access_denied = true;
                } else {
                    $this->is_access_denied = false;
                }
            } else {
                $this->is_access_denied = false;
            }
            if ($this->is_access_denied) {
                redirect('/pages/access_denied');
            }
        }
    }

    function get_role_id()
    {
        $session_data = $this->session->userdata('data');
        return !empty($session_data) ? $session_data['role_id'] : false;
    }

    public function layout($view_locator, $data = null, $is_report = false, $return = false)
    {
        $title = (isset($data['title']) ? $data['title'] : 'Performance Reporting System');
        $headline = (isset($data['headline']) ? $data['headline'] : 'Performance Reporting System');
        if ($return) {
            return $this->load->view('Layout/master', ['content' => $this->load->view($view_locator, $data, true), 'title' => $title, 'headline' => $headline, 'is_report' => $is_report], true);
        } else {
            $this->load->view('Layout/master', ['content' => $this->load->view($view_locator, $data, true), 'title' => $title, 'headline' => $headline, 'is_report' => $is_report]);
        }
    }

    public function get_login_name()
    {
        $user = $this->ci->session->userdata('data');
        return !empty($user) ? $user['full_name'] : false;
    }

    function is_super_admin()
    {
        $session_data = $this->session->userdata('data');
        return !empty($session_data) ? $session_data['is_super_admin'] : false;
    }

    public function paginate($config = [])
    {
        $config['reuse_query_string'] = TRUE;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
    }

    public function counter($segment = 3)
    {
        $segment_value = $this->uri->segment($segment);
        return empty($segment_value) ? 0 : (int)$segment_value;
    }

    function unique_check($table, $field, $id, $value)
    {
        $check = $this->db->query("SELECT '$field' FROM '$table' WHERE `id` != '$id' AND '$field' = '$value' LIMIT 1 ")->row();
        return empty($check) ? true : false;
    }

    function get_user_id()
    {
        return $this->session->userdata('data')['id'];
    }

    function is_ip()
    {
        $user_id = $this->session->userdata('data')['id'];
        $check = $this->db->query("SELECT `is_super_admin`, `role_id` FROM `users` WHERE `id` = '$user_id'")->row();
        return !($check->is_super_admin == '1' || $check->role_id == '1');
    }

    function get_activity_id()
    {
        $user_id = $this->session->userdata('data')['id'];
        return $check = $this->db->query("SELECT `project_id` FROM `users` WHERE `id` = '$user_id'")->row()->project_id;
    }


}
