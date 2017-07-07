<?php

  class Jobstates_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function addjobtrack($data) {
            if ($this->db->insert('jobstates', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

  }
  