<?php

  class Home extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('orders/orders_model', 'organisation_model'));

            // Load the necessary stuff...
       }

       function index() {
            $user_id = $this->session->userdata('user_id');
            $total_result = $this->orders_model->getorderslist_count($user_id, NULL, NULL, NULL, NULL);
            $data['count'] = $total_result;
            $this->load->view('landing', $data);
       }

       public function get_user_status() {
            $result = array();
            $result['status'] = 0;
            $user_id = $this->session->userdata('user_id');
            $total_orders = $this->orders_model->getorderslist_count($user_id, NULL, NULL, NULL, NULL);
            if ($total_orders == 0) {
                 $org_count = $this->organisation_model->getorganisationlist_by_user_id_count($user_id, NULL);
                 if ($org_count == 0) {
                      $result['status'] = 1;
                 }
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */