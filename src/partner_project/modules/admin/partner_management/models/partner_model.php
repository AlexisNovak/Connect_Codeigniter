<?php

  class Partner_model extends MY_Model {

       function __construct() {
            parent::__construct();
       }

       //add new partner to database
       public function add_partner($data) {
            if ($this->db->insert('partner', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       //update partner details
       public function update_partner($data, $partner_id) {
            $this->db->where('partner_id', $partner_id);
            return $this->db->update('partner', $data);
       }

       //get partner created by provided user_id

       public function get_partner($partner_id, $user_id) {
            return $this->db->select('*')->from('partner')->where('owner', $user_id)->where('partner_id', $partner_id)->get()->row();
       }

       public function getpartnerlist_by_user_id_count($user_id, $search) {
            if ($search != NULL) {
                 $this->db->where('(partner_name LIKE \'%' . $search . '%\' OR shortname LIKE \'%' . $search . '%\')');
            }
            $query = $this->db->select('partner_id')->from('partner')->where('owner', $user_id)->get();
            return $query->num_rows();
       }

       public function getpartnerlist_by_user_id($user_id, $perpage, $search, $start) {
            if ($search != NULL) {
                 $this->db->where('(partner_name LIKE \'%' . $search . '%\' OR shortname LIKE \'%' . $search . '%\')');
            }
            $this->db->limit($perpage, $start);
            return $this->db->select('P.*,M.username,M.email')
                            ->from('partner as P')
                            ->join('member as M', 'M.id=P.partner_user', 'left')
                            ->where('P.owner', $user_id)
                            ->get()
                            ->result();
       }

       //get partner by id

       public function get_by_id($partner_id) {
            return $this->db->select('*')->from('partner')->where('partner_id', $partner_id)->get()->row();
       }

       //get partner by shortname

       public function get_by_shortname($shortname) {
            return $this->db->from('partner')->where('shortname', $shortname)->get()->row();
       }

       public function get_partner_user($partner_user) {
            return $this->db->from('partner')->where('partner_user', $partner_user)->get()->row();
       }

  }
  