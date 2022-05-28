<?php

/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/24/17
 * Time: 4:22 PM
 */
class Indicator_group extends MY_Model
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
    function get_list($group_id = null)
    {
        if($group_id == null)
        {
            $query = $this->db->query('SELECT * FROM indicator_groups');
        }else
        {
            $this->db->select('*')->from('projects')->where('id', $group_id);
            $data = $this->db->get()->row_array();
            $query = $this->db->query('SELECT * FROM projects WHERE lft>=? and rgt<= ?',array($data['lft'],$data['rgt']));
        }
        return $query->result_array();
    }
}