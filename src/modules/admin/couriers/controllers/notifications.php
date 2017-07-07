<?php

  class Notifications extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model('account/notification_model');           
       }

       public function index() {
            $data = array();
            $this->load->view('notification_settings', $data);
       }

       public function get_notification_settings() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            if ($courier_id) {

                 $settngs = array('assignorder' => ($this->notification_model->get_notification(array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_DIRECT_ASSIGN))) ? TRUE : FALSE,
                     'addcomment' => ($this->notification_model->get_notification(array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_COMMENT_RESPONSE))) ? TRUE : FALSE,
                     'bidwon' => ($this->notification_model->get_notification(array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_BID_WON))) ? TRUE : FALSE,
                     'cancelorder' => ($this->notification_model->get_notification(array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_CANCEL_ORDER))) ? TRUE : FALSE);
                 $result['notification'] = $settngs;
            }
            echo json_encode($result);
            exit();
       }

       public function update_notification_settings() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $notifications = json_decode(file_get_contents('php://input'));
            $where = array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_DIRECT_ASSIGN);
            if (isset($notifications->assignorder) && $notifications->assignorder) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }

            $where = array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_COMMENT_RESPONSE);
            if (isset($notifications->addcomment) && $notifications->addcomment) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }
            $where = array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_BID_WON);
            if (isset($notifications->bidwon) && $notifications->bidwon) {
                 if (!$this->notification_model->get_notification($where)) {
                      $this->notification_model->add_notification($where);
                 }
            } else {
                 if ($this->notification_model->get_notification($where)) {
                      $this->notification_model->remove_notification($where);
                 }
            }

            $where = array('account_id' => $courier_id, 'type' => 1, 'notification_id' => N_CANCEL_ORDER);
            if (isset($notifications->cancelorder) && $notifications->cancelorder) {
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
  