<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/31/17
 * Time: 10:22 AM
 */
class Project_indicator_disaggregate_sets extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'html']);
        $this->load->model(['Project_indicator', 'Project_indicator_disaggregate_set', 'Disaggregate_set', 'Commodity'], '', TRUE);
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */

    function index()
    {
        $data = $cond = $this->input->get();
        $data['counter'] = $this->counter();
        $data['indicator_disaggregates'] = $this->Project_indicator_disaggregate_set->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        //print_r($data);exit;
        $config['base_url'] = site_url('project_indicators/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Project_indicator_disaggregate_set->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Project indicator disaggregate set Info';
        $this->Layout('Project_indicator_disaggregate_sets/index', $data);

    }

    function view($pi_id,$c_id)
    {
        $data = $cond = $this->input->get();
        $data['counter'] = 0;
        $data['disaggregate_set'] = $this->Project_indicator_disaggregate_set->get_disaggregate_set($pi_id,$c_id, $cond);
        $data['pi_id'] = $pi_id;
        $data['c_id'] = $c_id;
        $config = [];
        // print_r($data);exit;
        $config['base_url'] = site_url('project_indicators/view/' . $pi_id.'/'.$c_id);
        $data['total_rows'] = $config['total_rows'] = $this->Project_indicator_disaggregate_set->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Project indicator disaggregate set Info';
        $this->Layout('Project_indicator_disaggregate_sets/view', $data);

    }

    /**
     * Action for add new data
     * @uses    insert info
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */

    public function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
//           echo '<pre>';
//           print_r($data);
//           die;
            if ($this->form_validation->run() === TRUE) {
                if ($this->Project_indicator_disaggregate_set->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/project_indicator_disaggregate_sets');
                }
            }
        }

        $data = [];
        $data['headline'] = $data['title'] = 'Add Project Indicator Disaggregate set ';
        $data['action'] = 'add';
        $data['project_indicator'] = $this->Project_indicator->get_item();
        $data['disaggregate_sets'] = $this->Disaggregate_set->get_item();
        $data['commodities'] = $this->Commodity->get_item();
//        echo '<pre>'; print_r($data); die;
        $this->layout('Project_indicator_disaggregate_sets/save', $data);
    }

    /**
     * Action for get  data
     * @uses    ajax  data
     * @access  public
     * @param
     * @return
     * @author  nur
     * @createdon       08/06/2017
     * @lastmodified Date: 08/06/2017
     */
    function ajax_get_disaggregate_list_by_project_indicator_id()
    {
        $data = [];
        $data['disaggregate_sets'] = $this->Disaggregate_set->get_item();
        $data['disaggregate_set'] = $this->Project_indicator_disaggregate_set->get_disaggregate_set($this->input->post('project_indicator_id'));
        $this->load->view('Project_indicator_disaggregate_sets/ajax_get_disaggregate_set', $data);
    }

    /**
     * Action for edit  data
     * @uses    edit  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */
    function edit($id_project_indicators,$id_commodity)
    {

        if ($_POST) {
            $data = $this->input->post();
            $data['id_project_indicators'] = $id_project_indicators;
            if ($this->Project_indicator_disaggregate_set->edit($id_project_indicators, $data)) {
                $this->session->set_flashdata('success', EDIT_MESSAGE);
                redirect('/project_indicator_disaggregate_sets/');
            }
        }
        $data = [];
        $data['project_indicator'] = $this->Project_indicator->get_item();
        $data['disaggregate_sets'] = $this->Disaggregate_set->get_item();
        $data['commodities'] = $this->Commodity->get_item();
//        echo '<pre>';
//        print_r($data);
//        die;
        $data['action'] = 'edit/' . $id_project_indicators.'/'.$id_commodity;
        $data['selected_disaggregate_set'] = $this->Project_indicator_disaggregate_set->get_disaggregate_set($id_project_indicators,$id_commodity);
        $data['selected_commodity'] = $this->Project_indicator_disaggregate_set->get_commodities($id_project_indicators);
        $data['row'] = $this->Project_indicator_disaggregate_set->get_info_by_id($id_project_indicators);
        $data['headline'] = $data['title'] = 'Update Project indicator disaggregate sets Info';
        $this->layout('Project_indicator_disaggregate_sets/save', $data);
    }

    /**
     * Action for delete  data
     * @uses    delete  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */
    function delete($id, $pi_id, $c_id)
    {
        if ($this->Project_indicator_disaggregate_set->delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/project_indicator_disaggregate_sets/view/' . $pi_id.'/'.$c_id);
        }
    }

    function project_indicator_delete($id)
    {
        if ($this->Project_indicator_disaggregate_set->project_indicator_delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/project_indicator_disaggregate_sets/');
        }
    }

    /**
     * Action for  set validation
     * @uses    validation  form
     * @access  public
     * @param   $action
     * @return
     * @author  Sara
     * @createdon       03/06/2017
     * @lastmodified Date: 3/06/2017
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_project_indicators', 'Project Indicator Code', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
    }

    function check_unique()
    {
        $id_project_indicators = $this->input->post('id_project_indicators');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            if (!$this->Project_indicator_disaggregate_set->simple_check('project_indicator_disaggregate_sets', ['id_project_indicators' => $id_project_indicators])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        } else {
            $id = $this->uri->segment(3);
            if (!$this->Project_indicator_disaggregate_set->simple_check('project_indicator_disaggregate_sets', ['id_project_indicators' => $id_project_indicators, 'id!=' => $id])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        }
    }

}


