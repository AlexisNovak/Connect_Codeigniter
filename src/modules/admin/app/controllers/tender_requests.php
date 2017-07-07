<?php

class Tender_requests extends MY_Controller{
     function __construct() {
          parent::__construct();
     }
     public function index(){
          $this->load->view('tender_requests_main');
     }
     public function delivery(){
          $this->load->view('tender_delivery');
     }
}