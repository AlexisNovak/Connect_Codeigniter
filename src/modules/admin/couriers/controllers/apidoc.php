<?php

  class Apidoc extends CourierController {

       public function __construct() {
            parent::__construct();
            $this->load->model(array('couriers_model', 'courier_service_model'));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $data = array();
            $courier = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $data['access_key'] = $courier ? $courier->access_key : '';
            $this->load->view('apidoc/index', $data);
       }

  }
  