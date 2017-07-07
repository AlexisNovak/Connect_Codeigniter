<?php

  /*
   * Account_password Controller
   */

  class Account_password_reset extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl'));
            $this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
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
            $this->form_validation->set_message('matches', 'Retype passwords does not match');
            $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
            $this->form_validation->set_rules(array(array('field' => 'password_new_password', 'label' => 'lang:password_new_password', 'rules' => 'trim|required|min_length[8]|callback_is_old_pass'), array('field' => 'password_retype_new_password', 'label' => 'lang:password_retype_new_password', 'rules' => 'trim|required|matches[password_new_password]')));

            ### Run form validation
            if ($this->form_validation->run($this)) {
                 // Change user's password
                 $old_passwords = $this->account_model->get_old_password($data['account']->id);
                 $old_pass = array($old_passwords->password);
                 if ($old_passwords->oldpasswords !== NULL) {
                      $passwords = json_decode($old_passwords->oldpasswords);
                      $old_pass[] = $passwords[0] ? $passwords[0] : '';
                 } else {
                      $old_pass[] = '';
                 }
                 $this->account_model->update_password($data['account']->id, $this->input->post('password_new_password', TRUE), json_encode($old_pass));
                 $this->session->set_flashdata('password_info', lang('password_password_has_been_changed'));
                 redirect('account/sign_out/?reset=success');
            }

            $this->load->view('account/account_password_reset', $data);
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
            foreach ($old_pass as $pass) {
                 if ($hasher->CheckPassword($npass, $pass)) {
                      $this->form_validation->set_message('is_old_pass', 'Please do not use the last 3 old passwords.');
                      return FALSE;
                 }
            }

            return TRUE;
       }

  }

  /* End of file account_password.php */
  /* Location: ./application/account/controllers/account_password.php */