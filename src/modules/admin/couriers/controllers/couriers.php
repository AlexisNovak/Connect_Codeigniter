<?php

  class Couriers extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'couriers_model',
                'courier_service_model',
                'account/notification_model'
            ));
       }

       public function index() {
            redirect('couriers/login');
       }

       public function login() {
            $data = array();

            if ($this->input->post('hashdata')) {
                 $hashPath = $this->input->post('hashdata');
            } else {
                 $hashPath = '';
            }
            $this->session->set_userdata('sign_in_redirect', site_url('couriers/dashboard' . $hashPath));

            if ($this->is_logged_in()) {
                 redirect(site_url("couriers/dashboard" . $hashPath));
            } else {
                 if ($this->input->get('reset') && $this->input->get('reset') == 'success') {
                      $data['password_info'] = lang('password_password_has_been_changed');
                 }
                 $data['title'] = "Couriers Login";
                 $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
                 $this->form_validation->set_rules(array(
                     array(
                         'field' => 'sign_in_username_email',
                         'label' => 'lang:sign_in_username_email',
                         'rules' => 'trim|required'
                     ),
                     array(
                         'field' => 'sign_in_password',
                         'label' => 'lang:sign_in_password',
                         'rules' => 'trim|required|min_length[8]'
                     )
                 ));
                 // Run form validation
                 if ($this->form_validation->run() === TRUE) {
                      // Get user by username / email
                      if (!$courier = $this->couriers_model->get_by_email($this->input->post('sign_in_username_email', TRUE))) {
                           // Username / email doesn't exist
                           $data['sign_in_username_email_error'] = lang('sign_in_username_email_does_not_exist');
                      } else {
                           // Check password
                           if (!$this->check_password($courier->password, $this->input->post('sign_in_password', TRUE))) {
                                // Increment sign in failed attempts
                                $this->session->set_userdata('sign_in_failed_attempts', (int) $this->session->userdata('sign_in_failed_attempts') + 1);

                                $data['sign_in_error'] = lang('sign_in_combination_incorrect');
                           } else {
                                $remember = $this->input->post('sign_in_remember', TRUE);
                                // Run sign in routine
                                if ($remember) {
                                     $this->session->sess_expire_on_close = FALSE;
                                     $this->session->sess_update();
                                }
                                $this->courier_sign_in($courier->courier_id, $this->input->post('sign_in_remember', TRUE));
                                redirect(site_url("couriers/dashboard" . $hashPath));
                           }
                      }
                 }
                 $this->load->view("login", $data);
            }
       }

       public function not_verified() {
            $this->load->view('not_verified');
       }

       public function dashboard() {
            $data = array();
            $data['title'] = "Dashboard";
            $data['courier'] = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $this->load->view('dashboard', $data);
       }

       public function couriers_info() {
            $data = array();
            $data['courier'] = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $this->load->view('info', $data);
       }

       public function check() {
            if ($this->is_logged_in()) {
                 echo json_encode(array(
                     "result" => TRUE
                 ));
                 exit();
            } else {
                 echo json_encode(array(
                     "result" => FALSE
                 ));
                 exit();
            }
       }

       public function overview() {
            $this->load->view('overview');
       }

       public function confirm_email() {
            if ($this->input->get("email", TRUE)) {
                 $email = $this->input->get("email", TRUE);
                 $token = $this->input->get("token", TRUE);
                 $this->load->model('courier_email_confirmation_model');
                 $verify = $this->courier_email_confirmation_model->check_verification($email, $token);
                 if ($verify) {
                      $data['email'] = $email;
                      if ($verify->status == 0) {
                           $data['confirm'] = 1;
                           $this->couriers_model->verifyEmail($email);
                           $this->courier_email_confirmation_model->update_verification($email);
                      } elseif ($verify->status == 1) {
                           $data['confirm'] = 2;
                      }
                 } else {
                      $data['confirm'] = 0;
                 }
                 $this->load->view('confirm_success', $data);
            } else {
                 redirect(site_url('couriers/login'));
            }
       }

       public function log_out() {
            $this->logout();
            redirect(site_url("couriers/login"));
       }

       public function register() {
            $data = array();
            if ($this->is_logged_in()) {
                 redirect(site_url("couriers/dashboard"));
            }
            $data['title'] = "Couriers Register";
            // Setup form validation
            $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
            $this->form_validation->set_message('is_unique', '%s already taken.');
            $this->form_validation->set_rules(array(
                array(
                    'field' => 'sign_up_companyname',
                    'label' => 'lang:sign_up_companyname',
                    'rules' => 'trim|required|min_length[6]'
                ),
                array(
                    'field' => 'sign_up_password',
                    'label' => 'lang:sign_up_password',
                    'rules' => 'trim|required|min_length[6]'
                ),
                array(
                    'field' => 'sign_up_email',
                    'label' => 'lang:sign_up_email',
                    'rules' => 'trim|required|valid_email|max_length[160]|is_unique[couriers.email]'
                ),
                array(
                    'field' => 'sign_up_confirm_password',
                    'label' => 'lang:sign_up_confirm_password',
                    'rules' => 'trim|required|min_length[6]'
                ),
                array(
                    'field' => 'sign_up_policy',
                    'label' => 'Policy',
                    'rules' => 'required'
                )
            ));

            if (($this->form_validation->run() === TRUE)) {
                 if (($this->input->post('sign_up_confirm_password') != $this->input->post('sign_up_password'))) {

                      $data['sign_up_password_error'] = "Password does not match";
                 }   // Check if email already exist
                 elseif ($this->email_check($this->input->post('sign_up_email')) === TRUE && !$this->invited($this->input->post('sign_up_email'))) {
                      $data['sign_up_email_error'] = lang('sign_up_email_exist');
                 } else {
                      do {
                           $token = $this->token();
                      } while ($this->access_key_check($token));

                      if ($this->invited($this->input->post('sign_up_email'))) {
                           $courier = $this->couriers_model->get_by_email($this->input->post('sign_up_email', TRUE));
                           $courier_id = $courier->courier_id;
                           $this->couriers_model->update($courier_id, array(
                               "company_name" => $this->input->post('sign_up_companyname', TRUE),
                               "access_key" => $token,
                               'is_invited' => 0
                           ));
                           $this->couriers_model->update_password($courier_id, $this->input->post('sign_up_password', TRUE));
                      } else {
                           // Create courier
                           $courier_id = $this->couriers_model->create($this->input->post('sign_up_companyname', TRUE), $this->input->post('sign_up_email', TRUE), $this->input->post('sign_up_password', TRUE), $token);
                      }

                      // add notification settings initially
                      $notifications = array(
                          array(
                              'account_id' => $courier_id,
                              'type' => 1,
                              'notification_id' => N_DIRECT_ASSIGN
                          ),
                          array(
                              'account_id' => $courier_id,
                              'type' => 1,
                              'notification_id' => N_COMMENT_RESPONSE
                          ),
                          array(
                              'account_id' => $courier_id,
                              'type' => 1,
                              'notification_id' => N_BID_WON
                          ),
                          array(
                              'account_id' => $courier_id,
                              'type' => 1,
                              'notification_id' => N_BID_WON_SERVICE
                          ),
                          array(
                              'account_id' => $courier_id,
                              'type' => 1,
                              'notification_id' => N_CANCEL_ORDER
                          )
                      );
                      $this->notification_model->initiate_notification($notifications);

                      $this->courier_sign_in($courier_id);
                      $verifiy_token = md5(uniqid(mt_rand(), true));
                      $email = $this->input->post('sign_up_email', TRUE);
                      $this->load->model('courier_email_confirmation_model');
                      if ($this->courier_email_confirmation_model->add_verification($email, $verifiy_token)) {
                           $to = $email;
                           $to_name = $email;
                           $subject = 'Confirm Your Email Address';
                           $message = array(
                               'title' => '',
                               'name' => $to_name,
                               'content' => array(
                                   'You have registered a courier account with 6Connect!',
                                   'We are excited to get you started so you can start receiving jobs and manage them easily for your courier business.',
                                   'Please click the link below to confirm your email:'
                               ),
                               'link_title' => '',
                               'link' => '',
                               'link2' => '<a href="' . site_url('couriers/confirm_email?email=' . $email . '&token=' . $verifiy_token) . '" style=" background-color: #34495e; border-color: #34495e;color: #ffffff;display:inline-block; padding:0px 20px; height:50px;line-height:50px;font-weight: bold;text-align: center;text-decoration: none;">Confirm Your Email</a>'
                           );
                           $mail_result = save_mail($to, $to_name, $subject, $message);
                           $result['mail'] = $mail_result;
                      }
                      redirect(site_url("couriers/login"));
                 }
            }
            $this->load->view("register", $data);
       }

       function password_check($password, $confirm_password) {
            if ($password != $confirm_password) {
                 return TRUE;
            } else {
                 return FALSE;
            }
       }

       function check_password($password_hash, $password) {
            $this->load->helper('account/phpass');

            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            return $hasher->CheckPassword($password, $password_hash) ? TRUE : FALSE;
       }

       /**
        * Check if an email exist
        *
        * @access public
        * @param
        *        	string
        * @return bool
        */
       function email_check($email) {
            return $this->couriers_model->get_by_email($email) ? TRUE : FALSE;
       }

       function access_key_check($token) {
            return $this->couriers_model->courier_by_token($token) ? TRUE : FALSE;
       }

       private function token() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            return substr(str_shuffle($characters), 0, 13);
       }

       public function associate_orglist() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            if ($courier_id) {
                 $result['organisations'] = $this->couriers_model->myorganisations_all($courier_id);
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function invited($email) {
            $row = $this->couriers_model->get_by_email($email);
            return $row ? (($row->is_invited) ? TRUE : FALSE) : FALSE;
       }

  }
  