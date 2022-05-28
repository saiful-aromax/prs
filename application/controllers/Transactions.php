<?php

class Transactions extends MY_Controller
{
    var $type = "";

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'html']);
        $this->load->model(['Transaction', 'Activity'], '', TRUE);
        $this->type = strtolower($this->uri->segment(3));
        if ($this->type != 'baseline' && $this->type != 'target' && $this->type != 'result' && !$this->input->is_ajax_request()) {
            redirect('/');
        }
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Nadim
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */

    function index()
    {
//        $result = $this->Transaction->form_data(1, null, 'Quarterly',1);
//        echo '<pre>';
//        print_r($result);
//        die;

        if ($_POST) {
            $this->Transaction->process($this->input->post());
            $this->session->set_flashdata('success', 'Form data Processed successfully');
            redirect('/Transactions/index/' . $this->type);
        }
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Transactions/save', $data);
    }


    function _load_combo_data()
    {
        $data = [];
        $data['projects'] = $this->Activity->get_item();
        $data['reporting_periods'] = $this->Transaction->get_reporting_periods();
        $data['years'] = $this->Transaction->get_years();
        return $data;
    }

    function ajax_generate_form()
    {
        $data = $this->input->post();
        if (isset($data['id_periods'])) {
            foreach ($data['id_periods'] as $key => $value) {
                $data['id_periods'][$value] = $value;
            }
        }
        $data['reporting_periods'] = $this->Transaction->get_reporting_periods();
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($data['id_reporting_periods']);
        $data['show_target'] = (isset($data['show_target'])) ? $data['show_target'] : '';
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($data['id_years'], $data['id_reporting_periods']);
        $data['transactions'] = $this->Transaction->form_data($data['id_projects'], null, $data['reporting_periods_type'], $data['id_years']);
//        echo '<pre>';
//        print_r($data['transactions']);
//        exit;
        $this->load->view('Transactions/ajax_entry_form', $data);
    }


}