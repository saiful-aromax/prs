<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session 
{
	
	function __construct(){
		parent::__construct();		
	}

	/**
	* Update an existing session
	*
	* @access    public
	* @return    void
	*/
    function sess_update()
    {
       // skip the session update if this is an AJAX call!
       if ( !IS_AJAX )
       {
           parent::sess_update();
       }
    }
    
    function sess_destroy()
    {
        parent::sess_destroy();
        $this->userdata = array();
    }

}

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */  
