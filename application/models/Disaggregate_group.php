<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/23/17
 * Time: 5:14 PM
 */
class Disaggregate_group extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get data
     * @author  :   Sara
     * @uses    :   To  get data
     * @access  :   public
     * @param   :   $offset, $limit, $cond
     * @return  :   array
     */
    function get_list($offset, $limit, $cond)
    {

        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select('disaggregate_groups.*, disaggregate_tiers.name disaggregate_tier_name, disaggregate_tiers.code disaggregate_tier_code');
        }
        $this->db->from('disaggregate_groups');
        $this->db->join('disaggregate_tiers', 'disaggregate_groups.id_disaggregate_tiers = disaggregate_tiers.id', 'inner');
        if (!empty($cond['name'])) {
            $where = "( disaggregate_groups.name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }


        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('disaggregate_tiers.name, disaggregate_groups.name', 'ASC');
        $query = $this->db->get();
//        $query->result();
//        echo $this->db->last_query();
//        die;
        return $query->result();
    }

    /**
     * Reads data of specific tiers
     * @author  :   Sara
     * @uses    :   To  read data of specific tiers
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function get_info_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('disaggregate_groups');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

//    function get_item()
//    {
//        $this->db->select('*');
//        $this->db->from('disaggregate_groups');
//        $query = $this->db->get();
//        $result_array = [];
//        foreach ($query->result() as $row) {
//            $result_array[$row->id] = $row->name . '-' . $row->code;
//        }
//        return $result_array;
//    }

    public function get_item($id_disaggregate_tiers)
    {
        $returnArray = [];
        $disaggregate_groups = $this->db->query("SELECT `id`, `name`, `code` FROM `disaggregate_groups` WHERE `id_disaggregate_tiers` = '$id_disaggregate_tiers' ORDER BY `name`")->result();
//        echo '<pre>';print_r($disaggregate_groups);die;
        $count = count($disaggregate_groups);
        for ($i = 0; $i < $count; $i++) {
            $returnArray[$disaggregate_groups[$i]->id] = $disaggregate_groups[$i]->name . ' (' . $disaggregate_groups[$i]->code . ')';
        }
        return $returnArray;
    }


//    function get_disaggregate_group_by_id($id)
//    {
//        $this->db->select('disaggregate_groups.*');
//        $this->db->from('disaggregate_groups');
//        $this->db->join('disaggregate_tiers', 'disaggregate_groups.id_disaggregate_tiers = disaggregate_tiers.id', 'inner');
//        $this->db->where('disaggregate_groups.id', $id);
//        return $this->db->get()->row();
//    }

    /**
     * Add particular data
     * @author  :   Sara
     * @uses    :   To Add particular data
     * @access  :   public
     * @param   :   $data
     * @return  :   boolean
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function add($data)
    {
        //echo '<pre>'; print_r($data); die;
        return $this->db->insert('disaggregate_groups', $data);
    }

    /**
     * Edit particular data
     * @author  :   Sara
     * @uses    :   To Edit particular data
     * @access  :   public
     * @param   :    $id, $data
     * @return  :   boolean
     * @createdon       23/05/2017
     * @lastmodified Date: 23/05/2017
     */
    function edit($id, $data)
    {
        return $this->db->update('disaggregate_groups', $data, ['id' => $id]);
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
        return $this->db->delete('disaggregate_groups', ['id' => $id]);
    }
}