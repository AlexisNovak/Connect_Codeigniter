<?php

  class Consignment_activity_log_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_activity($data, $bid_id = 0, $group = FALSE) {
            if ($group) {
                 $c_group = $this->db->query("select consignment_id from bid_consignment_relation where bid_id=" . $bid_id)->result();

                 if ($c_group) {
                      $activity = array();
                      foreach ($c_group as $c) {
                           $activity[] = array("order_id" => $c->consignment_id, "activity" => "Accepted Bid", "category" => 2);
                      }
                      $this->db->insert_batch('consignment_activity_log', $activity);
                 }
                 return;
            } else {
                 $data['category'] = 1;
                 if ($this->db->insert('consignment_activity_log', $data)) {
                      return $this->db->insert_id();
                 }
                 return 0;
            }
       }

       public function getloglist_count($order_id, $search) {
            $this->db->select('id')->from('consignment_activity_log')
                    ->where('order_id', $order_id);
            return $this->db->get()->num_rows();
       }

       public function getloglist_by_orderid($order_id, $perpage, $search, $start) {
            $this->db->select('id, order_id, activity, DATE_FORMAT(time,"%d %b %Y %h:%i %p")as time', FALSE);
            $this->db->from('consignment_activity_log');
            $this->db->where("order_id", $order_id);
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function getloglist_count_for_courier($order_id, $search) {
            $this->db->select('id', FALSE);
            $this->db->from('consignment_activity_log');
            $this->db->where("order_id", $order_id);
            $this->db->where("category", 1);
            return $this->db->get()->num_rows();
       }

       public function getloglist_by_orderid_for_courier($order_id, $perpage, $search, $start) {
            $this->db->select('id, order_id, activity, DATE_FORMAT(time,"%d %b %Y %h:%i %p")as time', FALSE);
            $this->db->from('consignment_activity_log');
            $this->db->where("order_id", $order_id);
            $this->db->where("category", 1);
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

  }
  