<?php

class User_audit_trails extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form']);
        $this->load->model(['User_audit_trail', 'User']);
    }

    function index()
    {
        $data = $this->_load_combo_data();
        $cond = $this->input->get();
        $data = array_merge($data, $cond);
        $this->load->library('pagination');
        $config = [];
        $config['base_url'] = site_url('/user_audit_trails/index/');
        $config['total_rows'] = $data['total_rows'] = $this->User_audit_trail->row_count($cond);
        $data['counter'] = $this->counter();
        $data['audit_trails'] = $this->User_audit_trail->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'User Audit Trail';
        $this->layout('User_audit_trails/index', $data);
    }

    function view($id = null)
    {
        if (empty($id) && !$_GET) {
            $this->session->set_flashdata('message', 'ID is not provided');
            redirect('/user_audit_trails/index/', 'refresh');
        }
        $data = [];
        $data['row'] = $this->User_audit_trail->get_detail($id);
        $data['title'] = $data['headline'] = 'Audit Trail Details';
        $this->layout('User_audit_trails/view', $data);
    }

    function _load_combo_data()
    {
        $data = [];
        $data['actions'] = ['-1' => '--Action Type--', 'insert' => 'Insert', 'update' => 'Update', 'delete' => 'Delete'];
        $data['users'] = ['-1' => '--User--'] + $this->User->get_item();
        $data['tables'] = ['-1' => '--Table--'] + $this->User_audit_trail->get_tables();
        return $data;
    }

}
