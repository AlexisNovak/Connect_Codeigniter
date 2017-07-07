<?php

  class Rating_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function index() {
            
       }

       function rateData($email, $public_id, $value, $reason) {
            $data = $this->db->select('CR.courier_id AS courier_id,M.id AS user_id,C.service_id')
                    ->from('consignments AS C')
                    ->join('member AS M', 'M.id=C.customer_id')
                    ->join('courier_service AS CS', 'CS.id=C.service_id')
                    ->join('couriers AS CR', 'CS.courier_id=CR.courier_id')
                    ->where('M.email', $email)
                    ->where('C.public_id', $public_id)
                    ->get()
                    ->row();
            if (empty($data)) {
                 return FALSE;
            }
            $rating = $this->db->select('*')
                    ->from('courier_rating')
                    ->where('courier_id', $data->courier_id)
                    ->where('user_id', $data->user_id)
                    ->where('service_id', $data->service_id)
                    ->get()
                    ->row();
            $dataarray = array(
                'courier_id' => $data->courier_id,
                'user_id' => $data->user_id,
                'service_id' => $data->service_id,
                'score' => $value,
                'review' => $reason
            );
            if (empty($rating)) {
                 return $this->db->insert('courier_rating', $dataarray);
            } else {
                 $this->db->where('courier_id', $data->courier_id)
                         ->where('user_id', $data->user_id);
                 return $this->db->update('courier_rating', $dataarray);
            }
       }

  }
  