<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/** 
	* Form_Validation library extension Class.
	* @pupose		Extends Form_Validation library
	*		
	* @filesource	\app\libraries\MY_Form_validation.php	
	* @package		omegasoft 
	* @version      $Revision: 1 $
	* @author       $Author: Mahbub Titour Rahman Alamgir $	
	* @lastmodified $Date: 2011-01-27 $	 
 	*@source  	http://www.scottnelle.com/41/extending-codeigniters-validation-library/ , http://net.tutsplus.com/tutorials/php/6-codeigniter-hacks-for-the-masters/
*/ 
class MY_Form_validation extends CI_Form_validation {
	
	function __construct($config = array())
	{
	    parent::__construct($config);
	}
	

/**
 * unique[$table.$tbl_check_field.$PK_tbl_field.$PK_form_field]
 * customize for edit.
*/
	function unique($value, $params) {

		$CI =& get_instance();
		$CI->load->database();

		$CI->form_validation->set_message('unique','The %s is already being used.');

		list($table, $tbl_check_field, $PK_db_field, $PK_form_field) = explode(".", $params, 4);
		
		if ( isset($_POST[$PK_form_field]) and ! empty($_POST[$PK_form_field]) and isset($PK_db_field) and ! empty($PK_db_field))
		{			
			$FK_value = $_POST[$PK_form_field];
			$query = $CI->db->select($tbl_check_field)->from($table)->where(array($tbl_check_field=>$value,$PK_db_field.' !=' => $FK_value) )->limit(1)->get();			
		} else {
			$query = $CI->db->select($tbl_check_field)->from($table)->where($tbl_check_field, $value)->limit(1)->get();
         // print_r($query->row());
          // die;
		}
		return $query->row()?false:true;
	}
	
	/**
	 * check unique between to field
     * 
	 * @access	public
     * @param   void 
	 * @return	boolean
	 * @author  Mahbub Tito
	 */	
	function unique_between_two_field($value, $params) {

		$CI =& get_instance();
		$CI->load->database();

		$CI->form_validation->set_message('unique_between_two_field','The %s is already being used.');

		list($table, $tbl_check_field, $PK_db_field, $PK_form_field, $db_field_name_2nd, $form_field_name_2nd) = explode(".", $params, 6);
		
		if ( isset($_POST[$form_field_name_2nd]) and ! empty($_POST[$form_field_name_2nd]) and isset($db_field_name_2nd) and ! empty($db_field_name_2nd))
		{
			$field_value_2nd = $_POST[$form_field_name_2nd];
			if ( isset($_POST[$PK_form_field]) and ! empty($_POST[$PK_form_field]) and isset($PK_db_field) and ! empty($PK_db_field))
			{			
				$FK_value = $_POST[$PK_form_field];
				$query = $CI->db->select($tbl_check_field)->from($table)->where(array($tbl_check_field=>$value,$db_field_name_2nd=>$field_value_2nd,$PK_db_field.' !=' => $FK_value) )->limit(1)->get();			
			}
			 else {
				$query = $CI->db->select($tbl_check_field)->from($table)->where(array($tbl_check_field=>$value,$db_field_name_2nd=>$field_value_2nd))->limit(1)->get();
			}			
			return $query->row()?false:true;
		} else {
			return true;
		}
	}
	
	
	/**
	 * check unique for user
     * 
	 * @access	public
     * @param   void 
	 * @return	boolean
	 * @author  Tito 
	 */	
	function unique_check_for_user($value, $params) {
		
		$CI =& get_instance();
		$CI->load->database();
		/*------Bug #7161 A Database Error Occurred  when deleted user is create again------------------------------------*/
		/*------User is not editable if same login user  already inactive------------------------------------*/
		$CI->form_validation->set_message('unique_check_for_user','The %s has already being used.');

		list($table, $tbl_check_field, $PK_db_field, $PK_form_field, $db_field_name_2nd, $form_field_name_2nd) = explode(".", $params, 6);
		
		if ( isset($_POST[$form_field_name_2nd]) and ! empty($_POST[$form_field_name_2nd]) and isset($db_field_name_2nd) and ! empty($db_field_name_2nd))
		{
			$field_value_2nd = $_POST[$form_field_name_2nd];
			if ( isset($_POST[$PK_form_field]) and ! empty($_POST[$PK_form_field]) and isset($PK_db_field) and ! empty($PK_db_field))
			{			
				$FK_value = $_POST[$PK_form_field];
				$query = $CI->db->select($tbl_check_field)->from($table)->where(array($tbl_check_field=>$value,$db_field_name_2nd=>$field_value_2nd,$PK_db_field.' !=' => $FK_value) )->limit(1)->get();			
			}
			 else { 
				$query = $CI->db->select($tbl_check_field)->from($table)->where(array($tbl_check_field=>$value,$db_field_name_2nd=>$field_value_2nd))->limit(1)->get();
			}			
			return $query->row()?false:true;
		} else {
			return true;
		}
	}

		
/**
 * Check a valid date	
 * is_date
 * @Auth Mahbub Tito
*/
	function is_date($value) {

		$CI =& get_instance();

		$CI->form_validation->set_message('is_date','The %s is not a valid date. Use yyyy-mm-dd.');

		$date_array = explode('-',$value);
		if( !isset($date_array[0]) && empty($date_array[0])) {
			return false;
		}elseif( !isset($date_array[1]) && empty($date_array[1])) {
			return false;
		}elseif( !isset($date_array[2]) && empty($date_array[2])) {
			return false;
		}
		if(!is_numeric($date_array[0]) || !is_numeric($date_array[1]) || !is_numeric($date_array[2])) {
			return false;
		} 
		return checkdate($date_array[1],$date_array[2],$date_array[0]);
	}
	
	// todo
	// added Mahbub Tito
	function unique_multi_field($value, $params) {

		$CI =& get_instance();
		$CI->load->database();

		$CI->form_validation->set_message('unique',
			'The %s is already being used.');

		list($table, $field, $field) = explode(".", $params, 2);

		$query = $CI->db->select($field)->from($table)
			->where($field, $value)->limit(1)->get();

		return $query->row()?false:true;


	}
	
	/**
    * @desc Validates a date format
    * @params format,delimiter
    * e.g. d/m/y,/ or y-m-d,-
    */
    function valid_date($str, $params)
    {
        // setup
        $CI =& get_instance();
        $params = explode(',', $params);
        $delimiter = $params[1];
        $date_parts = explode($delimiter, $params[0]);

        // get the index (0, 1 or 2) for each part
        $di = $this->valid_date_part_index($date_parts, 'd');
        $mi = $this->valid_date_part_index($date_parts, 'm');
        $yi = $this->valid_date_part_index($date_parts, 'y');

        // regex setup
        $dre = "(0?1|0?2|0?3|0?4|0?5|0?6|0?7|0?8|0?9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31)";
        $mre = "(0?1|0?2|0?3|0?4|0?5|0?6|0?7|0?8|0?9|10|11|12)";
        $yre = "([0-9]{4})";
        $red = '\\'.$delimiter; // escape delimiter for regex
        $rex = "^[0]{$red}[1]{$red}[2]$";

        // do replacements at correct positions
        $rex = str_replace("[{$di}]", $dre, $rex);
        $rex = str_replace("[{$mi}]", $mre, $rex);
        $rex = str_replace("[{$yi}]", $yre, $rex);

        if (preg_match("/$rex/", $str, $matches)) {
            // skip 0 as it contains full match, check the date is logically valid
            if (checkdate($matches[$mi + 1], $matches[$di + 1], $matches[$yi + 1])) {
                return true;
            } else {
                // match but logically invalid
                $CI->form_validation->set_message('valid_date', "The date is invalid.");
                return false;
            }
        } 

        // no match
        $CI->form_validation->set_message('valid_date', "The date format is invalid. Use {$params[0]}");
        return false;
    }      

	function valid_date_part_index($parts, $search) {
		for ($i = 0; $i <= count($parts); $i++) {
			if ($parts[$i] == $search) {
				return $i;
			}
		}
	}
	/**
	* Check a future date	
	* date_check
	* @Auth Taposhi Rabeya
	*/
	function date_check($value) {
		$CI =& get_instance();
		$current_date=$CI->session->userdata('system.software_date');		
		$current_date=implode($current_date);	
		$CI->form_validation->set_message('date_check','This date should be less system date('. $current_date .').');			
		if (isset($value)){							
			$value=strtotime($value);
			$current_date=strtotime($current_date);
			if ($value>$current_date){
				return false;
			}
		}
		return true;
	}

    /**
	 * Positive Numeric
     *
	 *  @author Matin
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function positive_numeric($str)
	{
        $CI =& get_instance();
        if ( ! preg_match( '/^[0-9]*\.?[0-9]+$/', $str))
    	{
            $CI->form_validation->set_message('positive_numeric','Amount should be positive value');
    		return FALSE;
    	}
   		return TRUE;

	}

    /**
	 * Non Zero Positive Numeric
     *
	 * @author Matin
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function positive_numeric_no_zero($str)
	{
        $CI =& get_instance();
        if ( ! preg_match( '/^[0-9]*\.?[0-9]+$/', $str))
    	{
            $CI->form_validation->set_message('positive_numeric_no_zero','%s should be greater than zero(0).');
    		return FALSE;
    	}
        if($str == 0)
        {
            $CI->form_validation->set_message('positive_numeric_no_zero','%s should be greater than zero(0).');
    		return FALSE;
        }
   		return TRUE;

	}
	
    /**
	 * At first any Alpha-Numeric needed with other Aplha-Numeric-Special Charecter
     *
	 * @author Imtiaz
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_numeric_first_with_special_char($str)
	{
        $CI =& get_instance();
        if(!preg_match("/^([a-zA-Z0-9]){1}?[a-zA-Z0-9+-_!#@$&\s\-]+$/", $str)){
            $CI->form_validation->set_message('alpha_numeric_first_with_special_char','%s fields needs at-first any alpha-numeric charecter.');
    		return FALSE;
    	}
   		return TRUE;
	}
    /**
	 * Aplha-Numeric-Special Charecter
     *
	 * @author Imtiaz
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_numeric_with_special_char($str)
	{
        $CI =& get_instance();
        if(!preg_match("/^[a-zA-Z0-9+-_!#@$&\s\-]+$/", $str)){
            $CI->form_validation->set_message('alpha_numeric_with_special_char','%s fields needs at-first any alpha-numeric charecter.');
    		return FALSE;
    	}
   		return TRUE;
	}
	
	/**
	 * Aplha-Numeric-Special Charecter
     *
	 * @author Mahbub Tito
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function valid_name($str)
	{
        $CI =& get_instance();
   	   if (is_numeric($str))
	   {
          $CI->form_validation->set_message('valid_name','%s fields needs at least one alpha charecter.');
		  return FALSE;
	   }
        if(!preg_match("/^([a-zA-Z0-9]){1}?[a-zA-Z0-9+-_!#@$&\s\-]+$/", $str)){
               $CI->form_validation->set_message('valid_name','%s fields needs valid alpha-numeric charecters.');
               return FALSE;
         }
               return TRUE;
	}
	
	/*
	 * Added Mahbub Tito
	 * //src http://codeigniter.com/forums/viewthread/193147/
	 * */
	public function is_between($str,$val)
	{
	   if (! is_numeric($str))
	   {
		  return FALSE;
	   }

	   list($val, $max)=explode('.', $val);
	   $CI =& get_instance();
		$CI->form_validation->set_message('is_between',"The %s is between $val and $max.");
	   return (($str >= $val) AND ($str <= $max));
	}  
	
	function is_time_12($in_time) {
		$CI =& get_instance();
		if ( ! preg_match( '/^[0-9]*\:?[0-9]+$/', $in_time)){
			$CI->form_validation->set_message('is_time_12','('.$in_time.') is not a valid time ');
			return FALSE;
		}	
		
		if (strstr($in_time,":")) {
			$time = explode(":",$in_time);
			$parts = count($time);
		} elseif (strlen(trim($in_time)) > 2) {
			$parts = 0;
		} else {
			$parts = 1;
			$time[0] = trim($in_time);
		} 
		 
		switch($parts) {
			case '1':
				$hour = $time[0];
				$min = "00";
			break;

			case '2':
				$hour = $time[0];
				$min = $time[1];
			break;

			default:
				$hour = "99";
				$min = "99";
		}

		$out_time="";
		settype($hour,"integer");
		settype($min,"integer");
		if (strlen($hour) < 2) {
			$out_time = "0";
		}
		$out_time .= "$hour:";
		if (strlen($min) < 2) {
		       $out_time .= "0";
		}
		$out_time .= "$min";
		
		if ( ! ((($hour >= 0) and ($hour <= 12)) and (($min >= 0) and ($min < 60)))) {			
			$CI->form_validation->set_message('is_time_12','('.$in_time.') is not a valid time ');
		        return FALSE;
		}	
		return true;
	}
	function alpha_dash_dot($str)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('alpha_dash_dot','The %s field may only contain alpha-numeric characters, underscores, dot and dashes.');
		return ( ! preg_match("/^([-a-z0-9._-])+$/i", $str)) ? FALSE : TRUE;
	}
	
	/**
	 * Alpha-numeric with first character alpha only
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function alpha_dash_first_alpha($str)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('alpha_dash_first_alpha','The %s field may only contain alpha-numeric characters, first character must be englih letter.');
		return ( ! preg_match("/^[a-zA-Z][a-zA-Z0-9]+$/i", $str)) ? FALSE : TRUE;
		
	}

	/**
	 * check duplicate entry in same day
     * 
	 * @access	public
     * @param   void 
	 * @return	boolean
	 * @author  Mahbub Tito
	 */	
	function check_duplicate_entry($value, $params) {

		$CI =& get_instance();
		$CI->load->database();

		list($tbl_name, $db_tbl_field_1, $field_1_value, $db_tbl_field_2, $field_2_value, $db_tbl_field_3, $field_3_value, $db_tbl_field_4, $field_4_value) = explode(".", $params, 9);

		
		if ( !empty($field_1_value) AND !empty($field_2_value) AND !empty($field_3_value) AND !empty($db_tbl_field_1) AND !empty($db_tbl_field_2) AND !empty($db_tbl_field_3) )
		{
			if ( empty($db_tbl_field_4) AND empty($field_4_value) )
			{			
				$query = $CI->db->select("*")->from($tbl_name)->where(array($db_tbl_field_1=>$field_1_value,$db_tbl_field_2=>$field_2_value,$db_tbl_field_3 => $field_3_value) )->limit(1)->get();			
			} else {
				$query = $CI->db->select("*")->from($tbl_name)->where(array($db_tbl_field_1=>$field_1_value,$db_tbl_field_2=>$field_2_value,$db_tbl_field_3 => $field_3_value,$db_tbl_field_4 => $field_4_value) )->limit(1)->get();			
			}	
			//echo $CI->db->last_query().'<br>';
			if($query->row()){
				if(isset($query->row()->amount)){
					$transaction_amount = $query->row()->amount;
				} elseif(isset($query->row()->transaction_amount)){
					$transaction_amount = $query->row()->transaction_amount;
				} else{
					$transaction_amount =0;
				}
				if(isset($query->row()->is_auto_process) && $query->row()->is_auto_process) {					
					$CI->form_validation->set_message('check_duplicate_entry',"Multiple transaction is not allowed in same date.(Ref : Auto Process , Amount:{$transaction_amount}).");
				} else {				
					$CI->form_validation->set_message('check_duplicate_entry',"Multiple transaction is not allowed in same date.(Ref : Manual Screen ,Amount:{$transaction_amount}).");
				}
			}
			return $query->row()?false:true;
		} else {
			return true;
		}
	}
        function validate_name($str){
            $CI=&get_instance();
            if(!preg_match("/^[A-Za-z][A-Za-z0-9-_. ]*(?:_[A-Za-z0-9]+)*$/", $str)){
               $CI->form_validation->set_message('validate_name','The %s field may only contain alpha-numeric characters, underscores, dot and dashes.'); 
               return FALSE;
            }
            return TRUE;
        }
        /**
        * check current date is migration date?
        * 
        * @access	public
        * @param       void 
        * @return	boolean
        * @author      Feroz
        */
        function check_is_migration_date($date,$branch_id)
        {
            $CI =& get_instance();
            $CI->load->database();
            $sw_date_of_operation=  $CI->db->select('sw_start_date_of_operation')->get_where('po_branches',array('id'=>$branch_id))->row()->sw_start_date_of_operation;
            $current_date=$CI->get_current_date_of_branch($branch_id);          
            if(strtotime($current_date)==strtotime($sw_date_of_operation)){
                $CI->form_validation->set_message('check_is_migration_date',"Any Kind of transfer is not allowed in migration date");
                return FALSE;
            }
            return TRUE;
        }
        function check_transaction_date_with_system_date($date=''){
            $CI =& get_instance();
            $branch_current_date=  $CI->get_current_date(TRUE);
            if(strtotime($date)!=strtotime($branch_current_date)){
                $CI->form_validation->set_message("check_transaction_date_with_system_date","Transaction Date must be same as software date ($branch_current_date)");
                return FALSE;
            }
            return TRUE;
        }
}
