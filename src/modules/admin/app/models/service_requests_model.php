<?php

  class Service_requests_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       function getrequestlist_by_org_count($user_id, $org_id, $search, $category = NULL) {
            if ($category !== NULL) {
                 if ($category == 1) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
                 if ($category == 2) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date <='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
                 if ($category == 0) {
                      $this->db->where('SR.status', 0);
                 }
                 if ($category == 3) {
                      $this->db->where('SR.status', 2);
                 }
            }
            if ($search != NULL) {
                 $this->db->where('(SR.name LIKE \'%' . $search . '%\')');
            }
            $this->db->select('SR.req_id');
            $this->db->from('service_requests as SR');
            $this->db->where('SR.user_id', $user_id);
            if ($org_id != NULL) {
                 $this->db->where('SR.org_id', $org_id);
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function add_tender_files($tenders) {
            return $this->db->insert_batch('tender_files', $tenders);
       }

       function delete_tender_files($tender_id) {
            return $this->db->where('tender_id', $tender_id)->delete('tender_files');
       }

       function delete_tender_file($req_id, $filename) {
            return $this->db->where('tender_id', $req_id)->where('url', $filename)->delete('tender_files');
       }

       function getrequestlist_by_org($user_id, $org_id, $perpage, $search, $start, $category = NULL) {
            if ($category !== NULL) {
                 if ($category == 1) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
                 if ($category == 2) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date <='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
                 if ($category == 0) {
                      $this->db->where('SR.status', 0);
                 }
                 if ($category == 3) {
                      $this->db->where('SR.status', 2);
                 }
            }
            if ($search != NULL) {
                 $this->db->where('(SR.name LIKE \'%' . $search . '%\')');
            }
            $this->db->select('SR.req_id, SR.name as title, price_range as price, delivery_p_m,'
                    . ' service_duration as duration, payment_term as payment, '
                    . 'other_conditions, remarks as description, '
                    . 'SR.status,O.name as org_name,count(SB.bid_id) as bid_count', FALSE);
            $this->db->from('service_requests as SR');
            $this->db->join('service_bids as SB', 'SB.req_id=SR.req_id AND SB.status <> 0', 'left');
            $this->db->join('organizations as O', 'O.id=SR.org_id', 'left');
            $this->db->where('SR.user_id', $user_id);
            if ($org_id != NULL) {
                 $this->db->where('SR.org_id', $org_id);
            }
            $this->db->group_by('SR.req_id');
            $this->db->order_by('req_id', 'desc');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function add_request($row, $request_id = '') {
            if (empty($request_id)) {
                 if ($this->db->insert('service_requests', $row))
                      return $this->db->insert_id();
            }
            else {
                 $this->db->update('service_requests', $row, array('req_id' => $request_id));
                 $this->db->update('service_bids', array('status' => 0), array('req_id' => $request_id));
                 return $request_id;
            }
            return FALSE;
       }

       public function cancel_request($request_id = 0) {
            if ($this->db->update('service_requests', array('status' => 2), array('req_id' => $request_id))) {
                 $this->db->update('service_bids', array('status' => 0), array('req_id' => $request_id));
                 return TRUE;
            }
            return FALSE;
       }

       public function get_org($req) {
            return $this->db->select('org_id')->from('service_requests')->where('req_id', $req)->get()->row();
       }

       public function get_request_details($req_id) {
            $this->db->select('req_id, name as title, price_range as price,SR.is_open_bid as open_bid, '
                    . 'delivery_p_m, service_duration as duration, payment_term as payment,'
                    . ' other_conditions, remarks as description,expiry_date as expiry, status,'
                    . 'added_on');
            $this->db->from('service_requests as SR');
            $this->db->where('SR.req_id', $req_id);
            return $this->db->get()->row();
       }

       public function get_request_files($req_id) {
            return $this->db->select('filename,url')->from('tender_files')->where('tender_id', $req_id)->get()->result();
       }

       public function get_request_list($search) {
            if ($search != NULL) {
                 $this->db->where('(SR.name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('req_id as request_id, name, delivery_p_m as deliveries_per_month, service_duration , payment_term , other_conditions, remarks as description, added_on')
                            ->from('service_requests as SR')
                            ->where('status', 1)
                            ->get()
                            ->result();
       }

       public function get_request_list_count($search, $category = NULL, $courier_id = 0) {
            if ($category !== NULL) {
                 if ($category == 1) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
                 if ($category == 2) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date <='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
                 }
            }
            if ($search != NULL) {
                 $this->db->where('(SR.name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('SR.req_id as request_id', FALSE)
                            ->from('service_requests as SR')
                            ->join("pre_approved_bidders as PB", 'PB.org_id=SR.org_id AND PB.courier_id=' . $courier_id, 'left')
                            ->where('SR.status <> 2')
                            ->where('(SR.is_open_bid=1 OR PB.id IS NOT NULL)')
                            ->get()
                            ->num_rows();
       }

       public function get_request_list_for_courier($search, $start, $perpage, $category = NULL, $sort = '', $sort_direction = '', $courier_id = 0) {
            $sort_array = array('name', 'remarks', 'delivery_p_m', 'service_duration', 'added_on', 'request_stat');
            if ($category !== NULL) {
                 if ($category == 0) {
                      $this->db->where('SR.status', 0);
                 } else if ($category == 1) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date >='" . date('Y-m-d H:i:s') . "'");
                 } else if ($category == 2) {
                      $this->db->where('SR.status', 1);
                      $this->db->where("SR.expiry_date <='" . date('Y-m-d H:i:s') . "'");
                 }
            }
            if ($search != NULL) {
                 $this->db->where('(SR.name LIKE \'%' . $search . '%\')');
            }
            if (!empty($sort) && in_array($sort, $sort_array)) {
                 $this->db->order_by($sort, $sort_direction);
            }
            return $this->db->select('SR.req_id as request_id, SR.name, SR.delivery_p_m as deliveries_per_month,'
                                    . ' SR.service_duration , SR.payment_term , SR.other_conditions, SR.remarks as description,SR.status as request_stat, '
                                    . "DATE_FORMAT(SR.added_on,'%d %b %Y %h:%i %p')as added_on, Case When SR.status=1 and SR.expiry_date <='" . date('Y-m-d H:i:s') . "' Then 1
                                        Else 0
                                        End as expired ", FALSE)
                            ->from('service_requests as SR')
                            ->join("pre_approved_bidders as PB", 'PB.org_id=SR.org_id AND PB.courier_id=' . $courier_id, 'left')
                            ->where('SR.status <> 2')
                            ->where('(SR.is_open_bid=1 OR PB.id IS NOT NULL)')
                            ->limit($perpage, $start)
                            ->get()
                            ->result();
       }

       public function is_available_for_bid($req_id) {
            return $this->db->select('req_id')
                            ->from('service_requests')
                            ->where('status', 1)
                            ->where('req_id', $req_id)
                            ->get()
                            ->row();
       }

       public function get_owners($req_id = 0) {
            $this->db->select('M.id as user_id,M.username as name, M.email')
                    ->from('service_requests as SR')
                    ->join('organization_members  as OM', 'OM.org_id=SR.org_id AND OM.role_id=1')
                    ->join('member as M', 'M.id=OM.user_id')
                    ->where('SR.req_id', $req_id);
            return $this->db->get()->result();
       }

       public function get_expired_tenders() {
            return $this->db->select('req_id, name', FALSE)
                            ->from('service_requests')
                            ->where('status', 1)
                            ->where("expiry_date <= '" . date('Y-m-d H:i:s') . "'")
                            ->where('inform_expire <> 2')
                            ->get()
                            ->result();
       }

       public function get_biders($req_id) {
            $this->db->select('C.courier_id,C.email,C.company_name as name');
            $this->db->from('service_bids as SB');
            $this->db->join('courier_service as CS', 'CS.id=SB.service_id');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id');
            $this->db->where('SB.req_id', $req_id);
            $this->db->where('SB.status <> 0');
            return $this->db->get()->result();
       }

       public function get_expiring_tenders() {
            return $this->db->select('req_id, name', FALSE)
                            ->from('service_requests')
                            ->where('status', 1)
                            ->where("expiry_date <= '" . date('Y-m-d H:i:s', strtotime('+1 hours')) . "'")
                            ->where('inform_expire', 0)
                            ->get()
                            ->result();
       }

       public function updateExpireInfo($data, $id) {
            return $this->db->update('service_requests', $data, array('req_id' => $id));
       }

  }
  