<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Organisation_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       //function to verify wether current user has the role of 'admin' for an organisation
       function is_admin($org_id) {
            $this->db->select('*')
                    ->from('organization_members')
                    ->where(array("org_id" => $org_id, "user_id" => $this->session->userdata('user_id'), "role_id" => 1))
                    ->get();
            if ($this->db->count_all_results() == 1) {
                 return true;
            }
            return FALSE;
       }

       function is_exist_org($org_id) {
            $org = $this->db->select("id,name")->from('organizations')->where("id", $org_id)->get()->row();
            return $org ? $org->name : FALSE;
       }

       public function get_admin_members($org_id) {
            $this->db->select('member.username,member.email');
            $this->db->from('organization_members');
            $this->db->join('member', 'member.id=organization_members.user_id', 'left');
            $this->db->where('organization_members.org_id', $org_id);
            $this->db->where('organization_members.role_id', 1);
            return $this->db->get()->result();
       }

       function getorganisationlist_by_user_id_count($user_id, $search) {
            $this->db->select('M.org_id,organizations.id as id,organizations.name as org_name,organizations.shortname as org_shortname,organizations.description as Description,organizations.website as Website');
            $this->db->from('organization_members as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.user_id', $user_id);
            if (@$search != '') {
                 $this->db->where('(organizations.name LIKE \'%' . @$search . '%\' OR  organizations.shortname LIKE \'%' . @$search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getorganisationlist_by_user_id($user_id, $perpage, $search, $start) {

            $this->db->select('M.org_id,M.role_id,organizations.id as id,organizations.name as org_name,'
                    . 'organizations.shortname as org_shortname,organizations.description as Description,'
                    . 'organizations.website as Website,count(CS.id) as scount');
            $this->db->from('organization_members as M');
            $this->db->join('courier_service as CS', 'CS.org_id=M.org_id AND CS.org_status=2', 'left');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.user_id', $user_id);
            if (@$search != '') {
                 $this->db->where('(organizations.name LIKE \'%' . @$search . '%\' OR  organizations.shortname LIKE \'%' . @$search . '%\')');
            }
            $this->db->group_by('M.org_id');
            $this->db->order_by('M.org_id', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function getallorg_count($search) {
            $this->db->select('id');
            $this->db->from('organizations as O');
            if ($search != NULL) {
                 $this->db->where('(O.name LIKE \'%' . $search . '%\' OR O.shortname LIKE \'%' . $search . '%\' OR  O.description LIKE \'%' . $search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getallorg($perpage, $search, $start) {

            $this->db->select('id, name, shortname, hash_code, website, description, avatar, open_bid,'
                    . ' public_tracking, tracking_logo, tracking_intro, use_public_service, allow_api, status');
            $this->db->from('organizations as O');
            if ($search != NULL) {
                 $this->db->where('(O.name LIKE \'%' . $search . '%\' OR O.shortname LIKE \'%' . $search . '%\' OR  O.description LIKE \'%' . $search . '%\')');
            }
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function myorganisations($user_id) {

            $this->db->select('M.org_id,organizations.name as org_name');
            $this->db->from('organization_members as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.user_id', $user_id);

            return $this->db->get()->result();
       }

       function allorganisations($search = NULL, $limit = FALSE) {
            if ($search != NULL) {
                 $this->db->where("O.name LIKE '%$search%'");
            }
            $this->db->select('O.id as org_id,O.name as org_name');
            $this->db->from('organizations as O');
            if ($limit) {
                 $this->db->limit(50);
            }
            return $this->db->get()->result();
       }

       function myorganisations_all($user_id, $root = false) {
            if ($root) {
                 $this->db->select('organizations.id as org_id,organizations.name as org_name');
                 $this->db->from('organizations');
            } else {
                 $this->db->select('M.org_id,organizations.name as org_name');
                 $this->db->from('organization_members as M');
                 $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
                 $this->db->where('M.user_id', $user_id);
            }
            return $this->db->get()->result();
       }

       function admin_organisations($user_id) {
            $this->db->select('M.org_id,organizations.name as org_name');
            $this->db->from('organization_members as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.user_id', $user_id);
            $this->db->where('M.role_id', 1);
            return $this->db->get()->result();
       }

       function request_allowed_organisations($user_id) {
            $this->db->select('M.org_id,O.name as org_name');
            $this->db->from('organization_members as M');
            $this->db->join('organizations as O', 'M.org_id=O.id', 'left');
            $this->db->where('M.user_id', $user_id);
            $this->db->where('M.role_id', 1);
            $this->db->where('O.use_public_service', 1);
            return $this->db->get()->result();
       }

       function getorganisationDetails($org_id) {
            return $this->db->select('*')
                            ->from('organizations')
                            ->where('id', $org_id)
                            ->get()->row();
       }

       function get_last_avatar($org_id) {
            $row = $this->db->select('avatar')
                            ->from('organizations')
                            ->where('id', $org_id)
                            ->get()->row();
            return $row ? $row->avatar : NULL;
       }

       function getName($org_id) {
            return $this->db->select('name')
                            ->from('organizations')
                            ->where('id', $org_id)
                            ->get()->row();
       }

       function get_admin_user_by_org_id($org_id) {
            return $this->db->select('member.username')
                            ->from('organizations')
                            ->join('organization_members', 'organizations.id=organization_members.org_id', 'left')
                            ->join('member', 'member.id=organization_members.user_id', 'left')
                            ->where('organization_members.role_id', 1)
                            ->where('organizations.id', $org_id)
                            ->get()->result();
       }

       function add_new_organisation($data) {
            if ($this->db->insert('organizations', $data)) {
                 $id = $this->db->insert_id();
                 $member = array(
                     'org_id' => $id,
                     'user_id' => $data['user_id'],
                     'note' => "Creater",
                     'role_id' => 1, //admin
                     "is_superadmin" => 1,
                     'status' => "active"
                 );
                 $this->db->insert('organization_members', $member);
                 return $id;
            }
       }

       function edit_organisation($data, $org_id) {
            $this->db->where('id', $org_id);
            return $this->db->update('organizations', $data);
       }

       function edit_dp($data, $org_id) {
            $this->db->where('id', $org_id);
            return $this->db->update('organizations', $data);
       }

       public function is_open_bid($org_id) {
            $row = $this->db->select('open_bid')->from('organizations')->where('id', $org_id)->get()->row();
            if ($row) {
                 return $row->open_bid;
            }
            return 0;
       }

       public function is_allow_api($hash_code) {
            $row = $this->db->select('allow_api')->from('organizations')->where('hash_code', $hash_code)->get()->row();
            if ($row) {
                 return $row->allow_api;
            }
            return 0;
       }

       public function is_unique_hash($hash_code) {
            $query = $this->db->select("hash_code")
                    ->from("organizations")
                    ->where("hash_code", $hash_code)
                    ->get();
            if ($query->num_rows() == 0) {
                 return TRUE;
            }
            return FALSE;
       }

       public function get_id_by_accesskey($hash_code) {
            $query = $this->db->select("id")
                            ->from("organizations")
                            ->where("hash_code", $hash_code)
                            ->get()->row();
            if ($query) {
                 return $query->id;
            }
            return 0;
       }

       public function get_accesskey($org_id) {
            $query = $this->db->select("hash_code")
                            ->from("organizations")
                            ->where("id", $org_id)
                            ->get()->row();
            if ($query) {
                 return $query->hash_code;
            }
            return "";
       }

       public function get_use_public_status($org_id) {
            $query = $this->db->select("use_public_service")
                            ->from("organizations")
                            ->where("id", $org_id)
                            ->get()->row();
            if ($query) {
                 return $query->use_public_service ? TRUE : FALSE;
            }
            return FALSE;
       }

       public function get_api_status($org_id) {
            $query = $this->db->select("allow_api")
                            ->from("organizations")
                            ->where("id", $org_id)
                            ->get()->row();
            if ($query) {
                 return $query->allow_api ? TRUE : FALSE;
            }
            return FALSE;
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

       public function get_service_payment_terms($id) {
            return $this->db->select('payments')
                            ->from('organizations')
                            ->where('id', $id)
                            ->get()
                            ->row();
       }

  }
  