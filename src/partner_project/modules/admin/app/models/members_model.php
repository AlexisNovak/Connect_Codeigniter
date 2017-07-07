<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Members_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
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

       function is_admin($org_id) {
            $user = $this->db->select('root')->get_where('member', array('id' => $this->session->userdata("partner_user_id")))->row();
            if ($user->root) {
                 return TRUE;
            } else {
                 $query = $this->db->where(array("org_id" => $org_id, "role_id" => 1, "user_id" => $this->session->userdata("partner_user_id")))
                         ->from('organization_members')
                         ->get();
                 if ($query->num_rows() === 1) {
                      return TRUE;
                 }
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
            $query = $this->db->where(array("org_id" => $org_id, "role_id" => 2, "user_id" => $this->session->userdata("partner_user_id")))
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

       function addMember($data) {
            return $this->db->insert('organization_members', $data);
       }

//function to verify new member id not added before
       function is_member_exist($orgid, $user_id) {
            $query = $this->db->from('organization_members')->where(array('org_id' => $orgid, "user_id" => $user_id))->get();
            if ($query->num_rows() == 1)
                 return TRUE;
            return FALSE;
       }

       function getmemberslist_count($org_id, $search) {
            $this->db->select('organization_members.note');
            $this->db->from('organization_members');
            $this->db->join('member', 'member.id=organization_members.user_id', 'left');
            $this->db->join('member_details', 'member.id=member_details.user_id', 'left');
            $this->db->where('organization_members.org_id', $org_id);
            if (@$search != '') {
                 $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getmemberslist_by_orgid($org_id, $perpage, $search, $start) {
            $this->db->select('member.id as id,member.email as Email,member_details.fullname as FullName,organization_members.org_id,organization_members.note as Note,organization_members.role_id as role,organization_members.status as Status,groups.name as `groupname`,groups.id as `group`');
            $this->db->from('organizations');
            $this->db->join('organization_members', 'organizations.id=organization_members.org_id', 'left');
            $this->db->join('member', 'member.id=organization_members.user_id', 'left');
            $this->db->join('member_details', 'member.id=member_details.user_id', 'left');
            $this->db->join('group_members', 'group_members.user_id=member.id AND group_members.org_id=organization_members.org_id', 'left');
            $this->db->join('groups', 'group_members.group_id=groups.id', 'left');
            $this->db->limit($perpage, $start);

            if (@$search != '') {
                 $this->db->where('(member_details.fullname LIKE \'%' . @$search . '%\' OR  member.email LIKE \'%' . @$search . '%\' OR  member.username LIKE \'%' . @$search . '%\')');
            }
            $this->db->where('organizations.id', $org_id);

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
            $this->db->select('member.email,member_details.*,organization_members.*,country.country,groups.name as `groupname`,groups.id as `group`', FALSE);
            $this->db->from('organization_members');
            $this->db->join('member', 'organization_members.user_id=member.id', 'left');
            $this->db->join('member_details', 'member.id=member_details.user_id', 'left');
            $this->db->join('group_members', 'group_members.user_id=member.id AND group_members.org_id=organization_members.org_id', 'left');
            $this->db->join('groups', 'group_members.group_id=groups.id', 'left');
            $this->db->join('country', 'country.code=member_details.country', 'left');
            $this->db->where('organization_members.org_id', $org_id);
            $this->db->where('organization_members.user_id', $user_id);

            return $this->db->get()->result();
       }

       function update_member($org_id, $user_id, $data) {
            $this->db->where('org_id', $org_id);
            $this->db->where('user_id', $user_id);
            $res = $this->db->update('organization_members', $data);
            return $res;
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

       function delete_members($user_id, $org_id) {
            $res = $this->db->where(array('org_id' => $org_id, 'user_id' => $user_id, 'is_superadmin' => 0))
                    ->delete('organization_members');
            $result = $res;
            $this->db->where(array("org_id" => $org_id, "member_id" => $user_id))->delete("service_members");
            return $result;
       }

       public function get_root_admin() {
            return $this->db->select('username as name,email')->from('member')->where('root', 1)->get()->result();
       }

  }
  