<?php

/**
 * User Model Class.
 * @pupose        Manage user information
 *
 * @filesource    \app\model\user.php
 * @package        microfin
 * @subpackage    microfin.model.user
 * @version      $Revision: 1 $
 * @author       $Author: Amlan Chowdhury $
 * @lastmodified $Date: 2011-01-04 $
 */
class User extends MY_Model
{
    var $title = '';
    var $content = '';
    var $date = '';
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     * Generates list of users
     * @author  :   Anis Alamgir
     * @uses    :   To get users list
     * @access  :   public
     * @param   :   int $offset, int $limit, string $cond
     * @return  :   array
     */
    function get_list($offset, $limit, $cond = [])
    {
        //echo "<pre>";print_r($cond);echo "</pre>";
        $this->db->select('users.id,users.full_name,users.login,users.role_id,user_roles.role_name,users.status,users.is_super_admin,users.created_on');
        $this->db->order_by('login', 'ASC');
        $this->db->from('users', $offset, $limit);
        $this->db->join('user_roles', 'users.role_id = user_roles.id');
        if (isset($cond['name']) and !empty($cond['name'])) {
            $where = "( users.login LIKE '%{$cond['name']}%' OR users.full_name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }
        if (isset($cond['user_role']) and !empty($cond['user_role']) and $cond['user_role'] != -1) {
            $this->db->where('users.role_id', $cond['user_role']);
        }

        if (isset($cond['status']) and !empty($cond['status'])) {
            $this->db->where('users.status', $cond['status']);
        }
        if ($offset == 0 && $limit == 0) {
            return $this->db->count_all_results();
        }
        $this->db->limit($offset, $limit);
        $this->db->order_by('users.login', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Action for add a branch
     * @author  Amlan Chowdhury
     * @access    public
     * @param   array $data
     * @return    bool
     */
    function add($data)
    {
        $data['id'] = $this->get_new_id('users', 'id');
        $data['created_on'] = date('Y-m-d H:i:s');
        $data['password'] = sha1($data['password']);
        $data['project_id'] = empty($data['project_id']) ? null : $data['project_id'];
        $data['status'] = '1';
        unset($data['confirm_password']);
        return $this->db->insert('users', $data);
    }

    /**
     * Action for update a branch
     * @author  Amlan Chowdhury
     * @access    public
     * @param   array $data , $default_secret_hash
     * @return    bool
     */
    function edit($data, $id)
    {
        $data['modified_on'] = date('Y-m-d H:i:s');
        $data['project_id'] = empty($data['project_id']) ? null : $data['project_id'];
        return $this->db->update('users', $data, ['id' => $id]);
    }

    /**
     * Reads the record on given id
     * @author  :   Amlan Chowdhury
     * @uses    :   To read the record on given id
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     */
    function read($user_id)
    {
        return $this->db->get_where('users', ['id' => $user_id])->row();
    }

    /**
     * Gets the record  by login
     * @author  :   Amlan Chowdhury
     * @uses    :   To get the record by login
     * @access  :   public
     * @param   :   int $login
     * @return  :    boolean
     */
    function get_user_by_login($login, $password)
    {
        $this->db->select('users.id,users.full_name,users.login,users.role_id,users.is_super_admin,users.email');
        $this->db->from('users');
        $this->db->where('users.login', $login);
        $this->db->where('users.password', SHA1($password));
        $this->db->where('users.status', 'A');
        $query = $this->db->get()->row();
        return !empty($query) ? $query : false;
    }

    /**
     * Checks password
     * @author  :   Amlan Chowdhury
     * @uses    :   To check password
     * @access  :   public
     * @param   :   int $id, string $password
     * @return  :   boolean
     */
    function checkPassword($user_id, $password)
    {
        $password = sha1($password);
        $sql = "select id from users where id= ? and password= ?";
        $query = $this->db->query($sql, array($user_id, $password));

        if ($query->num_rows() == 1) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Checks valid email
     * @author  :   Amlan Chowdhury
     * @uses    :   To check password
     * @access  :   public
     * @param   :   int $id, string $password
     * @return  :   boolean
     */
    function checkEmail($email)
    {
        $query = $this->db->query("select id,login,email from users where email= '$email'")->row();
        return empty($query) ? false : (array)$query;
    }

    /**
     * Changes password
     * @author  :   Amlan Chowdhury
     * @uses    :   To change password
     * @access  :   public
     * @param   :   array $user_data
     * @return  :   boolean
     */
    function changePassword($data)
    {
        $data['password'] = sha1($data['password']);
        return $this->db->update('users', $data, ['id' => $data['id']]);
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     * @author  :   Amlan Chowdhury
     * @param    int $user_id
     * @param    bool $record_ip
     * @param    bool $record_time
     * @return    void
     */
    function update_login_info($user_id, $record_ip, $record_time = null)
    {
        $data = array();
        $data['last_login'] = date('Y-m-d H:i:s');
        if ($record_ip) {
            $data['last_ip'] = $record_ip;
        }
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }


    function check_user_password_grace_time($user_id)
    {
        $query = "SELECT default_value FROM config_general_new WHERE db_field_name = 'password_grace_time';";

        $results = $this->db->query($query)->row_array();
        //echo $query;
        //echo "<pre>==";print_r($results);die;
        //echo $query;
        $results = $this->db->query($query)->row_array();
        if (empty($results['default_value']) || $results['default_value'] < 1) {
            return false;
        }

        $query = "SELECT login FROM users WHERE id = $user_id AND id > 1 AND last_password_changed < DATE_ADD(NOW(), INTERVAL -{$results['default_value']} DAY);";

        $results = $this->db->query($query)->row_array();
        if (empty($results['login'])) {
            return false;
        }
        return true;
    }

    function get_user_list($branch_id)
    {
        $user_list = $this->db->select('id,full_name')->get_where('users', array('default_branch_id' => $branch_id))->result();
        $users = array();
        foreach ($user_list as $user) {
            $users[$user->id] = $user->full_name;
        }
        return $users;
    }

    public function get_item()
    {
        $returnArray = [];
        $users = $this->db->query("SELECT `id`, `login` FROM `users` ORDER BY `login`")->result();
        $count = count($users);
        for ($i = 0; $i < $count; $i++) {
            $returnArray[$users[$i]->id] = $users[$i]->login;
        }
        return $returnArray;
    }
}
