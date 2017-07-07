<?php

  class Rating extends MX_Controller {

       function __construct() {
            parent::__construct();
            $this->load->language('english');
            $this->load->model('rating_model');
       }

       public function index() {
            $data = array();
            $data['email'] = $this->input->get('email');
            $data['rate'] = $this->input->get('rate');
            $data['public_id'] = $this->input->get('public_id');
            $this->load->view('rating', $data);
       }

       public function submitrate() {
            $ordersData = json_decode(file_get_contents('php://input'));
            $email = isset($ordersData->email) ? $ordersData->email : NULL;
            $value = isset($ordersData->value) ? $ordersData->value : NULL;
            $reason = isset($ordersData->reason) ? $ordersData->reason : NULL;
            $public_id = isset($ordersData->public_id) ? $ordersData->public_id : NULL;
            if (is_numeric($value)) {
                 $this->rating_model->rateData($email, $public_id, $value, $reason);
                 echo json_encode(array('status' => true));
            } else {
                 echo json_encode(array('status' => false));
            }
       }

  }
  