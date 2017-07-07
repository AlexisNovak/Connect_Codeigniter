<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Invitations_model extends CI_Model {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       function addInvite($data) {
            if ($this->db->insert('invitations', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       //function to retieve invitation of a token
       function get_invitation_by_token($token) {
            return $this->db->from('invitations')
                            ->where("token", $token)
                            ->get()
                            ->row();
       }

       //function to retieve invitation of a token and email id
       function get_invitation_by_token_email($token, $email) {
            return $this->db->from('invitations')
                            ->where("token", $token)
                            ->where("email", $email)
                            ->get()
                            ->row();
       }

       //delete invitation

       function delete_invitation($id) {
            return $this->db->where('id', $id)->delete('invitations');
       }

  }
  