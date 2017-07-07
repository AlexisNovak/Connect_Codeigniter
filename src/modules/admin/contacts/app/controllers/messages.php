<?php

  class Messages extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('messages_model');
       }

       public function get_latest_msg_info() {
            $result = array();
            $user_id = $this->session->userdata('user_id');
            $msg_count = $this->messages_model->get_msg_count($user_id);
            $smsg_count = $this->messages_model->get_smsg_count($user_id);
            $omsg_count = $this->messages_model->get_omsg_count($user_id);
            $result['omcount'] = ($omsg_count->count > 9) ? "n" : $omsg_count->count;
            $result['smcount'] = ($smsg_count->count > 9) ? "n" : $smsg_count->count;
            $result['tmcount'] = $omsg_count->count + $smsg_count->count;
            $messages = $this->messages_model->get_latest_msgs($user_id);
            // debug($messages);
            $maxlen = 40;
            $result_msg = array();
            foreach ($messages as $msg) {
                 $msg = json_decode($msg->message);
                 if (is_array($msg->content)) {
                      $msg->content = implode(',', $msg->content);
                 }
                 if (strlen($msg->content) > $maxlen) {
                      $msg->content = substr($msg->content, 0, $maxlen) . '...';
                 }
                 $result_msg[] = array('content' => $msg->content, 'link' => $msg->link);
            }
            $result['msgs'] = $result_msg;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_notifications() {
            $result = array();
            $user_id = $this->session->userdata('user_id');
            $postData = json_decode(file_get_contents('php://input'));
            if (isset($postData->page) && !empty($postData->page)) {
                 $page = (int) $postData->page;
            } else {
                 $page = 1;
            }
            if (isset($postData->limit) && !empty($postData->limit)) {
                 $limit = (int) $postData->limit;
            } else {
                 $limit = 10;
            }
            $messages = $this->messages_model->get_latest_msgs($user_id, $page, $limit);
            $result_msg = array();
            foreach ($messages as $msg) {
                 $msg = json_decode($msg->message);
                 if (is_array($msg->content)) {
                      $msg->content = implode(',', $msg->content);
                 }
                 $result_msg[] = array('content' => $msg->content, 'link' => $msg->link, 'title' => $msg->title);
            }
            $result['msgs'] = $result_msg;
            $result['last_notified'] = $this->messages_model->get_last_msg_time($user_id);
            ;
            $more = $this->messages_model->get_latest_msgs($user_id, $page + 1, $limit);
            if (count($more) > 0) {
                 $result['loadmore'] = TRUE;
            } else {
                 $result['loadmore'] = FALSE;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_notifications_courier() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $postData = json_decode(file_get_contents('php://input'));
            if (isset($postData->page) && !empty($postData->page)) {
                 $page = (int) $postData->page;
            } else {
                 $page = 1;
            }
            if (isset($postData->limit) && !empty($postData->limit)) {
                 $limit = (int) $postData->limit;
            } else {
                 $limit = 10;
            }
            $messages = $this->messages_model->get_latest_msgs_courier($courier_id, $page, $limit);
            $result_msg = array();
            foreach ($messages as $msg) {
                 $msg = json_decode($msg->message);
                 if (is_array($msg->content)) {
                      $msg->content = implode(',', $msg->content);
                 }
                 $result_msg[] = array('content' => $msg->content, 'link' => $msg->link, 'title' => $msg->title);
            }
            $result['msgs'] = $result_msg;
            $result['last_notified'] = $this->messages_model->get_last_msg_time_courier($courier_id);
            ;
            $more = $this->messages_model->get_latest_msgs_courier($courier_id, $page + 1, $limit);
            if (count($more) > 0) {
                 $result['loadmore'] = TRUE;
            } else {
                 $result['loadmore'] = FALSE;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_latest_msg_info_for_courier() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $msg_count = $this->messages_model->get_msg_count_courier($courier_id);
            $smsg_count = $this->messages_model->get_scount_courier($courier_id);
            $omsg_count = $this->messages_model->get_ocount_courier($courier_id);
            $result['omcount'] = ($omsg_count->count > 9) ? "n" : $omsg_count->count;
            $result['smcount'] = ($smsg_count->count > 9) ? "n" : $smsg_count->count;
            $result['tmcount'] = $msg_count->count;
            $messages = $this->messages_model->get_latest_msgs_courier($courier_id);
            // debug($messages);
            $maxlen = 40;
            $result_msg = array();
            foreach ($messages as $msg) {
                 $msg = json_decode($msg->message);
                 if (is_array($msg->content)) {
                      $msg->content = implode(',', $msg->content);
                 }
                 if (strlen($msg->content) > $maxlen) {
                      $msg->content = substr($msg->content, 0, $maxlen) . '...';
                 }
                 $result_msg[] = array('content' => $msg->content, 'link' => $msg->link);
            }
            $result['msgs'] = $result_msg;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function update_last_msg_time() {
            $result = array();
            $user_id = $this->session->userdata('user_id');
            $this->messages_model->update_last_msg($user_id);
            exit();
       }

       public function update_last_msg_time_of_courier() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $this->messages_model->update_last_msg_courier($courier_id);
            exit();
       }

       public function notifications() {
            $this->load->view('notifications');
       }

  }
  