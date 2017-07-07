<?php

  class Orders_model extends MY_Model {

       function __construct() {
            parent::__construct();
       }

       public function addOrder($data) {

            if ($this->db->insert('consignments', $data)) {
                 $id = $this->db->insert_id();
                 $this->track_status_change($data, $id);
                 return $id;
            }
            return 0;
       }

       public function getHolidays($date, $num = 0) {
            $count = $this->db->select('*')
                            ->from('holiday_setting')
                            ->where('date', date("Y-m-d", strtotime($date . "+ $num days")))
                            ->get()->num_rows();
            if ($count == 0 && !$this->isWeekend($date . "+ $num days")) {
                 return $num;
            } else {
                 return $this->getHolidays($date, $num + 1);
            }
       }

       function isWeekend($date) {
            return (date('N', strtotime($date)) >= 6);
       }

       public function viewOrder($user_id) {
            return $this->db->select('*')
                            ->from('consignments')
                            ->where('consignment_id')
                            ->where('C.is_deleted', 0)
                            ->get()->result();
       }

       public function get_order_id($public_id) {
            $rs = $this->db->select('consignment_id')->from('consignments')->where('public_id', $public_id)->get()->row();
            if ($rs)
                 return $rs->consignment_id;
            return 0;
       }

       public function get_customer_name($public_id) {
            $rs = $this->db->select('M.username, O.shortname as organisation')
                    ->from('consignments as C')
                    ->join('member as M', 'M.id = C.customer_id')
                    ->join('organization_members as OM', 'OM.user_id = M.id AND OM.org_id=C.org_id', 'left')
                    ->join('organizations as O', 'O.id = OM.org_id', 'left')
                    ->where('public_id', $public_id)
                    ->get()
                    ->row();
            if ($rs)
                 return $rs;
            return 0;
       }

       public function get_public_id($order_id) {
            $rs = $this->db->select('public_id')->from('consignments')->where('consignment_id', $order_id)->get()->row();
            if ($rs)
                 return $rs->public_id;
            return 0;
       }

       public function get_price_change($order_id) {
            $rs = $this->db->select('change_price')->from('consignments')->where('consignment_id', $order_id)->get()->row();
            if ($rs)
                 return $rs->change_price;
            return 0;
       }

       public function get_price($order_id) {
            $rs = $this->db->select('price')->from('consignments')->where('consignment_id', $order_id)->get()->row();
            if ($rs)
                 return $rs->price;
            return 0;
       }

       public function is_direct_assign($id) {
            $rs = $this->db->select('is_service_assigned')->from('consignments')->where('consignment_id', $id)->get()->row();
            if ($rs)
                 return $rs->is_service_assigned;
            return 0;
       }

       public function get_order_assign_type($order_id) {
            $rs = $this->db->select('is_for_bidding,is_third_party')->from('consignments')->where('consignment_id', $order_id)->get()->row();
            if ($rs) {
                 return $rs->is_third_party ? 3 : ($rs->is_for_bidding ? 2 : 1);
            }
            return 0;
       }

       public function get_statuslist() {
            return $this->db->select('status_id,display_name')
                            ->from('consignment_status')
                            ->get()->result();
       }

       public function get_teamlist($user_id, $flag = 0, $org_id = NULL) {
            if ($flag == 0) {
                 $this->db->select('G.id,G.name');
                 $this->db->from('groups as G');
                 $this->db->where("(G.id IN (select group_id from group_members WHERE user_id=$user_id)  OR G.id IN (select id from groups WHERE org_id IN (select org_id from organization_members WHERE user_id=$user_id AND role_id=1)) )");
            } else {
                 $this->db->select('G.id,G.name');
                 $this->db->from('groups as G');
            }
            if ($org_id !== NULL) {
                 $this->db->where('G.org_id', $org_id);
            }

            return $this->db->get()->result();
       }

       public function is_unique_publicid($public_id) {
            $query = $this->db->select("public_id")
                    ->from("consignments")
                    ->where("public_id", $public_id)
                    ->get();
            if ($query->num_rows() == 0) {
                 return TRUE;
            }
            return FALSE;
       }

       public function is_unique_privateid($private_id) {
            $query = $this->db->select("private_id")
                    ->from("consignments")
                    ->where("private_id", $private_id)
                    ->get();
            if ($query->num_rows() == 0) {
                 return TRUE;
            }
            return FALSE;
       }

       public function getorderslist_count($user_id, $search, $org, $service, $status, $team = null, $flag = 0, $excluse_status = array(), $where = NULL) {
            $service_id = (int) $service;
            $status_id = (int) $status;
            $team_id = (int) $team;
            $this->db->select('C.consignment_id');
            $this->setorderlist_by_userid($this->db, $user_id, $search, $org, $service, $status, $team, $flag, $excluse_status, $where);
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getorderslist_by_userid($user_id, $perpage, $search, $start, $org, $service, $status, $team = null, $flag = 0, $excluse_status = array(), $where = NULL) {
            $this->db->select('C.consignment_id,C.public_id,C.private_id,O.name as org_name,U.username,C.is_confirmed,C.is_for_bidding,'
                    . 'S.display_name as service,C.consignment_status_id,C.collection_address,'
                    . 'F.country as from_country,T.country as to_country,C.cancel_request,C.is_confirmed,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,C.is_c_restricted_area as crestrict,'
                    . 'C.delivery_contact_name,C.delivery_contact_phone,'
                    . 'C.is_d_restricted_area as drestrict,CS.display_name as status,'
                    . 'Z.courier_id,Z.company_name as courier_name,G.name as group_name,DATE_FORMAT(created_date,"%d-%m-%Y")as cdate, '
                    . 'C.is_third_party,TP.email as third_party_email', FALSE);
            $this->setorderlist_by_userid($this->db, $user_id, $search, $org, $service, $status, $team, $flag, $excluse_status, $where);
            $this->db->order_by('C.consignment_id', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       private function setorderlist_by_userid($db, $user_id, $search, $org, $service, $status, $team = null, $flag = 0, $excluse_status = array(), $where = NULL) {
            $service_id = (int) $service;
            $status_id = (int) $status;
            $team_id = (int) $team;
            $this->db->from('consignments as C');
            if (!empty($excluse_status)) {
                 $db->where_not_in('C.consignment_status_id', $excluse_status);
            }
            if ($where != NULL) {
                 $db->where($where);
            }
            if ($org != NULL) {
                 $db->where("C.org_id", $org);
            }
            if ($search != NULL) {
                 $db->where('(C.public_id LIKE \'%' . $search . '%\' OR C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\' OR C.tags LIKE \'%' . $search . '%\' OR C.ref LIKE \'%' . $search . '%\')');
            }
            if ($service_id) {
                 $db->where('C.service_id', $service_id);
            }
            if ($status_id) {
                 $db->where('C.consignment_status_id', $status_id);
            }
            if ($team_id) {
                 $db->where("C.created_user_id IN (select user_id from group_members where group_id= $team_id)");
            }
//            $this->db->where('C.is_for_bidding', 1);

            $db->join('organizations as O', 'O.id=C.org_id', 'left');
            $db->join('member as U', 'U.id=C.created_user_id', 'left');
            $db->join('group_members as GM', 'GM.user_id=C.created_user_id AND GM.org_id=C.org_id', 'left');
            $db->join('groups as G', 'G.id=GM.group_id', 'left');
            $db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $db->join('courier_service as S', 'S.id=C.service_id', 'left');
            $db->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left');
            $db->join('country as F', 'F.code=C.collection_country', 'left');
            $db->join('country as T', 'T.code=C.delivery_country', 'left');
            $db->join('couriers_external as TP', 'TP.job_id=C.consignment_id', 'left');
            if ($flag == 0) {
                 $db->where("(C.created_user_id=$user_id OR C.org_id IN (select org_id from organization_members WHERE user_id=$user_id AND role_id=1) )");
            }
            $db->where('C.is_deleted', 0);
       }

       public function getmorderslist_count($user_id, $search, $status, $cgroup_id, $flag) {
            $this->db->select('C.consignment_id');
            $this->setmorderlist_by_userid($this->db, $user_id, $search, $status, $cgroup_id, $flag);
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getmorderslist_by_userid($user_id, $perpage, $search, $start, $status, $cgroup_id, $flag) {
            $this->db->select('C.consignment_id,C.public_id,C.private_id,O.name as org_name,U.username,'
                    . 'S.display_name as service,C.consignment_status_id,C.collection_address,'
                    . 'F.country as from_country,T.country as to_country,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,C.is_c_restricted_area as crestrict,'
                    . 'C.delivery_contact_name,C.delivery_contact_phone,'
                    . 'C.is_d_restricted_area as drestrict,CS.display_name as status,'
                    . 'Z.courier_id,Z.company_name as courier_name,G.name as group_name');
            $this->setmorderlist_by_userid($this->db, $user_id, $search, $status, $cgroup_id, $flag);
            $this->db->order_by('C.consignment_id', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       private function setmorderlist_by_userid($db, $user_id, $search, $status, $cgroup_id, $flag) {
            $status_id = (int) $status;
            $db->from('consignments as C');

            if ($search != NULL) {
                 $db->where('(C.public_id LIKE \'%' . $search . '%\' OR C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\')');
            }

            if ($status_id) {
                 $db->where('C.consignment_status_id', $status_id);
            }
            if ($cgroup_id) {
                 $db->where('C.c_group_id', $cgroup_id);
            }
            $db->join('organizations as O', 'O.id=C.org_id', 'left');
            $db->join('member as U', 'U.id=C.created_user_id', 'left');
            $db->join('group_members as GM', 'GM.user_id=C.created_user_id AND GM.org_id=C.org_id', 'left');
            $db->join('groups as G', 'G.id=GM.group_id', 'left');
            $db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $db->join('courier_service as S', 'S.id=C.service_id', 'left');
            $db->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left');
            $db->join('country as F', 'F.code=C.collection_country', 'left');
            $db->join('country as T', 'T.code=C.delivery_country', 'left');
            if ($flag == 0) {
                 $db->where("(C.created_user_id=$user_id OR C.org_id IN (select org_id from organization_members WHERE user_id=$user_id AND role_id=1) )");
            }
            $db->where('C.is_deleted', 0);
       }

       public function getmorderslist($cgroup_id) {
            return $this->db->select('C.consignment_id')->from('consignments as C')->where('C.c_group_id', $cgroup_id)->get()->result();
       }

       public function getorderslist_count_for_courier($courier_id, $search, $org, $service, $status) {
            $service_id = (int) $service;
            $status_id = (int) $status;
            $this->db->select('C.consignment_id');
            $this->db->from('consignments as C');
            if ($org != NULL) {
                 $this->db->where("C.org_id", $org);
            }
            if ($search != NULL) {
                 $this->db->where('(C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\')');
            }
            if ($service_id) {
                 $this->db->where('C.service_id', $service_id);
            }
            if ($status_id) {
                 $this->db->where('C.consignment_status_id', $status_id);
            }
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('member as U', 'U.id=C.created_user_id');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('courier_service as S', "S.id=C.service_id AND S.courier_id=$courier_id");
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where('C.consignment_status_id <> ' . C_DRAFT);
            $this->db->order_by('C.collection_date', 'DESC');
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getorderslist_for_courier($courier_id, $perpage, $search, $start, $org, $service, $status) {
            $service_id = (int) $service;
            $status_id = (int) $status;
            $this->db->select('C.consignment_id,C.public_id,C.private_id,C.price,O.name as org_name,U.username,'
                    . 'S.display_name as service,SP.price as service_price,C.consignment_status_id,C.collection_address,C.is_c_restricted_area as crestrict,'
                    . 'F.country as from_country,T.country as to_country,C.cancel_request,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,'
                    . 'C.delivery_contact_name,C.is_for_bidding,C.is_d_restricted_area as drestrict,'
                    . 'C.delivery_contact_phone,CS.courier_display_name as status,DATE_FORMAT(created_date,"%d-%m-%Y")as cdate', FALSE);
            $this->db->from('consignments as C');
            if ($org != NULL) {
                 $this->db->where("C.org_id", $org);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE \'%' . $search . '%\' OR C.consignment_id LIKE \'%' . $search . '%\' OR U.username LIKE \'%' . $search . '%\' OR C.collection_address LIKE \'%' . $search . '%\' OR C.delivery_address LIKE \'%' . $search . '%\')');
            }
            if ($service_id) {
                 $this->db->where('C.service_id', $service_id);
            }
            if ($status_id) {
                 $this->db->where('C.consignment_status_id', $status_id);
            }
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('member as U', 'U.id=C.created_user_id');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('courier_service as S', "S.id=C.service_id AND S.courier_id=$courier_id");
            $this->db->join('service_parcel_price as SP', 'S.id = SP.service_id AND C.consignment_type_id=SP.type', 'left');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where('C.consignment_status_id <> ' . C_DRAFT);
            $this->db->order_by('C.created_date', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function getorderslist_for_api($user_id, $perpage, $start) {
            $this->db->select('consignment_id, private_id, public_id,consignment_type_id, description,  customer_id, service_id, quantity,  length, breadth, height, volume, weight, collection_address, collection_date, collection_date_to, collection_country, collection_timezone, delivery_address, delivery_post_code, delivery_country, delivery_timezone, delivery_date, delivery_date_to, delivery_contact_name, delivery_contact_email, delivery_contact_phone,collection_post_code, collection_contact_name, collection_contact_number, collection_contact_email,consignment_status_id,created_date, remarks, picture');
            $this->db->from('consignments as C');
            $this->db->where('C.created_user_id', $user_id);
            $this->db->where('C.is_deleted', 0);
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function edit_order($data, $order) {
            $this->db->where('consignment_id', $order);
            $this->db->where('is_deleted', 0);
            $this->db->where('consignment_status_id', C_DRAFT);
            return $this->db->update('consignments', $data);
       }

       public function getDetails($where) {
            return $this->db->select('C.*,SP.price AS service_price,O.name as org_name,consignment_type.display_name,F.country as from_country,T.country as to_country,'
                                    . 'ZF.offset as from_zone,ZT.offset as to_zone,CS.display_name as consignment_status,CS.courier_display_name as consignment_courier_status,'
                                    . 'S.display_name as service,S.payment_terms,,P.contact_name as account_name,Z.courier_id,'
                                    . 'Z.company_name as courier_name,Z.phone,Z.fax,Z.support_email,Z.logo,TP.email as third_party_email,TP.permalink', FALSE)
                            ->from('consignments as C')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('organizations as O', 'O.id=C.org_id', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->join('couriers_external as TP', 'TP.job_id=C.consignment_id', 'left')
                            ->join('payment_accounts as P', 'P.id=C.payment_acount_id', 'left')
                            ->join('service_parcel_price as SP', 'S.id = SP.service_id AND C.consignment_type_id=SP.type', 'left')
                            ->where($where)
                            ->get()
                            ->row();
       }

       public function getDetails_print($where) {
            return $this->db->select('C.consignment_id, C.private_id,ST.name AS cb_name,C.collect_back, C.public_id,  C.service_id,C.quantity,'
                                    . 'F.country as from_country,T.country as to_country,C.payment_type, '
                                    . 'C.collection_address , C.collection_date as cdate, C.collection_date_to as cdate2, '
                                    . ' C.delivery_address , C.delivery_post_code as dpin,  C.delivery_contact_name as dname,'
                                    . ' C.collection_contact_email as cemail,C.delivery_date as ddate, C.delivery_date_to as ddate2,'
                                    . ' C.delivery_contact_phone as dphone,  C.collection_post_code as cpin, '
                                    . 'C.collection_contact_name as cname,C.delivery_contact_email as demail,'
                                    . ' C.collection_contact_number as cphone,C.delivery_company_name,C.collection_company_name, C.remarks,consignment_type.display_name as consignment_type,'
                                    . 'CS.display_name as service,Z.company_name as courier_name, CS.payment_terms')
                            ->from('consignments as C')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('courier_service as CS', "CS.id=C.service_id", 'left')
                            ->join('surcharge_types as ST', "ST.id=C.collect_back", 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('couriers as Z', 'Z.courier_id=CS.courier_id', 'left')
                            ->where($where)
                            ->get()
                            ->row();
       }

       public function getService($id) {
            return $this->db->select('CS.id as service_id,CS.display_name as service_name,CS.payments,'
                                    . 'CS.description as description,CS.is_public,CS.auto_approve,CS.org_id,'
                                    . 'C.courier_id,C.company_name as courier_name,C.logo,C.phone,CS.threshold_price,CS.time_to_deliver,'
                                    . 'C.compliance_id,CR.display_name as rating,CR.label_class,'
                                    . 'SP.price,SP.max_volume,SP.volume_cost,SP.max_weight,SP.weight_cost', FALSE)
                            ->from('consignments as CM')
                            ->join('courier_service as CS', 'CM.service_id=CS.id')
                            ->join('couriers as C', 'C.courier_id=CS.courier_id')
                            ->join('service_parcel_price as SP', 'CS.id = SP.service_id AND SP.type=CM.consignment_type_id')
                            ->join('compliance_ratings as CR', 'CR.id=C.compliance_id', 'left')
                            ->where('CM.consignment_id', $id)
                            ->get()
                            ->row();
       }

       public function getZone($zoneinfo) {
            return $this->db->select("*")
                            ->from('zoneinfo')
                            ->where('zoneinfo', $zoneinfo)
                            ->get()
                            ->row();
       }

       public function getCountry() {
            return $this->db->select("country")
                            ->from('member_details')
                            ->where('user_id', $this->session->userdata('user_id'))
                            ->get()
                            ->row();
       }

       public function updateStatus($id, $status) {
            $this->db->where('consignment_id', $id);
            return $this->db->update('consignments', array('consignment_status_id' => $status));
       }

       public function updateOrder($data, $id) {
            $this->track_status_change($data, $id);
            $this->db->where('consignment_id', $id);
            return $this->db->update('consignments', $data);
       }

       function countOrdersDelivered($data) {
            $data = $this->db->select('COUNT(*) as count')
                            ->from('jobstates')
                            ->where('status_code', $data['consignment_status_id'])
                            ->where('changed_user_id', $data['changed_user_id'])
                            ->get()->row();
            return $data->count;
       }

       private function track_status_change($data, $id) {
            if (count($data) > 1 && isset($data['consignment_status_id'])) {
                 $state = array(
                     'job_id' => $id,
                     'status_code' => $data['consignment_status_id'],
                     'status_name' => '',
                     'status_description' => '',
                     'user_type' => 1,
                     'changed_user_id' => 0
                 );
                 $this->db->insert('jobstates', $state);
            }
            return;
       }

       public function get_threshold($job_id) {
            // $row = $this->db->select('threshold_price')->from('courier_service as CS')->join('consignments as C', 'C.service_id=CS.id')->where('C.consignment_id', $job_id)->where('is_public', 0)->get()->row();
            $row = $this->db->select('threshold_price')->from('consignments as C')->where('C.consignment_id', $job_id)->get()->row();
            if ($row) {
                 return $row->threshold_price;
            }
            return -1;
       }

       public function getjobs($search = NULL, $company = NULL, $username = NULL, $collection_address = NULL, $delivery_address = NULL, $date_from = NULL, $date_to = NULL, $ddate_from = NULL, $ddate_to = NULL, $expired_date = NULL, $offset = 0, $limit = 20, $courier_id = 0) {
            $this->db->select("consignment_id as id,public_id,display_name,U.username,O.name as company_name,"
                    . "height,breadth,length, volume, weight, collection_address,C.is_c_restricted_area as crestrict,"
                    . "DATE_FORMAT(collection_date,'%d-%m-%Y')as collection_date_from,"
                    . "DATE_FORMAT(collection_date,'%h:%i %p')as collection_time_from,"
                    . "DATE_FORMAT(collection_date_to,'%d-%m-%Y')as collection_date_to,"
                    . "DATE_FORMAT(collection_date_to,'%h:%i %p')as collection_time_to,"
                    . "DATE_FORMAT(delivery_date,'%d-%m-%Y')as delivery_date_from,"
                    . "DATE_FORMAT(delivery_date,'%h:%i %p')as delivery_time_from,"
                    . "DATE_FORMAT(delivery_date_to,'%d-%m-%Y')as delivery_date_to,"
                    . "DATE_FORMAT(delivery_date_to,'%h:%i %p')as delivery_time_to,"
                    . "F.country as collection_country,"
                    . " delivery_address, delivery_post_code, D.country as delivery_country,C.is_d_restricted_area as drestrict,"
                    . "collection_post_code,"
                    . "DATE_FORMAT(created_date,'%d %b %Y %h:%i %p')as created_date ", FALSE);
            $this->db->from('consignments as C');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id', 'left');
            $this->db->join("pre_approved_bidders as PB", 'PB.org_id=C.org_id AND PB.courier_id=' . $courier_id, 'left');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as D', 'D.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where("C.is_for_bidding", 1);
            //  $this->db->where("C.is_confirmed", 0);
            $this->db->where("C.bidding_deadline >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
            if ($company != NULL) {
                 $this->db->where("org_id", $company);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE  \'%' . $search . '%\' OR U.username LIKE  \'%' . $search . '%\' OR O.name LIKE \'%' . $search . '%\' )');
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($collection_address != NULL) {
                 $this->db->where('(C.collection_address LIKE  \'%' . $collection_address . '%\' OR C.collection_contact_name LIKE  \'%' . $collection_address . '%\' OR F.country LIKE \'%' . $collection_address . '%\' )');
            }
            if ($delivery_address != NULL) {
                 $this->db->where('(C.delivery_address LIKE \'%' . $delivery_address . '%\' OR C.delivery_contact_name LIKE  \'%' . $delivery_address . '%\' OR D.country LIKE  \'%' . $delivery_address . '%\')');
            }
            if ($date_from != NULL) {
                 $cdate = date('Y-m-d H:m:s', strtotime($date_from));
                 $this->db->where("C.collection_date >= '$cdate'");
            }
            if ($date_to != NULL) {
                 $todate = date('Y-m-d H:m:s', strtotime($date_to));
                 $this->db->where("C.collection_date_to <= '$todate'");
            }
            if ($ddate_from != NULL) {
                 $ddate = date('Y-m-d H:m:s', strtotime($ddate_from));
                 $this->db->where("C.delivery_date >= '$ddate'");
            }
            if ($ddate_to != NULL) {
                 $dtodate = date('Y-m-d H:m:s', strtotime($ddate_to));
                 $this->db->where("C.delivery_date_to <= '$dtodate'");
            }
            $this->db->where('(C.is_open_bid=1 OR PB.id IS NOT NULL)');
            $this->db->order_by('C.created_date', 'desc');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
       }

       public function getjobdetail($job_id) {
            return $this->db->select('C.*,consignment_type.display_name,F.country as from_country,T.country as to_country,ZF.offset as from_zone,ZT.offset as to_zone,CS.display_name as consignment_status')
                            ->from('consignments as C')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->where('consignment_id', $job_id)
                            ->get()
                            ->row();

            $this->db->select('consignment_id as id,display_name,O.name as company_name, volume, weight, collection_address,collection_country, delivery_address, delivery_post_code, delivery_country,delivery_contact_name,  delivery_contact_phone,collection_post_code, collection_contact_name, collection_contact_number, created_date');
            $this->db->from('consignments as C');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id', 'left');
            $this->db->where('consignment_id', $job_id);
            return $this->db->get()->row();
       }

       public function getwonjobs($courier_id = 0, $search = NULL, $company = NULL, $username = NULL, $collection_address = NULL, $delivery_address = NULL, $date_from = NULL, $date_to = NULL, $ddate_from = NULL, $ddate_to = NULL, $expired_date = NULL, $status = NULL, $offset = 0, $limit = 20, $is_confirmed = -1) {
            $this->db->select("consignment_id as id,public_id,private_id,T.display_name,U.username,O.name as company_name,"
                    . "height,breadth,length, volume, weight, collection_address,C.is_for_bidding,C.is_confirmed,C.price, "
                    . "DATE_FORMAT(collection_date,'%d-%m-%Y')as collection_date_from,"
                    . "DATE_FORMAT(collection_date,'%h:%i %p')as collection_time_from,"
                    . "DATE_FORMAT(collection_date_to,'%d-%m-%Y')as collection_date_to,"
                    . "DATE_FORMAT(collection_date_to,'%h:%i %p')as collection_time_to,"
                    . "DATE_FORMAT(delivery_date,'%d-%m-%Y')as delivery_date_from,"
                    . "DATE_FORMAT(delivery_date,'%h:%i %p')as delivery_time_from,"
                    . "DATE_FORMAT(delivery_date_to,'%d-%m-%Y')as delivery_date_to,"
                    . "DATE_FORMAT(delivery_date_to,'%h:%i %p')as delivery_time_to,"
                    . "F.country as collection_country,ST.display_name as consignment_status, "
                    . " delivery_address, delivery_post_code, D.country as delivery_country,"
                    . "delivery_contact_name, delivery_contact_phone,collection_post_code,"
                    . " collection_contact_name, collection_contact_number, CS.display_name as service_name,"
                    . "DATE_FORMAT(created_date,'%d %b %Y %h:%i %p')as created_date ", FALSE);
            $this->db->from('consignments as C');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id', 'left');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->join("courier_service as CS", 'CS.id=C.service_id');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as D', 'D.code=C.delivery_country', 'left');
            $this->db->join('consignment_status as ST', 'ST.status_id=C.consignment_status_id');
            $this->db->where('C.is_deleted', 0);
            $this->db->where("C.is_service_assigned", 1);
            $this->db->where("CS.courier_id", $courier_id);
            if ($company != NULL) {
                 $this->db->where("C.org_id", $company);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE  \'%' . $search . '%\' OR U.username LIKE  \'%' . $search . '%\' OR O.name LIKE \'%' . $search . '%\' )');
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($status != NULL) {
                 $this->db->where('C.consignment_status_id', $status);
            }
            if ($collection_address != NULL) {
                 $this->db->where('(C.collection_address LIKE  \'%' . $collection_address . '%\' OR C.collection_contact_name LIKE  \'%' . $collection_address . '%\' OR F.country LIKE \'%' . $collection_address . '%\' )');
            }
            if ($delivery_address != NULL) {
                 $this->db->where('(C.delivery_address LIKE \'%' . $delivery_address . '%\' OR C.delivery_contact_name LIKE  \'%' . $delivery_address . '%\' OR D.country LIKE  \'%' . $delivery_address . '%\')');
            }
            if ($date_from != NULL) {
                 $cdate = date('Y-m-d H:m:s', strtotime($date_from));
                 $this->db->where("C.collection_date >= '$cdate'");
            }
            if ($date_to != NULL) {
                 $todate = date('Y-m-d H:m:s', strtotime($date_to));
                 $this->db->where("C.collection_date_to <= '$todate'");
            }
            if ($ddate_from != NULL) {
                 $ddate = date('Y-m-d H:m:s', strtotime($ddate_from));
                 $this->db->where("C.delivery_date >= '$ddate'");
            }
            if ($ddate_to != NULL) {
                 $dtodate = date('Y-m-d H:m:s', strtotime($ddate_to));
                 $this->db->where("C.delivery_date_to <= '$dtodate'");
            }
            if ($is_confirmed != -1)
                 $this->db->where("C.is_confirmed", $is_confirmed);

            $this->db->order_by('C.created_date', 'desc');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
       }

       public function getbidderslist_totalcount($order_id) {
            $order_id = (int) $order_id;
            $this->db->select('CB.bid_id');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=CB.bid_id');
            $this->db->from('bids as CB');
            $this->db->where('BC.consignment_id', $order_id);
            $this->db->where('CB.status', 1);
            //bids which are new
            $this->db->where('CB.is_approved', 0);
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getbidderslist_count($order_id, $search) {
            $order_id = (int) $order_id;
            $this->db->select('CB.bid_id');
            $this->db->join('couriers as C', 'C.courier_id=CB.courier_id');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=CB.bid_id');
            $this->db->join('courier_service as CS', 'CS.id=CB.service_row_id');
            $this->db->from('bids as CB');
            if ($search != NULL) {
                 $this->db->where('(C.company_name LIKE \'%' . $search . '%\' OR CS.display_name LIKE \'%' . $search . '%\')');
            }
            $this->db->where('BC.consignment_id', $order_id);
            $this->db->where('CB.status', 1);
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getbidderslist_by_userid($order_id, $perpage, $search, $start) {
            $order_id = (int) $order_id;
            $this->db->select('CB.bid_id, CB.service_id, CB.service_row_id,CB.is_approved, CB.courier_id, CB.bidding_price as price, CB.remarks,C.company_name as courier,CS.display_name as service');
            $this->db->join('couriers as C', 'C.courier_id=CB.courier_id');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=CB.bid_id');
            $this->db->join('courier_service as CS', 'CS.id=CB.service_row_id');
            $this->db->from('bids as CB');
            if ($search != NULL) {
                 $this->db->where('(C.company_name LIKE \'%' . $search . '%\' OR CS.display_name LIKE \'%' . $search . '%\')');
            }
            $this->db->where('BC.consignment_id', $order_id);
            $this->db->where('CB.status', 1);
            $this->db->limit($perpage, $start);
            $query = $this->db->get();
            return $query->result();
       }

       public function accept_bid($order_id, $bid_id, $price, $row_id) {
            $order_id = (int) $order_id;
            $accept_query = "UPDATE bids as CB SET CB.is_approved=1,CB.updated_date='"
                    . mdate('%Y-%m-%d %H:%i:%s', now()) . "',CB.approved_date='"
                    . mdate('%Y-%m-%d %H:%i:%s', now())
                    . "' WHERE  CB.bid_id=" . $bid_id;
            $reject_query = "UPDATE bids as CB  JOIN bids as CC ON CC.bid_id=CB.bid_id AND CC.bid_id IN (SELECT bid_id from bid_consignment_relation WHERE consignment_id=$order_id) SET CB.is_approved=2,CB.updated_date='"
                    . mdate('%Y-%m-%d %H:%i:%s', now()) . "',CB.approved_date='"
                    . mdate('%Y-%m-%d %H:%i:%s', now())
                    . "' WHERE  CB.is_approved=0";
            $update_order_query = "UPDATE consignments as C JOIN consignments as T ON T.c_group_id=C.c_group_id AND T.consignment_id=$order_id  SET C.service_id=" . $row_id . ", C.price=" . $price . ',C.consignment_status_id=' . C_PENDING;

            $this->db->trans_begin();
            $this->db->query($accept_query);
            $this->db->query($reject_query);
            $this->db->query($update_order_query);

            if ($this->db->trans_status() === FALSE) {
                 $this->db->trans_rollback();
            } else {
                 $this->db->trans_commit();
            }

            return TRUE;
       }

       public function cancel_bids($order_id) {
            $reject_query = "UPDATE bids as CB  JOIN bids as CC ON CC.bid_id=CB.bid_id AND CC.bid_id IN (SELECT bid_id from bid_consignment_relation WHERE consignment_id=$order_id) SET CB.status=2,CB.updated_date='"
                    . mdate('%Y-%m-%d %H:%i:%s', now()) . "',CB.is_approved=0";
            return $this->db->query($reject_query);
       }

       public function get_bid($bid_id) {
            return $this->db->select('CB.service_row_id as row_id, CB.bidding_price as price')
                            ->from('bids as CB')
                            ->where('CB.bid_id', $bid_id)
                            ->get()
                            ->row();
       }

       public function is_available_for_bid($job_id) {
            return $this->db->select('consignment_id')->from('consignments')->where(array('consignment_id' => $job_id, "is_confirmed" => 0, "is_service_assigned" => 0))->get()->row();
       }

       public function get_courier($order_id = 0) {
            return $this->db->select('CR.courier_id,CR.email,CR.company_name as name')
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'CS.id=C.service_id')
                            ->join('couriers as CR', 'CR.courier_id=CS.courier_id')
                            ->where('C.consignment_id', $order_id)->get()->result();
       }

       public function get_courier_by_cgroup_id($c_group_id) {
            return $this->db->select('CR.company_name as name')
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'CS.id=C.service_id')
                            ->join('couriers as CR', 'CR.courier_id=CS.courier_id')
                            ->where('C.c_group_id', $c_group_id)->get()->row();
       }

       public function get_biders($order_id = 0) {
            $this->db->select('DISTINCT C.courier_id,C.email,C.company_name as name', FALSE);
            $this->db->join('couriers as C', 'C.courier_id=CB.courier_id');
            $this->db->join('bid_consignment_relation as BC', 'BC.bid_id=CB.bid_id');
            $this->db->join('courier_service as CS', 'CS.id=CB.service_row_id');
            $this->db->from('bids as CB');
            $this->db->where('BC.consignment_id', $order_id);
            $this->db->where('CB.is_approved <>', 2);
            return $this->db->get()->result();
       }

       public function get_closed_biders($order_id = 0) {
            $this->db->select('C.courier_id,C.email,C.company_name as name');
            $this->db->from('consignments as CO');
            $this->db->join('pre_approved_bidders as PB', 'PB.org_id=CO.org_id');
            $this->db->join('couriers as C', 'C.courier_id=PB.courier_id');
            $this->db->where('CO.consignment_id', $order_id);
            return $this->db->get()->result();
       }

       public function get_open_biders() {
            $this->db->select('C.courier_id,C.email,C.company_name as name');
            $this->db->from('couriers as C');
            $this->db->where('C.is_verified', 1);
            $this->db->where('C.is_approved', 1);
            return $this->db->get()->result();
       }

       public function get_owner($order_id = 0) {
            $this->db->select('M.id as user_id,M.username as name, M.email')
                    ->from('consignments as C')
                    ->join('member as M', 'M.id=C.created_user_id')
                    ->where('C.consignment_id', $order_id);
            return $this->db->get()->result();
       }

       public function get_status() {
            return $this->db->select('SELECT * FROM (SELECT C.consignment_id,JS.status_code,CS.display_name,JS.created_date FROM consignments as C,`jobstates` as JS,consignment_status as CS WHERE C.consignment_id=32 AND C.consignment_id=JS.job_id AND JS.status_code=CS.status_id  ORDER BY JS.created_date) AS U GROUP BY U.consignment_id')
                            ->get()
                            ->row();
       }

       public function get_job_permission($job_id) {
            return $this->db->select('C.consignment_id,CS.courier_id,C.is_confirmed')
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'CS.id=C.service_id', 'left')
                            ->where('C.consignment_id', $job_id)
                            ->where('C.is_confirmed', 1)
                            ->get()
                            ->row();
       }

       public function get_deliveries_count_by_status($user_id, $from_date) {
            if ($from_date != NULL) {
                 $this->db->where("C.created_date >= '" . $from_date . "'");
            }
            return $this->db->select('COUNT(C.consignment_id) as deliveries,C.consignment_status_id as status', FALSE)
                            ->from('consignments as C')
                            ->where('C.created_user_id', $user_id)
                            ->group_by('C.consignment_status_id')
                            ->get()
                            ->result();
       }

       public function gettenderslist_count_for_courier($search = NULL, $company = NULL, $username = NULL, $collection_address = NULL, $delivery_address = NULL, $date_from = NULL, $date_to = NULL, $ddate_from = NULL, $ddate_to = NULL, $expired_date = NULL, $courier_id = 0) {
            $this->db->select("consignment_id as id");
            $this->db->from('consignments as C');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id', 'left');
            $this->db->join("pre_approved_bidders as PB", 'PB.org_id=C.org_id AND PB.courier_id=' . $courier_id, 'left');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as D', 'D.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where("C.is_for_bidding", 1);
            $this->db->where("C.is_confirmed", 0);
            $this->db->where("C.bidding_deadline >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
            if ($company != NULL) {
                 $this->db->where("org_id", $company);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE  \'%' . $search . '%\' OR U.username LIKE  \'%' . $search . '%\' OR O.name LIKE \'%' . $search . '%\' )');
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($collection_address != NULL) {
                 $this->db->where('(C.collection_address LIKE  \'%' . $collection_address . '%\' OR C.collection_contact_name LIKE  \'%' . $collection_address . '%\' OR F.country LIKE \'%' . $collection_address . '%\' )');
            }
            if ($delivery_address != NULL) {
                 $this->db->where('(C.delivery_address LIKE \'%' . $delivery_address . '%\' OR C.delivery_contact_name LIKE  \'%' . $delivery_address . '%\' OR D.country LIKE  \'%' . $delivery_address . '%\')');
            }
            if ($date_from != NULL) {
                 $cdate = date('Y-m-d H:m:s', strtotime($date_from));
                 $this->db->where("C.collection_date >= '$cdate'");
            }
            if ($date_to != NULL) {
                 $todate = date('Y-m-d H:m:s', strtotime($date_to));
                 $this->db->where("C.collection_date_to <= '$todate'");
            }
            if ($ddate_from != NULL) {
                 $ddate = date('Y-m-d H:m:s', strtotime($ddate_from));
                 $this->db->where("C.delivery_date >= '$ddate'");
            }
            if ($ddate_to != NULL) {
                 $dtodate = date('Y-m-d H:m:s', strtotime($ddate_to));
                 $this->db->where("C.delivery_date_to <= '$dtodate'");
            }
            $this->db->where('C.consignment_status_id <> ' . C_DRAFT);
            $this->db->where('(C.is_open_bid=1 OR PB.id IS NOT NULL)');
            $this->db->order_by('C.created_date', 'desc');
            return $this->db->get()->num_rows();
       }

       public function gettenderslist_for_courier($search = NULL, $company = NULL, $username = NULL, $collection_address = NULL, $delivery_address = NULL, $date_from = NULL, $date_to = NULL, $ddate_from = NULL, $ddate_to = NULL, $expired_date = NULL, $offset = 0, $limit = 20, $courier_id = 0) {
            $this->db->select("consignment_id as id,public_id,display_name,U.username,O.name as org_name,"
                    . "height,breadth,length, volume, weight, collection_address,C.is_c_restricted_area as crestrict,"
                    . "DATE_FORMAT(collection_date,'%d-%m-%Y')as collection_date_from,"
                    . "DATE_FORMAT(collection_date,'%h:%i %p')as collection_time_from,"
                    . "DATE_FORMAT(collection_date_to,'%d-%m-%Y')as collection_date_to,"
                    . "DATE_FORMAT(collection_date_to,'%h:%i %p')as collection_time_to,"
                    . "DATE_FORMAT(delivery_date,'%d-%m-%Y')as delivery_date_from,"
                    . "DATE_FORMAT(delivery_date,'%h:%i %p')as delivery_time_from,"
                    . "DATE_FORMAT(delivery_date_to,'%d-%m-%Y')as delivery_date_to,"
                    . "DATE_FORMAT(delivery_date_to,'%h:%i %p')as delivery_time_to,"
                    . "F.country as collection_country,C.is_d_restricted_area as drestrict,"
                    . " delivery_address, delivery_post_code, D.country as delivery_country,"
                    . "collection_post_code,"
                    . "DATE_FORMAT(created_date,'%d-%m-%Y')as created_date ", FALSE);
            $this->db->from('consignments as C');
            $this->db->join("consignment_type as T", 'T.consignment_type_id=C.consignment_type_id');
            $this->db->join("organizations as O", 'O.id=C.org_id', 'left');
            $this->db->join("pre_approved_bidders as PB", 'PB.org_id=C.org_id AND PB.courier_id=' . $courier_id, 'left');
            $this->db->join("member as U", 'U.id=C.created_user_id');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as D', 'D.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where("C.is_for_bidding", 1);
            $this->db->where("C.is_confirmed", 0);
            $this->db->where("C.bidding_deadline >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
            if ($company != NULL) {
                 $this->db->where("org_id", $company);
            }
            if ($search != NULL) {
                 $this->db->where('(C.public_id LIKE  \'%' . $search . '%\' OR U.username LIKE  \'%' . $search . '%\' OR O.name LIKE \'%' . $search . '%\' )');
            }
            if ($username != NULL) {
                 $this->db->where('U.username LIKE  \'%' . $username . '%\'');
            }
            if ($collection_address != NULL) {
                 $this->db->where('(C.collection_address LIKE  \'%' . $collection_address . '%\' OR C.collection_contact_name LIKE  \'%' . $collection_address . '%\' OR F.country LIKE \'%' . $collection_address . '%\' )');
            }
            if ($delivery_address != NULL) {
                 $this->db->where('(C.delivery_address LIKE \'%' . $delivery_address . '%\' OR C.delivery_contact_name LIKE  \'%' . $delivery_address . '%\' OR D.country LIKE  \'%' . $delivery_address . '%\')');
            }
            if ($date_from != NULL) {
                 $cdate = date('Y-m-d H:m:s', strtotime($date_from));
                 $this->db->where("C.collection_date >= '$cdate'");
            }
            if ($date_to != NULL) {
                 $todate = date('Y-m-d H:m:s', strtotime($date_to));
                 $this->db->where("C.collection_date_to <= '$todate'");
            }
            if ($ddate_from != NULL) {
                 $ddate = date('Y-m-d H:m:s', strtotime($ddate_from));
                 $this->db->where("C.delivery_date >= '$ddate'");
            }
            if ($ddate_to != NULL) {
                 $dtodate = date('Y-m-d H:m:s', strtotime($ddate_to));
                 $this->db->where("C.delivery_date_to <= '$dtodate'");
            }
            $this->db->where('C.consignment_status_id <> ' . C_DRAFT);
            $this->db->where('(C.is_open_bid=1 OR PB.id IS NOT NULL)');
            $this->db->order_by('C.created_date', 'desc');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
       }

       public function trackjobs($list, $org) {
            $this->db->select('C.consignment_id,C.public_id,'
                    . 'C.consignment_status_id,C.collection_address,C.is_c_restricted_area as crestrict,'
                    . 'F.country as collection_country,T.country as delivery_country,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,'
                    . 'C.delivery_contact_name,C.is_d_restricted_area as drestrict,'
                    . 'C.delivery_contact_phone,CS.display_name as status');
            $this->db->from('consignments as C');
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where('C.org_id', $org);
            $this->db->where("C.public_id IN ($list)");
            return $this->db->get()->result();
       }

       public function getTags($order_id) {
            return $this->db->select('tags,ref')->from('consignments')->where('consignment_id', $order_id)->get()->row();
       }

       public function get_template_prference($user) {
            $row = $this->db->select('template_preference')->from('member_details')->where('user_id', $user)->get()->row();
            return $row->template_preference;
       }

       public function update_template_prference($user, $preference) {
            return $this->db->where('user_id', $user)->update('member_details', array('template_preference' => $preference));
       }

       public function get_transatctions($start_date, $end_date, $user_id, $status) {
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
                                    . 'S.display_name as service,Z.courier_id,Z.company_name as courier_name,U.username,G.name as team')
                            ->from('consignments as C')
                            ->join('member as U', 'U.id=C.created_user_id', 'left')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->join('group_members as GM', 'GM.user_id=C.created_user_id AND GM.org_id=C.org_id', 'left')
                            ->join('groups as G', 'G.id=GM.group_id', 'left')
                            ->where('C.created_user_id', $user_id)
                            ->where_in('C.consignment_status_id', $status)
                            ->get()->result();
       }

       public function get_third_party_courier($job_id) {
            return $this->db->select('id as courier_id,email as email, email as name,permalink,job_id')
                            ->from('couriers_external')
                            ->where('job_id', $job_id)
                            ->get()
                            ->result();
       }

       public function get_expired_orders() {
            return $this->db->select('C.consignment_id,C.public_id,C.bidding_deadline', FALSE)
                            ->from('consignments as C')
                            ->where('C.is_for_bidding', 1)
                            ->where("C.bidding_deadline <= '" . date('Y-m-d H:i:s') . "'")
                            ->where('C.inform_expire <> 2')
                            ->where('C.is_service_assigned', 0)
                            ->get()
                            ->result();
       }

       public function get_expiring_orders() {
            return $this->db->select('C.consignment_id,C.public_id,C.bidding_deadline', FALSE)
                            ->from('consignments as C')
                            ->where('C.is_for_bidding', 1)
                            ->where("C.bidding_deadline <= '" . date('Y-m-d H:i:s', strtotime('+1 hours')) . "'")
                            ->where('C.inform_expire', 0)
                            ->where('C.is_service_assigned', 0)
                            ->get()
                            ->result();
       }

       public function get_account_money($id) {
            return $this->db->select('*')->from('payment_accounts')->where(array('id' => $id, "status" => 2))->get()->row();
       }

       public function update_account_money($id, $balance) {
            return $this->db->where('id', $id)
                            ->update('payment_accounts', array('credit' => $balance));
       }

  }
  