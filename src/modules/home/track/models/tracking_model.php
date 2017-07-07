<?php

  class Tracking_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_orderslist($list, $org=NULL) {
            $this->db->select('C.consignment_id,C.public_id,O.name as org_name,'
                    . 'S.display_name as service,C.consignment_status_id,C.collection_address,C.is_c_restricted_area as crestrict,'
                    . 'F.country as from_country,T.country as to_country,'
                    . 'C.delivery_address,C.collection_contact_name,C.collection_contact_number,'
                    . 'C.delivery_contact_name,C.is_d_restricted_area as drestrict,'
                    . 'C.delivery_contact_phone,CS.display_name as status');
            $this->db->from('consignments as C');
            $this->db->join('organizations as O', 'O.id=C.org_id', 'left');
            $this->db->join('member as U', 'U.id=C.created_user_id');
            $this->db->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id');
            $this->db->join('courier_service as S', "S.id=C.service_id", 'left');
            $this->db->join('country as F', 'F.code=C.collection_country', 'left');
            $this->db->join('country as T', 'T.code=C.delivery_country', 'left');
            $this->db->where('C.is_deleted', 0);
//            $this->db->where('C.org_id', $org);
            $this->db->where("C.public_id IN ($list)");
            return $this->db->get()->result();
       }

       public function get_org($id) {
            if ($id != NULL) {
                 $this->db->select('O.name,O.description,O.tracking_intro,O.tracking_logo');
                 $this->db->from('organizations as O');
                 $this->db->where('O.id', $id);
                 return $this->db->get()->row();
            }
            return NULL;
       }

       public function get_tracking_status($org_id) {
            $query = $this->db->select("public_tracking")
                            ->from("organizations")
                            ->where("id", $org_id)
                            ->get()->row();
            if ($query) {
                 return $query->public_tracking ? TRUE : FALSE;
            }
            return FALSE;
       }

  }
  