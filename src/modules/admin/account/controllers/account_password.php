<?php

  /*
   * Account_password Controller
   */

  class Account_password extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization'));
            $this->load->model(array('account/account_model'));
       }

       /**
        * Account password
        */
       function index() {
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if (!$this->authentication->is_signed_in()) {
                 redirect('account/sign_in/?continue=' . urlencode(base_url() . 'account/account_password'));
            }

            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));

            // No access to users without a password
            if (!$data['account']->password)
                 redirect('');

            ### Setup form validation
            $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
            $this->form_validation->set_rules(array(
                array('field' => 'password_new_password', 'label' => 'lang:password_new_password', 'rules' => 'trim|required|min_length[6]'),
                array('field' => 'password_retype_new_password', 'label' => 'lang:password_retype_new_password', 'rules' => 'trim|required|matches[password_new_password]')));

            ### Run form validation
            if ($this->form_validation->run()) {
                 // Change user's password
                 $this->account_model->update_password($data['account']->id, $this->input->post('password_new_password', TRUE));
                 $this->session->set_flashdata('password_info', lang('password_password_has_been_changed'));
                 redirect('account/account_password');
            }

            $this->load->view('account/account_password', $data);
       }

       public function updatePassword() {
            $postdata = file_get_contents("php://input");
            $postdata = json_decode($postdata);
            $errors = array();
            $user_id = $this->session->userdata('user_id');
            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($user_id);

            // No access to users without a password
            if (!$data['account']->password)
                 return;
            if (isset($postdata->password_current_password)) {
                 $cpass=$postdata->password_current_password;
                 $this->load->helper('account/phpass');
                 $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                 if ($hasher->CheckPassword($cpass, $data['account']->password)) {
                      if (isset($postdata->password_new_password)) {
                           if (preg_match('/^[A-Za-z0-9!@#\$\^&\*]+$/', $postdata->password_new_password) && strlen($postdata->password_new_password) >= 8) {
                                if (isset($postdata->password_retype_new_password)) {
                                     if (strcmp($postdata->password_new_password, $postdata->password_retype_new_password) != 0) {
                                          $errors['new_retype_password_error'] = "Please retype password correctly";
                                     } else {
                                          if ($this->is_old_pass($postdata->password_new_password)) {
                                               $errors['new_password_error'] = "Please do not use the last 3 old passwords.";
                                          } else {
                                               $old_passwords = $this->account_model->get_old_password($user_id);
                                               $old_pass = array($old_passwords->password);
                                               if ($old_passwords->oldpasswords !== NULL) {
                                                    $passwords = json_decode($old_passwords->oldpasswords);
                                                    $old_pass[] = $passwords[0] ? $passwords[0] : '';
                                               } else {
                                                    $old_pass[] = '';
                                               }
                                               $this->account_model->update_password($data['account']->id, htmlentities($postdata->password_new_password), json_encode($old_pass));
                                          }
                                     }
                                } else {
                                     $errors['new_retype_password_error'] = "Re-type password field is required";
                                }
                           } else {
                                $errors['new_password_pattern_error'] = lang('password_pattern');
                           }
                      } else {
                           $errors['new_password_error'] = "New password field is required";
                      }
                 } else {
                      $errors['current_password_error'] = "Current password is incorrect";
                 }
            } else {
                 $errors['current_password_error'] = "Current password field is required";
            }
            echo json_encode($errors);
       }

       public function is_old_pass($npass) {
            $this->load->helper('account/phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $user_id = $this->session->userdata('user_id');
            $old_passwords = $this->account_model->get_old_password($user_id);
            $old_pass = array($old_passwords->password);
            if ($old_passwords->oldpasswords !== NULL) {
                 $passwords = json_decode($old_passwords->oldpasswords);
                 $old_pass[] = $passwords[0] ? $passwords[0] : '';
                 $old_pass[] = $passwords[1] ? $passwords[1] : '';
            }
            //  debug($old_pass);
            foreach ($old_pass as $pass) {
                 if ($hasher->CheckPassword($npass, $pass)) {
                      return TRUE;
                 }
            }

            return FALSE;
       }

  }

  /* End of file account_password.php */
  /* Location: ./application/account/controllers/account_password.php */