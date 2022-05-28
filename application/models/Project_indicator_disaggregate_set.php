<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/31/17
 * Time: 10:22 AM
 */
class Project_indicator_disaggregate_set extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_list($offset, $limit, $cond)
    {
        $where = '';
        if (!empty($cond['pi_code'])) {
            $where = "WHERE project_indicators.code LIKE '{$cond['pi_code']}%'";
        }

        return $this->db->query("SELECT 
  project_indicators.id,
  project_indicators.`code` AS pi_code,
  commodities.`id` AS c_id,
  commodities.`code` AS c_code 
FROM
  (SELECT 
    pids.`id_project_indicators`,
    pids.`id_commodity` 
  FROM
    `project_indicator_disaggregate_sets` pids 
  WHERE pids.`status`= '1' 
  GROUP BY pids.`id_project_indicators`,
    pids.`id_commodity`) tab1 
  INNER JOIN commodities 
    ON tab1.id_commodity = commodities.`id` 
  INNER JOIN `project_indicators` 
    ON tab1.id_project_indicators = project_indicators.`id` 
  
    $where")->result();

    }

    function get_list_backup($offset, $limit, $cond)
    {
        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select("project_indicator_disaggregate_sets.*,project_indicators.code pi_code, disaggregate_sets.code ds_code, commodities.code c_code");
        }
        $this->db->from('project_indicator_disaggregate_sets');
        $this->db->join('project_indicators', 'project_indicator_disaggregate_sets.id_project_indicators = project_indicators.id', 'inner');
        $this->db->join('disaggregate_sets', 'project_indicator_disaggregate_sets.id_disaggregate_sets = disaggregate_sets.id', 'inner');
        $this->db->join('commodities', 'project_indicator_disaggregate_sets.id_commodity = commodities.id', 'inner');
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
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */
    function add($data = [])
    {
//         echo '<pre>';
//           print_r($data['id_disaggregate_sets']);
//           die;
        $i = 0;
        $table_data = [];
        foreach ($data['id_commodity'] as $id_commodity) {
            foreach ($data['id_disaggregate_sets'] as $id_disaggregate_set) {
                $table_data[$i++] = ['status' => '1', 'id_project_indicators' => $data['id_project_indicators'], 'id_commodity' => $id_commodity, 'id_disaggregate_sets' => $id_disaggregate_set, 'updtated_on' => date('Y-m-d')];
            }
        }

        return $this->db->insert_batch('project_indicator_disaggregate_sets', $table_data);
    }


    /**
     * get particular data
     * @author  :   Sara
     * @uses    :   To get particular data
     * @access  :   public
     * @param   :    $id
     * @return  :   Array
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */


    function get_info_by_id($id_project_indicators)
    {
        return $this->db->query("SELECT `id_commodity`, `id_project_indicators` FROM `project_indicator_disaggregate_sets` WHERE `id_project_indicators` = '$id_project_indicators' AND `status`='1' LIMIT 1")->row();
    }

    /**
     * Edit particular data
     * @author  :   Sara
     * @uses    :   To Edit particular data
     * @access  :   public
     * @param   :    $id, $data
     * @return  :   boolean
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */
    function edit($id_project_indicators, $data)
    {
        $date = date('Y-m-d');
        $from_database = $this->_check_data($id_project_indicators);
        $from_form = [];

        foreach ($data['id_commodity'] as $id_commodity) {
            foreach ($data['id_disaggregate_sets'] as $id_disaggregate_sets) {
                $from_form[$id_commodity][$id_disaggregate_sets] = $id_disaggregate_sets;
                $check = $this->db->query("SELECT 
                  `id` 
                FROM
                  `project_indicator_disaggregate_sets` 
                WHERE `id_commodity` = '$id_commodity' 
                  AND `id_disaggregate_sets` = '$id_disaggregate_sets' 
                  AND `id_project_indicators` = '$id_project_indicators'")->result_array();
                $table_data = [];
                // Form-e ache, Kintu Database-e Nai
                if (empty($check)) {
                    $table_data['id_project_indicators'] = $id_project_indicators;
                    $table_data['id_commodity'] = $id_commodity;
                    $table_data['id_disaggregate_sets'] = $id_disaggregate_sets;
                    $table_data['status'] = '1';
                    $table_data['updtated_on'] = $date;
                    $this->db->insert('project_indicator_disaggregate_sets', $table_data);
                }
            }
        }
        foreach ($from_database as $commodity_key => $value) {
            foreach ($value as $disaggregate_key => $disaggregate) {
                if (!isset($from_form[$commodity_key][$disaggregate_key])) {
                    // Database-e ache, Kintu Form-e Nai
                    $this->db->query("UPDATE 
                    `project_indicator_disaggregate_sets`
                    SET
                        `status` = 0,
                        `updtated_on` = '$date'
                    WHERE `id_project_indicators` = '$id_project_indicators'
                      AND `id_commodity` = '$commodity_key'
                      AND `id_disaggregate_sets` = '$disaggregate_key' ");
                }
            }
        }

        // echo '<pre>';
        // print_r($diff);
        //die;

//        if ($this->db->delete('project_indicator_disaggregate_sets', ['id_project_indicators' => $id_project_indicators])) {
//            return $this->add($data);
//        }
    }

    function _check_data($id_project_indicators)
    {
        $returnArray = [];
        $result = $this->db->query("SELECT 
  * 
FROM
  `project_indicator_disaggregate_sets` pids 
WHERE pids.`id_project_indicators` = '$id_project_indicators' ")->result_array();
        foreach ($result as $row) {
            $returnArray[$row['id_commodity']][$row['id_disaggregate_sets']] = $row['id_disaggregate_sets'];
        }
        return $returnArray;
    }

    /**
     * Deletes particular data
     * @author  :   Sara
     * @uses    :   To delete particular data
     * @access  :   public
     * @param   :   int $id, $delete_by
     * @return  :   boolean
     * @createdon       31/05/2017
     * @lastmodified Date: 31/05/2017
     */
    function delete($row_id)
    {
        $date = date('Y-m-d');
        $this->db->query("UPDATE 
                    `project_indicator_disaggregate_sets`
                    SET
                        `status` = 0,
                        `updtated_on` = '$date'
                    WHERE `id` = '$row_id'");
        return true;
    }

    function project_indicator_delete($id)
    {
        return $this->db->delete('project_indicator_disaggregate_sets', ['id_project_indicators' => $id]);
    }

    function get_disaggregate_set($project_indicator_id = null, $id_commodity = null, $cond = [])
    {
        $where = '';
        if (isset($cond['ds_code'])) {
            $where = " AND ds.code LIKE '{$cond['ds_code']}%'";
        }
        $disagrregate_set = [];
        $result = $this->db->query("SELECT 
         `pids`.`id`,
          ds.`code` 
        FROM
        `project_indicator_disaggregate_sets` AS `pids` 
         INNER JOIN `disaggregate_sets` ds 
          ON `pids`.`id_disaggregate_sets` = ds.`id` 
        WHERE `pids`.`id_project_indicators` = '$project_indicator_id' AND `pids`.`id_commodity` = '$id_commodity' AND `pids`.`status` = '1' $where")->result();

        foreach ($result as $row) {
            $disagrregate_set[$row->id] = $row->code;
        }
        //print_r($disagrregate_set); die;
        return $disagrregate_set;
    }

    function get_commodities($project_indicator_id, $cond = [])
    {
        $where = '';
        if (isset($cond['c_code'])) {
            $where = " AND c.code LIKE '{$cond['c_code']}%'";
        }
        $commodity = [];
        $result = $this->db->query("SELECT 
         `pids`.`id`,
         c.`name`,
          c.`code` 
        FROM
        `project_indicator_disaggregate_sets` AS `pids` 
         INNER JOIN `commodities` c 
          ON `pids`.`id_commodity` = c.`id` 
        WHERE `pids`.`id_project_indicators` = '$project_indicator_id' AND `pids`.`status`='1' $where")->result();

        foreach ($result as $row) {
            $commodity[$row->id] = $row->name . '-' . $row->code;
        }
        //print_r($disagrregate_set); die;
        return $commodity;
    }

    function rowspan($t_name, $count_field, $group_field)
    {
        $span = [];
        $result = $this->bd->query("SELECT COUNT($count_field),$t_name.$group_field
        FROM $t_name
        GROUP BY  $group_field")->result();
        foreach ($result as $row) {
            $span[$row->{$group_field}] = $row->{COUNT($count_field)};
        }
        return $span;
    }
}

