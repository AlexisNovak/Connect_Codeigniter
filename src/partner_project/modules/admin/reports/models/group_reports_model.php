<?php

  class Group_reports_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_total_deliveries($group, $start_date, $end_date, $org_id) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
                    ->where('C.is_deleted', 0)->where('C.is_service_assigned', 1);
            return $this->db->get()->row()->deliveries;
       }

       public function get_active_day($group, $start_date, $end_date, $org_id) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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

       public function group_performance_month_info($group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('MONTHNAME(created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function group_performance_week_info($group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('WEEK(created_date,1) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function group_performance_day_info($group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('DATE_FORMAT(C.created_date,"%d/%m/%Y") as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function group_performance_hour_info($group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('HOUR(C.created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.consignment_status_id', FALSE)
                    ->from('consignments as C')
                    ->where('(C.consignment_status_id =' . SUCCESS_DELIVERY_STATUS . ' OR C.consignment_status_id=' . FAILED_DELIVERY_STATUS . ')')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->group_by('date_label,C.consignment_status_id');
            return $this->db->get()->result();
       }

       public function group_performance_service($service_id, $group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('COUNT(C.consignment_id) as deliveries,C.service_id,CS.display_name', FALSE)
                    ->from('consignments as C')
                    ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.service_id', $service_id)
                    ->group_by('C.service_id');
            return $this->db->get()->row();
       }
   public function group_performance_user($user_id, $group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('COUNT(C.consignment_id) as deliveries,C.created_user_id,U.username', FALSE)
                    ->from('consignments as C')
                    ->join('member as U', 'U.id=C.created_user_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.created_user_id', $user_id)
                    ->group_by('C.created_user_id');
            return $this->db->get()->row();
       }
       public function group_performance_day_trend_per_service($service_id, $group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
            $this->db->select('HOUR(C.created_date) as date_label, COUNT(C.consignment_id) as deliveries,C.service_id,CS.display_name', FALSE)
                    ->from('consignments as C')
                    ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                    ->where('C.is_deleted', 0)
                    ->where('C.is_service_assigned', 1)
                    ->where('C.service_id', $service_id)
                    ->group_by('date_label,C.service_id');
            return $this->db->get()->result();
       }

       public function group_performance_week_trend_per_service($service_id, $group = NULL, $start_date = "", $end_date = "", $org_id = NULL) {
            if ($group != NULL) {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.group_id=$group AND GM.org_id=$org_id )");
            } else {
                 $this->db->where("C.created_user_id IN (SELECT GM.user_id FROM group_members AS GM WHERE GM.org_id=$org_id )");
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
  