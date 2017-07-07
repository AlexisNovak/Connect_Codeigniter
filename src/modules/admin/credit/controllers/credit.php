<?php

  class Credit extends MY_Controller {

       function __construct() {
            $this->load->model(array('payment_accounts_model',
                'account/account_model',
                'account/account_details_model',
                'app/organisation_model',
                'app/members_model', 'account/ref_country_model'));
            parent::__construct();
       }

       public function index() {
            $data = array();
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $this->load->view('accounts', $data);
       }

       public function members() {
            $data = array();
            $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
            if ($account->root) {
                 $data['is_admin'] = 1;
            }
            $this->load->view('members', $data);
       }

       public function organisations() {
            $data = array();
            $this->load->view('organisations', $data);
       }

       public function organisationlist_json() {
            $perpage = '';
            $search = '';
            $orgData = json_decode(file_get_contents('php://input'));

            if ($orgData != NULL && isset($orgData->perpage_value)) {

                 $perpage = $orgData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($orgData->filter)) {
                 if ($orgData->filter != NULL) {
                      $search = $orgData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            if (isset($orgData->currentPage)) {

                 $page = $orgData->currentPage;
            } else {
                 $page = 1;
            }
            $total_result = $this->organisation_model->getallorg_count($search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $organisation_detail = $this->organisation_model->getallorg($perpage, $search, $start);
            $result['end'] = (int) ($start + count($organisation_detail));
            $orgns_detail = array();
            foreach ($organisation_detail as $org_detail) {
                 $organisation_admin_user = $this->organisation_model->get_admin_user_by_org_id($org_detail->id);
                 $admin_users = '';
                 foreach ($organisation_admin_user as $admin_user) {
                      $admin_users .=$admin_user->username . ',';
                 }
                 if ($org_detail->role_id == 3) {
                      $path = "leads";
                 } else {
                      $path = "members";
                 }
                 $orgns_detail[] = array(
                     'id' => $org_detail->id,
                     'role_id' => $path,
                     'name' => $org_detail->name,
                     'shortname' => $org_detail->shortname,
                     'description' => $org_detail->description,
                     'website' => $org_detail->website,
                     'adminusers' => rtrim($admin_users, ','),
                     'scount' => $org_detail->scount ? 0 : 1
                 );
            }
            $result['organisations'] = $orgns_detail;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function view_org($id = 0) {
            $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
            if ($account->root) {
                 $data['is_admin'] = 1;
            } $data['org_id'] = $id;
            $this->load->view('org_info', $data);
       }

       public function memberslist_json() {
            $perpage = '';
            $search = NULL;
            $membersData = json_decode(file_get_contents('php://input'));
            if (isset($membersData->perpage_value)) {

                 $perpage = $membersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($membersData->currentPage)) {

                 $page = $membersData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($membersData->filter)) {
                 if ($membersData->filter != NULL) {
                      $search = $membersData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->members_model->getallmemberaccounts_count($search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['member_detail'] = $this->members_model->getallmemberaccounts($perpage, $search, $start);
            $result['end'] = (int) ($start + count($result['member_detail']));
            echo json_encode($result);
       }

       public function view_member($id = 0) {
            $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
            if ($account->root) {
                 $data['is_admin'] = 1;
            }
            $data['countries'] = $this->ref_country_model->get_all();
            $data['languages'] = $this->config->item("languages");
            $this->load->view('member_info', $data);
       }

       public function get_member_info() {
            $result = array();
            $membersData = json_decode(file_get_contents('php://input'));
            if (isset($membersData->id)) {

                 $id = $membersData->id;
            } else {
                 $id = 0;
            }
            $result['user'] = $this->members_model->get_member_details($id);
            echo json_encode($result);
            exit();
       }

       public function update_member() {
            $error = false;
            $errors = array();
            $result = array();
            $userdata = json_decode(file_get_contents('php://input'));
            if (isset($userdata->id) && !empty($userdata->id)) {
                 $id = $userdata->id;
            } else {
                 $error = TRUE;
                 $errors['id'] = "Invalid";
            }
            if (isset($userdata->email) && !empty($userdata->email)) {
                 $email = $userdata->email;
                 if (!valid_email($email)) {
                      $error = TRUE;
                      $errors['email_error'] = "Invalid email";
                 } else {
                      $row = $this->account_model->get_by_email($email);
                      if ($row) {
                           if ($row->id !== $id) {
                                $error = TRUE;
                                $errors['email_error'] = lang('settings_email_exist');
                           }
                      }
                 }
            } else {
                 $error = TRUE;
                 $errors['email_error'] = "Invalid email";
            }
            $update = array();
            if (isset($userdata->fullname) && !empty($userdata->fullname)) {
                 $update['fullname'] = $userdata->fullname;
            }
            if (isset($userdata->description) && !empty($userdata->description)) {
                 $update['description'] = $userdata->description;
            }
            if (isset($userdata->phone_no) && !empty($userdata->phone_no)) {
                 $update['phone_no'] = $userdata->phone_no;
            }
            if (isset($userdata->fax_no) && !empty($userdata->fax_no)) {
                 $update['fax_no'] = $userdata->fax_no;
            }
            if (isset($userdata->country) && !empty($userdata->country)) {
                 $update['country'] = $userdata->country;
            }
            if (isset($userdata->language) && !empty($userdata->language)) {
                 $language = $userdata->language;
            }
            if (!$error) {
                 $this->account_model->update_email($id, $email);
                 if ($update) {
                      $this->account_model->update_language($id, $language);
                      $this->account_details_model->update($id, $update);
                 }
                 $result['status'] = 1;
                 $result['class'] = "alert-success";
                 $result['msg'] = 'Account details updated';
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
                 $result['errors'] = $errors;
            }
            echo json_encode($result);
            exit();
       }

       public function get_my_accounts() {
            $result = array();
            $id = $this->session->userdata('user_id');
            $type = 1;
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
//            $this->form_validation->set_rules('country_code', 'Country', 'trim|required');
            $this->form_validation->set_rules('deli_per_mnth', 'Deliveries/Month', 'trim|numeric');
            return $this->form_validation->run($this) !== FALSE;
       }

       public function add_postpaid_account() {
            $result = array();
            $error = false;
            $errors = array();
            $postData = json_decode(file_get_contents("php://input"));
            $_POST = (array) $postData;
            $user_id = $this->session->userdata('user_id');
            if ($this->_validate_account()) {
                 $account_no = $this->input->post('account_holder', TRUE);
                 $user_data = $this->payment_accounts_model->getOneWhere(array('id' => $user_id), 'member');

                 $new_ac_data = array(
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
                     'comments' => $this->input->post('comments', TRUE)
                 );
//                 debug($user_data);
                 if ($account_no != '') {
                      $org = $this->payment_accounts_model->getOneWhere(array('id' => $account_no), 'organizations');
                      $new_ac_data['organisation'] = $org->name;
                      $account_holder = $account_no;
                      $holder_type = 2;
                 } else {
                      $account_holder = $user_id;
                      $holder_type = 1;
                 }
                 $new_account = array(
                     'account_holder' => $account_holder,
                     'holder_type' => $holder_type, //user
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

                 $Rmessage = "6Connect organisation has applied for postpaid account from " . ucfirst($user_data->username) . " (" . $user_data->email . "). <br/><br/>
                      <h3 style='font-weight:bold'>Form data as below</h3>";
                 foreach ($new_ac_data as $key => $value) {
                      $Rmessage = $Rmessage . ucfirst(str_replace('_', " ", $key)) . ":" . $value . "<br>";
                 }

                 if ($this->input->post('account_id', TRUE)) {
                      $account_id = $this->input->post('account_id', TRUE);
                 } else {
                      $account_id = NULL;
                 }
                 if ($this->payment_accounts_model->add_account($new_account, $account_id)) {
                      $result['status'] = 1;
                      $result['class'] = "alert-success";
                      if ($account_id != NULL) {
                           $result['msg'] = lang('account_updated');
                      } else {
                           $result['msg'] = lang("payment_add_credit_info");
                           //inform root admin    

                           $root_admins = $this->members_model->get_root_admin();
                           foreach ($root_admins as $root) {
                                if ($root->id == $user_id) {
                                     continue;
                                }
                                $to = $root->email;
                                $to_name = "Admin";
                                $subject = 'New Account Added';
                                $message = array(
                                    'title' => "Application for postpaid account",
                                    'name' => $to_name,
                                    'content' => $Rmessage,
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

       public function get_payments() {
            $result = array();
            $id = $this->session->userdata('user_id');
            $payments = $this->account_details_model->get_service_payment_terms($id);
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
            $id = $this->session->userdata('user_id');
            $type = 1;
            $accounts = $this->payment_accounts_model->get_accounts_count($id, $type);
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
            } else {
                 $sender = 0;
            }
            if (isset($getdata->recipient) && $getdata->recipient == TRUE) {
                 $value+=2;
                 $recipient = 1;
            } else {
                 $recipient = 0;
            }
            $data = array(
                'payments' => sprintf('%04d', decbin($value))
            );
            if ($this->account_details_model->update($id, $data)) {
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
  