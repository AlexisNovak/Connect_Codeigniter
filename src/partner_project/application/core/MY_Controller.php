<?php

  class MY_Controller extends MX_Controller {

       var $language = "english";

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
            if ($this->input->is_ajax_request()) {
                 $this->session->sess_update();
            }
            //debug($this->session->userdata);
       }

       public function send_mail_for_courier($order_id = 0, $type = 0, $settings = 0, $courier_id = 0, $attr = array()) {
            /*
             * send email notification to courier
             */
            $this->load->model('orders/orders_model');
            $this->load->model('account/notification_model');
            $this->load->model('couriers/couriers_model');
            if ($type == N_COMMENT_RESPONSE || $type == N_COMMENT_FROM_OWNER) {
                 $order_type = $this->orders_model->get_order_assign_type($order_id);
                 if ($order_type == 1) {
                      $couriers = $this->orders_model->get_courier($order_id);
                 } else if ($order_type == 2) {
                      $couriers = $this->orders_model->get_biders($order_id);
                 } else if ($order_type == 3) {
                      $settings = 1;
                      $couriers = $this->orders_model->get_third_party_courier($order_id);
                 }
            } else if ($type == N_CLOSED_BID) {
                 $couriers = $this->orders_model->get_closed_biders($order_id);
            } else if ($type == N_THIRD_PARTY) {
                 $couriers = $this->orders_model->get_third_party_courier($order_id);
            } else if (!empty($courier_id)) {
                 $couriers = array($this->couriers_model->get_for_mail($courier_id));
            } else if ($type == N_ORDER_CHANGED) {
                 $couriers = $this->orders_model->get_biders($order_id);
            } else {
                 $couriers = $this->orders_model->get_courier($order_id);
            }

            foreach ($couriers as $courier) {
                 $public_id = $this->orders_model->get_public_id($order_id);
                 $owner = $this->orders_model->get_owner($order_id);
                 $customer = $this->orders_model->get_customer_name($public_id);
                 if (!empty($owner) && is_array($owner))
                      $owner = $owner[0];
                 $where = array('account_id' => $courier->courier_id, 'type' => 1, 'notification_id' => $type);
                 if ($this->notification_model->get_notification($where) || $settings == 1) {
                      switch ($type) {
                           case N_DIRECT_ASSIGN:
                                $title = ""; //sprintf(lang('direct_assign_email_title'), $public_id);
                                $content = sprintf(lang('direct_assign_email_content'), $customer->username, $customer->organisation);
                                $link_title = lang('direct_assign_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           case N_COMMENT_RESPONSE:
                                $title = lang('comment_response_email_title');
                                $content = lang('comment_response_email_content');
                                $link_title = lang('comment_response_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : '') . '/message');
                                break;
                           case N_COMMENT_FROM_OWNER:
                                $title = lang('new_comment_email_title');
                                $content = lang('new_comment_email_content');
                                $link_title = lang('new_comment_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : '') . '/message');
                                break;
                           case N_BID_WON:
                                if (!empty($attr['type']) && $attr['type'] == 'service') {
                                     $this->load->model("app/service_requests_model");
                                     $request = $this->service_requests_model->get_request_details($attr['request_id']);
                                     $title = lang('bid_won_service_email_title');
                                     $content = sprintf(lang('bid_won_service_email_content'), $request->title);
                                     $link_title = lang('bid_won_service_email_link_title');
                                     $link = site_url('couriers/dashboard#/service_tenders/view_tender/' . $request->req_id);
                                } else {
                                     $title = lang('bid_won_email_title');
                                     $content = lang('bid_won_email_content');
                                     $link_title = lang('bid_won_email_link_title');
                                     $link = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                }
                                break;
                           case N_CANCEL_ORDER:
                                $title = lang('cancel_order_email_title');
                                $content = lang('cancel_order_email_content');
                                $link_title = lang('cancel_order_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                break;
                           case N_ORDER_CHANGED:
                                $title = lang('change_order_email_title');
                                $content = lang('change_order_email_content');
                                $link_title = lang('change_order_email_link_title');
                                $link = site_url('couriers/dashboard#/delivery_requests' . (!empty($public_id) ? '/view_request/' . $public_id : ''));
                                break;
                           case N_CLOSED_BID:
                                $title = lang('closed_bid_email_title');
                                $content = lang('closed_bid_email_content');
                                $link_title = lang('closed_bid_email_link_title');
                                $link = site_url('couriers/dashboard#/delivery_requests' . (!empty($public_id) ? '/view_request/' . $public_id : ''));
                                break;
                           case N_THIRD_PARTY:
                                $order_url = site_url('thirdparty/orders/view/' . $courier->job_id . '/' . $courier->permalink);
                                $title = lang('thridparty_new_job_email_subject');
                                $content = sprintf(lang('thridparty_new_job_email'), $customer->username, $customer->organisation);
                                $link_title = lang('view_order_email_title');
                                $link = $order_url;
                                break;
                           case N_PRICE_APPROVED:
                                $order_url = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                $title = 'Price approved';
                                $content = 'Price approved by customer';
                                $link_title = lang('view_order_email_title');
                                $link = $order_url;
                                break;
                           case N_PRICE_REJECTED:
                                $order_url = site_url('couriers/dashboard#/assigned_orders' . (!empty($public_id) ? '/view_order/' . $public_id : ''));
                                $title = 'Price Rejected';
                                $content = 'Price rejected by customer';
                                $link_title = lang('view_order_email_title');
                                $link = $order_url;
                                break;
                           default :
                                $title = '';
                                $content = '';
                                $link_title = '';
                                $link = '';
                      }
                      $to = $courier->email;
                      $to_name = $courier->name;
                      $subject = '6connect email notification';
                      $message = array(
                          'title' => $title,
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => $link_title,
                          'link' => $link);
                      save_mail($to, $to_name, $subject, $message, 2);
                 }
            }

            /*
             * end email send
             */
            return;
       }

  }
  