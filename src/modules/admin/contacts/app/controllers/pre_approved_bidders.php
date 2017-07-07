<?php

  class Pre_approved_bidders extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('members_model', 'pre_approved_bidders_model', 'couriers/couriers_model', 'app/organisation_model'));
       }

       function index($org_id) {
            $data['is_admin'] = $is_admin = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            if ($is_admin)
                 $this->load->view('pre_approved_bidders_list', $data);
            else {
                 $this->load->view('unauth');
            }
       }

       public function couriers_count($org_id = 0) {
            $result = array();
            $result['total'] = $this->pre_approved_bidders_model->get_bidders_list_count($org_id, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
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
            if (isset($biddersData->org_id)) {

                 $org_id = $biddersData->org_id;
            } else {
                 $org_id = 0;
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
            $user_id = $this->session->userdata('user_id');
            $total_result = $this->pre_approved_bidders_model->get_bidders_list_count($org_id, $search);
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
            $bidders = $this->pre_approved_bidders_model->get_bidders_list($org_id, $perpage, $search, $start);
            $result['bidders'] = $bidders;
            $result['current_user_id'] = $user_id;
            $result['end'] = (int) ($start + count($result['bidders']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function update_open_bidding() {
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = $post_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($post_data->open_bid) && !empty($post_data->open_bid)) {
                 $open_bid = 1;
            } else {
                 $open_bid = 0;
            }

            if ($this->pre_approved_bidders_model->update_open_bid($org_id, $open_bid)) {
                 $result['status'] = 1;
                 $result['msg'] = lang('open_bid_updated_suc');
                 $result['class'] = "alert-success";
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_open_bid_status($org_id = 0) {
            $result = array();
            $result['status'] = $this->pre_approved_bidders_model->get_open_bid_status($org_id);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_all_couriers() {
            $result = array();
            $org_id = 0;
            $search = NULL;
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = $post_data->org_id;
            }
            if (isset($post_data->search) && !empty($post_data->search)) {
                 $search = $post_data->search;
            }
            $result['couriers'] = $this->pre_approved_bidders_model->non_approve_couriers($org_id, $search);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function invite() {
            $result = array();
            $error = false;
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = $post_data->org_id;
                 $org = $this->organisation_model->getorganisationDetails($org_id);
            } else {
                 $error = true;
            }
            if (isset($post_data->courier_id) && !empty($post_data->courier_id)) {
                 $courier_id = $post_data->courier_id;
                 $courier = $this->couriers_model->get_by_id($courier_id);
            } else {
                 if (isset($post_data->courier_mail) && !empty($post_data->courier_mail)) {
                      $mail = $post_data->courier_mail;
                      $courier = $this->couriers_model->get_by_email($mail);
                      if (!$courier) {
                           if (valid_email($mail)) {
                                $email_array = explode('@', $mail);
                                $companyname = $email_array[0] ? $email_array[0] : 'Anonymous';
                                $courier_id = $this->couriers_model->create($companyname, $mail, NULL, NULL, "", "", NULL, 1);
                                $courier = $this->couriers_model->get_by_id($courier_id);
                                $infom_admin = TRUE;
                           } else {
                                $error = true;
                           }
                      } else {
                           $courier_id = $courier->courier_id;
                      }
                 }
            }
            if (!$error) {
                 if ($this->pre_approved_bidders_model->add_courier(array(
                             "org_id" => $org_id,
                             "courier_id" => $courier_id,
                             "status" => 2
                         ))) {
                      $send_mail = TRUE;
                      $result['status'] = 1;
                      $result['msg'] = lang('new_pre_courier_added');
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-danger";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            if (isset($send_mail)) {
                 //inform courier via email
                 $registeration_url = site_url('couriers/register');
                 $to = $courier->email;
                 $to_name = $courier->company_name;
                 $subject = lang('pre_approved_bidder_email_subject');
                 // $message = sprintf(lang('pre_approved_bidder_email'), $to_name, $org->name, $registeration_url, site_url());
                 $message = array(
                     'title' => 'Invitation',
                     'name' => $to_name,
                     'content' => sprintf(lang('pre_approved_bidder_email'), $org->name, $registeration_url, site_url()),
                     'link_title' => '',
                     'link' => '',
                     'link2' => ''
                 );
                 save_mail($to, $to_name, $subject, $message);

                 if (isset($infom_admin)) {
                      //inform root admin                 
                      $root_admins = $this->members_model->get_root_admin();
                      foreach ($root_admins as $root) {
                           $to = $root->email;
                           $to_name = $root->name;
                           $subject = '6connect Courier Invitation';
                           $message = array(
                               'title' => "Invitation",
                               'name' => $to_name,
                               'content' => "Courier " . $courier->company_name . "(" . $courier->email . ") has been invited by " . $org->name . " to be a pre-approved courier",
                               'link_title' => "",
                               'link' => "",
                               'link2' => '');

                           $this->load->config('email');
                           save_mail($to, $to_name, $subject, $message, '', $this->config->item('bcc_email'));
                      }
                 }
            }
            exit();
       }

       public function remove_pre_approved_courier() {
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = $post_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($post_data->courier_id) && !empty($post_data->courier_id)) {
                 $courier_id = $post_data->courier_id;
            } else {
                 $courier_id = 0;
            }

            if ($this->pre_approved_bidders_model->delete_pre_approved_courier($org_id, $courier_id)) {
                 $total = $this->pre_approved_bidders_model->get_bidders_list_count($org_id, NULL);
                 if ($total < 2) {
                      $this->pre_approved_bidders_model->update_open_bid($org_id, 1);
                      $result['reload'] = true;
                 }
                 $result['status'] = 1;
                 $result['msg'] = lang('courier_removed_suc');
                 $result['class'] = "alert-success";
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  