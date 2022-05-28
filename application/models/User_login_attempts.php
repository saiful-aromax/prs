<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login_attempts
 *
 * This model serves to watch on all attempts to login on the site
 * (to protect the site from brute-force attack to user database)
 *
 */
class User_login_attempts extends MY_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	function get_attempts_num($ip_address, $login)
	{
		$this->db->select('1', FALSE);
		$this->db->where('ip_address', $ip_address);
                if (strlen($login) > 0){ 
                    $this->db->where('login', $login);
                }
		$qres = $this->db->get('user_login_attempts');
		return $qres->num_rows();
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function increase_attempt($ip_address, $login)
	{
		return $this->db->insert('user_login_attempts', array('ip_address' => $ip_address, 'login' => $login,'time_stamp'=>time()));
	}

	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$this->db->where(array('ip_address' => $ip_address, 'login' => $login));
		
		// Purge obsolete login attempts
		$this->db->or_where('time_stamp <', time() - $expire_period);

		return $this->db->delete('user_login_attempts');
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/user_login_attempts.php */
