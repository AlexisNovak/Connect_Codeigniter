<?php

  class Partner_user_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function is_partner_user() {
            $user_id = $this->session->userdata('user_id');
            return $this->db->from('partner')->where('partner_user', $user_id)->get()->row();
       }

       public function getuserslist_count($partner_id, $search) {
            if ($search != NULL) {
                 $this->db->where("(M.username LIKE '%" . $search . "%' OR M.email LIKE '%" . $search . "%')");
            }
            return $this->db->select('M.id')
                            ->from('member as M')
                            ->join('member_details as MD', 'MD.user_id=M.id')
                            ->join('partner_members as PM', 'PM.member_id=M.id')
                            ->join('country as C', 'C.code=MD.country', 'left')
                            ->where('PM.partner_id', $partner_id)
                            ->get()
                            ->num_rows();
       }

       public function getuserslist($partner_id, $search, $perpage, $start) {
            if ($search != NULL) {
                 $this->db->where("(M.username LIKE '%" . $search . "%' OR M.email LIKE '%" . $search . "%')");
            }
            return $this->db->select('M.email,MD.fullname,MD.description,MD.phone_no as phone,MD.fax_no as fax,C.country as country')
                            ->from('member as M')
                            ->join('member_details as MD', 'MD.user_id=M.id')
                            ->join('partner_members as PM', 'PM.member_id=M.id')
                            ->join('country as C', 'C.code=MD.country', 'left')
                            ->where('PM.partner_id', $partner_id)
                            ->limit($perpage, $start)
                            ->get()
                            ->result();
       }

       public function getorderslist_count($partner_id, $search, $status, $exclude_status) {

            $status_id = (int) $status;
            $this->db->select('C.consignment_id');
            $this->db->from('consignments as C');
            if (!empty($exclude_status)) {
                 $this->db->where_not_in('C.consignment_status_id', $exclude_status);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE \'%' . $search . '%\' OR C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\' OR C.tags LIKE \'%' . $search . '%\' OR C.ref LIKE \'%' . $search . '%\')');
            }
            if ($status_id) {
                 $this->db->where('C.consignment_status_id', $status_id);
            }
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('member as U', 'U.id=C.created_user_id', 'left');
            $this->db->join('partner_members as PM', 'PM.member_id=U.id', 'left');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('courier_service as S', 'S.id=C.service_id', 'left');
            $this->db->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where('(PM.partner_id=' . $partner_id . ' OR O.id IN (SELECT id from organizations WHERE user_id IN(SELECT member_id from partner_members where partner_id=' . $partner_id . ') ))');
            $this->db->order_by('C.collection_date', 'DESC');
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getorderslist_by_partnerid($partner_id, $perpage, $search, $start, $status, $exclude_status) {
            $status_id = (int) $status;
            $this->db->select('C.consignment_id,C.public_id,C.private_id,O.name as org_name,U.email as username,C.is_confirmed,C.is_for_bidding,'
                    . 'S.display_name as service,C.consignment_status_id,C.collection_address,'
                    . 'F.country as from_country,T.country as to_country,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,C.is_c_restricted_area as crestrict,'
                    . 'C.delivery_contact_name,C.delivery_contact_phone,'
                    . 'C.is_d_restricted_area as drestrict,CS.display_name as status,'
                    . 'Z.courier_id,Z.company_name as courier_name,PM.partner_id');
            $this->db->from('consignments as C');
            if (!empty($exclude_status)) {
                 $this->db->where_not_in('C.consignment_status_id', $exclude_status);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE \'%' . $search . '%\' OR C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\' OR C.tags LIKE \'%' . $search . '%\' OR C.ref LIKE \'%' . $search . '%\')');
            }
            if ($status_id) {
                 $this->db->where('C.consignment_status_id', $status_id);
            }
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('member as U', 'U.id=C.created_user_id');
            $this->db->join('partner_members as PM', 'PM.member_id=U.id', 'left');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('courier_service as S', 'S.id=C.service_id', 'left');
            $this->db->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where('(PM.partner_id=' . $partner_id . ' OR O.id IN (SELECT id from organizations WHERE user_id IN(SELECT member_id from partner_members where partner_id=' . $partner_id . ') ))');
            $this->db->order_by('C.collection_date', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function get_orders($start_date, $end_date, $partner_id, $status) {
            if ($start_date != "") {
                 $cdate = date('Y-m-d H:m:s', strtotime($start_date));
                 $this->db->where("C.created_date >= '$cdate'");
            }
            if ($end_date != "") {
                 $todate = date('Y-m-d H:m:s', strtotime($end_date));
                 $this->db->where("C.created_date <= '$todate'");
            }
            return $this->db->select('C.*,consignment_type.display_name,F.country as from_country,T.country as to_country,'
                                    . 'ZF.offset as from_zone,ZT.offset as to_zone,CS.display_name as consignment_status,'
                                    . 'S.display_name as service,Z.courier_id,Z.company_name as courier_name,U.username as fullname,U.email,O.id as org_id,O.name as org_name')
                            ->from('consignments as C')
                            ->join('organizations as O', 'O.id=C.org_id', 'left')
                            ->join('member as U', 'U.id=C.created_user_id', 'left')
                            ->join('partner_members as PM', 'PM.member_id=U.id', 'left')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->where('(PM.partner_id=' . $partner_id . ' OR O.id IN (SELECT id from organizations WHERE user_id IN(SELECT member_id from partner_members where partner_id=' . $partner_id . ') ))')
                            ->where_in('C.consignment_status_id', $status)
                            ->get()->result();
       }

  }
  