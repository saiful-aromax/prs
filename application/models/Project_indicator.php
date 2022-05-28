<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/29/17
 * Time: 2:59 PM
 */
class Project_indicator extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_list($offset, $limit, $cond)
    {
        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select("project_indicators.*, projects.name project_name,indicators.name indicator_name");
        }
        $this->db->from('project_indicators');
        $this->db->join('projects', 'project_indicators.id_projects = projects.id', 'inner');
        $this->db->join('indicators', 'project_indicators.id_indicators = indicators.id', 'inner');
        if (!empty($cond['code'])) {
            $where = "( project_indicators.code LIKE '%{$cond['code']}%')";
            $this->db->where($where);
        }
        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('project_indicators.code', 'ASC');
        $query = $this->db->get();
        $query->result();
        return $query->result();
    }

    /**
     * add data
     * @author  :   Sara
     * @uses    :   To insert  data
     * @access  :   public
     * @param   :    $id, $data
     * @return  :   array
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function add($data)
    {
        return $this->db->insert('project_indicators', $data);
    }

    function get_code_by_id($id_projects, $id_indicators)
    {
        $project_code = $this->db->query("SELECT `code` FROM `projects` WHERE `id` = '$id_projects'")->row()->code;
        $indicator_code = $this->db->query("SELECT `code` FROM `indicators` WHERE `id` = '$id_indicators'")->row()->code;
        return $project_code . '-' . $indicator_code;
    }


    /**
     * get particular data
     * @author  :   Sara
     * @uses    :   To get particular data
     * @access  :   public
     * @param   :    $id
     * @return  :   Array
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */

    function get_info_by_id($id)
    {
        return $this->db->query("SELECT * FROM `project_indicators` WHERE `id` = '$id'")->row();

    }

    /**
     * Edit particular data
     * @author  :   Sara
     * @uses    :   To Edit particular data
     * @access  :   public
     * @param   :    $id, $data
     * @return  :   boolean
     * @createdon       29/05/2017
     * @lastmodified Date: 29/05/2017
     */
    function edit($id, $data)
    {
        return $this->db->update('project_indicators', $data, ['id' => $id]);
    }

    /**
     * Deletes particular data
     * @author  :   Sara
     * @uses    :   To delete particular data
     * @access  :   public
     * @param   :   int $id, $delete_by
     * @return  :   boolean
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function delete($id)
    {
        return $this->db->delete('project_indicators', ['id' => $id]);
    }

    /**
     * get  data
     * @author  :   Sara
     * @uses    :   To get all  data
     * @access  :   public
     * @param   :
     * @return  :   array
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */

    function get_item(){
        $this->db->select('*');
        $this->db->from('project_indicators');
        $query =  $this->db->get();
        $result_array = [];
        foreach ($query->result() as $row){
            $result_array[$row->id] =$row->code;
        }
        return $result_array;
    }
}
//
//
