<?php
/** 
	* Extended  Loader Class.
	* @pupose		Dynamically load custom database driver 
	*		
	* @filesource	\app\libraries\MY_Loader.php	
	* @package		microfin 
	* @subpackage	microfin.libraries.MY_Loader
	* @version      $Revision: 1 $
	* @author       $Author: Amlan Chowdhury, Saroj Roy $	
	* @lastmodified $Date: 2011-10-12 $	 
*/

class MY_Loader extends CI_Loader {
	
	public function __construct()
    {
        parent::__construct();
    }
	
	function database($params = '', $return = FALSE, $active_record = NULL)
    {
		// Grab the super object
        $CI =& get_instance();
		
        // Do we even need to load the database class?             
        if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db)) {
			return FALSE;
        }

        require_once(BASEPATH.'database/DB'.EXT);

        // Load the DB class
        $db =& DB($params, $active_record);     

        $my_driver = config_item('subclass_prefix').'DB_'.$db->dbdriver.'_driver';
        $my_driver_file = APPPATH.'core/'.$my_driver.EXT;

        if (file_exists($my_driver_file)) {
            require_once($my_driver_file);
            $db = new $my_driver(get_object_vars($db));
        }

        if ($return === TRUE) {
            return $db;
        }

        // Initialize the db variable.  Needed to prevent
        // reference errors with some configurations
        $CI->db = '';
        $CI->db = $db;	
    }
}
