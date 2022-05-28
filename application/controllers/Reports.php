<?php

class Reports extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'html']);
        $this->load->model(['Transaction', 'Activity','ExportImport','Gross_margin'], '', TRUE);
        $this->type = strtolower($this->uri->segment(3));

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
        if ($_POST) {
            $this->Transaction->process($this->input->post());
            $this->session->set_flashdata('success', 'Form data Processed successfully');
            redirect('/Reports/index/');
        }
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Reports/index', $data);
    }


    function _load_combo_data()
    {
        $data = [];
        $data['projects'] = $this->Activity->get_item();
        $data['reporting_periods'] = $this->Transaction->get_reporting_periods();
        $data['years'] = $this->Transaction->get_years();
        return $data;
    }

    function IncrementalSales_report()
    {
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Reports/IncrementalSales/index', $data);
    }

    function ajax_generate_IncrementalSales_report()
    {
        $post_data = $this->input->post();
        $data = [];
        $data['type'] = $post_data['type'];
        $data['id_reporting_periods'] = $this->Transaction->get_reporting_periods($post_data['id_reporting_periods']);
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($post_data['id_reporting_periods']);
        $data['id_years'] = $this->Transaction->get_years($post_data['id_years']);
        $data['id_years_next1'] = $this->Transaction->get_years($post_data['id_years'] + 1);
        $data['id_years_next2'] = $this->Transaction->get_years($post_data['id_years'] + 2);
        $data['id_years_next3'] = $this->Transaction->get_years($post_data['id_years'] + 3);
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($post_data['id_years'], $post_data['id_reporting_periods']);
        $data['transactions'] = $this->Transaction->IncrementalSales_report_data($post_data['indicator_id'], $data['reporting_periods_type'], $post_data['id_years']);
        $data['sub_header'] = $this->Transaction->get_sub_header($post_data['id_years'], $post_data['id_reporting_periods']);
        if ($post_data['report_type'] == '') {
            $this->load->view('Reports/IncrementalSales/ajax_generate_report', $data);
        } else {
            $html = $this->layout('Reports/IncrementalSales/ajax_generate_report', $data, true, true);
            $this->load->library('m_pdf');
            $mpdf = new mPDF('', 'A2-L');
            $mpdf->WriteHTML($html);
            $file_name = $this->Transaction->get_file_name_info($post_data['id_years'], $post_data['id_reporting_periods'], $post_data['id_projects']);
            $mpdf->Output($file_name . '.pdf', 'D');
        }

    }

    function GrossMargin_report()
    {
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Reports/GrossMargin/index', $data);
    }

    function ajax_generate_GrossMargin_report()
    {

        $post_data = $this->input->post();
        $data = [];
        $data['type'] = $post_data['type'];
        $data['id_reporting_periods'] = $this->Transaction->get_reporting_periods($post_data['id_reporting_periods']);
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($post_data['id_reporting_periods']);
        $data['id_years'] = $this->Transaction->get_years($post_data['id_years']);
        $data['id_years_next1'] = $this->Transaction->get_years($post_data['id_years'] + 1);
        $data['id_years_next2'] = $this->Transaction->get_years($post_data['id_years'] + 2);
        $data['id_years_next3'] = $this->Transaction->get_years($post_data['id_years'] + 3);
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($post_data['id_years'], $post_data['id_reporting_periods']);

        $data['transactions'] = $this->Gross_margin->GrossMargin_report_data($data['reporting_periods_type'], $post_data['id_years']);

        $data['sub_header'] = $this->Transaction->get_sub_header($post_data['id_years'], $post_data['id_reporting_periods']);
        if ($post_data['report_type'] == '') {
            $this->load->view('Reports/GrossMargin/ajax_generate_report', $data);
        } else {
            $html = $this->layout('Reports/GrossMargin/ajax_generate_report', $data, true, true);
            $this->load->library('m_pdf');
            $mpdf = new mPDF('', 'A2-L');
            $mpdf->WriteHTML($html);
            $file_name = $this->Transaction->get_file_name_info($post_data['id_years'], $post_data['id_reporting_periods'], $post_data['id_projects']);
            $mpdf->Output($file_name . '.pdf', 'D');
        }
    }

    function test()
    {
        return 'HELLO THEre';
    }

    function ajax_generate_report()
    {
        $post_data = $this->input->post();

        $data = [];
        $data['type'] = $post_data['type'];
        $data['id_reporting_periods'] = $this->Transaction->get_reporting_periods($post_data['id_reporting_periods']);
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($post_data['id_reporting_periods']);
        $data['id_years'] = $this->Transaction->get_years($post_data['id_years']);

        $data['id_years_next1'] = $this->Transaction->get_years($post_data['id_years'] + 1);
        $data['id_years_next2'] = $this->Transaction->get_years($post_data['id_years'] + 2);
        $data['id_years_next3'] = $this->Transaction->get_years($post_data['id_years'] + 3);
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($post_data['id_years'], $post_data['id_reporting_periods']);
        $data['transactions'] = $this->Transaction->form_data($post_data['id_projects'], null, $data['reporting_periods_type'], $post_data['id_years']);
//        echo '<pre>';
//        print_r($data['transactions']);
//        die;
        $data['sub_header'] = $this->Transaction->get_sub_header($post_data['id_years'], $post_data['id_reporting_periods']);
        $data['report_type'] = $post_data['report_type'];

        if ($post_data['report_type'] == '') {
            $this->load->view('Reports/ajax_generate_report', $data);
        } else {
            $html = $this->layout('Reports/ajax_generate_report', $data, true, true);
            $this->load->library('m_pdf');
            $mpdf = new mPDF('', 'A2-L');
            // $stylesheet = file_get_contents(base_url('applications/views/Layout/css.php'));
//            echo '<pre>';
//            print_r($stylesheet); die;
//            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);
            $file_name = $this->Transaction->get_file_name_info($post_data['id_years'], $post_data['id_reporting_periods'], $post_data['id_projects']);
            $mpdf->Output($file_name . '.pdf', 'D');
        }
    }

    public function data_authorization()
    {
        if ($_POST) {
            $post_data = $this->input->post();
            $data = [];
            $data['type'] = $post_data['type'];
            $data['id_reporting_periods'] = $this->Transaction->get_reporting_periods($post_data['id_reporting_periods']);
            $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($post_data['id_reporting_periods']);
            $data['id_years'] = $this->Transaction->get_years($post_data['id_years']);
            $data['id_years_next1'] = $this->Transaction->get_years($post_data['id_years'] + 1);
            $data['id_years_next2'] = $this->Transaction->get_years($post_data['id_years'] + 2);
            $data['id_years_next3'] = $this->Transaction->get_years($post_data['id_years'] + 3);
            $data['id_time_sets'] = $this->Transaction->get_id_time_sets($post_data['id_years'], $post_data['id_reporting_periods']);
            $data['transactions'] = $this->Transaction->form_data($post_data['id_projects'], null, $data['reporting_periods_type'], $post_data['id_years']);
            $file_name = 'IPreport_' . date();
            $path_name = FCPATH . 'uploads/' . $file_name;
            $html = $this->layout('Reports/ajax_generate_report', $data, true, true);
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
                $this->Transaction->authorization_action($post_data['id_projects'], $post_data['id_reporting_periods'], $post_data['id_years']);
                $this->session->set_flashdata('success', 'Data Authorized successfully');
                redirect('/Reports/data_authorization/');
            } else {
                echo $this->email->print_debugger();
                $this->session->set_flashdata('error', $this->email->print_debugger());
                redirect('/Reports/data_authorization/');
            }

        }
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Reports/authorize_index', $data);
    }

    function data_export()
    {
        $data = $this->_load_combo_data();
        $data['type'] = $data['headline'] = $data['title'] = ucfirst($this->type);
        $this->Layout('Reports/data_export', $data);
    }

    function ajax_data_export_report()
    {
        $post_data = $this->input->post();
        $data = [];
        $data['type'] = $post_data['type'];
        if (isset($post_data['id_periods'])) {
            foreach ($post_data['id_periods'] as $key => $value) {
                $data['id_periods'][$value] = $value;
            }
        }
        $data['reporting_periods'] = $this->Transaction->get_reporting_periods();
        $data['id_reporting_periods'] = $post_data['id_reporting_periods'];
        $data['reporting_periods_type'] = $this->Transaction->get_reporting_peroid_type_by_id($data['id_reporting_periods']);
        $data['id_years'] = $post_data['id_years'];
        $data['show_target'] = (isset($post_data['show_target'])) ? $post_data['show_target'] : '';
        $data['id_time_sets'] = $this->Transaction->get_id_time_sets($post_data['id_years'], $data['id_reporting_periods']);
        $data['transactions'] = $this->Transaction->form_data($post_data['id_projects'], null, $data['reporting_periods_type'], $data['id_years']);
        $data['activity_name'] = $this->Transaction->get_activity_name_by_id($post_data['id_projects']);
        $data['fiscal_year_name'] = $this->Transaction->get_years($post_data['id_years'])['name'];
//        echo '<pre>';
//        print_r($data['activity_name']);
//        exit;
        $this->load->view('Reports/ajax_data_export_view', $data);
    }

    function data_import()
    {
        if ($_POST) {
            $data = $this->input->post();
            if($this->ExportImport->import($data)) {
                $this->session->set_flashdata('success', 'Data Import successfully');
            }
            else{
                $this->session->set_flashdata('success', 'Data Import failed !!');
            }
            redirect('/Reports/data_import');
        }
        $data['headline'] = $data['title'] = 'Import CSV File to Database';
        $this->Layout('Reports/import_file', $data);
    }

}