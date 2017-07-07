<?php

class Partner_members_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_member($data) {
        return $this->db->insert('partner_members', $data);
    }

    public function is_this_partner_member($user_id) {
        $partner = $this->session->userdata('partner_id') ? $this->session->userdata('partner_id') : 0;

        $row = $this->db->select('*')->from('partner_members')->where('partner_id', $partner)->where('member_id', $user_id)->get()->row();
        if ($row) {
            return true;
        }
        return false;
    }

}
