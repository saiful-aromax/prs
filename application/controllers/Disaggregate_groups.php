<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/23/17
 * Time: 5:14 PM
 */
class Disaggregate_groups extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Disaggregate_group', 'Disaggregate_tier'], '', TRUE);
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */

    function index()
    {
        $data = $cond = $this->input->get();
        $data['counter'] = $this->counter();
        $data['disaggregate_groups'] = $this->Disaggregate_group->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('disaggregate_groups/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Disaggregate_group->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Disaggregate Group Info';
//        echo '<pre>'; print_r($data); die;
        $this->Layout('Disaggregate_groups/index', $data);

    }

    function draft_index()
    {
        $data = [];
        $this->Layout('Draft_data_entry/index', $data);

    }

    /**
     * Action for add new data
     * @uses    insert info
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    public function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
//                    echo '<pre>'; print_r($data); die;

            if ($this->form_validation->run() === TRUE) {
                if ($this->Disaggregate_group->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/disaggregate_groups/');
                }
            }
        }

        $data = [];
        $data['headline'] = $data['title'] = 'Add Disaggregate group';
        $data['action'] = 'add';
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        //echo '<pre>'; print_r($data['tiers']); die;
        $this->layout('Disaggregate_groups/save', $data);
    }

    /**
     * Action for edit  data
     * @uses    edit  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function edit($id)
    {

        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Disaggregate_group->edit($id, $data)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/disaggregate_groups/index/');
                }
            }
        }
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Disaggregate_group->get_info_by_id($id);

        $data['headline'] = $data['title'] = 'Update Disaggregate group Info';
        $this->layout('Disaggregate_groups/save', $data);
    }

    /**
     * Action for delete  data
     * @uses    delete  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function delete($id)
    {
        if ($this->Disaggregate_group->delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/disaggregate_groups/index/');
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
        $this->form_validation->set_rules('code', 'Code', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('id_disaggregate_tiers', 'Select Tier', 'required|callback_check_unique[]');
    }

    function check_unique()
    {
        $id_disaggregate_tiers = $this->input->post('id_disaggregate_tiers');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            if (!$this->Disaggregate_group->simple_check('disaggregate_groups', ['id_disaggregate_tiers' => $id_disaggregate_tiers, 'name' => $name, 'code' => $code])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        } else {
            $id = $this->uri->segment(3);
            if (!$this->Disaggregate_group->simple_check('disaggregate_groups', ['id_disaggregate_tiers' => $id_disaggregate_tiers, 'name' => $name, 'code' => $code, 'id!=' => $id])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        }
    }


}


