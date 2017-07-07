<?php

  /*
   * Sign_up Controller
   */

  class Sign_up extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha'));
            $this->load->model(array('account/account_model', 'account/account_details_model', 'api/token_key_model', 'account/notification_model'));
       }

       /**
        * Account sign up
        *
        * @access public
        * @return void
        */
       function index() {
            if (!$this->config->item("sign_up_enabled")) {
                 header('Location: sign_in');
                 exit;
            }
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));
            //check the request is redirected from invitation

            if (isset($_GET['t']) && !empty($_GET['t'])) {
                 $token = htmlentities(trim($_GET['t']));
                 $this->load->model('app/invitations_model');

                 $invitation = $this->invitations_model->get_invitation_by_token($token);
                 if (is_object($invitation)) {
                      $data['invited_token'] = $invitation->token;
                      $data['invited_email'] = $invitation->email;
                 }
            }

            // Redirect signed in users to homepage
            if ($this->authentication->is_signed_in()) {
                 redirect('system/admin_home');
                 // echo print_r($this->session->all_userdata());
            }


            if ($this->input->post()) {
                 // Setup form validation
                 $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
                 $this->form_validation->set_message('is_unique', '%s already taken.');
                 $this->form_validation->set_rules(array(
                     array('field' => 'sign_up_username', 'label' => 'lang:sign_up_username', 'rules' => 'trim|required'),
                     array('field' => 'sign_up_password', 'label' => 'lang:sign_up_password', 'rules' => 'trim|required|min_length[8]|callback__password_pattern'),
                     array('field' => 'sign_up_policy', 'label' => 'Policy', 'rules' => 'required'),
                     array('field' => 'sign_up_email', 'label' => 'lang:sign_up_email', 'rules' => 'trim|required|valid_email|max_length[160]|callback__mail_unique')));
                 if (($this->form_validation->run($this) === TRUE) && ($this->config->item("sign_up_enabled"))) {

                      // Check if user name is taken
//                      if ($this->username_check($this->input->post('sign_up_username')) === TRUE) {
//                           $data['sign_up_username_error'] = lang('sign_up_username_taken');
//                      } else

                      if (($this->input->post('sign_up_confirm_password') != $this->input->post('sign_up_password'))) {

                           $data['sign_up_password_error'] = "Password does not match";
                      }
                      // Check if email already exist
                      elseif ($this->email_check($this->input->post('sign_up_email')) === TRUE && !$this->invited($this->input->post('sign_up_email'))) {
                           $data['sign_up_email_error'] = lang('sign_up_email_exist');
                      } else {
                           if ($this->invited($this->input->post('sign_up_email'))) {
                                $user = $this->account_model->get_by_email($this->input->post('sign_up_email', TRUE));
                                $user_id = $user->id;
                                $this->account_model->update_username($user_id, $this->input->post('sign_up_username', TRUE));
                                $this->account_model->update_password($user_id, $this->input->post('sign_up_password', TRUE));
                                $this->account_model->update($user_id, array('is_invited' => 0));
                           } else {

                                // Create user
                                $user_id = $this->account_model->create($this->input->post('sign_up_username', TRUE), $this->input->post('sign_up_email', TRUE), $this->input->post('sign_up_password', TRUE));
//                                $to = $this->input->post('sign_up_email', TRUE);
//                                $to_name = $this->input->post('sign_up_username', TRUE);
//                                $link = site_url('account/sign_up?t=' . $token);
//                                $subject = '6Connect Registration';
//                                $message = array(
//                                    'title' => 'Invitation to join the organization',
//                                    'name' => $to_name,
//                                    'content' => array('You have registered an account with 6Connect', ' We are excited to get you started so you can focus on your business while we help to streamline your logistics worries.', 'The first thing you should do is to', '(A) Setup your organisation, and<br>(B) Add some courier service into your account', 'Once you have done the above, you are ready to begin.', 'Start creating your delivery job now!'),
//                                    'link_title' => '',
//                                    'link' => '');
//                                $mail_result = save_mail($to, $to_name, $subject, $message);
                           }
                           // Add user details (auto detected country, language, timezone)
                           $this->account_details_model->update($user_id, array('fullname' => $this->input->post('sign_up_username', TRUE), 'country' => 'sg'));
                           //add notification settings
                           $notifications = array(
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_BID_RECEIVED),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_NEW_SERVICE_BID),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_ORDER_STATUS_UPDATE),
                               array('account_id' => $user_id, 'type' => 0, 'notification_id' => N_COMMENT_FROM_COURIER)
                           );
                           $this->notification_model->initiate_notification($notifications);
                           $token = $this->token();
                           $data['user_id'] = $user_id;
                           $data['token'] = $token;

                           $this->token_key_model->insert('token_key', $data);

                           if ($this->input->post('invited_token') && $this->input->post('invited_token') != NULL) {
                                $this->load->model('app/invitations_model');
                                $invitation = $this->invitations_model->get_invitation_by_token_email($this->input->post('invited_token'), $this->input->post('sign_up_email', TRUE));
                                if (is_object($invitation)) {
                                     $this->load->model('app/members_model');
                                     $data = array(
                                         'status' => 'active');
                                     if ($this->members_model->update_member($invitation->org_id, $user_id, $data)) {
                                          $this->invitations_model->delete_invitation($invitation->id);
                                     }
                                }
                           }
                           // $this->members_model->update_org_member($user_id);
                           // Auto sign in?
                           if ($this->config->item("sign_up_auto_sign_in")) {
                                // Run sign in routine
//                                $this->authentication->sign_in($user_id);
                           }



                           $verifiy_token = md5(uniqid(mt_rand(), true));
                           $email = $this->input->post('sign_up_email', TRUE);
                           $this->load->model('member_email_confirmation_model');
                           if ($this->member_email_confirmation_model->add_verification($email, $verifiy_token)) {
                                $to = $email;
                                $to_name = $email;
                                $subject = 'Confirm Your Email Address';
                                $message = array(
                                    'title' => '',
                                    'name' => $to_name,
                                    'content' => array(
                                        'You have registered an account with 6Connect!',
                                        'We are excited to get you started so you can start receiving jobs and manage them easily for your courier business.',
                                        'Please click the link below to confirm your email:'
                                    ),
                                    'link_title' => '',
                                    'link' => '',
                                    'link2' => '<center><a href="' . site_url('account/sign_up/confirm_email?email=' . $email . '&token=' . $verifiy_token) . '" style=" background-color: #34495e; border-color: #34495e;color: #ffffff;display:inline-block; padding:0px 20px; height:50px;line-height:50px;font-weight: bold;text-align: center;text-decoration: none;margin-top:20px;">Confirm Your Email</a></center>'
                                );
                                $mail_result = save_mail($to, $to_name, $subject, $message);
                                $result['mail'] = $mail_result;
                           }

                           $data['email'] = $email;
//                           redirect('account/sign_in');
                      }
                      if (isset($data['user_id'])) {
                           $this->load->view('sign_up_success', isset($data) ? $data : NULL);
                           $viewed = true;
                      }
                 }

                 //$this->load->view('sign_up', isset($data) ? $data : NULL);
            }
            if (!isset($viewed)) {
                 // Load sign up view
                 $this->load->view('sign_up', isset($data) ? $data : NULL);
            }
       }

       public function add_org() {
            if ($this->input->post()) {
                 if ($this->org_validate()) {
                      $this->load->model('app/organisation_model');
                      do {
                           $hash_code = substr(md5(uniqid(mt_rand(), true)), 0, 16);
                      } while (!$this->organisation_model->is_unique_hash($hash_code));
                      $attributes = array(
                          "name" => $this->input->post('org_name'),
                          "shortname" => $this->input->post('short_name'),
                          'hash_code' => $hash_code,
                          "website" => strtolower($this->input->post('website')),
                          "description" => "",
                          "status" => 1,
                          "user_id" => $this->input->post('user_data')
                      );
                      $this->organisation_model->add_new_organisation($attributes);
                      redirect('account/sign_up/organisation_success');
                 } else {
                      $data['user_id'] = $this->input->post('user_data');
                      $this->load->view('sign_up_success', isset($data) ? $data : NULL);
                 }
            } else {
                 redirect('account/sign_in');
            }
       }

       public function organisation_success() {
            $this->load->view('add_organisation_success');
       }

       function validate_website($website1) {
            if($website1==''){
                  return TRUE;
            }
            $website2 = htmlentities($website1);
            $website = strtolower($website2);
            $search = array('http://', 'ftp://', 'https://');
            $replace = array('', '', '');
            $domain = str_replace($search, $replace, $website);
            if (strpos($domain, "www.") < 1) {
                 $domain = str_replace('www.', '', $domain);
            }
            $regex = "/^([a-z0-9][a-z0-9\-]{1,63})\.[a-z\.]{2,6}$/i";

            if (preg_match($regex, $domain)) {
                 return TRUE;
            } else {
                 $this->form_validation->set_message('validate_website', 'Invalid website ');
                 return false;
            }
       }

       private function org_validate() {
            $this->form_validation->set_rules('org_name', 'Organisation name', 'trim|required');
            $this->form_validation->set_rules('short_name', 'Short name', 'trim|required');
            $this->form_validation->set_rules('website', 'Website', 'trim|callback_validate_website');

            if ($this->form_validation->run($this) != FALSE) {
                 return TRUE;
            }
            return FALSE;
       }

       public function _password_pattern() {
            $this->form_validation->set_message('_password_pattern', lang('password_pattern'));
            return preg_match('/^[A-Za-z0-9!@#\$\^&\*]+$/', $this->input->post('sign_up_password')) ? TRUE : FALSE;
       }

       public function mail_unique() {
            $this->form_validation->set_message('mail_unique', "Email already taken.");
            $email = $this->input->post('sign_up_email');
            $row = $this->account_model->get_by_email($email);
            return $row ? (($row->is_invited) ? TRUE : FALSE) : TRUE;
       }

       /**
        * Check if a username exist
        *
        * @access public
        * @param string
        * @return bool
        */
       function username_check($username) {
            return !empty($username) ? TRUE : FALSE;
       }

       function password_check($password, $confirm_password) {
            if ($password != $confirm_password) {
                 return TRUE;
            } else {
                 return FALSE;
            }
       }

       /**
        * Check if an email exist
        *
        * @access public
        * @param string
        * @return bool
        */
       function email_check($email) {
            return $this->account_model->get_by_email($email) ? TRUE : FALSE;
       }

       function invited($email) {
            $row = $this->account_model->get_by_email($email);
            return $row ? (($row->is_invited) ? TRUE : FALSE) : FALSE;
       }

       private function token() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            return substr(str_shuffle($characters), 0, 13);
       }

       public function confirm_email() {
            if ($this->input->get("email", TRUE)) {
                 $email = $this->input->get("email", TRUE);
                 $token = $this->input->get("token", TRUE);
                 $this->load->model('member_email_confirmation_model');
                 $verify = $this->member_email_confirmation_model->check_verification($email, $token);
                 if ($verify) {
                      $userdata = $this->member_email_confirmation_model->getMemberData($email);
                      $data['email'] = $email;
                      if ($userdata->username != '') {
                           $name = $userdata->username;
                      } else {
                           $name = $userdata->email;
                      }
                      $subject = "Congratulation! Your account is activated.";
                      $messages = "<div>
                           Thank you for signing up with 6Connect.<br/>
                           Your account is now verified and you are ready to make your deliveries.<br/><br/>
                           Click <a href='" . site_url('') . "'>here</a> to begin.<br/><br/>
                                </br>
                                         <img src='" . base_url('resource/images/save-more.jpg') . "' style='width:70%;'>
                                              <div style='clear:both;'>
                           For a limited time, we are doing a promotion<br/><br/>
                           Enjoy 10% rebate for your 1st 100 deliveries*<br/><br/>
                           And there's more! Get a chance to win a free dining at Mandarin Orchard Singapore every month. Every month, we are giving away dining vouchers worth over $1,000 to 6 lucky users. <br/><br/>
                           Start deliver and you will be qualified. The more you deliver, the higher your chance of winning!<br/><br/>
                           So start now before it ends!</div></div>";

                      $message = array(
                          'title' => $subject,
                          'name' => $name,
                          'content' => $messages,
                          'link_title' => '',
                          'link' => '');
                      $mail_result = save_mail($email, $name, $subject, $message);
                      if ($verify->status == 0) {
                           $data['confirm'] = 1;
                           $this->member_email_confirmation_model->verifyEmail($email);
                           $this->member_email_confirmation_model->update_verification($email);
                      } elseif ($verify->status == 1) {
                           $data['confirm'] = 2;
                      }
                 } else {
                      $data['confirm'] = 0;
                 }
                 $this->load->view('confirm_success', $data);
            } else {
                 redirect(site_url('account/sign_in'));
            }
       }

  }

  /* End of file sign_up.php */
       /* Location: ./application/controllers/account/sign_up.php */
       