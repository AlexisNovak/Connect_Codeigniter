<?php

  class Notification_model extends CI_Model {

       public function initiate_notification($data) {
            return $this->db->insert_batch('notification_settings', $data);
       }

       public function add_notification($data) {
            return $this->db->insert('notification_settings', $data);
       }

       public function remove_notification($where) {
            return $this->db->where($where)->delete('notification_settings');
       }

       public function get_notification($where) {
            return $this->db->where($where)->from('notification_settings')->get()->row();
       }

  }
  