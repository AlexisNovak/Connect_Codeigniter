<?php

  class Service_bids_model extends CI_Model {

//bid status 1=new, 2=accepted, 3=rejected,0=withdrawn
       function __construct() {
            parent::__construct();
       }

       public function addbid($data) {
            $data['updated_on'] = mdate('%Y-%m-%d %H:%i:%s', now());
            if ($this->db->insert('service_bids', $data)) {
                 return $this->db->insert_id();
            }
            return FALSE;
       }

       public function getbidderslist_count($request_id, $search) {
            $this->db->select(' SB.bid_id');
            $this->db->from('service_bids as SB');
            $this->db->join('courier_service as CS', 'CS.id=SB.service_id');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id');
            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\' OR C.company_name LIKE \'%' . $search . '%\')');
            }

            $this->db->where('SB.req_id', $request_id);
            $this->db->where('SB.status <> 0');
            $query = $this->db->get();
            return $query->num_rows();
       }

       public function getbidderslist($request_id, $perpage, $search, $start) {
            $this->db->select('C.email,SB.bid_id,CS.display_name as service,'
                    . ' DATE_FORMAT(CS.start_time,"%h:%i %p") as start_time,'
                    . 'DATE_FORMAT(CS.end_time,"%h:%i %p") as end_time,'
                    . 'CS.description,C.courier_id,C.company_name as courier, SB.status,CS.id as service_id', FALSE);
            $this->db->from('service_bids as SB');
            $this->db->join('courier_service as CS', 'CS.id=SB.service_id');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id');
            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\' OR C.company_name LIKE \'%' . $search . '%\')');
            }

            $this->db->where('SB.req_id', $request_id);
            $this->db->where('SB.status <> 0');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       public function get_bid($bid_id) {
            return $this->db->select('SB.*')->from('service_bids as SB')->where('SB.bid_id', $bid_id)->get()->row();
       }

       public function accept_bid($request_id, $bid_id, $org_id, $service_id) {
           $service = $this->db->where('CS.id', $service_id)->get('courier_service as CS')->row();
            $new_service = array(
                'service_id' => $service->service_id,
                'org_id' => $org_id,
                'display_name' => $service->display_name,
                'price' => $service->price,
                'start_time' => $service->start_time,
                'end_time' => $service->end_time,
                'week_0' => $service->week_0,
                'week_1' => $service->week_1,
                'week_2' => $service->week_2,
                'week_3' => $service->week_3,
                'week_4' => $service->week_4,
                'week_5' => $service->week_5,
                'week_6' => $service->week_6,
                'origin' => $service->origin,
                'destination' => $service->destination,
                'description' => $service->description,
                'threshold_price' => round($service->price + ($service->price * (10 / 100)),2),
                'is_for_bidding' => 0,
                'status' => 1,
                'org_status' => 2,
                'courier_id' => $service->courier_id,
                'auto_approve' => 0,
                'is_public' => 0,
                'payment_terms'=>$service->payment_terms
            );
            $accept_query = "UPDATE service_bids as SB SET SB.status=2,SB.updated_on='"
                    . mdate('%Y-%m-%d %H:%i:%s', now()) . "' WHERE  SB.bid_id=" . $bid_id;
            $reject_query = "UPDATE service_bids as SB SET SB.status=3,SB.updated_on='"
                    . mdate('%Y-%m-%d %H:%i:%s', now()) . "' WHERE  SB.status=1 AND SB.req_id=" . $request_id;
            $update_service_request_query = "UPDATE service_requests SET status=0 WHERE req_id=" . $request_id;
            $this->db->trans_begin();
            $this->db->query($accept_query);
            $this->db->query($reject_query);
            $this->db->query($update_service_request_query);
            $this->db->insert('courier_service', $new_service);

            if ($this->db->trans_status() === FALSE) {
                 $this->db->trans_rollback();
            } else {
                 $this->db->trans_commit();
            }

            return TRUE;
       }

       public function withdrawbid($bid_id, $courier_id) {
            if ($this->db->where(array("bid_id" => $bid_id, "courier_id" => $courier_id, 'status' => 1))
                            ->update("service_bids", array("status" => 0, 'updated_on' => mdate('%Y-%m-%d %H:%i:%s', now())))) {


                 return TRUE;
            }
            return FALSE;
       }

       public function get_request_id($bid_id) {
            return $this->db->select('req_id')->from('service_bids')->where('bid_id', $bid_id)->get()->row();
       }

       public function get_request_bids($courier_id, $sort, $sort_direction) {
            return $this->db->query('SELECT * FROM (SELECT SB.bid_id, SB.req_id, CS.display_name as service,'
                                    . ' SB.status FROM (service_bids as SB) '
                                    . 'JOIN courier_service as CS ON CS.id=SB.service_id '
                                    . 'WHERE SB.courier_id = ' . $courier_id . ' '
                                    . 'ORDER BY updated_on desc) AS U  GROUP BY U.req_id', FALSE)
                            ->result();
       }

       public function get_request_bid($req_id, $courier_id) {
            return $this->db->query('SELECT SB.bid_id, SB.req_id, CS.display_name as service,'
                                    . ' SB.status FROM (service_bids as SB) '
                                    . 'JOIN courier_service as CS ON CS.id=SB.service_id '
                                    . 'WHERE SB.req_id = ' . $req_id . ' AND SB.courier_id = ' . $courier_id . ' '
                                    . 'ORDER BY updated_on desc LIMIT 0,1', FALSE)
                            ->row();
       }
      
  }
  