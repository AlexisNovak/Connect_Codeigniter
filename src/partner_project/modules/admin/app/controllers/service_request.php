<?php

  class Service_request extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('members_model',
                'account/account_details_model',
                'service_requests_model',
                'service_bids_model',
                'groups_model',
                'services_model',
                'service_request_messages_model',
                'service_request_log_model'));
       }

       function index() {
            $data = array();
            $this->load->view('request/service_requestlist', $data);
       }

       function new_request() {
            $data = array();
            $this->load->view('request/new_request', $data);
       }

       public function intro() {
            $this->load->view('approved_service_intro');
       }

       public function get_srequest_count() {
            $result = array();
            $user_id = $this->session->userdata('partner_user_id');
            $result['total'] = $this->service_requests_model->getrequestlist_by_org_count($user_id, NULL, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }
       

       public function srequestlist_json() {
            $perpage = '';
            $search = '';
            $reqData = json_decode(file_get_contents('php://input'));

            if ($reqData != NULL && isset($reqData->perpage_value)) {

                 $perpage = $reqData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($reqData->filter)) {
                 if ($reqData->filter != NULL) {
                      $search = $reqData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            if (isset($reqData->currentPage)) {

                 $page = $reqData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($reqData->org_id)) {

                 $org_id = $reqData->org_id;
            } else {
                 $org_id = NULL;
            }
            $user_id = $this->session->userdata('partner_user_id');
            $total_result = $this->service_requests_model->getrequestlist_by_org_count($user_id, $org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $requests = $this->service_requests_model->getrequestlist_by_org($user_id, $org_id, $perpage, $search, $start);
            $result['end'] = (int) ($start + count($requests));
            foreach ($requests as $value) {
                 if (strlen($value->description) > 150) {
                      $length = strpos($value->description, ' ', 150);
                      $value->description = substr($value->description, 0, $length) . '...';
                 }
            }
            $result['srequests'] = $requests;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function _validate_req() {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('title', 'Title', 'required|trim');
            $this->form_validation->set_rules('duration', 'Duration', 'required|trim|numeric');
            $this->form_validation->set_rules('org_id', 'Organisation', 'required|trim|numeric');
            $this->form_validation->set_rules('type', 'Service type', 'required|trim');
            $this->form_validation->set_rules('payment', 'Payment', 'required|trim');
            $this->form_validation->set_rules('compensation', 'Compensation', 'trim');
            $this->form_validation->set_rules('delpermonth', 'Deliveries per month', 'required|trim|numeric');
            $this->form_validation->set_rules('price', 'Price range', 'trim');
            $this->form_validation->set_rules('description', 'Other details', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('expiry', 'Expiry Date', 'trim|required');
            return $this->form_validation->run($this) !== FALSE;
       }

       public function save() {
            $result = array();
            $reqData = json_decode(file_get_contents('php://input'));
            $_POST = (array) $reqData;
            if ($this->_validate_req()) {
                 $request_id = $this->input->post('req_id', TRUE);
                 $title = $this->input->post('title', TRUE);
                 $request = array(
                     'name' => $title,
                     'user_id' => $this->session->userdata('partner_user_id'),
                     'org_id' => $this->input->post('org_id', TRUE),
                     'type' => $this->input->post('type', TRUE),
                     'price_range' => $this->input->post('price', TRUE) ? $this->input->post('price', TRUE) : "",
                     'delivery_p_m' => $this->input->post('delpermonth', TRUE),
                     'service_duration' => $this->input->post('duration', TRUE),
                     'payment_term' => $this->input->post('payment', TRUE),
                     'other_conditions' => $this->input->post('compensation', TRUE) ? $this->input->post('compensation', TRUE) : "",
                     'remarks' => $this->input->post('description', TRUE),
                     'expiry_date' => date("Y-m-d H:i:s", strtotime($this->input->post('expiry', TRUE))),
                     "status" => 1
                 );
                 $page = 1;
                 $couriers = array();
                 do {
                      $result = $this->service_bids_model->getbidderslist($request_id, $page, '', ($page - 1) * 50);
                      foreach ($result as $row) {
                           $couriers[] = array('email' => $row->email, 'name' => $row->courier);
                      }
                      $page++;
                 } while (count($result) > 0);
                 if ($this->service_requests_model->add_request($request, $request_id)) {
                      if (!empty($request_id)) {
                           $request_url = site_url("system/admin_home#/tender_requests/service_request/view_request/" . $request_id);
                           $to = '';
                           $to_name = '';
                           $subject = lang('service_request_withdrawn_email_subject');
                           $message = '';
                           foreach ($couriers as $courier) {
                                $to = $courier['email'];
                                $to_name = $courier['name'];
                                $message = sprintf(lang('service_request_withdrawn_email'), $to_name, $title, $request_url);
                                save_mail($to, $to_name, $subject, $message,2);
                           }
                      }
                      $result['status'] = 1;
                      $result['msg'] = lang('srequest_save_success');
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-danger";
                 }
            } else {
                 $errors = array();
                 if (form_error('title')) {
                      $errors['title_error'] = form_error('title');
                 }
                 if (form_error('description')) {
                      $errors['description_error'] = lang('srequest_description_ph_sub');
                 }
                 if (form_error('delpermonth')) {
                      $errors['delpermonth_error'] = form_error('delpermonth');
                 }
                 if (form_error('duration')) {
                      $errors['duration_error'] = form_error('duration');
                 }
                 $result['status'] = 0;
                 $result['msg'] = lang('srequest_errors');
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       public function view($req_id) {
            $data = $this->get_request_details($req_id);
            if ($data['is_admin']) {
                 $this->load->view('request/view_request', $data);
            } else {
                 $this->load->view('unauth');
            }
       }

       public function request_details($req_id) {
            $data = $this->get_request_details($req_id);
            echo json_encode($data);
            exit;
       }

       private function get_request_details($req_id) {
            $org = $this->service_requests_model->get_org($req_id);
            $org_id = $org ? $org->org_id : 0;
            $data['is_admin'] = $is_admin = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            $data['org_id'] = $org_id;
            if ($is_admin) {
                 $data['request'] = $this->service_requests_model->get_request_details($req_id);
            } else {
                 $data['auth'] = 0;
            }
            $data['request'] = $this->service_requests_model->get_request_details($req_id);
            return $data;
       }

       public function save_request_details() {
            $requestData = json_decode(file_get_contents('php://input'));
            $originData = $this->get_request_details($requestData->req_id);
            $originReq = $originData['request'];
            if (!$originData['is_admin'] || $originReq->status == '0') {
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            } else {
                 $this->
                         $result['msg'] = lang('srequest_save_success');
                 $result['class'] = "alert-success";
            }
            echo json_encode($result);
            exit;
       }

       public function messageslist_json() {
            $result = array();
            $perpage = '';
            $search = '';
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->perpage_value)) {

                 $perpage = $biddersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($biddersData->request_id)) {

                 $request_id = $biddersData->request_id;
            } else {
                 $request_id = 0;
            }
            if (isset($biddersData->currentPage)) {

                 $page = $biddersData->currentPage;
            } else {
                 $page = 1;
            }
            $messages = $this->service_request_messages_model->getmessages($request_id);
            $result['total'] = $this->service_request_messages_model->get_message_count($request_id);
            $result['reply'] = $this->service_request_messages_model->get_reply_count($request_id);
            $result['messages'] = $messages;
            echo json_encode($result);
            exit();
       }

       public function add_reply() {
            $result = array();
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->msg_id)) {

                 $msg_id = (int) $biddersData->msg_id;
            } else {
                 $msg_id = 0;
            }
            if (isset($biddersData->reply)) {

                 $reply = $biddersData->reply;
            } else {
                 $reply = "";
            }

            $messages = $this->service_request_messages_model->add_reply($msg_id, array('reply' => $reply, 'updated_date' => mdate('%Y-%m-%d %H:%i:%s', now())));
            if ($messages) {
                 $this->send_mail_for_courier($request_id, N_COMMENT_RESPONSE);
            }
            $result['messages'] = $messages;
            echo json_encode($result);
            exit();
       }

       public function add_comment() {
            $result = array();
            $perpage = '';
            $search = '';
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->request_id)) {

                 $request_id = $biddersData->request_id;
            } else {
                 exit();
            }
            if (isset($biddersData->comment)) {

                 $comment = $biddersData->comment;
            } else {
                 $comment = "";
            }
            // $request_id = $this->service_requests_model->get_request_details($request_id);

            if ($this->service_request_messages_model->add_message(
                            array(
                                'courier_id' => $this->session->userdata('partner_user_id'),
                                'request_id' => $request_id,
                                'question' => $comment,
                                'reply' => NULL,
                                'type' => 1
                    ))) {
                 $last = $this->service_request_messages_model->get_last_comment($request_id);
            }
            $result['last'] = $last;
            echo json_encode($result);
            exit();
       }

       public function bidderlist_json() {
            $perpage = '';
            $search = '';
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->perpage_value)) {

                 $perpage = $biddersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($biddersData->request_id)) {

                 $request_id = $biddersData->request_id;
            } else {
                 $request_id = 0;
            }
            if (isset($biddersData->currentPage)) {

                 $page = $biddersData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($biddersData->filter)) {
                 if ($biddersData->filter != NULL) {
                      $search = $biddersData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $user_id = $this->session->userdata('partner_user_id');
            $total_result = $this->service_bids_model->getbidderslist_count($request_id, $search);
            //$total_result = 20;
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $bidders = array();
            $bidders = $this->service_bids_model->getbidderslist($request_id, $perpage, $search, $start);
            $result['bidders'] = $bidders;
            $result['current_user_id'] = $user_id;
            $result['end'] = (int) ($start + count($result['bidders']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function accept_bid() {
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->bid_id)) {
                 $bid_id = (int) $biddersData->bid_id;
            } else {
                 $bid_id = 0;
            }
            if (isset($biddersData->request_id)) {

                 $request_id = $biddersData->request_id;
            } else {
                 $request_id = 0;
            }

            $result = array();
            $bid = $this->service_bids_model->get_bid($bid_id);
            if ($bid) {
                 $org = $this->service_requests_model->get_org($request_id);
                 if ($this->service_bids_model->accept_bid($request_id, $bid_id, $org->org_id, $bid->service_id)) {
                      $groups = $this->groups_model->get_org_groupids($org->org_id);
                      foreach ($groups as $group) {
                           $data = array(
                               'service_id' => $bid->service_id,
                               'org_id' => $org->org_id,
                               'group_id' => $group->id,
                               'status' => 1);
                           $this->services_model->addGroup($data);
                      }
                      $this->service_request_log_model->add_activity(array("request_id" => $request_id, "activity" => "Accepted Bid"));
                      $this->send_mail_for_courier(0, N_BID_WON, 0, $bid->courier_id, array("type" => "service", "request_id" => $request_id));
                      $result['status'] = 1;
                      $result['org_id'] = $org->org_id;
                      $result['msg'] = lang('accept_bid_success');
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('accept_bid_failed');
                      $result['class'] = "alert-danger";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('accept_bid_failed');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function loglist_json() {
            $perpage = '';
            $search = '';
            $loglistData = json_decode(file_get_contents('php://input'));
            if (isset($loglistData->perpage_value)) {

                 $perpage = $loglistData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($loglistData->request_id)) {

                 $request_id = $loglistData->request_id;
            } else {
                 $request_id = 0;
            }
            if (isset($loglistData->currentPage)) {

                 $page = $loglistData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($loglistData->filter)) {
                 if ($loglistData->filter != NULL) {
                      $search = $loglistData->filter;
                 } else {
                      $search = NULL;
                 }
            }

            $user_id = $this->session->userdata('partner_user_id');
            $total_result = $this->service_request_log_model->getloglist_count($request_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $loglist = array();
            $loglist = $this->service_request_log_model->getloglist_by_reqid($request_id, $perpage, $search, $start);

            $result['loglist'] = $loglist;
            $result['current_user_id'] = $user_id;
            $result['end'] = (int) ($start + count($result['loglist']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }

?>