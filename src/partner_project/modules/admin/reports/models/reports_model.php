<?php

  class Reports_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_todays_deliveries($org_id) {

            if ($org_id != NULL) {
                 $this->db->where('C.org_id', $org_id);
            }
            $this->db->select('COUNT(C.consignment_id) as deliveries', FALSE)
                    ->from('consignments as C')
                    ->where('C.consignment_status_id ', CONFIRMATION_STATUS)
                    ->where('C.is_deleted', 0)
                    ->where('DATE(C.created_date) = DATE(CURDATE())')
                    ->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->deliveries;
       }

       public function get_failed_deliveries($org_id) {

            if ($org_id != NULL) {
                 $this->db->where('C.org_id', $org_id);
            }
            $this->db->select('COUNT(C.consignment_id) as deliveries', FALSE)
                    ->from('consignments as C')
                    ->where('C.consignment_status_id', FAILED_DELIVERY_STATUS)
                    ->where('C.is_deleted', 0)
                    ->where('DATE(C.created_date) = DATE(CURDATE())')
                    ->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->deliveries;
       }

       public function get_todays_spendings($org_id) {

            if ($org_id != NULL) {
                 $this->db->where('C.org_id', $org_id);
            }
            $this->db->select('SUM(price) as spendings', FALSE)
                    ->from('consignments as C')
                    ->where('C.consignment_status_id', CONFIRMATION_STATUS)
                    ->where('C.is_deleted', 0)
                    ->where('DATE(C.created_date) = DATE(CURDATE())')
                    ->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->spendings;
       }

  }
  