<?php

/**
 * Indicators Controller Class.
 * @pupose        Manage indicators information
 *
 * @filesource    \app\controllers\Indicators.php

 * @version      $Revision: 1 $
 * @author       $Author: Nadim $
 * @lastmodified $Date: 2017-05-22 $
 */
class Indicators extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Indicator'], '', TRUE);
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
        $data['indicators'] = $this->Indicator->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('Indicators/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Indicator->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Indicator Info';
        $this->layout('Indicators/index', $data);

    }

    public function add()
    {
        $this->_prepare_validation('add');

        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Indicator->add($data)) {
                    $this->session->set_flashdata('success', 'Indicator has been added successfully');
                    redirect('/Indicators/');
                }
            }
        }

        $data['headline'] = $data['title'] = 'Add Indicator';
        $data['action'] = 'add';
        $this->layout('Indicators/save', $data);
    }

    function edit($id)
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Indicator->edit($id, $data)) {
                    $this->session->set_flashdata('success', 'Indicators has been updated successfully');
                    redirect('/Indicators/index/');
                }
            }
        }
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Indicator->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Indicator Info';
        $this->layout('Indicators/save', $data);
    }

    function delete($id)
    {
        if ($this->Indicator->delete($id)) {
            $this->session->set_flashdata('success', 'Indicators has been deleted successfully');
            redirect('/Indicators/index/');
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
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|is_unique[indicators.name]|max_length[100]');
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|is_unique[indicators.code]|max_length[100]');
        }
        if ($action == "edit") {
            $id = $this->uri->segment(3);
            $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|edit_unique[indicators.code.' . $id . ']|max_length[100]');
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|max_length[100]|edit_unique[indicators.name.' . $id . ']|max_length[100]');
        }

    }
}
