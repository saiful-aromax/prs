<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/28/17
 * Time: 11:02 AM
 */
class Disaggregate_set extends MY_Model
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
            $this->db->select("disaggregate_sets.code,CONCAT(disaggregates.name,'-',disaggregate_groups.name,'-',disaggregate_tiers.name,'-',units.name) disaggregate_set, disaggregate_sets.id");
        }
        $this->db->from('disaggregate_sets');

        $this->db->join('disaggregates', 'disaggregate_sets.id_disaggregates = disaggregates.id', 'inner');
        $this->db->join('disaggregate_groups', 'disaggregates.id_disaggregate_groups = disaggregate_groups.id', 'inner');
        $this->db->join('disaggregate_tiers', 'disaggregate_groups.id_disaggregate_tiers = disaggregate_tiers.id', 'inner');
        $this->db->join('units', 'disaggregate_sets.unit_id = units.id', 'inner');
        if (!empty($cond['code'])) {
            $where = "( disaggregate_sets.code LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }
        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('disaggregate_sets.code', 'ASC');
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
        return $this->db->insert('disaggregate_sets', $data);
    }

    function get_code_by_disaggregate_id($disaggregate_id)
    {
        return $this->db->query("SELECT 
  CONCAT(
    dt.`code`,
    '-',
    dg.`code`,
    '-',
    d.`code`
  ) `code` 
FROM
  `disaggregates` d 
  INNER JOIN `disaggregate_groups` dg 
    ON dg.`id` = d.`id_disaggregate_groups` 
  INNER JOIN `disaggregate_tiers` dt 
    ON dt.`id` = dg.`id_disaggregate_tiers` 
WHERE d.`id` = '$disaggregate_id'")->row()->code;
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
        return $this->db->query("SELECT * FROM `disaggregate_sets` WHERE `id` = '$id'")->row();
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
        return $this->db->update('disaggregate_sets', $data, ['id' => $id]);
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
        return $this->db->delete('disaggregate_sets', ['id' => $id]);
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

    function get_item()
    {
        $this->db->select('*');
        $this->db->from('disaggregate_sets');
        $query = $this->db->get();
        $result_array = [];
        foreach ($query->result() as $row) {
            $result_array[$row->id] = $row->code;
        }
        return $result_array;
    }
}


