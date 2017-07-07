<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Log_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       function addLog($data) {
            $this->db->insert("groupactivity_log", $data);
            return $this->db->insert_id();
       }

       function getactivitylist_by_orgid_count($org_id, $search) {
            $this->db->select('G.id,G.remark,G.group_id,L.org_id');
            $this->db->from('groupactivity_log as G');
            $this->db->join('groups as L', "L.id=G.group_id", "left");
            $this->db->where('L.org_id', $org_id);
            if (@$search != '') {
                 $this->db->where('(G.remark LIKE \'%' . @$search . '%\' OR  L.name LIKE \'%' . @$search . '%\')');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getactivitylist_by_orgid($org_id, $perpage, $search, $start) {
            $this->db->select('G.id,G.remark,G.group_id,L.org_id,L.name');
            $this->db->from('groupactivity_log as G');
            $this->db->join('groups as L', "L.id=G.group_id", "left");
            $this->db->where('L.org_id', $org_id);
            if (@$search != '') {
                 $this->db->where('(G.remark LIKE \'%' . @$search . '%\' OR  L.name LIKE \'%' . @$search . '%\')');
            }
            $this->db->order_by("G.date","DESC");
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }
       
       function get_detail_by_id($activity_id){
          $this->db->select('G.id as activity_id,G.group_id,DATE_FORMAT(G.date,"%d-%m-%Y") as date,G.remark,G.update_by,L.name,U.username',FALSE);
            $this->db->from('groupactivity_log as G');
            $this->db->join('groups as L', "L.id=G.group_id", "left");
             $this->db->join('member as U', "U.id=G.update_by", "left");
            $this->db->where('G.id', $activity_id);
            return $this->db->get()->row();
              
       }
    }
  