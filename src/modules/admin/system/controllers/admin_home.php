<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  class Admin_home extends MY_Controller {

       function __construct() {
            parent::__construct();
            if ($this->session->userdata('user_id') == FALSE) {
                 redirect("account/sign_in");
            }
            // Load the necessary stuff...
            $this->load->helper(array('account/ssl', 'photo'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('account/account_model', 'account/account_details_model'));
       }

       function index() {
            maintain_ssl();

            if ($this->authentication->is_signed_in()) {
                 $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
                 $data['account']->is_partner_user = $this->account_model->is_partner_user($this->session->userdata('user_id'));
                 $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));
            }

            $this->load->view('admin_home', isset($data) ? $data : NULL);
            // echo print_r($this->session->all_userdata());
       }

       function edit() {
            maintain_ssl();

            if ($this->authentication->is_signed_in()) {
                 $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            }

            $this->load->view('form_edit', isset($data) ? $data : NULL);
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */