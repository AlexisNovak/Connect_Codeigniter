<?php

  /*
   * Forgot_password Controller
   */

  class Forgot_password extends MY_Controller {

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
        * Forgot password
        */
       function index() {
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect signed in users to homepage
            // Redirect signed in users to homepage
            if ($this->session->userdata("courier_id")) {
                 redirect('couriers/login');
            }

            // Check recaptcha
            // Setup form validation
            // max length as per IETF (http://www.rfc-editor.org/errata_search.php?rfc=3696&eid=1690)
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules(array(
                array(
                    'field' => 'forgot_password_username_email',
                    'label' => 'lang:forgot_password_username_email',
                    'rules' => 'trim|required|min_length[2]|max_length[254]|callback_check_username_or_email'
                )
            ));

            // Run form validation
            if ($this->form_validation->run()) {


                 // Username does not exist
                 if (!$account = $this->couriers_model->get_by_email($this->input->post('forgot_password_username_email', TRUE))) {
                      $data['forgot_password_username_email_error'] = lang('forgot_password_username_email_does_not_exist');
                 }
                 // Does not manage password
                 elseif (!$account->password) {
                      $data['forgot_password_username_email_error'] = lang('forgot_password_does_not_manage_password');
                 } else {
                      // Set reset datetime
                      $time = $this->couriers_model->update_reset_sent_datetime($account->courier_id);

                      // Generate reset password url
                      $password_reset_url = site_url('couriers/reset_password?id=' . $account->courier_id . '&token=' . sha1($account->courier_id . $time . $this->config->item('password_reset_secret')));

                      // Send reset password email

                      $to = $account->email;
                      $to_name = $account->email;
                      $subject = lang('reset_password_email_subject');
                      $message = $this->load->view('couriers/reset_password_email', array(
                          'username' => $account->company_name,
                          'password_reset_url' => anchor($password_reset_url, $password_reset_url)
                              ), TRUE);
                      $message = array(
                          'title' => '',
                          'name' => $to_name,
                          'content' => anchor($password_reset_url, $password_reset_url),
                          'link_title' => '',
                          'link' => '',
                          'link2' => '');
                      $mail_result = save_mail($to, $to_name, $subject, $message);
                      if ($mail_result == 1) {
                           // Load reset password sent view
                           $this->load->view('couriers/reset_password_sent', isset($data) ? $data : NULL);
                      } else {
                           //if the email could not be sent it will display the error
                           //should not happen if you have email configured correctly
                           echo $this->email->print_debugger();
                      }
                      return;
                 }
            }



            // Load forgot password view
            $this->load->view('couriers/forgot_password', isset($data) ? $data : NULL);
       }

       public function check_username_or_email($str) {
            //are we checking an email address?
            if (strpos($str, '@') !== false) {
                 //Its an email, so lets check if its valid
                 if ($this->form_validation->valid_email($str))
                      return TRUE;
                 else {
                      $this->form_validation->set_message('check_username_or_email', 'Invalid e-mail address format');
                      return FALSE;
                 }
            } else {
                 //check if username is alpha_dash
                 if ($this->form_validation->alpha_dash($str))
                      return TRUE;
                 else {
                      $this->form_validation->set_message('check_username_or_email', 'Invalid username format');
                      return FALSE;
                 }
            }
       }

  }

  /* End of file forgot_password.php */
/* Location: ./application/couriers/controllers/forgot_password.php */
