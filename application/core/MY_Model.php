<?php

/**
 * My Model Class.
 * @purpose        Generate the database table id
 *
 * @filesource    \app\model\M.php
 * @package        microfin
 * @subpackage    microfin.model
 * @version      $Revision: 2 $
 * @author       $Author: S. Abdul Matin $
 * @lastmodified $Date: 2016-04-25 $
 */
class MY_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $table_name = name of the dependent table, $cond = associative array()
     * @param string $cond
     * @return bool
     * @internal param $is_dependent
     * @uses example: is_dependency_found('disaggregate_sets',  array('unit_id' => $unit_id));
     * @purpose Check weather the data to be deleted is dependent on others.
     * @createdBy Nadim
     * @lastDate 11-May-2017
     */
    function is_dependency_found($table_name, $cond = [])
    {
        $this->db->where($cond);
        $this->db->from($table_name);
        return $this->db->count_all_results() > 0 ? true : false;
    }

    function get_new_id($table_name, $id)
    {
        $this->db->select_max($id);
        $query = $this->db->get($table_name);
        $max_id = $query->row();
        if (isset($max_id->{$id}) && empty($max_id->{$id})) {
            $new_id = 1;
        } else {
            $new_id = $max_id->{$id} + 1;
        }
        return $new_id;
    }

    function simple_read($table_name, $condition, $column_name = "*")
    {
        if ($table_name == '' || $condition == '') {
            return false;
        }
        return $this->db->select($column_name)->where($condition)->get_where($table_name)->row();
    }

    function simple_check($table_name, $condition, $column_name = "id")
    {
        $check = $this->db->select($column_name)->where($condition)->get_where($table_name)->result();
        return empty($check) ? true : false;
    }


}
