<?php

  class Available_service_requests extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array('couriers_model', 'orders/orders_model', 'courier_service_model',
                'orders/consignment_messages_model',
                'orders/consignment_pod_model', 'app/service_request_log_model',
                'app/service_request_messages_model', 'jobstates_model',
                'app/service_requests_model', 'app/request_courier_service_model'));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $this->load->view('available_service_requests');
       }

       public function view($req_id) {
            $courier_id = $this->session->userdata('courier_id');
            $data = array();
            $service = $this->courier_service_model->get_details_by_id($req_id);
            $data['service'] = $service;
            $this->load->view('service_request_view', $data);
       }

       public function get_requestlist_json($service_id = 0) {
            $courier_id = $this->session->userdata('courier_id');
            $result = array();
            $search = NULL;
            $requests = $this->request_courier_service_model->get_requests_for_courier($courier_id, $service_id, $search);
            $result['requests'] = $requests;
            echo json_encode($result);
            exit();
       }

       public function serviceslist_json() {
            $perpage = '';
            $search = '';
            $servicesData = json_decode(file_get_contents('php://input'));
            if (isset($servicesData->perpage_value)) {

                 $perpage = $servicesData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($servicesData->currentPage)) {
                 $page = $servicesData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($servicesData->filter)) {
                 if ($servicesData->filter != NULL) {
                      $search = $servicesData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $courier_id = $this->session->userdata("courier_id");
            $total_result = $this->courier_service_model->get_requested_services_count($courier_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $services = $this->courier_service_model->get_service_requests_count($courier_id, $perpage, $search, $start);

            $result['service_detail'] = $services;
            $result['current_user_id'] = $this->session->userdata('user_id');
            $result['end'] = (int) ($start + count($result['service_detail']));
            echo json_encode($result);
       }

       public function accept_request() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->request_id) && !empty($post_data->request_id)) {
                 $req_id = $post_data->request_id;
            } else {
                 $error = true;
                 $errors['request_id'] = "Request id missing";
            }

            if (!$error) {
                 $request = $this->request_courier_service_model->get_request($req_id);
                 if ($request) {
                      $this->request_courier_service_model->approve_service($request->request_id, $request->service_id, $request->org_id, $courier_id);
                      $result['status'] = 1;
                      $result['class'] = 'alert-success';
                      $result['msg'] = lang('a_s_request_accepted_suc');
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

       public function reject_request() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->request_id) && !empty($post_data->request_id)) {
                 $req_id = $post_data->request_id;
            } else {
                 $error = true;
                 $errors['request_id'] = "Request id missing";
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks)) {
                 $remarks = $post_data->remarks;
            } else {
                 $remarks = "";
            }

            if (!$error) {
                 if ($this->request_courier_service_model->reject_request($req_id, $remarks, $courier_id)) {
                      $result['status'] = 1;
                      $result['class'] = 'alert-success';
                      $result['msg'] = lang('a_s_request_rejected_suc');
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

  }
  