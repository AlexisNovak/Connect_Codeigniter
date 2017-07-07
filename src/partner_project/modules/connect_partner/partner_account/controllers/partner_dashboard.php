<?php

  class Partner_dashboard extends PartnerController {

       function __construct() {
            parent::__construct();
            if ($this->session->userdata('partner_user_id') == FALSE) {
                 //redirect("partner_account/sign_in");
            }
            // Load the necessary stuff...
            $this->load->helper(array('account/ssl', 'photo'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('account/account_model', 'account/account_details_model', 'partner_management/partner_model'));
       }

       public function index() {
            maintain_ssl();
            if ($this->authentication->is_signed_in()) {
                 $data['account'] = $this->account_model->get_by_id($this->session->userdata('partner_user_id'));
                 $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('partner_user_id'));
            }
            if ($this->session->userdata('partner_id')) {
                 $data['partner'] = $this->partner_model->get_by_id($this->session->userdata('partner_id'));
            }
            $this->load->view('dashboard', isset($data) ? $data : NULL);
       }

  }
  