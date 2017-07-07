<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Groups_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       function add_new_group($data) {
            if ($this->db->insert('groups', $data)) {
                 $id = $this->db->insert_id();
                 return $id;
            }
            return 0;
       }

       function update_groups($group_id, $data) {
            $this->db->where('id', $group_id);
            $res = $this->db->update('groups', $data);
            return $res;
       }

       function suspend_group($where) {
            $this->db->where($where);
            $res = $this->db->update('groups', array("status" => 2));
            return $res;
       }

       function activate_group($where) {
            $this->db->where($where);
            $res = $this->db->update('groups', array("status" => 1));
            return $res;
       }

       function delete_group($group_id) {
            $this->db->where('id', $group_id);
            $res = $this->db->delete('groups');
            return $res;
       }

       function delete_service_groups($group_id, $org_id) {
            $this->db->where(array("org_id" => $org_id, "group_id" => $group_id))->delete("service_groups");
            return $result;
       }

       function getOrg($groupid = 0, $groupname = NULL) {
            $org = 0;
            if (!is_null($groupname)) {
                 $this->db->where('name', $groupname);
            }
            $query = $this->db->select('org_id')
                    ->where('id', $groupid)
                    ->get("groups")
                    ->row();
            return $query ? $query->org_id : $org;
       }

       function getgroupslist_count($org_id, $search) {
            $this->db->select('groups.id');
            $this->db->from('groups');
            $this->db->where('groups.org_id', $org_id);
            if (@$search != '') {
                 $this->db->where('groups.name LIKE \'%' . @$search . '%\'');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getgroupslist_by_orgid($org_id, $perpage, $search, $start) {
            $this->db->select('groups.id,groups.name,groups.description, groups.code,groups.status');
            $this->db->from('groups');
            $this->db->where('groups.org_id', $org_id);
            if (@$search != '') {
                 $this->db->where('groups.name LIKE \'%' . @$search . '%\'');
            }
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function getallgroupslist($str) {
            return $this->db->select('groups.id,groups.name,groups.description, groups.code,groups.status')
                            ->from('groups')
                            ->or_like('groups.name', $str)
                            ->get()->result();
       }

       function get_org_groupids($org_id) {
            return $this->db->select('id')
                            ->from('groups')
                            ->where('groups.org_id', $org_id)
                            ->get()->result();
       }

       function get_all_org_groups_list($str, $org_id) {
            return $this->db->select('groups.id,groups.name,groups.code')
                            ->from('groups')
                            ->like('groups.name', $str)
                            ->where('org_id', $org_id)
                            ->get()->result();
       }

       function getallgroups($str) {
            $result = array();
            $query = $this->db->select('groups.id,groups.name,groups.description, groups.code')
                            ->from('groups')
                            ->or_like('groups.name', $str)
                            ->get()->result_array();
            foreach ($query as $value) {
                 $result[] = $value['name'] . "(" . $value['code'] . ")";
            }
            return $result;
       }

       function get_detail_by_groupid($group_id, $org_id) {
            $this->db->select('groups.id,groups.name,groups.description, groups.code,groups.status,groups.org_id');
            $this->db->from('groups');
            $this->db->where('groups.org_id', $org_id);
            $this->db->where('groups.id', $group_id);

            return $this->db->get()->row();
       }

       function get_all_group_org_id($org_id) {
            $this->db->select("id as group_id,name as group_name");
            $this->db->from('groups');
            $this->db->where('org_id', $org_id);
            return $this->db->get()->result();
       }

       function get_all_members($org_id, $group_id = NULL) {
            if ($group_id != NULL) {
                 $this->db->where('GM.group_id', $group_id);
            }
            return $this->db->select('GM.user_id,U.username')
                            ->from('group_members as GM')
                            ->join('member as U', 'U.id=GM.user_id')
                            ->where('org_id', $org_id)
                            ->get()
                            ->result();
       }

       public function get_assigned_services($group_id, $org_id) {
            $this->db->select('CS.id,CS.display_name,CS.description,CS.service_id');
            $this->db->from('courier_service as CS');
            $this->db->join('service_groups as SG', 'SG.service_id=CS.id');
            $this->db->where('SG.group_id', $group_id);
            $this->db->where('SG.org_id', $org_id);
            return $this->db->get()->result();
       }

       public function get_assigned_members($group_id, $org_id) {
            $this->db->select('M.id as id,M.email as Email,M.username as FullName,'
                    . 'OM.role_id as role,OM.status as Status');
            $this->db->from('group_members as GM');
            $this->db->join('member as M', 'M.id=GM.user_id', 'left');
            $this->db->join('organization_members as OM', 'OM.user_id=GM.user_id', 'left');
            $this->db->where('GM.group_id', $group_id);
            $this->db->where('GM.org_id', $org_id);
            $this->db->group_by('M.id');
            return $this->db->get()->result();
       }

  }
  