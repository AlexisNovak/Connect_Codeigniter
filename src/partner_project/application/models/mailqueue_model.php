<?php

  class Mailqueue_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_mailqueue($data) {
            if ($this->db->insert('mailqueue', $data)) {
                 return TRUE;
            }
            return FALSE;
       }

       public function get_mailqueue() {
            return $this->db->where('status', 1)->get('mailqueue')->result_array();
       }

       public function update_mailqueue($data, $id) {
            $data['modified_on']=date('Y-m-d H:i:s');
            return $this->db->update('mailqueue', $data, array('id' => $id));
       }

  }
  