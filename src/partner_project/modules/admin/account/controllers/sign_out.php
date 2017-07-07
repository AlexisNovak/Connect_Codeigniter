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
                 redirect('');

            // Run sign out routine
            $this->authentication->sign_out();

            // Redirect to homepage
            if (!$this->config->item("sign_out_view_enabled"))
                 redirect('');
            if ($this->input->get('reset') && $this->input->get('reset') == 'success') {
                 redirect("account/sign_in/?reset=success");
            }
            // Load sign out view
            redirect("account/sign_in");
       }

  }

  /* End of file sign_out.php */
/* Location: ./application/account/controllers/sign_out.php */