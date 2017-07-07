<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Account_details_model extends CI_Model {

       /**
        * Get details for all account
        *
        * @access public
        * @return object details for all accounts
        */
       function get() {
            return $this->db->get('member_details')->result();
       }

       /**
        * Get account details by user_id
        *
        * @access public
        * @param string $user_id
        * @return object account details object
        */
       function get_by_user_id($user_id) {
            return $this->db->get_where('member_details', array('user_id' => $user_id))->row();
       }
       
 function get_name_by_user_id($user_id) {
            return $this->db->select('M.username,D.fullname')->from('member as M')->join('member_details as D','D.user_id=M.id')->where('M.id',  $user_id)->get()->row();
       }
       // --------------------------------------------------------------------

       /**
        * Update account details
        *
        * @access public
        * @param int   $user_id
        * @param array $attributes
        * @return void
        */
       function update($user_id, $attributes = array()) {
            if (isset($attributes['fullname']))
                 if (strlen($attributes['fullname']) > 160)
                      $attributes['fullname'] = substr($attributes['fullname'], 0, 160);
           
            
            
            // Check that it's a recognized country (see http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
            if (isset($attributes['country'])) {
                 $this->load->model('account/ref_country_model');
                 $country = $this->ref_country_model->get($attributes['country']);
                 $country ? $attributes['country'] = $country->code : NULL;
            }
           
           
            // Try to guess timezone based on country
            elseif (isset($attributes['country'])) {
                 $this->load->model('account/ref_zoneinfo_model');
                 $result = $this->ref_zoneinfo_model->get_by_country($attributes['country']);
                 if (isset($result[0]))
                      $attributes['timezone'] = $result[0]->zoneinfo;
            }
            // At this point, if country is still not determined, use ip address to determine country
            if (!isset($attributes['country'])) {
                 $this->load->model('account/ref_iptocountry_model');
                 if ($country = $this->ref_iptocountry_model->get_by_ip($this->input->ip_address())) {
                      $attributes['country'] = $country;

                    
                    
                 }
            }

            // Update
            if ($this->get_by_user_id($user_id)) {
                 $this->db->where('user_id', $user_id);
                 $this->db->update('member_details', $attributes);
            }
            // Insert
            else {
                 $attributes['user_id'] = $user_id;
                 $this->db->insert('member_details', $attributes);
            }
       }

       //function to get current profile picture src value
       public function getDp() {
            $query = $this->db->select('picture')
                            ->where('user_id', $this->session->userdata('partner_user_id'))
                            ->get('member_details')->row();
            return $query->picture;
       }

  }

  /* End of file account_details_model.php */
/* Location: ./application/account/models/account_details_model.php */