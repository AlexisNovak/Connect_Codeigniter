<?php

  class Service_request_log_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_activity($data) {

            if ($this->db->insert('service_request_log', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function getloglist_count($request_id, $search) {
            $this->db->select('id')->from('service_request_log')
                    ->where('request_id', $request_id);
            return $this->db->get()->num_rows();
       }

       public function getloglist_by_reqid($request_id, $perpage, $search, $start) {
            $this->db->select('id, request_id, activity, DATE_FORMAT(time,"%d %b %Y %h:%i %p")as time', FALSE);
            $this->db->from('service_request_log');
            $this->db->where("request_id", $request_id);
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

  }
  