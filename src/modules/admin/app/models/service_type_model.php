<?php

  class Service_type_model extends CI_Model{
       function __construct() {
            parent::__construct();
       }
       public function type_list(){
            return $this->db->get('service_type')->result();
       }
  }