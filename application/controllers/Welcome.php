<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

    public function test_email()
    {
        require_once FCPATH.'vendor/autoload.php';
        $mpdf = new mPDF();
        $mpdf->WriteHTML('<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;    
}
</style>
</head>
<body>

<h2>Cell that spans two columns:</h2>
<table style="width:100%">
  <tr>
    <th>Name</th>
    <th colspan="2">Telephone</th>
  </tr>
  <tr>
    <td>Bill Gates</td>
    <td>55577854</td>
    <td>55577855</td>
  </tr>
</table>

</body>
</html>');
        $content = $mpdf->Output(FCPATH.'uploads/test.pdf','F');
        $this->load->library('email');
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'nhsajib316@gmail.com',
            'smtp_pass' => '0951004899',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'newline'   => "\r\n",
        );

        $this->email->initialize($config);

        $this->email->from('nhsajib316@gmail.com', 'Nadim');
        $this->email->to('devdhaka409@yahoo.com');
        $this->email->subject('Email Test With PDF');
        $this->email->attach(FCPATH.'uploads/test.pdf');
        $this->email->message('Testing the email class.');
        $this->email->send();
        echo $this->email->print_debugger();
	}
}
