<?php

  class Consignment_bids_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function addbid($data) {
            $data['updated_date'] = mdate('%Y-%m-%d %H:%i:%s', now());
            if ($this->db->insert('bids', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function add_bid_consignment_relation($bid_id, $job_id, $courier_name) {
            $c_group = $this->db->query("select consignment_id from consignments where c_group_id=(select c_group_id from consignments where consignment_id=$job_id)")->result();

            if ($c_group) {
                 $batch = array();
                 $activity = array();
                 foreach ($c_group as $c) {
                      $batch[] = array('bid_id' => $bid_id, "consignment_id" => $c->consignment_id);
                      $activity[] = array("order_id" => $c->consignment_id, "activity" => "New bid from $courier_name");
                 }
                 $this->db->insert_batch('bid_consignment_relation', $batch);
                 $this->db->insert_batch('consignment_activity_log', $activity);
            }
       }

       public function withdrawbid($bid_id, $courier_id, $courier_name) {
            if ($this->db->where(array("bid_id" => $bid_id, "courier_id" => $courier_id))
                            ->update("bids", array("status" => 2, 'updated_date' => mdate('%Y-%m-%d %H:%i:%s', now())))) {

                 $c_group = $this->db->query("select consignment_id from bid_consignment_relation where bid_id=$bid_id")->result();

                 if ($c_group) {
                      $activity = array();
                      foreach ($c_group as $c) {
                           $activity[] = array("order_id" => $c->consignment_id, "activity" => "Bid withdrawn by $courier_name");
                      }
                      $this->db->insert_batch('consignment_activity_log', $activity);
                 }
                 return TRUE;
            }
            return FALSE;
       }

       public function getpricehistory($job_id, $courier_id) {
            return $this->db->select("bidding_price as price,remarks,is_approved,bidding_date as created_date,approved_date")
                            ->from('bids as B')
                            ->join('bid_consignment_relation as BC', 'BC.bid_id=B.bid_id', 'right')
                            ->where(array("BC.consignment_id" => $job_id, "courier_id" => $courier_id))
                            ->get()
                            ->result();
       }

       public function getbidhistory1($courier_id, $company = NULL, $username = NULL, $pick_address = NULL, $delivery_address = NULL, $pick_date_from = NULL, $pick_date_to = NULL, $pick_time_from = NULL, $pick_time_to = NULL, $expired_date = NULL, $offset = 0, $limit = 20) {
            $this->db->select('C.consignment_id as id,U.username,display_name,O.name as company_name, volume, weight, collection_address,collection_country, delivery_address, delivery_post_code, delivery_country,delivery_contact_name,  delivery_contact_phone,collection_post_code, collection_contact_name, collection_contact_number, created_date');
            $this->db->from('bids as B');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=B.bid_id', 'left');
            $this->db->join('consignments as C', 'C.consignment_id=BC.consignment_id');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->where("B.courier_id", $courier_id);
            if ($company != NULL) {
                 $this->db->where("org_id", $company);
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($pick_address != NULL) {
                 $this->db->where('C.collection_address LIKE  \'%' . $pick_address . '%\'');
            }
            if ($delivery_address != NULL) {
                 $this->db->where(' C.delivery_address LIKE \'%' . $delivery_address . '%\'');
            }
            if ($pick_date_from != NULL) {
                 //$this->db->where("C.collection_date >= $pick_date_from");
            }
            if ($pick_date_to != NULL) {
                 //$this->db->where("C.collection_date_to <= $pick_date_to");
            }

            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
       }

       public function getbidhistory($courier_id, $company = NULL, $username = NULL, $pick_address = NULL, $delivery_address = NULL, $pick_date_from = NULL, $pick_date_to = NULL, $pick_time_from = NULL, $pick_time_to = NULL, $expired_date = NULL, $offset = 0, $limit = 20) {
            $this->db->select('C.consignment_id as id,U.username,O.name as company_name, B.bid_id,B.service_id,B.bidding_price,B.is_approved,B.status,CS.display_name');
            $this->db->from('bids as B');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=B.bid_id', 'left');
            $this->db->join('courier_service as CS', 'CS.id=B.service_row_id', 'left');
            $this->db->join('consignments as C', 'C.consignment_id=BC.consignment_id');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id','left');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->where("B.courier_id", $courier_id);
            $this->db->order_by("B.updated_date", 'asc');
            // $this->db->group_by("C.consignment_id");
            if ($company != NULL) {
                 $this->db->where("org_id", $company);
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($pick_address != NULL) {
                 $this->db->where('C.collection_address LIKE  \'%' . $pick_address . '%\'');
            }
            if ($delivery_address != NULL) {
                 $this->db->where(' C.delivery_address LIKE \'%' . $delivery_address . '%\'');
            }
            if ($pick_date_from != NULL) {
                 //$this->db->where("C.collection_date >= $pick_date_from");
            }
            if ($pick_date_to != NULL) {
                 //$this->db->where("C.collection_date_to <= $pick_date_to");
            }

           // $this->db->limit($limit, $offset);
            return $this->db->get()->result();
       }

       public function getwonbid($job_id) {
            return $this->db->select('*')->from('bids')
                            ->from('bids as B')
                            ->join('bid_consignment_relation as BC', 'BC.bid_id=B.bid_id', 'right')
                            ->where(array("BC.consignment_id" => $job_id))
                            ->where('B.is_approved', 1)
                            ->get()
                            ->row();
       }

  }
  