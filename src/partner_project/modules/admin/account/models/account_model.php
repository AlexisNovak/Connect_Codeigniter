<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Account_model extends CI_Model {

       /**
        * Get all accounts
        *
        * @access public
        * @return object all accounts
        */
       function get() {
            return $this->db->get('member')->result();
       }

       /**
        * Get account by id
        *
        * @access public
        * @param string $user_id
        * @return object account object
        */
       function get_by_id($user_id) {
            return $this->db->get_where('member', array('id' => $user_id))->row();
       }

       // --------------------------------------------------------------------

       /**
        * Get account by username
        *
        * @access public
        * @param string $username
        * @return object account object
        */
       function get_by_username($username) {
            return $this->db->get_where('member', array('username' => $username))->row();
       }

       // --------------------------------------------------------------------

       /**
        * Get account by email
        *
        * @access public
        * @param string $email
        * @return object account object
        */
       function get_by_email($email) {
            return $this->db->get_where('member', array('email' => $email))->row();
       }

       // --------------------------------------------------------------------

       /**
        * Get account by username or email
        *
        * @access public
        * @param string $username_email
        * @return object account object
        */
       function get_by_username_email($username_email) {
            return $this->db->from('member')->where('username', $username_email)->or_where('email', $username_email)->get()->row();
       }

       // --------------------------------------------------------------------

       /**
        * Create an account
        *
        * @access public
        * @param string $username
        * @param string $hashed_password
        * @return int insert id
        */
       function create($username, $email = NULL, $password = NULL) {
            // Create password hash using phpass
            if ($password !== NULL) {
                 $this->load->helper('account/phpass');
                 $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                 $hashed_password = $hasher->HashPassword($password);
            }

            $this->load->helper('date');
            $this->db->insert('member', array('username' => $username, 'email' => $email, 'password' => isset($hashed_password) ? $hashed_password : NULL, 'language' => 'english', 'createdon' => mdate('%Y-%m-%d %H:%i:%s', now())));
            return $this->db->insert_id();
       }

       // --------------------------------------------------------------------

       /**
        * Change account username
        *
        * @access public
        * @param int $user_id
        * @param int $new_username
        * @return void
        */
       function update_username($user_id, $new_username) {
            $this->db->update('member', array('username' => $new_username), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Change account email
        *
        * @access public
        * @param int $user_id
        * @param int $new_email
        * @return void
        */
       function update_email($user_id, $new_email) {
            $this->db->update('member', array('email' => $new_email), array('id' => $user_id));
       }

       function update_language($user_id, $new_language) {
            return $this->db->update('member', array('language' => $new_language), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Change account password
        *
        * @access public
        * @param int $user_id
        * @param int $hashed_password
        * @return void
        */
       function update_password($user_id, $password_new) {
            $this->load->helper('account/phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $new_hashed_password = $hasher->HashPassword($password_new);

            $this->db->update('member', array('password' => $new_hashed_password), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Update account last signed in dateime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function update_last_signed_in_datetime($user_id) {
            $this->load->helper('date');

            $this->db->update('member', array('lastsignedinon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Update password reset sent datetime
        *
        * @access public
        * @param int $user_id
        * @return int password reset time
        */
       function update_reset_sent_datetime($user_id) {
            $this->load->helper('date');

            $resetsenton = mdate('%Y-%m-%d %H:%i:%s', now());

            $this->db->update('member', array('resetsenton' => $resetsenton), array('id' => $user_id));

            return strtotime($resetsenton);
       }

       /**
        * Remove password reset datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function remove_reset_sent_datetime($user_id) {
            $this->db->update('member', array('resetsenton' => NULL), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Update account deleted datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function update_deleted_datetime($user_id) {
            $this->load->helper('date');

            $this->db->update('member', array('deletedon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $user_id));
       }

       /**
        * Remove account deleted datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function remove_deleted_datetime($user_id) {
            $this->db->update('member', array('deletedon' => NULL), array('id' => $user_id));
       }

       // --------------------------------------------------------------------

       /**
        * Update account suspended datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function update_suspended_datetime($user_id) {
            $this->load->helper('date');

            $this->db->update('member', array('suspendedon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $user_id));
       }

       /**
        * Remove account suspended datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function remove_suspended_datetime($user_id) {
            $this->db->update('member', array('suspendedon' => NULL), array('id' => $user_id));
       }

       function is_exist_user($username) {
            $user = $this->db->select("id")->from('member')->where("username", $username)->get()->row();
            return $user ? TRUE : FALSE;
       }

       /*
        * Check whether user is a partner
        * 
        */

       public function is_partner($user_id) {
            $user = $this->db->select("root")->from('member')->where("id", $user_id)->get()->row();
            if ($user && $user->root == 1) {
                 return TRUE;
            }
            return FALSE;
       }

       function update($user_id, $data) {
            $this->db->update('member', $data, array('id' => $user_id));
       }

  }

  /* End of file account_model.php */
/* Location: ./application/account/models/account_model.php */