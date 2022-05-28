<?php

/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/24/17
 * Time: 2:31 PM
 */
class Activity extends MY_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     * Generates a list of user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To Generate a list of user roles
     * @access  :   public
     * @param   :   int $parent_role_id
     * @return  :   array
     */

    function get_list($offset, $limit, $cond)
    {

        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select('projects.*');
        }

        $this->db->from('projects');
        if (!empty($cond['name'])) {
            $where = "( projects.name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }


        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('projects.name', 'ASC');
        $query = $this->db->get();
        $query->result();
        return $query->result();
    }

    function get_item()
    {
        $this->db->select('*');
        $this->db->from('projects');
        $query = $this->db->get();
        $result_array = [];
        foreach ($query->result() as $row) {
            $result_array[$row->id] = $row->name . '-' . $row->code;
        }
        return $result_array;
    }

    function get_info_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function add($data)
    {
        return $this->db->insert('projects', $data);
    }

    /**
     * Reads data of specific indicators
     * @author  :   Sara
     * @uses    :   To  read data of specific indicators
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     */
//    function get_list_by_id($id)
//    {
//        $query=$this->db->where('tiers', array('id' => $id));
//        return $query->row();
//    }

    function edit($id, $data)
    {
        return $this->db->update('projects', $data, ['id' => $id]);
    }

    /**
     * Deletes particular data
     * @author  :   Sara
     * @uses    :   To delete particular data
     * @access  :   public
     * @param   :   int $id, $delete_by
     * @return  :   boolean
     */
    function delete($id)
    {
        return $this->db->delete('projects', ['id' => $id]);
    }
}