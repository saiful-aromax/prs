<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/28/17
 * Time: 11:01 AM
 */
class Disaggregate_sets extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'html']);
        $this->load->model(['Unit', 'Disaggregate_group', 'Disaggregate_tier', 'Disaggregate_set', 'Disaggregate'], '', TRUE);
    }

    /**
     * Action for get all data
     * @uses    To get all data in a index page
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */

    function index()
    {
        $data = $cond = $this->input->get();
        $data['counter'] = $this->counter();
        $data['disaggregate_sets'] = $this->Disaggregate_set->get_list($data['counter'], ROW_PER_PAGE, $cond);
        $config = [];
        $config['base_url'] = site_url('disaggregate_sets/index/');
        $data['total_rows'] = $config['total_rows'] = $this->Disaggregate_set->get_list(0, 0, $cond);
        $this->paginate($config);
        $data['headline'] = $data['title'] = 'Disaggregate Set Info';
        $this->Layout('Disaggregate_sets/index', $data);

    }

    /**
     * Action for add new data
     * @uses    insert info
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       28/05/2017
     * @lastmodified Date: 28/05/2017
     */

    public function add()
    {
        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Disaggregate_set->add($data)) {
                    $this->session->set_flashdata('success', ADD_MESSAGE);
                    redirect('/disaggregate_sets');
                }
            }
        }

        $data = $this->_load_combo_data();
        $data['headline'] = $data['title'] = 'Add Disaggregate Set';
        $data['action'] = 'add';
        $this->layout('Disaggregate_sets/save', $data);
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
        $data['function'] = "ajax_get_disaggregate_by_disaggregate_group_id(this.value);";
        $data['field_name'] = 'id_disaggregate_groups';
        $id_disaggregate_tiers = $this->input->post('id_disaggregate_tiers');
        $data['options'] = $this->Disaggregate_group->get_item($id_disaggregate_tiers);
        $this->load->view('Disaggregate_sets/ajax_get_options', $data);
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
    function ajax_get_disaggregate_by_disaggregate_group_id()
    {
        $data = [];
        $data['function'] = "ajax_get_code_by_disaggregate_id()";
        $data['field_name'] = 'id_disaggregates';
        $id_disaggregate_groups = $this->input->post('id_disaggregate_groups');
        $data['options'] = $this->Disaggregate->get_item($id_disaggregate_groups);
//        echo '<pre>';print_r($data);die;
        $this->load->view('Disaggregate_sets/ajax_get_options', $data);
    }

    /**
     * Action for get  data
     * @uses    get code data using ajax
     * @access  public
     * @param
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function ajax_get_code_by_disaggregate_id()
    {
        echo $this->Disaggregate_set->get_code_by_disaggregate_id($this->input->post('id_disaggregates'));
    }


    /**
     * Action for edit  data
     * @uses    edit  data
     * @access  public
     * @param   $id
     * @return
     * @author  Sara
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function edit($id)
    {

        $this->_prepare_validation();
        if ($_POST) {
            $data = $this->input->post();
            if ($this->form_validation->run() === TRUE) {
                if ($this->Disaggregate_set->edit($id, $data)) {
                    $this->session->set_flashdata('success', EDIT_MESSAGE);
                    redirect('/disaggregate_sets/');
                }
            }
        }
        $data = $this->_load_combo_data($id);
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        $data['action'] = 'edit/' . $id;
        $data['row'] = $this->Disaggregate_set->get_info_by_id($id);
        $data['headline'] = $data['title'] = 'Update Disaggregate sets Info';
        $this->layout('Disaggregate_sets/save', $data);
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
        if ($this->Disaggregate_set->delete($id)) {
            $this->session->set_flashdata('success', DELETE_MESSAGE);
            redirect('/disaggregate_groups/index/');
        }
    }

    function _load_combo_data($id = null)
    {
        $row = $this->Disaggregate_set->get_info_by_id($id);
        $data = [];
        $data['units'] = $this->Unit->get_item();
        $data['disaggregate_groups'] = ($id == null ? [] : $this->Disaggregate_group->get_item($row->id_disaggregate_tiers));
        $data['disaggregates'] = ($id == null ? [] : $this->Disaggregate->get_item($row->id_disaggregate_groups));
        $data['tiers'] = $this->Disaggregate_tier->get_item();
        return $data;
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
        $this->form_validation->set_rules('id_disaggregate_tiers', 'Tier', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('id_disaggregate_groups', 'Disaggregate Group', 'required|trim|xss_clean|strip_tags|callback_check_unique[]|max_length[100]');
        $this->form_validation->set_rules('id_disaggregates', 'Disaggregate', 'required|callback_check_unique[]');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required|callback_check_unique[]');
    }

    function check_unique()
    {
        $id_disaggregate_tiers = $this->input->post('id_disaggregate_tiers');
        $id_disaggregate_groups = $this->input->post('id_disaggregate_groups');
        $id_disaggregates = $this->input->post('id_disaggregates');
        $unit_id = $this->input->post('unit_id');
        $action = $this->uri->segment(2);
        if ($action == 'add') {
            if (!$this->Disaggregate_set->simple_check('disaggregate_sets', ['id_disaggregate_groups' => $id_disaggregate_groups, 'unit_id' => $unit_id, 'id_disaggregates' => $id_disaggregates, 'id_disaggregate_tiers' => $id_disaggregate_tiers])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        } else {
            $id = $this->uri->segment(3);
            if (!$this->Disaggregate_set->simple_check('disaggregate_sets', ['id_disaggregate_groups' => $id_disaggregate_groups, 'unit_id' => $unit_id, 'id_disaggregates' => $id_disaggregates, 'id_disaggregate_tiers' => $id_disaggregate_tiers, 'id!=' => $id])) {
                $this->form_validation->set_message('check_unique', EXISTS);
                return false;
            } else {
                return true;
            }
        }
    }


}


