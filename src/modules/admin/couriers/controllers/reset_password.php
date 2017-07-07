<?php

  /*
   * Reset_password Controller
   */

  class Reset_password extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('couriers/couriers_model'));
       }

       /**
        * Reset password
        */
       function index($id = null) {
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect signed in users to homepage
            if ($this->session->userdata("courier_id")) {
                 redirect('couriers/login');
            }





            // Get account by email
            if ($account = $this->couriers_model->get_by_id($this->input->get('id'))) {
                 // Check if reset password has expired
                 if (now() < (strtotime($account->resetsenton) + $this->config->item("password_reset_expiration"))) {
                      // Check if token is valid
                      if ($this->input->get('token') == sha1($account->courier_id . strtotime($account->resetsenton) . $this->config->item('password_reset_secret'))) {
                           // Remove reset sent on datetime
                           $this->couriers_model->remove_reset_sent_datetime($account->courier_id);

                           // Upon sign in, redirect to change password page
                           $this->session->set_userdata('reset_courier', $this->input->get('id'));

                           redirect('couriers/account_password_reset');
                      }
                 }
            }

            // Load reset password unsuccessful view
            $this->load->view('couriers/login', isset($data) ? $data : NULL);
       }

  }

  /* End of file reset_password.php */
/* Location: ./application/account/controllers/reset_password.php */
