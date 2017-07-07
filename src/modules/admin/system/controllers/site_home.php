<?php

  class Site_home extends MY_Controller {

       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->helper(array( 'account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('account/account_model'));
       }

       function index() {
            maintain_ssl();

            if ($this->authentication->is_signed_in()) {

                 $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
                 if ($data['account']->username == 'admin') {
                      redirect('admin_home');
                 } else {
                      redirect('user_home');
                 }
            }

            $this->load->view('site_home', isset($data) ? $data : NULL);
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */