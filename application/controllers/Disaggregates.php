<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/22/17
 * Time: 3:15 PM
 */
class Disaggregates extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model(['Disaggregate', 'Disaggregate_group', 'Disaggregate_tier'], '', TRUE);
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
        $data['disaggregates'] = $this->Disaggregate->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('disaggregates/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Disaggregate->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Disaggregates Info';
        $this->Layout('Disaggregates/index', $data);
    }

    /**
     * Action for insert data
     * @uses    To add data
     * @access  public
     * @param
     * @return  $data
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
                if ($this->Disaggregate->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/disaggregates/');
                }
            }
        }
        $data = $this->_load_combo_data();
        $data['headline'] = $data['title'] = 'Add Disaggregates';
        $data['action'] = 'add';
        $this->layout('Disaggregates/save', $data);
    }

    function _load_combo_data($id = null)
    {
        $data = [];
        if ($id != null) {
            $data['disaggregate_groups'] = $this->Disaggregate_group->get_item($this->Disaggregate->get_info_by_id($id)->id_disaggregate_tiers);
        } else {
            $data['disaggregate_groups'] = [];
        }
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        return $data;
    }

    /**
     * Action for get  data
     * @uses    ajax  data
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function ajax_get_disaggregate_group_by_tier_id()
    {
        $data = [];
        $data['function'] = "";
        $data['field_name'] = 'id_disaggregate_groups';
        $id_disaggregate_tiers = $this->input->post('id_disaggregate_tiers');
        $data['options'] = $this->Disaggregate_group->get_item($id_disaggregate_tiers);
        $this->load->view('Disaggregates/ajax_get_options', $data);
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
                if ($this->Disaggregate->edit($id, $data)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/Disaggregates/');
                }
            }
        }
        $data = $this->_load_combo_data($id);
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Disaggregate->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Disaggregate Info';
        $this->layout('Disaggregates/save', $data);
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
        $has_unit_entry = $this->Unit->is_dependency_found('disaggregate_sets', array('unit_id' => $id));
        if ($has_unit_entry) {
            $this->session->set_flashdata('warning', DEPENDENT_DATA_FOUND);
            redirect('/Units/index/');
        }
        if ($this->Disaggregate->delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/Disaggregates/index/');
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
        $this->form_validation->set_rules('id_disaggregate_tiers', 'Disaggregate Tier', 'required|callback_check_unique[]');
        $this->form_validation->set_rules('id_disaggregate_groups', 'Disaggregate Group', 'required|callback_check_unique[]');
    }

    function check_unique()
    {
        $id_disaggregate_groups = $this->input->post('id_disaggregate_groups');
        $id_disaggregate_tiers = $this->input->post('id_disaggregate_tiers');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            if (!$this->Disaggregate->check_unique(['disaggregate_groups.id' => $id_disaggregate_groups, 'disaggregate_tiers.id' => $id_disaggregate_tiers, 'disaggregates.name' => $name, 'disaggregates.code' => $code])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        } else {
            $id = $this->uri->segment(3);
            if (!$this->Disaggregate->check_unique(['disaggregate_groups.id' => $id_disaggregate_groups, 'disaggregate_tiers.id' => $id_disaggregate_tiers, 'disaggregates.name' => $name, 'disaggregates.code' => $code, 'disaggregates.id!=' => $id])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        }
    }

}


