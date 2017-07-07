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
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('partner_user_id'));

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
            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('partner_user_id'));

            // No access to users without a password
            if (!$data['account']->password)
                 return;

            if (isset($postdata->password_new_password)) {
                 if (preg_match('/^[A-Za-z0-9!@#\$\^&\*]+$/', $postdata->password_new_password) && strlen($postdata->password_new_password) >= 8) {
                      if (isset($postdata->password_retype_new_password)) {
                           if (strcmp($postdata->password_new_password, $postdata->password_retype_new_password) != 0) {
                                $errors['new_retype_password_error'] = "Please retype password correctly";
                           } else {
                                $this->account_model->update_password($data['account']->id, htmlentities($postdata->password_new_password));
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
            echo json_encode($errors);
       }

  }

  /* End of file account_password.php */
  /* Location: ./application/account/controllers/account_password.php */