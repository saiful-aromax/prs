<?php

/**
 * Auths Controller Class.
 * @pupose        Manage user authentication
 *
 * @filesource    \app\controllers\auths.php
 * @package        insight
 * @subpackage    insight.controller.auts
 * @version      $Revision: 1 $
 * @author       $Author: Saiful Islam $
 * @lastmodified $Date: 2017-05-22 $
 */
class Auths extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->helper('form');
        $this->load->model(['User', 'User_captcha', 'User_login_attempts'], '', TRUE);
    }

    function index()
    {
        $this->login();
    }

    function login()
    {
        $this->output->enable_profiler(FALSE);
        $this->load->helper(array('captcha'));
        $data['title'] = 'User Authentication';
        $captcha_expire = $this->config->item('captcha_expire', 'auth');

        $this->User_captcha->cleanup_captcha($captcha_expire);
        $login = $this->input->post('txt_login', TRUE);
        $password = $this->input->post('txt_password', TRUE);
        $captcha_word = $this->input->post('txt_captcha', TRUE);
        $ip_address = $this->input->ip_address();
        $data['status'] = "failure";

        if ($_POST) {
            $is_valid_login_attempt = FALSE;

            if ($this->auth->is_max_login_attempts_exceeded($login)) {
                //allowed login attempt exceeded, validating captcha first
                if ($this->User_captcha->is_valid_captcha($captcha_word, $ip_address, $captcha_expire)) {
                    $is_valid_login_attempt = TRUE;
                } else {
                    $data['error_message'] = 'The captcha code you entered is incorrect.';
                    $login_attempt = $this->User_login_attempts->get_attempts_num($ip_address, $login);
                    $max_allowed_login_attempt = $this->config->item('login_max_attempts', 'auth');
                    if ($login_attempt >= $max_allowed_login_attempt) {
                        //captcha generation
                        $captcha = $this->User_captcha->create_captcha($this->input->ip_address());
                        $data['captcha_image'] = $captcha['image'];
                    }
                }
            } else {
                $is_valid_login_attempt = TRUE;
            }
            if ($is_valid_login_attempt) {

                if ($this->auth->login($login, $password, 0)) {
//                    echo '<pre>';
//                    print_r($_SESSION);
//                    die;
                    $data['status'] = "success";
                    $data['redirect'] = site_url("/");
                } else {
                    $data['error_message'] = 'The username or password you entered is incorrect.';
                    $login_attempt = $this->User_login_attempts->get_attempts_num($ip_address, $login);
                    $max_allowed_login_attempt = $this->config->item('login_max_attempts', 'auth');
                    if ($login_attempt >= $max_allowed_login_attempt) {
                        $captcha = $this->User_captcha->create_captcha($this->input->ip_address());
                        $data['captcha_image'] = $captcha['image'];
                    }
                }
            }
            if (!$data['error_message']) {
                redirect($data['redirect']);
            }
        }
        $this->load->view('Auths/login', $data);
    }

    function logout()
    {
        $this->auth->logout();
        redirect('/auths/login/');
    }

}
