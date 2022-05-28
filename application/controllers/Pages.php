<?php

class Pages extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->Layout('Pages/dashboard');
    }

    function about_us()
    {
        $this->layout->view('Pages/about_us');
    }

    function access_denied()
    {
        $data['title'] = 'Access Denied';
        $this->layout->view('Pages/access_denied', $data);
    }

    function under_development()
    {
        $data['title'] = 'Under Development';
        $this->layout('Pages/under_development', $data);
    }


}