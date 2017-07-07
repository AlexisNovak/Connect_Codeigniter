<?php

  /*
   * Sign_out Controller
   */

  class Sign_out extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->library(array('account/authentication', 'account/authorization'));
       }

       // --------------------------------------------------------------------

       /**
        * Account sign out
        *
        * @access public
        * @return void
        */
       function index() {
            // Redirect signed out users to homepage
            if (!$this->authentication->is_signed_in())
                 redirect('partner_account/sign_in');

            $redirect=  site_url().'/partner/'.$this->session->userdata('partner_name').'/';
            // Run sign out routine
            $this->authentication->sign_out();

            // Redirect to homepage
            if (!$this->config->item("sign_out_view_enabled"))
                 redirect('partner_account/sign_in');
            if ($this->input->get('reset') && $this->input->get('reset') == 'success') {
                 redirect($redirect."/?reset=success");
            }
            // Load sign out view
            redirect($redirect);
       }

  }

  /* End of file sign_out.php */
/* Location: ./application/account/controllers/sign_out.php */