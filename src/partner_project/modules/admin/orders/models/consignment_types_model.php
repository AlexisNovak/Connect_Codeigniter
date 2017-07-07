<?php

  class Consignment_types_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function getList() {
            return $this->db->get("consignment_type")->result();
       }
       public function getType($id){
            return $this->db->where('consignment_type_id',$id)->get("consignment_type")->row();
       }
  }
  