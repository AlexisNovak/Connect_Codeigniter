<?php

  class Reloader extends MY_Controller{
       function __construct() {
            parent::__construct();
       }
       public function index(){
            if($this->session->userdata('user_id')){
                 echo 0;
            }else{
                 echo 1;
            }
            exit();
       }
  }

