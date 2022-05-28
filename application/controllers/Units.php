<?php

/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/25/17
 * Time: 9:38 AM
 */
class Units extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Unit'], '', TRUE);
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

    function index()
    {

        $data = $cond = $this->input->get();
        $data['counter'] = $this->counter();
        $data['units'] = $this->Unit->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('Units/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Unit->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Unit Info';
        // echo '<pre>'; print_r($data); die;
        $this->Layout('Units/index', $data);
    }


    public function add()
    {
        $this->_prepare_validation('add');

        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Unit->add($data)) {
                    $this->session->set_flashdata('success', 'Unit has been added successfully');
                    redirect('/Units');
                }
            }
        }
        $data = [];
        $data['headline'] = $data['title'] = 'Add Unit';
        $data['action'] = 'add';
        $this->layout('Units/save', $data);
    }

    function edit($id)
    {

        $this->_prepare_validation('edit');
        if ($_POST) {
            $data = $this->input->post();

            if ($this->Unit->edit($id, $data)) {
                $this->session->set_flashdata('success', 'Unit has been updated successfully');
                redirect('/Units');
            }
        }
        $data = [];
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Unit->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Unit Info';
        $this->layout('Units/save', $data);
    }

    function delete($id)
    {
        $has_unit_entry = $this->Unit->is_dependency_found('disaggregate_sets', array('unit_id' => $id));
        if ($has_unit_entry) {
            $this->session->set_flashdata('warning', DEPENDENT_DATA_FOUND);
            redirect('/Units/index/');
        }
        if ($this->Unit->delete($id)) {
            $this->session->set_flashdata('success', 'Unit has been updated successfully');
            redirect('/Units/index/');
        }
    }

    function _prepare_validation($id = 1)
    {
        //Loading Validation Library to Perform Validation
        $this->load->library('form_validation');
        $action = $this->uri->segment(2);
        //Setting Validation Rule
        if ($action == "add") {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|is_unique[units.name]|max_length[100]');
        }
        if ($action == "edit") {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|edit_unique[units.name.' . $id . ']|max_length[100]');
        }
        // $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]');
        //echo '<pre>';print_r($this->form_validation);exit;
    }

}