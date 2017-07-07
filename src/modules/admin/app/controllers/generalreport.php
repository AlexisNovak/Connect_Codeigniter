<?php

  class Generalreport extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('orders/orders_model');
       }

       public function index() {
            
       }

       public function statusbd() {
            $result = array();
            $user_id=$this->session->userdata("user_id");
            $until = new DateTime();
            $interval = new DateInterval('P3M'); //2 months
            $from = $until->sub($interval);
            $from_date = $from->format('Y-m-d H:i:s');
            $orders = $this->orders_model->get_deliveries_count_by_status($user_id, $from_date);
            $collected = 0;
            $delivered = 0;
            $failed = 0;
            foreach ($orders as $order_cat) {
                 switch ($order_cat->status):
                      case C_FAILED_DELIVERY:$failed = $order_cat->deliveries;
                           break;
                      case C_DELIVERED:$delivered = $order_cat->deliveries;
                           break;
                      case C_COLLECTED:$collected = $order_cat->deliveries;
                           break;
                 endswitch;
            }
            $service_breakdown = array(
                array('name' => 'Collected', 'y' => $collected, 'color' => '#F0C027'),
                array('name' => 'Delivered', 'y' => $delivered, 'color' => '#152D34'),
                array('name' => 'Failed', 'y' => $failed, 'color' => '#5E9CA5')
            );
            $result['statusbreakdown'] = $service_breakdown;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  