<?php

  class CourierController extends MX_Controller {

       function __construct() {
            parent::__construct();
            global $current_language;
            $this->load->config('language');

            if (($this->session->userdata('language'))) {
                 $this->language = $this->session->userdata('language');
            } else {
                 $this->load->model('options_model');
                 $options = $this->options_model->getOption('language_setting');
                 if ((isset($options['language']) && !empty($options['language']))) {
                      $this->language = $options['language'];
                 }
            }
            $this->lang->load($this->language, $this->language);

            $current_language = $this->language;
            $method = $this->router->fetch_method();
            if ($method !== "login" && $method !== "register" && $method !== "confirm_email") {
                 if (!$this->session->userdata("courier_id")) {
                      $this->session->set_flashdata('message', 'Please login before continue');
                      redirect(site_url('couriers/login'));
                 }
            }
       }

       public function is_approved() {
            $this->load->model('couriers/couriers_model');
            $courier = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
            if ($courier)
                 return $courier->is_approved;
            return FALSE;
       }

       public function is_logged_in() {
            if ($this->session->userdata("courier_id"))
                 return TRUE;
            return FALSE;
       }

       public function courier_sign_in($courier_id, $remember = FALSE) {
            $this->session->set_userdata("courier_id", $courier_id);
            $remember ? $this->session->cookie_monster(TRUE) : $this->session->cookie_monster(FALSE);
       }

       function logout() {
            $this->session->unset_userdata('courier_id');
       }

       public function send_mail_for_member($order_id = 0, $type = 0, $settings = 0, $extra_detail = "") {

            /*
             * send email notification to courier
             */
            $this->load->model('orders/orders_model');
            $this->load->model('account/notification_model');
            if ($type == N_NEW_SERVICE_BID) {
                 $members = $this->service_requests_model->get_owners($order_id);
            } else {
                 $members = $this->orders_model->get_owner($order_id);
            }
            $order = $this->orders_model->getjobdetail($order_id);
            $public_id = $order->public_id;
            foreach ($members as $member) {
                 $where = array('account_id' => $member->user_id, 'type' => 0, 'notification_id' => $type);
                 if ($this->notification_model->get_notification($where) || $settings) {

                      switch ($type) {
                           case N_COMMENT_FROM_COURIER:
                                $title = lang('courier_comment_email_title');
                                $content = lang('courier_comment_email_content');
                                $link_title = lang('courier_comment_email_link_title');
                                $link = site_url('system/admin_home#/orders' . (!empty($public_id) ? '/view_order/' . $public_id : '') . '/message');
                                break;
                           case N_ORDER_STATUS_UPDATE:
                                $title = lang('status_update_email_title');
                                $content = sprintf(lang('status_update_email_content'), $order->consignment_status);
                                $link_title = lang('status_update_email_link_title');
                                $link = site_url('system/admin_home#/orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           case N_NEW_BID_RECEIVED:
                                $title = lang('new_bid_email_title');
                                $content = lang('new_bid_email_content');
                                $link_title = lang('new_bid_email_link_title');
                                $link = site_url('system/admin_home#/orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           case N_NEW_SERVICE_BID:
                                $title = lang('service_bid_email_title');
                                $content = lang('service_bid_email_content');
                                $link_title = lang('service_bid_email_link_title');
                                $link = site_url('system/admin_home#/tender_requests/service_request/view_request/' . $order_id);
                                break;
                           case N_THRESHOLD:
                                $title = lang('threshold_email_title');
                                $content = lang('threshold_email_content');
                                $link_title = lang('threshold_email_link_title');
                                $link = site_url('system/admin_home#/orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           case N_ACCEPT:
                                $title = lang('accept_email_title');
                                $content = lang('accept_email_content');
                                $link_title = lang('accept_email_link_title');
                                $link = site_url('system/admin_home#/orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           default :$title = '';
                                $content = '';
                                $link_title = '';
                                $link = '';
                      }
                      $to = $member->email;
                      $to_name = $member->name;
                      $subject = '6connect email notification';
                      if ($extra_detail) {
                           $content = $content . $extra_detail;
                      }
                      $message = array(
                          'title' => $title,
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => $link_title,
                          'link' => $link);
                      save_mail($to, $to_name, $subject, $message, 1);
                 }
                 return;
            }

            /*
             * end email send
             */
            return;
       }

  }
  