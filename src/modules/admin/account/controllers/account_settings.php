<?php

  /*
   * Account_settings Controller
   */

  class Account_settings extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();
            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('account/account_model', 'account/account_details_model', 'account/ref_country_model', 'account/ref_language_model', 'account/ref_zoneinfo_model'));
       }

       /**
        * Account settings
        */
       function index() {
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if (!$this->authentication->is_signed_in()) {
                 redirect('account/sign_in/?continue=' . urlencode(base_url() . 'account/account_settings'));
            }

            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));
            // Retrieve countries, languages and timezones
            $data['countries'] = $this->ref_country_model->get_all();
            $data['languages'] = $this->config->item("languages");
            // Split date of birth into month, day and year
            $this->load->view('account/account_settings', $data);
       }

       /*
        *  Retrieve sign in user details 
        * 
        */

       function getAccountDetails() {
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if ($this->authentication->is_signed_in()) {
                 $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
                 $account_details = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));

                 //values to auto-populate account settings form
                 $user = new stdClass();
                 // $user->settings_email = $account->email;
                 // $user->settings_fullname = $account_details->fullname;
                 $user->settings_language = $account->language ? $account->language : "english";
                 $user->settings_phone = $account_details->phone_no;
                 $user->settings_fax = $account_details->fax_no;
                 $user->settings_country = $account_details->country;
                 //$user->settings_description = $account_details->description;
                 //return details as a JSON object
                 echo json_encode($user);
            } else {
                 echo '';
            }
       }

       /*
        * 
        * upadte account settings
        * 
        */

       function updateSettings() {
            $postdata = file_get_contents("php://input");
            $userinfo = json_decode($postdata);
            $errors = array();
            // Retrieve sign in user
            $account = $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));
            // Update account details
            $attributes['country'] = $userinfo->settings_country ? $userinfo->settings_country : NULL;

            $this->account_details_model->update($data['account']->id, $attributes);
            if (isset($userinfo->settings_language) && !empty($userinfo->settings_language) && $account->language !== $userinfo->settings_language) {
                 $language = htmlentities($userinfo->settings_language);
                 if ($this->account_model->update_language($data['account']->id, $language))
                      $this->session->set_userdata('language', $language);
                 $result['reload'] = true;
            }

            $data['settings_info'] = lang('settings_details_updated');


            $result['errors'] = $errors;
            echo json_encode($result);
       }

  }

  /* End of file account_settings.php */
/* Location: ./application/account/controllers/account_settings.php */