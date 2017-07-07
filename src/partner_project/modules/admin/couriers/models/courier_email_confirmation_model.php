<?php

  class Courier_email_confirmation_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_verification($email, $verification) {

            return $this->db->insert("courier_email_confirmation", array(
                        "email" => $email,
                        "verification_code" => $verification,
                        "sent_time" => mdate('%Y-%m-%d %H:%i:%s', now())
            ));
       }

       public function check_verification($email, $token) {
            return $this->db->select("*")
                            ->from("courier_email_confirmation")
                            ->where("email", $email)
                            ->where("verification_code", $token)
                            ->get()->row();
       }

       public function update_verification($email) {
            $this->db->where("email", $email);
            return $this->db->update("courier_email_confirmation", array("status"=> 1));
       }

  }
  