<?php

  class Request_courier_service_model extends MY_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_request($data) {
            if ($this->db->insert('request_courier_service', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function is_available_to_request($service_id) {
            $service = $this->db->select('is_public')
                    ->from('courier_service')
                    ->where('id', $service_id)
                    ->get()
                    ->row();
            if ($service) {
                 return $service->is_public;
            }
            return FALSE;
       }

       public function is_auto_approve($service_id) {
            $service = $this->db->select('auto_approve')
                    ->from('courier_service')
                    ->where('id', $service_id)
                    ->get()
                    ->row();
            if ($service) {
                 return $service->auto_approve;
            }
            return FALSE;
       }

       public function approve_service($request_id, $service_id, $org_id, $c_id) {
            $this->load->model("couriers/courier_service_model");
            $service = $this->db->where('CS.id', $service_id)
                    ->get('courier_service as CS')
                    ->row();
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
                'threshold_price' => round($service->price + ($service->price * (10 / 100)), 2),
                'is_for_bidding' => 0,
                'status' => 1,
                'org_status' => 2,
                'courier_id' => $service->courier_id,
                'auto_approve' => 0,
                'is_public' => 0,
                'payment_terms' => $service->payment_terms,
                'derived_from' => $service_id,
                'is_new'=>1
            );
            $accept_query = "UPDATE request_courier_service as RCS SET RCS.status=2,RCS.updated_on='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "' WHERE  RCS.request_id=" . $request_id . " AND RCS.courier_id=" . $c_id;
            $reject_query = "UPDATE request_courier_service as RCS SET RCS.status=0,RCS.updated_on='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "' WHERE RCS.status=1 AND RCS.service_id=" . $service_id . " AND RCS.courier_id=" . $c_id;
            // $update_courier_service_query = "UPDATE courier_service as CS SET CS.is_for_bidding=0,CS.is_public=0,CS.org_status=2, CS.org_id=" . $org_id . " WHERE CS.id=" . $service_id . " AND CS.courier_id=" . $c_id;
            $update_courier_service_query = "UPDATE courier_service as CS SET CS.is_for_bidding=0,CS.org_status=2, CS.org_id=" . $org_id . " WHERE CS.id=" . $service_id . " AND CS.courier_id=" . $c_id;
          //  $update_query = "UPDATE courier_service SET is_new=0 WHERE id= $service_id";
            $this->db->trans_begin();
            $this->db->query($accept_query);
           // $this->db->query($update_query);
            $this->db->insert('courier_service', $new_service);
            $new_service_id = $this->db->insert_id();
            $this->db->query("INSERT INTO service_parcel_price (service_id, type, price, max_volume, volume_cost, max_weight, weight_cost) "
                    . "SELECT $new_service_id as service_id, type, price, max_volume, volume_cost, max_weight, weight_cost FROM service_parcel_price WHERE service_id = $service_id");
            if ($this->db->trans_status() === FALSE) {
                 $this->db->trans_rollback();
            } else {
                 $this->db->trans_commit();
            }

            return TRUE;
       }

       public function get_request($request_id) {
            return $this->db->where('request_id', $request_id)
                            ->get('request_courier_service')
                            ->row();
       }

       public function get_courier($courier_id) {
            return $this->db->select('C.courier_id,C.email,C.company_name as name')
                            ->from('couriers as C')
                            ->where('C.courier_id', $courier_id)
                            ->get()
                            ->row();
       }

       public function reject_request($request_id, $remarks, $c_id) {
            $reject_query = "UPDATE request_courier_service as RCS SET RCS.status=0, RCS.remarks='" . $remarks . "',RCS.updated_on='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "' WHERE RCS.request_id=" . $request_id . " AND RCS.courier_id=" . $c_id;

            return $this->db->query($reject_query);
       }

       public function get_requests($courier_id, $search) {
            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('RCS.request_id, CS.id as s_id,CS.display_name as service, ' . ' CS.service_id, O.name as organisation')
                            ->from('request_courier_service as RCS')
                            ->join('courier_service as CS', 'CS.id=RCS.service_id')
                            ->join('couriers as C', 'C.courier_id=CS.courier_id')
                            ->join('organizations as O', 'O.id=RCS.org_id')
                            ->where('CS.is_public', 1)
                            ->where('RCS.courier_id', $courier_id)
                            ->where('RCS.status', 1)
                            ->get()
                            ->result();
       }

       public function get_requests_for_courier($courier_id, $service_id, $search) {
            if ($search != NULL) {
                 $this->db->where('(CS.display_name LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('RCS.request_id, CS.id as s_id,CS.display_name as service,RCS.status, ' . '  O.name as organisation')
                            ->from('request_courier_service as RCS')
                            ->join('courier_service as CS', 'CS.id=RCS.service_id')
                            ->join('organizations as O', 'O.id=RCS.org_id')
                            ->where('RCS.service_id', $service_id)
                            ->where('RCS.courier_id', $courier_id)
                            ->get()
                            ->result();
       }

  }
  