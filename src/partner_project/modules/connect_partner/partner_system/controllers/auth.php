<?php

  class Auth extends MX_Controller{
       function __construct() {
            parent::__construct();
       }
       public function index(){
            $this->load->view('no_auth');
       }
        public function invalid_portal(){
            $this->load->view('no_auth');
       }
  }
