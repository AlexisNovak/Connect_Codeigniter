<?php

  class Mailqueue_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_mailqueue($data) {
            if ($this->db->insert('mailqueue', $data)) {
                 return TRUE;
            }
            return FALSE;
       }

       public function get_mailqueue() {
            return $this->db->where('status', 1)->get('mailqueue')->result_array();
       }

       public function update_mailqueue($data, $id) {
            $data['modified_on'] = date('Y-m-d H:i:s');
            return $this->db->update('mailqueue', $data, array('id' => $id));
       }

       public function get_notdelivered() {
            $dataresult = $this->db->select('cr.courier_id,cr.email,cr.fullname')
                            ->from('consignments AS c')
                            ->join('bid_consignment_relation AS bcr', 'bcr.consignment_id=c.consignment_id')
                            ->join('bids AS b', 'b.bid_id=bcr.bid_id')
                            ->join('couriers AS cr', 'b.courier_id=cr.courier_id')
                            ->where_in('c.consignment_type_id', array(1, 8))
                            ->group_by('cr.courier_id')
                            ->order_by('c.consignment_type_id')
                            ->get()->result();
            $returnArray = array();
            foreach ($dataresult as $value) {
                 $count1=$this->getconsignment_count($value->courier_id,1);
                 $count8=$this->getconsignment_count($value->courier_id,8);

                 $data['to'] = $value->email;
                 $data['name'] = $value->fullname;
                 $data['subject'] = "[6Connect] Orders Summary Report";
                 $data['cc'] = '';
                 $data['bcc'] = '';
                 $data['attachment'] = NULL;
                 $data['message'] = json_encode(
                         array(
                             'title' => $data['subject'],
                             'name' => ($data['name'] != '') ? $data['name'] : $data['to'],
                             'content' => "Below is a summary of your current deliveries. Please remember to update the status of the deliveries regularly for the customers to know.<br><br>
                             No of deliveries in-progress : <a href=" . site_url('couriers/dashboard#/assigned_orders') . ">" . $count1 . "</a><br>
                             No of jobs pending acceptance : <a href=" . site_url('couriers/dashboard#/assigned_orders') . ">" . $count8 . " </a><br><br>
                             ",
                             'link_title' => '',
                             'link' => ''
                         )
                 );
                 $returnArray[] = $data;
            }
            return $returnArray;
       }

       function getconsignment_count($courier_id, $status = '') {
            $service = "";
            $search = NULL;
            $org = null;
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
            $this->db->where('C.consignment_status_id', $status);
            $this->db->order_by('C.created_date', 'DESC');
            return $this->db->get()->num_rows();
       }

  }
  