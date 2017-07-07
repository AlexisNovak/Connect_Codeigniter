<?php

  class Mass_consignment_info_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_info($id, $data) {
            $this->db->where('user_id', $id);
            $q = $this->db->get('mass_consignment_info');

            if ($q->num_rows() > 0) {
                 $this->db->where('user_id', $id);
                 $this->db->update('mass_consignment_info', $data);
            } else {
                 $this->db->set('user_id', $id);
                 $this->db->insert('mass_consignment_info', $data);
            }
            return TRUE;
       }

       public function get_info($user) {
            return $this->db->where("user_id", $user)->get('mass_consignment_info')->row();
       }

       public function delete_info($user) {
            return $this->db->where("user_id", $user)->delete('mass_consignment_info');
       }

  }
  