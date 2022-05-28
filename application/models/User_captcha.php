<?php
/** 
	* PO Division Model Class.
	* @pupose		Manage division information
	*		
	* @filesource	\app\model\user_role.php	
	* @package		microfin 
	* @subpackage	microfin.model.user_role
	* @version      $Revision: 1 $
	* @author       $Author: Amlan Chowdhury $	
	* @lastmodified $Date: 2011-01-04 $	 
*/
class User_captcha extends MY_Model {

	var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function create_captcha($ip_address)
	{
		//captcha - 
		$vals = array(
            'img_path' => './media/captcha/',
            'img_url' => base_url().'media/captcha/',
            'font_path' => './media/captcha/fonts/5.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word' => mt_rand(10000,99999)
            );

		$captcha = create_captcha($vals);

		if(!empty($captcha)){
			$data = array(
				'captcha_time' => $captcha['time'],
				'ip_address' => $ip_address,
				'word' => $captcha['word']
				);
			$query = $this->db->insert_string('user_captcha', $data);
			$this->db->query($query);
		}
		//end captcha generation
		return $captcha;
	}
	
	function cleanup_captcha($expiration = '')
	{
		
		$expiration = time()-7200; // Two hour limit
		$this->db->query("DELETE FROM user_captcha WHERE captcha_time < ".$expiration); 
	}
	
    function is_valid_captcha($captcha_word,$ip_address,$expiration)
	{
		if(empty($captcha_word))
			return FALSE;
		// Then see if a captcha exists:
		$sql = "SELECT COUNT(*) AS count FROM user_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($captcha_word, $ip_address, $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();
		
		if ($row->count == 0)
			return FALSE;
		else
			return TRUE;
		
	}
}
