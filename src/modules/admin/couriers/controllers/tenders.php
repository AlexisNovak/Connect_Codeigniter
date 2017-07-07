<?php

  class Tenders extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array('couriers_model',
                'orders/orders_model',
                'consignment_bids_model',
                'orders/consignment_pod_model',
                'orders/consignment_messages_model'));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $this->load->view('tenders');
       }

       public function view($order_id) {
            $order_id = $this->orders_model->get_order_id($order_id);
            $courier_id = $this->session->userdata('courier_id');
            $data = array();
            $order = $this->orders_model->getDetails(array('consignment_id' => $order_id));
            if ($order) {
                 $collection_address = json_decode($order->collection_address);
                 $order->collection_address = $collection_address[1] ? $collection_address[1] : "";
                 $delivery_address = json_decode($order->delivery_address);
                 $order->delivery_address = $delivery_address[1] ? $delivery_address[1] : '';
            }
            $data['order'] = $order;
            if ($pods = $this->consignment_pod_model->get_pods($order_id)) {
                 $data['pods'] = array();
                 foreach ($pods as $pod) {
                      if ($pod->is_signature) {
                           $data['signature'] = $pod;
                      } else {
                           $data['pods'][] = $pod;
                      }
                 }
            }
            $data['messages'] = $this->consignment_messages_model->listjobmessages($order_id, $courier_id);
            $this->load->view('tender_view', $data);
       }

       public function get_tenders_json() {
            $perpage = '';
            $search = NULL;
            $service = "";
            $status = "";
            $ordersData = json_decode(file_get_contents('php://input'));
            if (isset($ordersData->perpage_value)) {

                 $perpage = $ordersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($ordersData->currentPage)) {

                 $page = $ordersData->currentPage;
            } else {
                 $page = 1;
            }

            if (isset($ordersData->filter)) {
                 if ($ordersData->filter != NULL) {
                      $search = $ordersData->filter;
                 }
            }
            if (isset($ordersData->service)) {
                 if ($ordersData->service != NULL) {
                      $service = $ordersData->service;
                 }
            }
            if (isset($ordersData->status)) {
                 if ($ordersData->status != NULL) {
                      $status = $ordersData->status;
                 }
            }

            $company = NULL;
            $username = NULL;
            $collection_address = "";
            $delivery_address = "";
            $date_from = NULL;
            $collection_time_from = "";
            $collection_time_to = "";
            $collection_date_from = "";
            $collection_date_to = "";
            $delivery_date_from = "";
            $delivery_date_to = "";
            $delivery_time_from = "";
            $delivery_time_to = "";

            if (isset($ordersData->company) && !empty($ordersData->company)) {
                 $company = $ordersData->company;
            }
            if (isset($ordersData->username) && !empty($ordersData->username)) {
                 $username = $ordersData->username;
            }
            if (isset($ordersData->collection_address) && !empty($ordersData->collection_address)) {
                 $collection_address = $ordersData->collection_address;
            }
            if (isset($ordersData->delivery_address) && !empty($ordersData->delivery_address)) {
                 $delivery_address = $ordersData->delivery_address;
            }

            if (isset($ordersData->collection_date_from) && !empty($ordersData->collection_date_from)) {
                 $collection_date_from = $ordersData->collection_date_from;
                 $date_from = $ordersData->collection_date_from;
            }
            $date_to = NULL;
            if (isset($ordersData->collection_date_to) && !empty($ordersData->collection_date_to)) {
                 $collection_date_to = $ordersData->collection_date_to;
                 $date_to = $ordersData->collection_date_to;
            }
            if (isset($ordersData->collection_time_from) && !empty($ordersData->collection_time_from)) {
                 $collection_time_from = $ordersData->collection_time_from;
            }
            if ($collection_time_from && $date_from) {
                 $date_from = $date_from . " " . $collection_time_from;
            }
            if (isset($ordersData->collection_time_to) && !empty($ordersData->collection_time_to)) {
                 $collection_time_to = $ordersData->collection_time_to;
            }
            if (isset($collection_time_to) && $date_to) {
                 $date_to = $date_to . " " . $collection_time_to;
            }
            $ddate_from = NULL;
            if (isset($ordersData->delivery_date_from) && !empty($ordersData->delivery_date_from)) {
                 $delivery_date_from = $ordersData->delivery_date_from;
            }
            if ($delivery_date_from) {
                 $ddate_from = $delivery_date_from;
            }
            $ddate_to = NULL;
            if (isset($ordersData->delivery_date_to) && !empty($ordersData->delivery_date_to)) {
                 $delivery_date_to = $ordersData->delivery_date_to;
                 $ddate_to = $delivery_date_to;
            }
            if (isset($ordersData->delivery_time_from) && !empty($ordersData->delivery_time_from)) {
                 $delivery_time_from = $ordersData->delivery_time_from;
            }
            if ($delivery_time_from && $ddate_from) {
                 $ddate_from = $ddate_from . " " . $delivery_time_from;
            }
            if (isset($ordersData->delivery_time_to) && !empty($ordersData->delivery_time_to)) {
                 $delivery_time_to = $ordersData->delivery_time_to;
            }
            if ($delivery_time_to && $ddate_to) {
                 $ddate_to = $ddate_to . " " . $delivery_time_to;
            }


            if (isset($ordersData->expired_date) && !empty($ordersData->expired_date)) {
                 $expired_date = $ordersData->expired_date;
            } else {
                 $expired_date = "";
            }

            $courier_id = $this->session->userdata('courier_id');
            $total_result = $this->orders_model->gettenderslist_count_for_courier($search, $company, $username, $collection_address, $delivery_address, $date_from, $date_to, $ddate_from, $ddate_to, $expired_date, $courier_id);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $order_detail = $this->orders_model->gettenderslist_for_courier($search, $company, $username, $collection_address, $delivery_address, $date_from, $date_to, $ddate_from, $ddate_to, $expired_date, $start, $perpage, $courier_id);
            $bid_history = $this->consignment_bids_model->getbidhistory($courier_id, $company, $username, $collection_address, $delivery_address, $collection_date_from, $collection_date_to, $collection_time_from, $collection_time_to, $expired_date, $start, $perpage);
            foreach ($order_detail as $job) {
                 $collection_address = json_decode($job->collection_address);
                 $job->collection_address = $collection_address[1] ? $collection_address[1] : "";
                 $delivery_address = json_decode($job->delivery_address);
                 $job->delivery_address = $delivery_address[1] ? $delivery_address[1] : "";
            }
            $sorders = $order_detail;

            //status temporary
            /*
             * 1 new
             * 2 pending approval
             * 3 won
             * 4 lost
             * 5 withdrawn
             */

            foreach ($sorders as $key => $value) {
                 $sorders[$key]->status = 1;
                 $sorders[$key]->status_name = 'New';
                 $sorders[$key]->bid = '-';
                 $sorders[$key]->bid_id = 0;
                 $sorders[$key]->test = "";
                 foreach ($bid_history as $history) {
                      if ($history->id == $sorders[$key]->id) {
                           $sorders[$key]->bid_id = $history->bid_id;
                           $sorders[$key]->test = $history->status . '-' . $history->is_approved;
                           if ($history->status == 1 && $history->is_approved == 0) {
                                $sorders[$key]->status = 2;
                                $sorders[$key]->status_name = "Pending Bid Approval";
                                $sorders[$key]->bid = "$" . $history->bidding_price . '(' . $history->display_name . ')';
                           } else if ($history->status == 1 && $history->is_approved == 1) {
                                $sorders[$key]->status = 3;
                                $sorders[$key]->status_name = "Bid Won";
                                $sorders[$key]->bid = "$" . $history->bidding_price . '(' . $history->display_name . ')';
                           } else if ($history->status == 1 && $history->is_approved == 2) {
                                $sorders[$key]->status = 4;
                                $sorders[$key]->status_name = "Bid Lost";
                                $sorders[$key]->bid = "$" . $history->bidding_price . '(' . $history->display_name . ')';
                           } else if ($history->status == 2 && $history->is_approved == 0) {
                                $sorders[$key]->status = 5;
                                $sorders[$key]->status_name = "Bid re-open";
                           }
                      }
                 }
            }
            $result['order_detail'] = $order_detail;
            $result['end'] = (int) ($start + count($result['order_detail']));
            echo json_encode($result);
            exit();
       }

       public function bid_order() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $courier = $this->couriers_model->get_by_id($courier_id);
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->order_id) && !empty($post_data->order_id)) {
                 $job_id = $post_data->order_id;
            } else {
                 $error = true;
                 $errors['order_id'] = "Order id missing";
            }
            if (isset($post_data->service) && !empty($post_data->service)) {
                 $service = json_decode($post_data->service);
                 $service_id = $service->service_id;
                 $service_row_id = $service->id;
            } else {
                 $error = true;
                 $errors['service'] = "Service missing";
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks)) {
                 $remarks = $post_data->remarks;
            } else {
                 $remarks = "";
            }
            if (isset($post_data->price) && !empty($post_data->price)) {
                 $price = $post_data->price;
                 if (!is_numeric($price)) {
                      $error = true;
                      $errors['price'] = "Price must be an integer";
                 }
            } else {
                 $error = true;
                 $errors['price'] = "Price missing";
            }


            if (!$error) {
                 if ($this->orders_model->is_available_for_bid($job_id)) {
                      $data = array(
                          'service_id' => $service_id,
                          'service_row_id' => $service_row_id,
                          'courier_id' => $courier_id,
                          'bidding_price' => $price,
                          'remarks' => $remarks,
                          'status' => 1);
                      $bid_id = $this->consignment_bids_model->addbid($data);
                      if ($bid_id) {
                           $this->consignment_bids_model->add_bid_consignment_relation($bid_id, $job_id, $courier->company_name);
                           $this->send_mail_for_member($job_id, N_NEW_BID_RECEIVED);
                           $result['status'] = 1;
                           $result['class'] = 'alert-success';
                           $result['msg'] = "Success";
                      } else {
                           $result['status'] = 0;
                           $result['class'] = 'alert-warning';
                           $result['msg'] = lang('try_again');
                      }
                 } else {
                      $result['status'] = 1;
                      $result['class'] = 'alert-warning';
                      $result['msg'] = 'You can\'t bid this job';
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = 'alert-danger';
                 $result['msg'] = lang('clear_error');
                 if (isset($message)) {
                      $result['msg'] = $message;
                 }
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function withdraw_bid() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $courier = $this->couriers_model->get_by_id($courier_id);
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->bid_id) && !empty($post_data->bid_id)) {
                 $bid_id = intval($post_data->bid_id);
            } else {
                 $error = true;
                 $errors['bid_id'] = "Bid id missing";
            }
            if (!$error) {

                 if (isset($bid_id)) {
                      $consignment = $this->couriers_model->get_bid_consignments($bid_id);
                 }
                 $job_id = $consignment->consignment_id;
                 if ($this->orders_model->is_available_for_bid($job_id)) {
                      if ($this->consignment_bids_model->withdrawbid($bid_id, $courier_id, $courier->company_name)) {
                           $result['status'] = 1;
                           $result['class'] = 'alert-success';
                           $result['msg'] = "Success";
                      } else {
                           $result['status'] = 0;
                           $result['class'] = 'alert-warning';
                           $result['msg'] = lang('try_again');
                      }
                 } else {
                      $result['status'] = 1;
                      $result['class'] = 'alert-warning';
                      $result['msg'] = 'You can\'t withdraw';
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = 'alert-danger';
                 $result['msg'] = lang('clear_error');
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  