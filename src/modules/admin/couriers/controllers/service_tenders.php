<?php

  class Service_tenders extends CourierController {

       function __construct($rootObject = 'tenderfiles') {
            parent::__construct();
            $this->load->model(array('couriers_model', 'orders/orders_model',
                'orders/consignment_messages_model',
                'orders/consignment_pod_model', 'app/service_request_log_model',
                'app/service_request_messages_model', 'jobstates_model',
                'app/service_requests_model', 'app/service_bids_model'));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
            $this->load->config('s3');
            $this->bucket = $this->config->item('aws_bucket_name');
            $rootObject = rtrim($rootObject, '/');
            $this->rootObject = $rootObject . '/';
            $this->load->library('s3');
       }

       public function index() {
            $this->load->view('service_tenders');
       }

       public function view($req_id) {
            $courier_id = $this->session->userdata('courier_id');
            $data = array();
            $request = $this->service_requests_model->get_request_details($req_id);
            if ($request) {
                 $uploads = $this->service_requests_model->get_request_files($req_id);
                 $fuploads = array();
                 foreach ($uploads as $upload) {
                      $object = $this->s3->getObjectInfo($this->bucket, $this->rootObject . $upload->url);

                      $fuploads[] = array(
                          'name' => $upload->filename,
                          'file_name' => $upload->url,
                          'path' => "http://$this->bucket.s3.amazonaws.com/" . $this->rootObject . $upload->url,
                          'filetype' => $object['type'],
                          'filesize' => $object['size']);
                 }
                 $request->uploads = $fuploads;
            }
            $data['request'] = $request;
            $data['bid'] = $this->service_bids_model->get_request_bid($req_id, $courier_id);
            $data['messages'] = $this->service_request_messages_model->list_req_messages($req_id, $courier_id);

            $this->load->view('service_tender_view', $data);
       }

       public function get_service_tenders_json() {
            $perpage = '';
            $search = NULL;
            $service = "";
            $status = "";
            $category = NULL;
            $sort = '';
            $sort_direction = '';
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
            if (isset($ordersData->category)) {
                 if ($ordersData->category != NULL) {
                      $catg = $ordersData->category;
                      switch ($catg) {
                           case "all":$category = NULL;
                                break;
                           case "open":$category = 1;
                                break;
                           case "closed":$category = 2;
                                break;
                           case "awarded":$category = 0;
                                break;
                      }
                 }
            }
            if (isset($ordersData->sort))
                 $sort = $ordersData->sort;
            if (isset($ordersData->sort_direction))
                 $sort_direction = $ordersData->sort_direction;

            $courier_id = $this->session->userdata('courier_id');
            $total_result = $this->service_requests_model->get_request_list_count($search, $category,$courier_id);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $reqs = $this->service_requests_model->get_request_list_for_courier($search, $start, $perpage, $category, $sort, $sort_direction,$courier_id);
            // debug($this->db->last_query());
            $bid_history = $this->service_bids_model->get_request_bids($courier_id, $sort, $sort_direction);

            foreach ($reqs as $key => $value) {
                 if (strlen($value->description) > 150) {
                      $length = strpos($value->description, ' ', 150);
                      $value->description = substr($value->description, 0, $length) . '...';
                 }
                 /*
                  * -1 new
                  * 0 withdrawn
                  * 1 pending
                  * 2 won
                  * 3 lost
                  */
                 if ($value->request_stat == 1) {
                      $reqs[$key]->status = -1;
                      $reqs[$key]->status_name = 'New';
                      $reqs[$key]->bid = '-';
                      $reqs[$key]->service_id = 0;
                      $reqs[$key]->bid_id = 0;
                 } else {
                      $reqs[$key]->status = NULL;
                      $reqs[$key]->status_name = 'Awarded';
                      $reqs[$key]->bid = '';
                      $reqs[$key]->service_id = 0;
                      $reqs[$key]->bid_id = 0;
                 }
                 foreach ($bid_history as $history) {
                      if ($reqs[$key]->request_id == $history->req_id) {
                           $reqs[$key]->bid = $history->service;
                           $reqs[$key]->bid_id = $history->bid_id;
                           $reqs[$key]->service_id = $history->service_id;
                           $reqs[$key]->status = $history->status;
                           if ($history->status == 1) {
                                $reqs[$key]->status_name = "Pending";
                           } else if ($history->status == 2) {
                                $reqs[$key]->status_name = "Accepted";
                           } else if ($history->status == 3) {
                                $reqs[$key]->status_name = "Rejected";
                           } else if ($history->status == 0) {
                                $reqs[$key]->status_name = "Withdrawn";
                           }
                      }
                 }
            }

            $result['requests'] = $reqs;
            $result['end'] = (int) ($start + count($result['requests']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function bid_request() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $courier = $this->couriers_model->get_by_id($courier_id);
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->request_id) && !empty($post_data->request_id)) {
                 $req_id = $post_data->request_id;
                 if (!$this->service_requests_model->is_available_for_bid($req_id)) {
                      $error = TRUE;
                      $message = "You can't bid this request";
                 }
            } else {
                 $error = true;
                 $errors['request_id'] = "Request id missing";
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


            if (!$error) {
                 $data = array(
                     'req_id' => $req_id,
                     'service_id' => $service_row_id,
                     'courier_id' => $courier_id,
                     'status' => 1);
                 $bid_id = $this->service_bids_model->addbid($data);
                 if ($bid_id) {
                      $this->send_mail_for_member($req_id, N_NEW_SERVICE_BID);
                      $this->service_request_log_model->add_activity(array('request_id' => $req_id, 'activity' => "New bid from " . $courier->company_name));
                      $result['status'] = 1;
                      $result['class'] = 'alert-success';
                      $result['msg'] = "Success";
                 } else {
                      $result['status'] = 0;
                      $result['class'] = 'alert-warning';
                      $result['msg'] = lang('try_again');
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
                 $bid_id = $post_data->bid_id;
            } else {
                 $error = true;
                 $errors['bid_id'] = "Bid id missing";
            }

            if (!$error) {
                 if ($this->service_bids_model->withdrawbid($bid_id, $courier_id)) {
                      $request = $this->service_bids_model->get_request_id($bid_id);
                      $this->service_request_log_model->add_activity(array('request_id' => $request->req_id, 'activity' => "Bid withdrawn by " . $courier->company_name));
                      $result['status'] = 1;
                      $result['class'] = 'alert-success';
                      $result['msg'] = "Success";
                 } else {
                      $result['status'] = 0;
                      $result['class'] = 'alert-warning';
                      $result['msg'] = lang('try_again');
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

       public function add_comment() {
            $courier_id = $this->session->userdata('courier_id');
            $result = array();
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->req_id)) {

                 $req_id = $biddersData->req_id;
            } else {
                 exit();
            }
            if (isset($biddersData->comment)) {

                 $comment = $biddersData->comment;
            } else {
                 $comment = "";
            }
            $data = array(
                'courier_id' => $courier_id,
                'request_id' => $req_id,
                'question' => $comment,
                'reply' => NULL,
                'type' => 2
            );
            if ($this->service_request_messages_model->add_message($data)) {
                 $result['status'] = 1;
                 $result['last'] = '<div class="question">'
                         . '<div class="q_head">'
                         . '<div class="q_title">Ask by you</div>'
                         . '<div class="q_time">' . date('Y-m-d h:i A', now()) . '</div>'
                         . '</div>'
                         . '<div class="q_text">'
                         . '<p>' . $comment . '</p>'
                         . '<div class="q_response">'
                         . '<div class="q_text">'
                         . '<p><strong>Customer not yet responded to your question.</strong></p>'
                         . '</div>'
                         . '</div>'
                         . '</div>'
                         . '</div>';
            }

            echo json_encode($result);
            exit();
       }

  }
  