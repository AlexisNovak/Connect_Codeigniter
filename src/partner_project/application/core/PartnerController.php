<?php

  class PartnerController extends MX_Controller {

       function __construct() {
            parent::__construct();
            global $current_language;
            $this->load->config('language');

            if (($this->session->userdata('language'))) {
                 $this->language = $this->session->userdata('language');
            } else {
                 $this->load->model('options_model');
                 $options = $this->options_model->getOption('language_setting');
                 if ((isset($options['language']) && !empty($options['language']))) {
                      $this->language = $options['language'];
                 } else {
                      $this->language = 'english';
                 }
            }
            $this->lang->load($this->language, $this->language);
            $current_language = $this->language;
            if ($this->uri->segment(1) == 'partner') {
                 $partner_name = $this->uri->segment(2);
                 $this->load->model('partner_management/partner_model');
                 $partner = $this->partner_model->get_by_shortname($partner_name);
                 if (!$partner) {
                      show_404();
                 }
                 $this->session->set_userdata('partner_id', $partner->partner_id);
                 $this->session->set_userdata('partner_name', $partner->shortname);
            }
            $method = $this->router->fetch_class();
            if (!$this->session->userdata('partner_user_id') && $method != "partner_system") {
                 redirect('partner_system/auth');
            }
       }

  }
  