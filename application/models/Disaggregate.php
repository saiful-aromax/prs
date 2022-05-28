<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/22/17
 * Time: 3:16 PM
 */
class Disaggregate extends MY_Model
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
            $this->db->select('disaggregates.*, disaggregate_groups.name disaggregate_group_name, disaggregate_tiers.name disaggregate_tier_name');
        }

        $this->db->from('disaggregates');
        $this->db->join('disaggregate_groups', 'disaggregates.id_disaggregate_groups = disaggregate_groups.id', 'inner');
        $this->db->join('disaggregate_tiers', 'disaggregate_groups.id_disaggregate_tiers = disaggregate_tiers.id', 'inner');
        if (!empty($cond['name'])) {
            $where = "( disaggregates.name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }


        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('disaggregates.name', 'ASC');
        $query = $this->db->get();

        //$query->result();
//        echo '<pre>';
//        print_r( $query->result());
//        die;
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
        return $this->db->query("SELECT 
  dt.`id` id_disaggregate_tiers,
  d.* 
FROM
  `disaggregate_tiers` dt 
  INNER JOIN `disaggregate_groups` dg 
    ON dg.`id_disaggregate_tiers` = dt.`id` 
  INNER JOIN `disaggregates` d 
    ON d.`id_disaggregate_groups` = dg.`id` 
WHERE d.`id` = '$id'")->row();
    }

    function add($data)
    {
        unset($data['id_disaggregate_tiers']);
        return $this->db->insert('disaggregates', $data);
    }

    function check_unique($cond)
    {
        $this->db->select('disaggregates.id');
        $this->db->from('disaggregates');
        $this->db->join('disaggregate_groups', 'disaggregates.id_disaggregate_groups = disaggregate_groups.id', 'inner');
        $this->db->join('disaggregate_tiers', 'disaggregate_groups.id_disaggregate_tiers = disaggregate_tiers.id', 'inner');
        $this->db->where($cond);
        $query = $this->db->get();
        return empty($query->result()) ? true : false;
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
        unset($data['id_disaggregate_tiers']);
        return $this->db->update('disaggregates', $data, ['id' => $id]);
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
        return $this->db->delete('disaggregates', ['id' => $id]);
    }

    public function get_item($id_disaggregate_groups)
    {
        $returnArray = [];
        $disaggregates = $this->db->query("SELECT `id`, `name`, `code` FROM `disaggregates` WHERE `id_disaggregate_groups` = '$id_disaggregate_groups' ORDER BY `name`")->result();
        $count = count($disaggregates);
        for ($i = 0; $i < $count; $i++) {
            $returnArray[$disaggregates[$i]->id] = $disaggregates[$i]->name . ' (' . $disaggregates[$i]->code . ')';
        }
        return $returnArray;
    }

}