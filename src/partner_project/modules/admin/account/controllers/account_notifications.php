<?php

  class Account_notifications extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('notification_model');
       }

       public function index() {
            $data = array();
            $this->load->view('notification_settings', $data);
       }

       public function get_notification_settings() {
            $result = array();
            $user_id = $this->session->userdata('partner_user_id');
            if ($user_id) {

                 $settngs = array('bidreceived' => ($this->notification_model->get_notification(array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_BID_RECEIVED))) ? TRUE : FALSE,
                     'servicebid' => ($this->notification_model->get_notification(array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_SERVICE_BID))) ? TRUE : FALSE,
                     'statusupdate' => ($this->notification_model->get_notification(array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_ORDER_STATUS_UPDATE))) ? TRUE : FALSE,
                     'comment_from_courier' => ($this->notification_model->get_notification(array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_COMMENT_FROM_COURIER))) ? TRUE : FALSE);
                 $result['notification'] = $settngs;
            }
            echo json_encode($result);
            exit();
       }

       public function update_notification_settings() {
            $result = array();
            $user_id = $this->session->userdata('partner_user_id');
            $notifications = json_decode(file_get_contents('php://input'));
            $where = array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_BID_RECEIVED);
            if (isset($notifications->bidreceived) && $notifications->bidreceived) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }

            $where = array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_SERVICE_BID);
            if (isset($notifications->servicebid) && $notifications->servicebid) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }
            $where = array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_ORDER_STATUS_UPDATE);
            if (isset($notifications->statusupdate) && $notifications->statusupdate) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }

            $where = array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_COMMENT_FROM_COURIER);
            if (isset($notifications->comment_from_courier) && $notifications->comment_from_courier) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }
            $result['msg'] = 'Notification settings updated';
            $result['class'] = 'alert-success';
            $result['status'] = 1;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  