<?php

  class Member_email_confirmation_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_verification($email, $verification) {
            $data = $this->db->select('*')
                            ->from('member_email_confirmation')
                            ->where('email', $email)
                            ->get()->row();
            if (empty($data)) {
                 return $this->db->insert("member_email_confirmation", array(
                             "email" => $email,
                             "verification_code" => $verification,
                             "sent_time" => mdate('%Y-%m-%d %H:%i:%s', now())
                 ));
            } else {
                 return $this->db->where('email', $email)
                                 ->update("member_email_confirmation", array(
                                     "verification_code" => $verification,
                                     "sent_time" => mdate('%Y-%m-%d %H:%i:%s', now())
                 ));
            }
       }

       public function check_verification($email, $token) {
            return $this->db->select("*")
                            ->from("member_email_confirmation")
                            ->where("email", $email)
                            ->where("verification_code", $token)
                            ->get()->row();
       }
       public function getMemberData($email) {
            return $this->db->select("*")
                            ->from("member")
                            ->where("email", $email)
                            ->get()->row();
       }

       public function update_verification($email) {
            $this->db->where("email", $email);
            return $this->db->update("member_email_confirmation", array("status" => 1));
       }

       public function verifyEmail($email) {
            $this->db->where("email", $email);
            return $this->db->update("member", array("status" => 1, 'verifiedon' => mdate('%Y-%m-%d %H:%i:%s', now())));
       }

  }
  