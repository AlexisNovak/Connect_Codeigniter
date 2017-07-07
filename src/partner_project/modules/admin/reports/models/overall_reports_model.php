<?php

  class Overall_reports_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_total_deliveries($start_date, $end_date, $org_id) {

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
                    ->where('C.is_deleted', 0)->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->deliveries;
       }

       public function get_active_day($start_date, $end_date, $org_id) {

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

       public function overall_performance_month_info($start_date = "", $end_date = "", $org = NULL) {

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

       public function overall_performance_week_info($start_date = "", $end_date = "", $org = NULL) {

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

       public function overall_performance_day_info($start_date = "", $end_date = "", $org = NULL) {

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

       public function overall_performance_hour_info($start_date = "", $end_date = "", $org = NULL) {

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

       public function overall_performance_service($service_id, $start_date = "", $end_date = "", $org = NULL) {
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

       public function overall_performance_group($group_id, $start_date = "", $end_date = "", $org = NULL) {
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
            $this->db->select('G.id,G.name,COUNT(C.consignment_id) as deliveries', FALSE)
                    ->from('groups as G')
                    ->join('group_members AS GM', 'GM.group_id=G.id')
                    ->join('consignments as C', "C.created_user_id=GM.user_id")
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where("G.id", $group_id)
                    ->where('G.org_id', $org);
            return $this->db->get()->row();
       }

       public function overall_performance_day_trend_per_service($service_id, $start_date = "", $end_date = "", $org = NULL) {

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

       public function overall_performance_week_trend_per_service($service_id, $start_date = "", $end_date = "", $org = NULL) {

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
  