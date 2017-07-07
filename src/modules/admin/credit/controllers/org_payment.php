<?php

  class Org_payment extends MY_Controller {

       function __construct() {
            $this->load->model(array('payment_accounts_model', 'app/organisation_model',
                'app/members_model', 'account/ref_country_model'));
            parent::__construct();
       }

       public function index($id = 0) {
            $data = array('org_id' => $id);
            $this->load->view('payment_accounts', $data);
       }

       public function get_org_accounts($id = 0) {
            $result = array();
            $type = 2;
            $accounts = $this->payment_accounts_model->get_accounts($id, $type);
            if ($accounts) {
                 $result['accounts'] = $accounts;
            }
            echo json_encode($result);
            exit();
       }

       private function _validate_account() {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('account_name', 'Name', 'required|trim|max_length[40]');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
            $this->form_validation->set_rules('address_line1', 'Address line 1', 'trim|required');
            $this->form_validation->set_rules('address_line2', 'Address line 2', 'trim');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('postal_code', 'Postal code', 'trim|required|numeric');
            $this->form_validation->set_rules('country_code', 'Country', 'trim');
            $this->form_validation->set_rules('deli_per_mnth', 'Deliveries/Month', 'trim|numeric');
            return $this->form_validation->run($this) !== FALSE;
       }

       public function add_postpaid_account($org_id = 0) {
            $result = array();
            $error = false;
            $errors = array();
            $postData = json_decode(file_get_contents("php://input"));
            $_POST = (array) $postData;
            $user_id = $this->session->userdata('user_id');
            if ($this->_validate_account()) {

                 $account_no = $this->input->post('account_holder', TRUE);

                 if ($account_no != '') {
                      $org = $this->payment_accounts_model->getOneWhere(array('id' => $account_no), 'organizations');
//                      $new_ac_data['organisation'] = $org->name;
                      $account_holder = $account_no;
                      $holder_type = 2;
                 } else {
                      $account_holder = $user_id;
                      $holder_type = 1;
                 }

                 $new_account = array(
                     'account_holder' => $account_holder,
                     'holder_type' => $holder_type, //organisation
                     'account_type' => 2, //post-paid
                     'credit' => 0,
                     'deposit' => 0,
                     'status' => 1,
                     'contact_name' => $this->input->post('account_name', TRUE),
                     'contact_number' => $this->input->post('phone_number', TRUE),
                     'address_line1' => $this->input->post('address_line1', TRUE),
                     'address_line2' => $this->input->post('address_line2', TRUE),
                     'city' => $this->input->post('city', TRUE),
                     'country_code' => $this->input->post('country_code', TRUE),
                     'postal_code' => $this->input->post('postal_code', TRUE),
                     'invoice_attention' => $this->input->post('attention', TRUE),
                     'company_reg_no' => $this->input->post('reg_no', TRUE),
                     'deliveries_per_month' => $this->input->post('deli_per_mnth', TRUE) ? $this->input->post('deli_per_mnth', TRUE) : 0,
                     'comments' => $this->input->post('comments', TRUE));
                 if ($this->input->post('account_id', TRUE)) {
                      $account_id = $this->input->post('account_id', TRUE);
                 } else {
                      $account_id = NULL;
                 }
                 if ($this->payment_accounts_model->add_account($new_account, $account_id)) {

                      $result['status'] = 1;
                      if ($account_id != NULL) {
                           $result['msg'] = lang('account_updated');
                      } else {
                           $result['msg'] = lang('payment_add_suc_info');
                           //inform root admin    
                           $root_admins = $this->members_model->get_root_admin();
                           foreach ($root_admins as $root) {
                                if ($root->id == $user_id) {
                                     continue;
                                }
                                $to = $root->email;
                                $to_name = $root->name;
                                $subject = 'Application for postpaid account';
                                $message = array(
                                    'title' => "Application for postpaid account",
                                    'name' => $to_name,
                                    'content' => "6Connect organisation has applied for postpaid account",
                                    'link_title' => "",
                                    'link' => "",
                                    'link2' => '');

                                $this->load->config('email');
                                save_mail($to, $to_name, $subject, $message, 1, $this->config->item('bcc_email'));
                           }

                           $messager = $this->members_model->get_member_admin($user_id);
                           foreach ($messager as $root) {
                                $to = $root->email;
                                $to_name = $root->name;
                                $subject = 'Application for postpaid account';
                                $message = array(
                                    'title' => "Application for postpaid account",
                                    'name' => $to_name,
                                    'content' => lang("payment_add_credit_info"),
                                    'link_title' => "",
                                    'link' => "",
                                    'link2' => '');

                                $this->load->config('email');
                                save_mail($to, $to_name, $subject, $message, 1, $this->config->item('bcc_email'));
                           }
                      }
                      $result['class'] = "alert-success";
                 }
            } else {
                 if (form_error('account_name')) {
                      $errors['account_name'] = form_error('account_name');
                 }
                 if (form_error('phone_number')) {
                      $errors['phone_number'] = form_error('phone_number');
                 }
                 if (form_error('address_line1')) {
                      $errors['address_line1'] = form_error('address_line1');
                 }
                 if (form_error('city')) {
                      $errors['city'] = form_error('city');
                 }
                 if (form_error('postal_code')) {
                      $errors['postal_code'] = form_error('postal_code');
                 }
                 if (form_error('country_code')) {
                      $errors['country_code'] = form_error('country_code');
                 }
                 if (form_error('deli_per_mnth')) {
                      $errors['deli_per_mnth'] = form_error('deli_per_mnth');
                 }
                 $result['status'] = 0;
                 $result['msg'] = lang('clear_error');
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function delete_postpaid_account() {
            $result = array();
            $error = false;
            $errors = array();
            $postData = json_decode(file_get_contents("php://input"));
            if (isset($postData->id) && !empty($postData->id)) {
                 $id = $postData->id;
            } else {
                 $id = 0;
            }
            if ($this->payment_accounts_model->delete_account($id)) {
                 $result['status'] = 1;
                 $result['msg'] = lang('account_deleted');
                 $result['class'] = "alert-success";
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_payments($id = 0) {
            $result = array();
            $payments = $this->organisation_model->get_service_payment_terms($id);
            if ($payments) {
                 $value = str_split($payments->payments);
                 $payment = array(
                     'sender' => $value[3] ? TRUE : FALSE,
                     'recipient' => $value[2] ? TRUE : FALSE,
                     'credit' => $value[1] ? TRUE : FALSE,
                     'credit_direct' => $value[0] ? TRUE : FALSE
                 );
                 $result['payment'] = $payment;
            }
            echo json_encode($result);
            exit();
       }

       public function save_payments() {
            /*
             * 1 - Cash on Collection
             * 2 - Cash on Delivery
             * 4 - Credit with 6Connect
             * 8 - Credit with Courier Directly
             */
            $result = array();
            $getdata = json_decode(file_get_contents("php://input"));
            $value = 0;
            $credit = 0;
            $credit_direct = 0;
            $sender = 0;
            $recipient = 0;
            if (isset($getdata->id) && !empty($getdata->id)) {
                 $id = $getdata->id;
            } else {
                 $id = 0;
            }
            $type = 1;
            $accounts = $this->payment_accounts_model->get_accounts_count($id);
            if (isset($getdata->credit) && $getdata->credit == TRUE) {
                 if ($accounts > 0) {
                      $value+=4;
                      $credit = 1;
                 } else {
                      $msg = TRUE;
                 }
            }
            if (isset($getdata->credit_direct) && $getdata->credit_direct == TRUE) {
                 if ($accounts > 0) {
                      $value+=8;
                      $credit_direct = 1;
                 } else {
                      $msg = TRUE;
                 }
            }
            if (isset($getdata->sender) && $getdata->sender == TRUE) {
                 $value+=1;
                 $sender = 1;
            }
            if (isset($getdata->recipient) && $getdata->recipient == TRUE) {
                 $value+=2;
                 $recipient = 1;
            }

            $data = array(
                'payments' => sprintf('%04d', decbin($value))
            );
            if ($this->organisation_model->edit_organisation($data, $id)) {
                 $result['status'] = 1;
                 $result['class'] = "alert-success";
                 $result['msg'] = lang('service_payment_updated');
                 if (isset($msg)) {
                      $result['msg'].=' ' . lang('credit_info');
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  