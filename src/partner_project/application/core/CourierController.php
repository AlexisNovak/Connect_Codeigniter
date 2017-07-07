<?php

  class CourierController extends MX_Controller {

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
                 }
            }
            $this->lang->load($this->language, $this->language);

            $current_language = $this->language;
            $method = $this->router->fetch_method();
            if ($method !== "login" && $method !== "register" && $method !== "confirm_email") {
                 if (!$this->session->userdata("partner_courier_id")) {
                      $this->session->set_flashdata('message','Please login before continue');
                      redirect(site_url('couriers/login'));
                 }
            }
       }

       public function is_approved() {
            $this->load->model('couriers/couriers_model');
            $courier = $this->couriers_model->get_by_id($this->session->userdata('partner_courier_id'));
            if ($courier)
                 return $courier->is_approved;
            return FALSE;
       }

       public function is_logged_in() {
            if ($this->session->userdata("partner_courier_id"))
                 return TRUE;
            return FALSE;
       }

       public function courier_sign_in($courier_id, $remember = FALSE) {
            $this->session->set_userdata("partner_courier_id", $courier_id);
            $remember ? $this->session->cookie_monster(TRUE) : $this->session->cookie_monster(FALSE);
       }

       function logout() {
            $this->session->unset_userdata('partner_courier_id');
       }

  }
  