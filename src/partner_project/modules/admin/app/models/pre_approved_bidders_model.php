<?php

  class Pre_approved_bidders_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_bidders_list_count($org_id, $search) {
            $this->db->select('PB.id');
            $this->db->from('pre_approved_bidders as PB');
            $this->db->join('couriers as C', 'C.courier_id=PB.courier_id');
            if ($search != NULL) {
                 $this->db->where('(C.company_name LIKE \'%' . $search . '%\' )');
            }
            $this->db->where('PB.org_id', $org_id);
            $this->db->where('PB.status <> 0');
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function get_bidders_list($org_id, $perpage, $search, $start) {
            $this->db->select('C.courier_id,C.company_name as courier,C.email,C.url,C.description,PB.status');
            $this->db->join('couriers as C', 'C.courier_id=PB.courier_id');
            $this->db->from('pre_approved_bidders as PB');
            if ($search != NULL) {
                 $this->db->where('(C.company_name LIKE \'%' . $search . '%\' )');
            }
            $this->db->where('PB.org_id', $org_id);
            $this->db->where('PB.status <> 0');
            $this->db->limit($perpage, $start);
            $query = $this->db->get();
            return $query->result();
       }

       public function update_open_bid($org_id, $open_bid) {
            return $this->db->where('id', $org_id)->update('organizations', array('open_bid' => $open_bid));
       }

       public function get_open_bid_status($org_id) {
            $row = $this->db->select('open_bid')->from('organizations')->where('id', $org_id)->get()->row();
            if ($row) {
                 return $row->open_bid;
            }
            return 0;
       }

       public function non_approve_couriers($org_id, $search) {
            $this->db->select('C.courier_id, C.company_name ,C.email');
            $this->db->from('couriers as C');
            if ($search != NULL) {
                 $this->db->where('(C.company_name LIKE \'%' . $search . '%\' OR C.email LIKE \'%' . $search . '%\' )');
            }
            $this->db->where('C.courier_id NOT IN (SELECT courier_id FROM pre_approved_bidders AS PB WHERE PB.org_id=' . $org_id . ')');
            $this->db->limit(30);
            return $this->db->get()->result();
       }

       public function add_courier($data) {
            if ($this->db->insert('pre_approved_bidders', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

  }
  