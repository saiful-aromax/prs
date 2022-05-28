<?php
class Errors extends MY_Controller {
 
	function __construct()
	{
		parent::__construct();
	}
	
	function error_404()
	{
		$data['title']='Page Not Found';
		//$this->layout->view('/errors/error_404',$data);
        $this->Layout('/errors/html/error_404');
	}
}
