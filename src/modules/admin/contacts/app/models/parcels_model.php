<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parcels_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function is_superadmin($org_id, $member_id) {
        $query = $this->db->where(array("org_id" => $org_id, "role_id" => 1, "is_superadmin" => 1, "user_id" => $member_id))
                ->from('organization_members')
                ->get();
        if ($query->num_rows() === 1) {
            return TRUE;
        }
        return FALSE;
    }

    function is_rootadmin($user_id) {
        $query = $this->db->where('root', 1)
                ->where('id', $user_id)
                ->from('member')
                ->get();
        if ($query->num_rows() === 1) {
            return TRUE;
        }
        return FALSE;
    }

    function is_admin($org_id) {
        $user = $this->db->select('root')->get_where('member', array('id' => $this->session->userdata("user_id")))->row();
        if ($user->root) {
            return TRUE;
        } else {
            $query = $this->db->where(array("org_id" => $org_id, "role_id" => 1, "user_id" => $this->session->userdata("user_id")))
                    ->from('organization_members')
                    ->get();
            if ($query->num_rows() === 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function is_orgadmin($org_id, $mem_id) {
        $query = $this->db->where(array("org_id" => $org_id, "role_id" => 1, "user_id" => $mem_id))
                ->from('organization_members')
                ->get();
        if ($query->num_rows() === 1) {
            return TRUE;
        }
        return FALSE;
    }

    function is_orguser($org_id, $mem_id) {
        $query = $this->db->where(array("org_id" => $org_id, "user_id" => $mem_id))
                ->from('organization_members')
                ->get();
        if ($query->num_rows() === 1) {
            return TRUE;
        }
        return FALSE;
    }

    function is_member($org_id) {
        $query = $this->db->where(array("org_id" => $org_id, "role_id" => 2, "user_id" => $this->session->userdata("user_id")))
                ->from('organization_members')
                ->get();
        if ($query->num_rows() === 1) {
            return TRUE;
        }
        return FALSE;
    }

    function is_self($org_id, $member_id, $role_id) {
        $query = $this->db->select('role_id')
                        ->where(array("org_id" => $org_id, "user_id" => $member_id))
                        ->from('organization_members')
                        ->get()->row();
        if ($query->role_id !== $role_id) {
            return TRUE;
        }
        return FALSE;
    }

    ///add new member to an organisation

    function addParcelType($data) {
        return $this->db->insert('consignment_type', $data);
    }

//function to verify new member id not added before
    function is_member_exist($orgid, $user_id) {
        $query = $this->db->from('organization_members')->where(array('org_id' => $orgid, "user_id" => $user_id))->get();
        if ($query->num_rows() == 1)
            return TRUE;
        return FALSE;
    }

    function getmemberslist_count($org_id, $search) {
        $this->db->select('consignment_type.consignment_type_id');
        $this->db->from('consignment_type');
        $this->db->where('consignment_type.org_id', $org_id);
        if (@$search != '') {
            $this->db->where('(consignment_type.display_name LIKE \'%' . @$search . '%\')');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getmemberslist_by_orgid($org_id, $perpage, $search, $start) {
        $this->db->select('*');
        $this->db->from('consignment_type');
        if (@$search != '') {
            $this->db->where('(consignment_type.display_name LIKE \'%' . @$search . '%\')');
        }
        $this->db->limit($perpage, $start);
        if (@$search != '') {
            $this->db->where('(consignment_type.display_name LIKE \'%' . @$search . '%\')');
        }
        $this->db->where('consignment_type.org_id', $org_id);

        return $this->db->get()->result();
    }

    function getallmemberslist($str, $org_id) {
        if ($org_id != NULL) {
            $this->db->where('M.org_id', $org_id);
        }
        return $this->db->select('member.id as Userid,member.username as Username,member.email as Email,member.username as FullName,M.role_id')
                        ->from('member')
                        ->join('organization_members as M', "member.id=M.user_id", 'left')
                        ->where('(member.email LIKE \'%' . $str . '%\' OR member.username LIKE \'%' . $str . '%\')')
                        ->group_by('member.id')
                        ->get()
                        ->result();
    }

    function getallorgmemberslist($search, $org_id) {
        $this->db->select('member.id as Userid,member.username as Username,member.email as Email,member_details.fullname as FullName,organization_members.org_id');
        $this->db->from('organizations');
        $this->db->join('organization_members', 'organizations.id=organization_members.org_id', 'left');
        $this->db->join('member', 'member.id=organization_members.user_id', 'left');
        $this->db->join('member_details', 'member.id=member_details.user_id', 'left');
        $this->db->join('group_members', 'group_members.user_id=member.id AND group_members.org_id=organization_members.org_id', 'left');
        $this->db->join('groups', 'group_members.group_id=groups.id', 'left');
        if (@$search != '') {
            $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\' OR  member.username LIKE \'%' . @$search . '%\')');
        }
        $this->db->where('organizations.id', $org_id);

        return $this->db->get()->result();
    }

    function getallorgmemberslistwithgroup($search, $org_id) {
        $this->db->select('member.id as Userid,member.username as Username,member.email as Email,member_details.fullname as FullName,organization_members.org_id,groups.name as groupname');
        $this->db->from('organizations');
        $this->db->join('organization_members', 'organizations.id=organization_members.org_id', 'left');
        $this->db->join('member', 'member.id=organization_members.user_id', 'left');
        $this->db->join('member_details', 'member.id=member_details.user_id', 'left');
        $this->db->join('group_members', 'group_members.user_id=member.id AND group_members.org_id=organization_members.org_id', 'left');
        $this->db->join('groups', 'group_members.group_id=groups.id', 'left');
        if (@$search != '') {
            $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\' OR  member.username LIKE \'%' . @$search . '%\')');
        }
        $this->db->where('organizations.id', $org_id);

        return $this->db->get()->result();
    }

    function getallmembers($str) {
        $result = array();
        $query = $this->db->select('member.id as Userid,member.email as Email,member_details.fullname as FullName')
                        ->from('member')
                        ->join('member_details', 'member.id=member_details.user_id', 'left')
                        ->like('member_details.fullname', $str)
                        ->or_like('member.email', $str)
                        ->get()->result_array();
        foreach ($query as $value) {
            $result[] = $value['FullName'] . "(" . $value['Email'] . ")";
        }
        return $result;
    }

    function get_detail_by_userid($user_id, $org_id) {
        $this->db->select('*', FALSE);
        $this->db->from('consignment_type');
        $this->db->where('consignment_type.org_id', $org_id);
        $this->db->where('consignment_type.consignment_type_id', $user_id);

        return $this->db->get()->result();
    }

    function update_parcel($org_id, $ct_id, $data) {
        $this->db->where('consignment_type_id', $ct_id);
        $this->db->where('org_id', $org_id);
        $res = $this->db->update('consignment_type', $data);
        return $res;
    }

    function update_org_member($user_id) {
        $data = array(
            'status' => 'active');
        $this->db->where('user_id', $user_id);
        return $this->db->update('organization_members', $data);
    }

    function member_group_check($org_id, $user_id) {
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('org_id', $org_id);
        $this->db->where('user_id', $user_id);
        return $this->db->get()->result();
    }

    function update_member_group($org_id, $user_id, $data) {

        $this->db->where('org_id', $org_id);
        $this->db->where('user_id', $user_id);
        $res = $this->db->update('group_members', $data);
        return $res;
    }

    function add_member_group($data) {

        $res = $this->db->insert('group_members', $data);
        return $res;
    }

    function delete_ptype($ctid, $org_id) {
        return $this->db->where(array('org_id' => $org_id, 'consignment_type_id' => $ctid))
                ->delete('consignment_type');
    }

    public function get_root_admin() {
        return $this->db->select('id,username as name,email')->from('member')->where('root', 1)->get()->result();
    }

    public function get_member_admin($user_id) {
        return $this->db->select('id,username as name,email')->from('member')->where('id', $user_id)->get()->result();
    }

    public function getallmemberaccounts_count($search) {
        if ($search != NULL) {
            $this->db->where('(email LIKE \'%' . $search . '%\' OR description LIKE \'%' . $search . '%\' OR fullname LIKE \'%' . $search . '%\')');
        }
        return $this->db->select('id')
                        ->from('member as M')
                        ->join('member_details as MD', 'M.id=MD.user_id')
                        ->get()
                        ->num_rows();
    }

    public function getallmemberaccounts($perpage, $search, $start) {
        if ($search != NULL) {
            $this->db->where('(email LIKE \'%' . $search . '%\' OR description LIKE \'%' . $search . '%\' OR fullname LIKE \'%' . $search . '%\')');
        }
        return $this->db->select('id, email, root, status, language,fullname, '
                                . 'gender, C.country, picture, phone_no, fax_no, description')
                        ->from('member as M')
                        ->join('member_details as MD', 'M.id=MD.user_id')
                        ->join('country as C', 'C.code=MD.country', 'left')
                        ->limit($perpage, $start)
                        ->get()
                        ->result();
    }

    public function get_member_details($id = 0) {
        if ($search != NULL) {
            $this->db->where('(email LIKE \'%' . $search . '%\' OR description LIKE \'%' . $search . '%\' fullname LIKE \'%' . $search . '%\')');
        }
        return $this->db->select('id, email, root, status, language,fullname, '
                                . 'gender,country, picture, phone_no, fax_no, description')
                        ->from('member as M')
                        ->join('member_details as MD', 'M.id=MD.user_id')
                        ->where('M.id', $id)
                        ->get()
                        ->row();
    }

}

