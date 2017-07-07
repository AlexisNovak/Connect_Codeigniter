<?php

  class Contact_us extends MX_Controller{
       function __construct() {
            parent::__construct();
       }
       public function index(){
           $this->load->view('contact_us');
       }
        
  }

