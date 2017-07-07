<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Services_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       function add_new_service($data) {
            if ($this->db->insert('courier_service', $data)) {
                 $id = $this->db->insert_id();
                 return $id;
            }
            return 0;
       }

       function update_services($service_id, $data) {
            $this->db->where('id', $service_id);
            $res = $this->db->update('courier_service', $data);
            return $res;
       }

       function delete_service($service_id) {
            $this->db->where('id', $service_id);
            $res = $this->db->update('courier_service', array(
                "status" => 2
            ));
            return $res;
       }

       function approve_service($service_id, $org_id) {
            $this->db->where('id', $service_id);
            $this->db->where('org_id', $org_id);
            $res = $this->db->update('courier_service', array(
                "org_status" => 2
            ));
            return $res;
       }

       function reject_service($service_id, $org_id) {
            $this->db->where('id', $service_id);
            $this->db->where('org_id', $org_id);
            $res = $this->db->update('courier_service', array(
                "org_status" => 3
            ));
            return $res;
       }

       function delete_group($where) {
            $this->db->where($where);
            $res = $this->db->delete('service_groups');
            return $res;
       }

       function delete_member($where) {
            $this->db->where($where);
            $res = $this->db->delete('service_members');
            return $res;
       }

       function activate_service($where) {
            $this->db->where($where);
            $res = $this->db->update('courier_service', array(
                "status" => 1
            ));
            return $res;
       }

       function activate_group($where) {
            $this->db->where($where);
            $res = $this->db->update('service_groups', array(
                "status" => 1
            ));
            return $res;
       }

       function activate_member($where) {
            $this->db->where($where);
            $res = $this->db->update('service_members', array(
                "status" => 1
            ));
            return $res;
       }

       function suspend_service($where) {
            $this->db->where($where);
            $res = $this->db->update('courier_service', array(
                "status" => 2
            ));
            return $res;
       }

       function suspend_group($where) {
            $this->db->where($where);
            $res = $this->db->update('service_groups', array(
                "status" => 2
            ));
            return $res;
       }

       function suspend_member($where) {
            $this->db->where($where);
            $res = $this->db->update('service_members', array(
                "status" => 2
            ));
            return $res;
       }

       function not_exist_service_id($uid) {
            $query = $this->db->select('id')
                    ->where('service_id', $uid)
                    ->from('courier_service')
                    ->get();
            if ($query->num_rows() === 1) {
                 return TRUE;
            }
            return FALSE;
       }

       public function getCourier_with_service($service_id) {
            return $this->db->select("C.email,CS.display_name")
                            ->from("courier_service as CS")
                            ->join("couriers as C", "C.courier_id=CS.courier_id")
                            ->where("CS.id", $service_id)
                            ->get()
                            ->row();
       }

       function getmemberslist_count($service, $org_id, $search) {
            $this->db->select('service_members.member_id');
            $this->db->from('service_members');
            $this->db->join('member', 'member.id=service_members.member_id');
            $this->db->join('member_details', 'member.id=member_details.user_id');
            $this->db->where('service_members.org_id', $org_id);
            $this->db->where('service_members.service_id', $service);
            if (@$search != '') {
                 $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getmemberslist_by_orgid($service, $org_id, $perpage, $search, $start) {
            $this->db->select('member.id as id,member.email as Email,member_details.fullname as FullName,service_members.org_id,service_members.status as Status');
            $this->db->from('service_members');
            $this->db->join('member', 'member.id=service_members.member_id');
            $this->db->join('member_details', 'member.id=member_details.user_id');
            $this->db->where('service_members.org_id', $org_id);
            $this->db->where('service_members.service_id', $service);
            $this->db->limit($perpage, $start);

            if (@$search != '') {
                 $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\' OR  member.username LIKE \'%' . @$search . '%\')');
            }
            return $this->db->get()->result();
       }

       function getgroupslist_count($service, $org_id, $search) {
            $this->db->select('service_groups.group_id');
            $this->db->from('service_groups');
            $this->db->join('groups', 'groups.id=service_groups.group_id');
            $this->db->where('service_groups.org_id', $org_id);
            $this->db->where('service_groups.service_id', $service);
            if (@$search != '') {
                 $this->db->where('(groups.name LIKE \'%' . @$search . '%\' OR  groups.description LIKE \'%' . @$search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getgroupslist_by_orgid($service, $org_id, $perpage, $search, $start) {
            $this->db->select('service_groups.group_id as group_id,groups.name as name,groups.code as code,service_groups.status as Status');
            $this->db->from('service_groups');
            $this->db->join('groups', 'groups.id=service_groups.group_id');
            $this->db->where('service_groups.org_id', $org_id);
            $this->db->where('service_groups.service_id', $service);
            $this->db->limit($perpage, $start);

            if (@$search != '') {
                 $this->db->where('(groups.name LIKE \'%' . @$search . '%\' OR  groups.description LIKE \'%' . @$search . '%\')');
            }
            return $this->db->get()->result();
       }

       function getserviceslist_count($org_id, $search) {
            $this->db->select('CS.id');
            $this->db->from('courier_service as CS');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id', 'left');
            $this->db->where('CS.org_id', $org_id);
            $this->db->where('CS.status', 1);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getserviceslist_by_orgid($org_id, $perpage, $search, $start) {
            $this->db->select('CS.id,CS.display_name, CS.description,CS.threshold_price, CS.service_id,CS.status,' . 'CS.org_status,C.courier_id,C.company_name as courier_name');
            $this->db->from('courier_service as CS');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id', 'left');
            $this->db->where('CS.org_id', $org_id);
            $this->db->where('CS.status', 1);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function getallserviceslist($str) {
            return $this->db->select('courier_service.id,courier_service.display_name,courier_service.description, courier_service.service_id,courier_service.status')
                            ->from('courier_service')
                            ->or_like('courier_service.display_name', $str)
                            ->get()
                            ->result();
       }

       function getallservices($str) {
            $result = array();
            $query = $this->db->select('courier_service.id,courier_service.display_name,courier_service.description, courier_service.service_id')
                    ->from('courier_service')
                    ->or_like('courier_service.display_name', $str)
                    ->get()
                    ->result_array();
            foreach ($query as $value) {
                 $result[] = $value['name'] . "(" . $value['service_id'] . ")";
            }
            return $result;
       }

       function get_price_by_serviceid($service_id) {
            $this->db->select('CS.price');
            $this->db->where('CS.id', $service_id);
            $this->db->from('courier_service as CS');
            $row = $this->db->get()->row();
            if ($row)
                 return $row->price;
            return 0;
       }

       function get_detail_by_serviceid($service_id, $org_id) {
            $this->db->select('CS.id,CS.display_name,CS.description,CS.threshold_price, CS.service_id,CS.org_id,' . 'CS.org_status,C.courier_id,C.company_name as courier_name,CS.limit_use, ' . "TIME_FORMAT(CS.start_time, '%h:%i %p' ) as start_time," . " TIME_FORMAT( CS.end_time, '%h:%i %p' ) as end_time, " . "CS.week_0, CS.week_1, CS.week_2, CS.week_3, CS.week_4, CS.week_5, CS.week_6," . 'CS.is_for_bidding,CS.status,CS.origin,CS.destination,' . 'CS.is_public,CS.auto_approve', FALSE);
            $this->db->from('courier_service as CS');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id', 'left');
            $this->db->where('CS.org_id', $org_id);
            $this->db->where('CS.id', $service_id);
            return $this->db->get()->row();
       }

       function get_all_service_org_id($org_id) {
            $this->db->select("id as service_id,display_name as service_name");
            $this->db->from('courier_service');
            $this->db->where('courier_service.status', 1);
            $this->db->where('org_id', $org_id);
            return $this->db->get()->result();
       }

       function get_all_service_group($org_id, $group_id) {
            if ($group_id != NULL) {
                 $this->db->where("SG.group_id", $group_id);
            }
            $this->db->select('SG.service_id as service_id,CS.display_name as service_name')
                    ->from("service_groups as SG")
                    ->where("SG.org_id", $org_id)
                    ->where('SG.status', 1)
                    ->join('courier_service as CS', 'CS.id=SG.service_id');
            return $this->db->get()->result();
       }

       function addMember($data) {
            return $this->db->insert('service_members', $data);
       }

       function addGroup($data) {
            return $this->db->insert('service_groups', $data);
       }

       // function to verify new member id not added before
       function is_member_exist($orgid, $user_id, $service_id) {
            $query = $this->db->from('service_members')
                    ->where(array(
                        'org_id' => $orgid,
                        "member_id" => $user_id,
                        'service_id' => $service_id
                    ))
                    ->get();
            if ($query->num_rows() == 1)
                 return TRUE;
            return FALSE;
       }

       // function to verify new member id not added before
       function is_group_exist($orgid, $group_id, $service_id) {
            $query = $this->db->from('service_groups')
                    ->where(array(
                        'org_id' => $orgid,
                        "group_id" => $group_id,
                        'service_id' => $service_id
                    ))
                    ->get();
            if ($query->num_rows() == 1)
                 return TRUE;
            return FALSE;
       }

       public function get_services_all($user) {
            $sql = "SELECT CS.id as service_id,CS.display_name as service_name,CS.description as description,CS.is_public," . "CS.org_id,C.courier_id,C.company_name as courier_name,C.logo " . "FROM courier_service as CS,couriers as C " . "WHERE CS.courier_id=C.courier_id  AND CS.status=1";
            return $this->db->query($sql)->result();
       }

       public function get_services_assigned($user, $org_id = -1, $where, $status, $type, $consignment_type = -1) {
            if (isset($consignment_type) && $consignment_type != -1) {
                 $this->db->where('SP.type', $consignment_type);
            }
            if ($status && $type) {
                 $this->db->where('(CS.is_public=1 OR (CS.limit_use=0 AND CS.org_id=' . $org_id . ' AND CS.org_status=2) OR (CS.org_id=' . $org_id . ' AND CS.org_status=2 AND (SM.member_id IS NOT NULL OR SG.group_id IS NOT NULL)))');
            } else {
                 $this->db->where('((CS.limit_use=0 AND CS.org_id=' . $org_id . ' AND CS.org_status=2) OR (CS.org_id=' . $org_id . ' AND CS.org_status=2) AND (SM.member_id IS NOT NULL OR SG.group_id IS NOT NULL))');
            }
            return $this->db->select('CASE WHEN CS.org_id=0 THEN 2 ELSE 1 END AS priority,CS.id as service_id,CS.display_name as service_name,CS.payment_terms,'
                                    . 'CS.description as description,CS.is_public,CS.auto_approve,CS.org_id,'
                                    . 'C.courier_id,C.company_name as courier_name,C.logo,CS.threshold_price,CS.time_to_deliver,'
                                    . 'C.compliance_id,CR.display_name as rating,CR.label_class,'
                                    . 'SP.price,SP.max_volume,SP.volume_cost,SP.max_weight,SP.weight_cost', FALSE)
                            ->from('courier_service as CS')
                            ->join('couriers as C', 'C.courier_id=CS.courier_id', 'left')
                            ->join('service_parcel_price as SP', 'CS.id = SP.service_id', 'left')
                            ->join('service_members as SM', 'SM.service_id=CS.id AND SM.member_id=' . $user, 'left')
                            ->join('service_groups AS SG', 'SG.service_id=CS.id AND SG.group_id IN (select group_id from group_members WHERE user_id=' . $user . ')', 'left')
                            ->join('compliance_ratings as CR', 'CR.id=C.compliance_id', 'left')
                            ->where($where)
                            ->where('CS.status', 1)
                            ->where('CS.is_archived', 0)
                            ->order_by('CS.org_id', 'desc')
                            ->order_by('SP.price', 'asc')
                            ->order_by('SP.volume_cost', 'asc')
                            ->order_by('SP.weight_cost', 'asc')
                            ->get()
                            ->result();
       }

       public function service_count($search, $type) {
            $user_id = $this->session->userdata('partner_user_id');
            if (strcmp($type, "all") != 0) {
                 if (strcmp($type, "pre") == 0)
                      $this->db->where('CS.org_id  IN (select org_id from organization_members where user_id=' . $user_id . ')');
                 else
                      $this->db->where("CS.id in (SELECT service_id FROM service_parcel_price SP WHERE SP.type = '" . str_replace("'", "", $type) . "')");
            } else {
                 $this->db->where('(CS.org_id  IN (select org_id from organization_members where user_id=' . $user_id . ') OR CS.is_public=1 )');
            }
            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('CS.id')
                            ->from('courier_service as CS')
                            ->join('couriers as C', 'C.courier_id=CS.courier_id')
                            ->where('CS.status', 1)
                            ->where('CS.is_archived', 0)
                            ->get()
                            ->num_rows();
       }

       public function services($perpage, $search, $start, $org_id, $type, $sort = '', $sort_direction = '') {
            $sort_array = array(
                'service' => 'CS.display_name',
                'description' => 'CS.description',
                'org' => 'pre_approved',
                'courier' => 'C.company_name',
                'cutoff' => 'CS.start_time'
            );
            if (array_key_exists($sort, $sort_array))
                 $this->db->order_by($sort_array[$sort], $sort_direction);
            if (strcmp($sort, 'org') == 0)
                 $this->db->order_by("O.name");

            $user_id = $this->session->userdata('partner_user_id');
            $result = $this->get_service_query($user_id, $org_id)->result();
            $service_ids = '';
            foreach ($result as $row) {
                 if ($service_ids != '')
                      $service_ids .= ',';
                 $service_ids .= $row->id;
            }
            if (strlen($service_ids) == 0)
                 $service_ids = '-1';
            $where = '(CS.id in (' . $service_ids . ') OR CS.is_public = 1)';
            if ($type != 'all') {
                 if ($type != 'pre') {
                      $this->db->join("service_parcel_price SP", "CS.id=SP.service_id");
                      $this->db->where("SP.type", $type);
                      $this->db->order_by('SP.price');
                 }
            }
            $this->db->where($where);

            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('CS.id,CS.courier_id,CS.display_name as service,CS.description,' . 'CS.is_public, O.id as org_id, O.name as org, O.name, IF(O.name IS NULL, 0, 1) pre_approved, ' . "TIME_FORMAT(CS.start_time, '%h:%i %p' ) as start_time," . " TIME_FORMAT( CS.end_time, '%h:%i %p' ) as end_time," . " CS.week_0, CS.week_1, CS.week_2, CS.week_3, CS.week_4, CS.week_5, CS.week_6," . 'CS.service_id,C.company_name as courier,RCS.status as request_status', FALSE)
                            ->from('courier_service as CS')
                            ->join('couriers as C', 'C.courier_id=CS.courier_id')
                            ->join('(SELECT id, name FROM organizations WHERE use_public_service=1) as O', 'O.id=CS.org_id', 'left')
                            ->join('request_courier_service as RCS', 'RCS.service_id=CS.id AND RCS.org_id=' . $org_id, 'left')
                            ->where('CS.status', 1)
                            ->where('CS.is_archived', 0)
                            ->limit($perpage, $start)
                            ->get()
                            ->result();
       }

       public function get_courier($courier_id) {
            return $this->db->select('C.company_name as name,C.description,C.logo,'
                                    . 'C.phone,C.reg_address,C.rating,CR.display_name as compliance_rating,'
                                    . 'CR.label_class')
                            ->from('couriers as C')
                            ->join('compliance_ratings as CR', 'CR.id=C.compliance_id', 'left')
                            ->where('C.courier_id', $courier_id)
                            ->get()
                            ->row();
       }

       public function get_reviews($courier_id) {
            return $this->db->select('CR.review,CR.user_id,U.username,CR.score')
                            ->from('courier_rating as CR')
                            ->join('member as U', 'U.id=CR.user_id')
                            ->where('CR.courier_id', $courier_id)
                            ->order_by('timestamp', 'desc')
                            ->limit(5)
                            ->get()
                            ->result();
       }

       public function get_deliveries_count_by_status($courier_id, $from_date) {
            if ($from_date != NULL) {
                 $this->db->where("C.created_date >= '" . $from_date . "'");
            }
            return $this->db->select('COUNT(C.consignment_id) as deliveries,C.consignment_status_id as status', FALSE)
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'CS.id=C.service_id AND CS.courier_id=' . $courier_id)
                            ->group_by('C.consignment_status_id')
                            ->get()
                            ->result();
       }

       public function can_use_this_service($user, $org_id, $service_id) {
            return $this->get_service_query($user, $org_id, $service_id)->num_rows();
       }

       private function get_service_query($user, $org_id = -1, $service_id = -1) {
            $where_org = ($org_id > 0 ? "AND CS.org_id = $org_id " : " ");
            $where_service = ($service_id > 0 ? "AND CS.id = $service_id " : " ");
            $sql = "SELECT DISTINCT CS.id FROM courier_service as CS, service_members SM "
                    . "WHERE CS.id=SM.service_id "
                    . "AND SM.member_id=$user "
                    . "AND CS.status=1 AND CS.org_status=2 "
                    . "AND SM.member_id=$user "
                    . $where_org . $where_service
                    . "UNION "
                    . "SELECT DISTINCT CS.id FROM courier_service as CS,group_members as GM, organization_members as OM, service_groups SG "
                    . "WHERE CS.id=SG.service_id "
                    . "AND OM.org_id=SG.org_id "
                    . "AND GM.group_id=SG.group_id "
                    . "AND CS.status=1 AND CS.org_status=2 "
                    . "AND GM.user_id=$user "
                    . $where_org . $where_service
                    . "UNION "
                    . "SELECT DISTINCT CS.id FROM courier_service as CS, organizations O, organization_members OM "
                    . "WHERE CS.status=1 AND CS.org_status=2 "
                    . "AND O.id=OM.org_id AND OM.user_id=$user "
                    . "AND CS.org_id = O.id "
                    . "AND O.use_public_service=1 "
                    . $where_org . $where_service;
            return $this->db->query($sql);
       }

       public function get_services() {
            return $this->db->select('CS.id as service_id')
                            ->from('courier_service as CS')
                            ->get()
                            ->result();
       }

       public function get_deliveries($id) {
            return $this->db->select('C.consignment_id,J.created_date as collection_time,S.created_date as delivery_time,', FALSE)
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'C.service_id=CS.id')
                            ->join('jobstates as J', "J.job_id=C.consignment_id AND J.status_code=" . C_COLLECTING)
                            ->join('jobstates as S', "S.job_id=C.consignment_id AND S.status_code=" . C_DELIVERED)
                            ->where('CS.id', $id)
                            ->where('C.created_date > ', date("Y-m-d H:i:s", strtotime("-3 month", now())))
                            ->get()
                            ->result();
       }

  }
  