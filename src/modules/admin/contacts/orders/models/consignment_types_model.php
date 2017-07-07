<?php

class Consignment_types_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getList() {
        return $this->db->get("consignment_type")->result();
    }

    public function getType($id) {
        return $this->db->where('consignment_type_id', $id)->get("consignment_type")->row();
    }

    function myorganisation($user_id) {
        $this->db->select('M.org_id');
        $this->db->from('organization_members as M');
        $this->db->join('courier_service as CS', 'CS.org_id=M.org_id AND CS.org_status=2', 'left');
        $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
        $this->db->where('M.user_id', $user_id);
        $this->db->group_by('M.org_id');
        $data = $this->db->get()->result();
        $ret = array();
        foreach ($data AS $a) {
            $ret[] = $a->org_id;
        }
        return $ret;
    }

    function getMyTypeList($organisations) {
        $data = $this->db->where('org_id', NULL)
                        ->get("consignment_type")->result();
        $data1 = $this->db->where_in('org_id', $organisations)
                        ->get("consignment_type")->result();
        return array_merge($data1, $data);
    }

}

