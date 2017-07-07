<?php

  class Reports extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('orders/orders_model');
       }

       public function index() {
            $data = array();
            $this->load->view('report_container', $data);
       }

       public function user_performance() {
            $data = array();
            $this->load->view('user_performance', $data);
       }

       public function overall() {
            $data = array();
            $this->load->view('overall', $data);
       }

       public function group_performance() {
            $data = array();
            $this->load->view('group_performance', $data);
       }

       public function export_transaction($org_id = 0) {
            $data = array();
            $data['org_id'] = $org_id;
            $data['statuses'] = $this->orders_model->get_statuslist();
            $this->load->view('export_transaction', $data);
       }

       public function email_schedule() {
            $data = array();
            $this->load->view('email_schedule', $data);
       }

  }
  