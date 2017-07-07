<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of consignment_attachment_model
   *
   * @author nice
   */
  class Consignment_attachment_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_attachments($data, $bid_id = 0, $group = FALSE) {
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
                 if ($this->db->insert('consignment_attachments', $data)) {
                      return $this->db->insert_id();
                 }
                 return 0;
            }
       }

       public function getlist_count($order_id, $search) {
            $this->db->select('id')->from('consignment_attachments')
                    ->where('order_id', $order_id);
            return $this->db->get()->num_rows();
       }

       public function getlist_by_orderid($order_id, $perpage, $search, $start) {
            $this->db->select('*, (UNIX_TIMESTAMP(timestamp)*1000) as time', FALSE);
//            $this->db->select('id, order_id, activity, DATE_FORMAT(time,"%d %b %Y %h:%i %p")as time', FALSE);
            $this->db->from('consignment_attachments');
            $this->db->where("order_id", $order_id);
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }
       
       public function attachmentCountsByOrderId($order_id){
           $this->db->select('id')->from('consignment_attachments')
                   ->where('order_id', $order_id);
           return $this->db->get()->num_rows();
       }

  }
  