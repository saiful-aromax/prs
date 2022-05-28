<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/22/17
 * Time: 3:15 PM
 */
class Authorization_unauthorizations extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Authorization_unauthorization', 'Transaction', 'Activity'], '', TRUE);
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       22/05/2017
     * @lastmodified Date: 22/05/2017
     */

    function authorization()
    {
        $data = $this->_load_combo_data();
        $data['is_authorization'] = 1;
        $data['headline'] = $data['title'] = 'Data Authorization';
        $this->Layout('Authorization_unauthorizations/index', $data);
    }

    function unauthorization()
    {
        $data = $this->_load_combo_data();
        $data['is_authorization'] = 0;
        $data['headline'] = $data['title'] = 'Data Un-Authorization';
        $this->Layout('Authorization_unauthorizations/index', $data);
    }

    function _load_combo_data()
    {
        $data = [];
        $data['projects'] = $this->Activity->get_item();
        $data['reporting_periods'] = $this->Transaction->get_reporting_periods();
        $data['years'] = $this->Transaction->get_years();
        return $data;
    }

    function ajax_report()
    {
        $data = $this->input->post();
        if (isset($data['id_periods'])) {
            foreach ($data['id_periods'] as $key => $value) {
                $data['id_periods'][$value] = $value;
            }
        }
        $data['reporting_periods_info'] = $this->Transaction->get_reporting_periods($data['id_reporting_periods']);
        $data['years_info'] = $this->Transaction->get_years($data['id_years']);
        $data['years_next1'] = $this->Transaction->get_years($data['id_years'] + 1);
        $data['years_next2'] = $this->Transaction->get_years($data['id_years'] + 2);
        $data['years_next3'] = $this->Transaction->get_years($data['id_years'] + 3);
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($data['id_years'], $data['id_reporting_periods']);
        $data['sub_header'] = $this->Transaction->get_sub_header($data['id_years'], $data['id_reporting_periods']);
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($data['id_reporting_periods']);
        $data['show_target'] = (isset($data['show_target'])) ? $data['show_target'] : '';
        $data['transactions'] = $this->Transaction->form_data($data['id_projects'], null, $data['reporting_periods_type'], $data['id_years']);
        if (!isset($data['report_type'])) {
            $data['report_type'] = 'EMAIL';
            $file_name = $this->Transaction->get_file_name_info($data['id_years'], $data['id_reporting_periods'], $data['id_projects'], true);
            $path_name = FCPATH . 'uploads/' . $file_name . '.pdf';
            $html = $this->layout('Authorization_unauthorizations/ajax_report', $data, true, true);
            $this->load->library('m_pdf');
            $mpdf = new mPDF('', 'A2-L');
            $mpdf->WriteHTML($html);
            $mpdf->Output($path_name, 'F');
            $email_info = $this->Transaction->get_logged_in_user_email_info();
            $this->load->library('email');
            $email_config = $this->config->item('email_config');
            $this->email->initialize($email_config);
            $this->email->from($email_info['from_email'], $email_info['from_name']);
            $this->email->to($email_info['to_email']);
            $this->email->subject('IP report email');
            $this->email->attach($path_name);
            $this->email->message('Testing the email class.');
            if ($this->email->send()) {
                $this->Authorization_unauthorization->authorize($data['id_projects'], $data['id_reporting_periods'], $data['id_years']);
                $this->session->set_flashdata('success', 'Data Authorized successfully');
                redirect('/authorization_unauthorizations/authorization');
            } else {
                echo $this->email->print_debugger();
                $this->session->set_flashdata('error', $this->email->print_debugger());
                redirect('/authorization_unauthorizations/authorization/');
            }

        } else {
            $this->load->view('Authorization_unauthorizations/ajax_report', $data);
        }
    }


}


