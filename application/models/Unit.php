<?php

/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/25/17
 * Time: 9:38 AM
 */
class Unit extends MY_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_list($offset, $limit, $cond)
    {

        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select('units.*');
        }

        $this->db->from('units');
        if (!empty($cond['name'])) {
            $where = "( units.name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }


        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('units.name', 'ASC');
        $query = $this->db->get();
        $query->result();
        return $query->result();
    }

    /**
     * Reads data of specific indicators
     * @author  :   Sara
     * @uses    :   To  read data of specific indicators
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     */
    function get_info_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('units');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function get_item(){
        $this->db->select('*');
        $this->db->from('units');
        $query =  $this->db->get();
        $result_array = [];
        foreach ($query->result() as $row){
            $result_array[$row->id] =$row->name;
        }
        return $result_array;
    }

    function add($data)
    {
        return $this->db->insert('units', $data);
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
        return $this->db->update('units', $data, ['id' => $id]);
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
        return $this->db->delete('units', ['id' => $id]);
    }

}