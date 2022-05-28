<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/29/17
 * Time: 2:59 PM
 */
class Project_indicators extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'html']);
        $this->load->model(['Project_indicator', 'Indicator', 'Activity'], '', TRUE);
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       30/05/2017
     * @lastmodified Date: 30/05/2017
     */

    function index()
    {
        $data = $cond = $this->input->get();
        $data['counter'] = $this->counter();
        $data['project_indicators'] = $this->Project_indicator->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('project_indicators/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Project_indicator->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Project indicators  Info';
        $this->Layout('Project_indicators/index', $data);

    }

    /**
     * Action for add new data
     * @uses    insert info
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */

    public function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();

            if ($this->form_validation->run() === TRUE) {
                if ($this->Project_indicator->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/project_indicators/');
                }
            }
        }

        $data['headline'] = $data['title'] = 'Add Project Indicators ';
        $data['action'] = 'add';
        $data['projects'] = $this->Activity->get_item();
        $data['indicators'] = $this->Indicator->get_item();
//        echo '<pre>'; print_r($data); die;
        $this->layout('Project_indicators/save', $data);
    }

    /**
     * Action for get  data
     * @uses    get code data using ajax
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       30/05/2017
     * @lastmodified Date: 30/05/2017
     */
    function ajax_get_code_by_id()
    {
        $data = $this->input->post();
        echo $this->Project_indicator->get_code_by_id($data['id_projects'], $data['id_indicators']) . '-' . $data['reporting_text'] . '-' . $data['indicator_type_text'];
    }

    /**
     * Action for edit  data
     * @uses    edit  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       30/05/2017
     * @lastmodified Date: 30/05/2017
     */
    function edit($id)
    {

        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Project_indicator->edit($id, $data)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/project_indicators/');
                }
            }
        }
        $data['projects'] = $this->Activity->get_item();
        $data['indicators'] = $this->Indicator->get_item();
//        echo '<pre>';
//        print_r($data);
//        die;
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Project_indicator->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Project indicator Info';
        $this->layout('Project_indicators/save', $data);
    }

    /**
     * Action for delete  data
     * @uses    delete  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function delete($id)
    {
        if ($this->Project_indicator->delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/project_indicators/');
        }
    }

    /**
     * Action for  set validation
     * @uses    validation  form
     * @access  public
     * @param   $action
     * @return
     * @author  Sara
     * @createdon       01/06/2017
     * @lastmodified Date: 3/06/2017
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_projects', 'Activity', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('id_indicators', 'Indicator', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('reporting_period', 'Reporting Period', 'required|callback_check_unique[]');
    }

    function check_unique()
    {
        $id_projects = $this->input->post('id_projects');
        $reporting_period = $this->input->post('reporting_period');
        $id_indicators = $this->input->post('id_indicators');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            if (!$this->Project_indicator->simple_check('project_indicators', ['reporting_period' => $reporting_period, 'id_indicators' => $id_indicators, 'id_projects' => $id_projects])) {
                $this->form_validation->set_message('check_unique',EXISTS);
                return false;
            } else {
                return true;
            }
        } else {
            $id = $this->uri->segment(3);
            if (!$this->Project_indicator->simple_check('project_indicators', ['reporting_period' => $reporting_period, 'id_indicators' => $id_indicators, 'id_projects' => $id_projects, 'id!=' => $id])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        }
    }

}


