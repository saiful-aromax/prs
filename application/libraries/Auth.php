<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->config('auth', TRUE);
        $this->ci->load->library('session');
        $this->ci->load->model(['User']);
    }

    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param    string (username or email or both depending on settings in config file)
     * @param    string
     * @param    bool
     * @return    bool
     */
    function login($login, $password)
    {
        if ((strlen($login) > 0) AND (strlen($password) > 0)) {
            if ($user = $this->ci->User->get_user_by_login($login, $password)) {
                $session_data = ['data' => ['id' => $user->id, 'login' => $user->login, 'name' => $user->full_name, 'logged_in' => TRUE, 'role_id' => $user->role_id, 'is_super_admin' => $user->is_super_admin, 'project_id' => $user->project_id]];
                $this->ci->session->set_userdata($session_data);
                $this->clear_login_attempts($login);
                $this->ci->User->update_login_info($user->id, $this->ci->config->item('login_record_ip', 'auth'), $this->ci->config->item('login_record_time', 'auth'));
                return TRUE;
            } else {
                $this->increase_login_attempt($login);
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * Increase number of attempts for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param    string
     * @return    void
     */
    function increase_login_attempt($login)
    {
        if ($this->ci->config->item('login_count_attempts', 'auth')) {
            if (!$this->is_max_login_attempts_exceeded($login)) {
                $this->ci->load->model('User_login_attempts');
                return $this->ci->User_login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Check if login attempts exceeded max login attempts (specified in config)
     *
     * @param    string
     * @return    bool
     */
    function is_max_login_attempts_exceeded($login)
    {
        $max_attempt = $this->ci->config->item('login_max_attempts', 'auth');
        if (!empty($max_attempt)) {
            $this->ci->load->model('User_login_attempts');
            $attempt_count = $this->ci->User_login_attempts->get_attempts_num($this->ci->input->ip_address(), $login);
            if ($attempt_count <= $max_attempt) {
                ;
                return FALSE;
            }
        }
        return TRUE;
    }

    function logout()
    {
        $this->ci->session->unset_userdata('data');
        $this->ci->session->sess_destroy();
    }

    function is_logged_in()
    {
        return !empty($this->ci->session->userdata('data')) ? true : false;
    }

    private function clear_login_attempts($login)
    {
        if ($this->ci->config->item('login_count_attempts', 'auth')) {
            $this->ci->load->model('User_login_attempts');
            return $this->ci->User_login_attempts->clear_attempts($this->ci->input->ip_address(), $login, $this->ci->config->item('login_attempt_expire', 'auth'));
        }
    }


}

?>
