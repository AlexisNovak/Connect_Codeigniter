<?php

  defined('BASEPATH') OR exit('No direct script access allowed');
  error_reporting(0);

  require(APPPATH . '/libraries/REST_Controller.php');

  class Api extends REST_Controller {

       public function __construct() {
            parent::__construct();
            $this->load->model('orders/orders_model');
       }

       function getConsignments_post() {
            $users = $this->session->userdata('partner_user_id');
            if ($users) {
                 $consignmentList = $this->orders_model->getorderslist_by_userid($users, 5, "", 0);

                 if (!$consignmentList) {
                      $this->response(array('code' => '13', 'message' => 'Consignment with these params could not be found'), 200);
                 }

                 $consignmentList = array_map("unserialize", array_unique(array_map("serialize", $consignmentList)));
                 //echo "<pre>"; print_r($consignmentList); exit;
                 $this->response($consignmentList, 200);
            } else {
                 $this->response(array('code' => '2', 'message' => 'Invalid Token ID', "user" => $users), 200);
            }

            $this->response($consignmentList, 200);
       }

  }
  