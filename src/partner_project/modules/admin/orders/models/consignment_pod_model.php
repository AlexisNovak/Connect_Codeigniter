<?php

class Consignment_pod_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_pods($consignment_id) {
        return $this->db->select('P.pod_id,P.pod_image_url, P.is_signature, P.pod_remarks')->from('consignment_pod as P')->where('P.consignment_id', $consignment_id)->get()->result();
    }

    public function add_signature($consignment_id, $attributes) {
        // Update
        if ($this->get_signature_by_consignment_id($consignment_id)) {
            $attributes['update_on'] = date('Y-m-d H:i:s', now());
            $this->db->where('consignment_id', $consignment_id);
            $this->db->where('is_signature', 1);
            $this->db->update('consignment_pod', $attributes);
        }
        // Insert
        else {
            $attributes['consignment_id'] = $consignment_id;
            $this->db->insert('consignment_pod', $attributes);
        }
        return;
    }

    public function add_pod($consignment_id, $attributes) {
        // insert
        if ($this->get_count_by_consignment_id($consignment_id) < 3) {
            $attributes['consignment_id'] = $consignment_id;
            $this->db->insert('consignment_pod', $attributes);
        }
        return;
    }

    public function get_signature_by_consignment_id($consignment_id) {
        return $this->db->select('P.pod_id')->from('consignment_pod as P')->where('P.consignment_id', $consignment_id)->where('P.is_signature', 1)->get()->row();
    }

    public function get_count_by_consignment_id($consignment_id) {
        return $this->db->select('P.pod_id')->from('consignment_pod as P')->where('P.consignment_id', $consignment_id)->where('P.is_signature', 0)->get()->num_rows();
    }

}
