<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/22/17
 * Time: 3:57 PM
 */
class Commodities extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Commodity'], '', TRUE);
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
        $data['Commodities'] = $this->Commodity->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('Commodities/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Commodity->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Commodities Info';
        // echo '<pre>'; print_r($data); die;
        $this->Layout('Commodities/index', $data);

    }

    /**
     * Action for get all individual info
     * @uses    To Show all individual info
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       03/05/2017
     * @lastmodified Date: 03/05/2017
     */

    public function add()
    {
        $this->_prepare_validation();

        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Commodity->add($data)) {
                    $this->session->set_flashdata('success', 'Commodity has been added successfully');
                    redirect('/Commodities/');
                }
            }
        }
        $data = [];
        $data['headline'] = $data['title'] = 'Add Commodities';
        $data['action'] = 'add';
        $this->layout('Commodities/save', $data);
    }

    /**
     * Action for edit info
     * @uses    To edit info
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       03/05/2017
     * @lastmodified Date: 03/05/2017
     */
    function edit($id)
    {

        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Commodity->edit($id, $data)) {
                    $this->session->set_flashdata('success', 'Commodity has been updated successfully');
                    redirect('/Commodities/index/');
                }
            }
        }
        $data = [];
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Commodity->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Commodities Info';
        $this->layout('Commodities/save', $data);
    }

    /**
     * Action for delete info
     * @uses    To delete info
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       03/05/2017
     * @lastmodified Date: 03/05/2017
     */
    function delete($id)
    {
        if ($this->Commodity->delete($id)) {
            $this->session->set_flashdata('success', 'Commodities has been updated successfully');
            redirect('/Commodities/index/');
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
     * @lastmodified Date: 1/06/2017
     */
    function _prepare_validation()
    {
        $this->load->library('form_validation');
        $action = $this->uri->segment(2);
        if ($action == "add") {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|is_unique[commodities.name]|max_length[100]');
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|is_unique[commodities.code]|max_length[100]');
        }
        if ($action == "edit") {
            $id = $this->uri->segment(3);
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|edit_unique[commodities.code.' . $id . ']|max_length[100]');
        }
    }
}


