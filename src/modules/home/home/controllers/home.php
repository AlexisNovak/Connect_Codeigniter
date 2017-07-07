<?php

  class Home extends MX_Controller{
       function __construct() {
            parent::__construct();
            $this->load->config('account/account');
       }
       public function index(){
            $this->load->view('home');
       }
        
  }

