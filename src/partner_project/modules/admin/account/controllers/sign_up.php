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
            $this->load->model(array('account/account_model', 'account/account_details_model', 'api/token_key_model'));
       }

       /**
        * Account sign up
        *
        * @access public
        * @return void
        */
       function index() {
       		if (!$this->config->item("sign_up_enabled"))
       		{
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
                 redirect('admin_home');
                 // echo print_r($this->session->all_userdata());
            }


            if ($this->input->post()) {
                 // Setup form validation
                 $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
                 $this->form_validation->set_rules(array(
                     array('field' => 'sign_up_username', 'label' => 'lang:sign_up_username', 'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[24]'),
                     array('field' => 'sign_up_password', 'label' => 'lang:sign_up_password', 'rules' => 'trim|required|min_length[8]|callback__password_pattern'),
                     array('field' => 'sign_up_email', 'label' => 'lang:sign_up_email', 'rules' => 'trim|required|valid_email|max_length[160]')));
                 if (($this->form_validation->run($this) === TRUE) && ($this->config->item("sign_up_enabled"))) {

// Check if user name is taken
                      if ($this->username_check($this->input->post('sign_up_username')) === TRUE) {
                           $data['sign_up_username_error'] = lang('sign_up_username_taken');
                      } elseif (($this->input->post('sign_up_confirm_password') != $this->input->post('sign_up_password'))) {

                           $data['sign_up_password_error'] = "Password does not match";
                      }
                      // Check if email already exist
                      elseif ($this->email_check($this->input->post('sign_up_email')) === TRUE) {
                           $data['sign_up_email_error'] = lang('sign_up_email_exist');
                      } else {

                           // Create user
                           $user_id = $this->account_model->create($this->input->post('sign_up_username', TRUE), $this->input->post('sign_up_email', TRUE), $this->input->post('sign_up_password', TRUE));

                           // Add user details (auto detected country, language, timezone)
                           $this->account_details_model->update($user_id);
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
                                         'org_id' => $invitation->org_id,
                                         'user_id' => $user_id,
                                         'note' => "",
                                         'role_id' => 2, //member
                                         'status' => 'active');
                                     if ($this->members_model->addMember($data)) {
                                          $this->invitations_model->delete_invitation($invitation->id);
                                     }
                                }
                           }
                           // Auto sign in?
                           if ($this->config->item("sign_up_auto_sign_in")) {
                                // Run sign in routine
                                $this->authentication->sign_in($user_id);
                           }
                           redirect('account/sign_in');
                      }
                      //$this->load->view('sign_up', isset($data) ? $data : NULL);
                 }
               
                 //$this->load->view('sign_up', isset($data) ? $data : NULL);
            }

            // Load sign up view
            $this->load->view('sign_up', isset($data) ? $data : NULL);
       }

       public function _password_pattern() {
           $this->form_validation->set_message('_password_pattern', lang('password_pattern'));
            return preg_match('/^[A-Za-z0-9!@#\$\^&\*]+$/', $this->input->post('sign_up_password'))  ? TRUE : FALSE;
       }

       /**
        * Check if a username exist
        *
        * @access public
        * @param string
        * @return bool
        */
       function username_check($username) {
            return $this->account_model->get_by_username($username) ? TRUE : FALSE;
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

       private function token() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            return substr(str_shuffle($characters), 0, 13);
       }

  }

  /* End of file sign_up.php */
       /* Location: ./application/controllers/account/sign_up.php */
       