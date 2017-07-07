<?php

  class User_reports_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_total_deliveries($user, $start_date, $end_date, $org_id) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org_id != NULL) {
                 $this->db->where('C.org_id', $org_id);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('COUNT(C.consignment_id) as deliveries', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->deliveries;
       }

       public function get_active_day($user, $start_date, $end_date, $org_id) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org_id != NULL) {
                 $this->db->where('C.org_id', $org_id);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('DATE_FORMAT(C.created_date,"%d %M %y") as date_label,COUNT(C.consignment_id) as deliveries', FALSE)
                    ->from('consignments as C')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label')
                    ->order_by('deliveries', 'DESC');
            return $this->db->get()->row();
       }

       public function user_performance_month_info($user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('MONTHNAME(created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function user_performance_week_info($user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('WEEK(created_date,1) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function user_performance_day_info($user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('DATE_FORMAT(C.created_date,"%d/%m/%Y") as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function user_performance_hour_info($user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('HOUR(C.created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function user_performance_service($service_id, $user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('COUNT(C.consignment_id) as deliveries,C.service_id,CS.display_name', FALSE)
                    ->from('consignments as C')
                    ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.service_id', $service_id)
                    ->group_by('C.service_id');
            return $this->db->get()->row();
       }

       public function user_performance_day_trend_per_service($service_id, $user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('HOUR(C.created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.service_id,CS.display_name', FALSE)
                    ->from('consignments as C')
                    ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.service_id', $service_id)
                    ->group_by('date_label,C.service_id');
            return $this->db->get()->result();
       }

       public function user_performance_week_trend_per_service($service_id, $user = NULL, $start_date = "", $end_date = "", $org = NULL) {
            if ($user != NULL) {
                 $this->db->where('C.created_user_id', $user);
            }
            if ($org != NULL) {
                 $this->db->where('C.org_id', $org);
            }
            if ($start_date != "" && $end_date != "") {
                 $this->db->where("(created_date >= '" . $start_date . "' AND created_date <= '" . $end_date . "')");
            } else if ($start_date != "" && $end_date == "") {
                 $this->db->where("(created_date >= '" . $start_date . "')");
            } else if ($end_date != "" && $start_date == "") {
                 $this->db->where("(created_date <= '" . $end_date . "')");
            }
            $this->db->select('WEEKDAY(C.created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.service_id,CS.display_name', FALSE)
                    ->from('consignments as C')
                    ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.service_id', $service_id)
                    ->group_by('date_label,C.service_id');
            return $this->db->get()->result();
       }

  }
  