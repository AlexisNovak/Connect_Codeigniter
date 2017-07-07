<?php

  class Reloader extends CI_Controller{
       function __construct() {
            parent::__construct();
       }
       public function index(){
            if($this->session->userdata('partner_user_id')){
                 echo 0;
            }else{
                 echo 1;
            }
            exit();
       }
  }

