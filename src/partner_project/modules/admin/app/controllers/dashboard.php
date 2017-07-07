<?php

  class Dashboard extends MY_Controller {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
       }

       function index() {
            $this->load->view('app/dashboard');
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */