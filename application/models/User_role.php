<?php

/**
 * User Role Model Class.
 * @pupose        Manage user role information
 *
 * @filesource    \app\model\user_role.php
 * @package        microfin
 * @subpackage    microfin.model.user_role
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @lastmodified $Date: 2011-01-04 $
 */
class User_role extends MY_Model
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
    function get_list($parent_role_id = null)
    {
        if ($parent_role_id == null) {
            $query = $this->db->query('SELECT * FROM user_roles');
        } else {
            $this->db->select('lft,rgt')->from('user_roles')->where('id', $parent_role_id);
            $data = $this->db->get()->row_array();
            $query = $this->db->query('SELECT * FROM user_roles WHERE lft>=? and rgt<= ?', array($data['lft'], $data['rgt']));
        }
        return $query->result_array();
    }

    public function get_item()
    {
        $returnArray = [];
        $user_roles = $this->db->query("SELECT `id`, `role_name` FROM `user_roles` ORDER BY `role_name`")->result();
        $count = count($user_roles);
        for ($i = 0; $i < $count; $i++) {
            $returnArray[$user_roles[$i]->id] = $user_roles[$i]->role_name;
        }
        return $returnArray;
    }

    /**
     * Generates a tree stucture of user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To Generate a tree stucture of user roles
     * @access  :   public
     * @param   :   $cond
     * @return  :   array
     */
    function index_tree_data($cond = null)
    {
        $query = "SELECT node.role_name, node.id, node.role_description,(COUNT(parent.id) - 1) AS depth  
				   FROM user_roles AS node, user_roles AS parent 
					WHERE node.lft BETWEEN parent.lft AND parent.rgt
					GROUP BY node.role_name, node.id, node.role_description 
					ORDER BY node.lft";
        $result = $this->db->query($query);
        return $result->result();
    }

    /**
     * Adds data to user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To add data to user roles
     * @access  :   public
     * @param   :   array $data
     * @return  :   boolean/int
     */
    function add($data)
    {
        $this->db->trans_start();
        $data['id'] = $this->get_new_id('user_roles', 'id');
        $data['lft'] = 1;
        $data['rgt'] = 2;
        //If no parent ID is not specified, then it is the root node
        if (!empty($data['parent_id'])) {
            $query = "select rgt from user_roles where id=?";
            $res = $this->db->query($query, $data['parent_id']);
            $row = $res->row();

            $query = "update user_roles set rgt=rgt+2 where rgt>=?";
            $res = $this->db->query($query, $row->rgt);

            $query = "update user_roles set lft=lft+2 where lft>?";
            $res = $this->db->query($query, $row->rgt);

            $data['lft'] = $row->rgt;
            $data['rgt'] = $row->rgt + 1;
        }
        $id = $this->db->insert('user_roles', $data);
        if ($this->db->trans_complete())
            return $id;
        else
            return FALSE;
    }

    /**
     * Updates data of user roles
     * @author  :   Amlan Chowdhury
     * @uses    :   To update data of user roles
     * @access  :   public
     * @param   :   array $data
     * @return  :   boolean
     */
    function edit($data)
    {
        unset($data['lft']);
        unset($data['rgt']);
        return $this->db->update('user_roles', $data, array('id' => $data['id']));
    }

    /**
     * Reads data of specific user role
     * @author  :   Amlan Chowdhury
     * @uses    :   To  read data of specific user role
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   array
     */
    function read($role_id)
    {
        $query = $this->db->get_where('user_roles', array('id' => $role_id));
        return $query->row_array();
    }

    function get_role_name_by_id($role_id)
    {
        return $this->db->query("SELECT `role_name` FROM `user_roles` WHERE `id` = '$role_id'")->row()->role_name;
    }

    /**
     * Deletes data of specific user role
     * @author  :   Amlan Chowdhury
     * @uses    :   To  delete data of specific user role
     * @access  :   public
     * @param   :   int $role_id
     * @return  :   boolean
     */
    function delete($role_id)
    {
        $query = $this->db->get_where('user_roles', array('id' => $role_id));
        $data = $query->row_array();
        if (($data['rgt'] - $data['lft']) != 1) {
            //got child, do not delete
            return FALSE;
        } else {
            $width = $data['rgt'] - $data['lft'] + 1;
            $this->db->trans_start();
            $query = "Delete from user_role_wise_privileges where role_id=$role_id";
            $this->db->query($query);
            $query = "DELETE FROM user_roles WHERE lft BETWEEN ? AND ?";
            $res = $this->db->query($query, array($data['lft'], $data['rgt']));
            $query = "UPDATE user_roles SET rgt = rgt - ? WHERE rgt > ?";
            $res = $this->db->query($query, array($width, $data['rgt']));
            $query = "UPDATE user_roles SET lft = lft - ? WHERE lft > ?";
            $res = $this->db->query($query, array($width, $data['rgt']));
            return $this->db->trans_complete();
        }
    }

    function is_edit_user_role_permitable($role_id)
    {
        $branch_info = $this->session->userdata('system.user');
        $user_role_id = $branch_info['role_id'];

        $query = $this->db->query("select parent_id from user_roles where id ='$role_id' ");
        $parent_id = $query->row_array();
        $parent_id = (isset($parent_id['parent_id'])) ? $parent_id['parent_id'] : 99999;

        $query = $this->db->query("select parent_id from user_roles where id ='$user_role_id' ");
        $user_parent_id = $query->row_array();
        $user_parent_id = $user_parent_id['parent_id'];

        $depth = $this->depth($role_id);
        $user_depth = $this->depth($user_role_id);

        if ($role_id == $user_role_id) {
            //die('role');
            return true;
        } else if ($parent_id == $user_parent_id) {
            //die('parent');
            return true;
        } else if (empty($user_parent_id)) {
            //die('super admin');
            return true;
        } else if ($depth >= $user_depth) {
            // die('depth');
            return true;
        } else { // die(' Not Permitted ');
            return false;
        }
    }

    function depth($role_id)
    {
        $depth = 1;
        // if(empty($role_id) || $role_id ==0 ){ return $depth; }
        $parent_id = $this->db->select('parent_id')->from('user_roles')->where('id', $role_id)->get()->row()->parent_id;

        $parent_id = !isset($parent_id) ? false : $parent_id;
        //print_r($parent_id); die;
        //echo "<pre>"; print_r($parent_id); echo "</pre>";
        while ($parent_id) {
            $depth++;
            $parent_id = $this->db->select('parent_id')->from('user_roles')->where('id', $parent_id)->get()->row();
            //echo "<pre>"; print_r($parent_id->parent_id);
            if (empty($parent_id->parent_id) || $parent_id->parent_id == null) {
                return $depth;
            }
        }
        return $depth;
    }
}
