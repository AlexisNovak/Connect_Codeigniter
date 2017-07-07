<?php

  class Partner_system extends PartnerController {

       public function __construct() {
            parent::__construct();
            $this->load->config('account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha'));
            $this->load->model(array('account/account_model', 'account/account_details_model','account/notification_model', 'partner_account/partner_members_model'));
       }

       public function index() {
            $this->session->unset_userdata('partner_user_id');
            if ($this->input->get('email') && $this->input->get('fullname')) {

                 // debug($this->account_model->get_by_username_email($this->input->get('email', TRUE)));
                 // Get user by username / email
                 if (!$user = $this->account_model->get_by_username_email($this->input->get('email', TRUE))) {
                      // Username / email doesn't exist
                      // $data['sign_in_username_email_error'] = lang('sign_in_username_email_does_not_exist');
                      //check whether its an email
                      if (!valid_email($this->input->get('email'))) {
                           $data['sign_in_username_email_error'] = 'Please provide valid email address';
                      } else {
                           $fullname = $this->input->get('fullname', TRUE);
                           if (empty($fullname)) {
                                $email_array = explode('@', $this->input->get('email'));
                                $fullname = $email_array[0] ? $email_array[0] : 'Anonymous';
                           }
                           $user_id = $this->account_model->create($fullname, $this->input->get('email', TRUE), NULL);
                           $this->partner_members_model->add_member(array('partner_id' => $this->session->userdata('partner_id'), 'member_id' => $user_id));
                           // Add user details (auto detected country, language, timezone)
                           $this->account_details_model->update($user_id, array('fullname' => $fullname));
                            $notifications = array(
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_BID_RECEIVED),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_SERVICE_BID),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_ORDER_STATUS_UPDATE),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_COMMENT_FROM_COURIER)
                           );
                           $this->notification_model->initiate_notification($notifications);
                           // Auto sign in?
                           if ($this->config->item("sign_up_auto_sign_in")) {
                                // Run sign in routine
                                // Clear sign in fail counter
                                $this->session->unset_userdata('sign_in_failed_attempts');
                                $user = $user = $this->account_model->get_by_id($user_id);
                                // Run sign in routine
                                $this->session->set_userdata('partner_user_id', $user->id);
                                $this->account_model->update_last_signed_in_datetime($user_id);
                                $this->session->set_userdata('language', $user->language);
                                if ($this->input->get('company_name', TRUE)) {
                                     $this->load->model('app/organisation_model');
                                     $attributes = array(
                                         "name" => $this->input->get('company_name', TRUE),
                                         "shortname" => $this->input->get('company_name', TRUE),
                                         "status" => 1,
                                         "user_id" => $user->id
                                     );
                                     $this->organisation_model->add_new_organisation($attributes);
                                }

                                redirect('partner_account/partner_dashboard');
                           }
                           redirect('partner_account/sign_in');
                      }
                 } else {
                      // Either don't need to pass recaptcha or just passed recaptcha
                      if (!($recaptcha_pass === TRUE || $recaptcha_result === TRUE) && $this->config->item("sign_in_recaptcha_enabled") === TRUE) {
                           $data['sign_in_recaptcha_error'] = $this->input->post('recaptcha_response_field') ? lang('sign_in_recaptcha_incorrect') : lang('sign_in_recaptcha_required');
                      } else {
                           if (!$this->partner_members_model->is_this_partner_member($user->id)) {
                                redirect('partner_system/auth/invalid_portal');
                           }
                           // Clear sign in fail counter
                           $this->session->unset_userdata('sign_in_failed_attempts');

                           // Run sign in routine
                           $this->session->set_userdata('partner_user_id', $user->id);
                           $this->account_model->update_last_signed_in_datetime($user_id);
                           $this->session->set_userdata('language', $user->language);

                           $this->account_model->update_username($user->id, $this->input->get('fullname', TRUE));
                           $attributes = array();
                           $attributes['fullname'] = $this->input->get('fullname', TRUE) ? $this->input->get('fullname', TRUE) : NULL;
                           $this->account_details_model->update($user->id, $attributes);

                           redirect('partner_account/partner_dashboard');
                      }
                 }
            } else {
                 redirect('partner_system/auth');
            }
       }

       function username_check($username) {
            return $this->account_model->get_by_username($username) ? TRUE : FALSE;
       }

  }
  