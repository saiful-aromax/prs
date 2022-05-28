<?php

/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/25/17
 * Time: 10:07 AM
 */
class Activites extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Activity'], '', TRUE);
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
        $data['activites'] = $this->Activity->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('Activites/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Activity->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Activity Info';
        // echo '<pre>'; print_r($data); die;
        $this->Layout('Activites/index', $data);

    }

    public function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Activity->add($data)) {
                    $this->session->set_flashdata('success', 'Activity has been added successfully');
                    redirect('/Activites/');
                }
            }
        }
        $data = [];
        $data['headline'] = $data['title'] = 'Add Activites';
        $data['action'] = 'add';
        $this->layout('Activites/save', $data);
    }

    function edit($id)
    {

        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Activity->edit($id, $data)) {
                    $this->session->set_flashdata('success', 'Activity has been updated successfully');
                    redirect('/Activites/');
                }
            }
        }
        $data = [];
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Activity->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Activites Info';
        $this->layout('Activites/save', $data);
    }

    function delete($id)
    {
        if ($this->Activity->delete($id)) {
            $this->session->set_flashdata('success', 'Activity has been updated successfully');
            redirect('/Activites/');
        }
    }

    /**

     * @uses    To set up validations on various fiels
     * @access  private
     * @param
     * @return
     * @author  Sara
     * @createdon       22/05/2017
     * @lastmodified Date: 22/05/2017
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $action = $this->uri->segment(2);
        if ($action == "add") {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|is_unique[projects.name]|max_length[100]');
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|is_unique[projects.code]|max_length[100]');
            $this->form_validation->set_rules('organization_name', 'Organization Name', 'required|trim|xss_clean|strip_tags|is_unique[projects.organization_name]|max_length[100]');
        }
        if ($action == "edit") {
            $id = $this->uri->segment(3);
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|edit_unique[projects.name.' . $id . ']|max_length[100]');
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|edit_unique[projects.code.' . $id . ']|max_length[100]');
            $this->form_validation->set_rules('organization_name', 'Organization Name', 'required|trim|xss_clean|strip_tags|edit_unique[projects.organization_name.' . $id . ']|max_length[100]');

        }
        $this->form_validation->set_rules('cor_email', 'CoR Email', 'required|trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('acme_email', 'Acme Email', 'required|trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('cor_name', 'CoR Name', 'required|trim|xss_clean|strip_tags');

    }

}