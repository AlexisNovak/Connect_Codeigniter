<?php

  class Contacts extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('contacts_model');
       }

       public function index() {
            $this->load->view('contacts_list');
       }

       public function get_total_contacts() {
            $result = array();
            $user_id = $this->session->userdata('partner_user_id');
            $result['total'] = $this->contacts_model->get_contactlist_count($user_id, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function _validate_contact() {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('contact_name', 'Name', 'required|trim|max_length[40]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
            $this->form_validation->set_rules('company_name', 'Company name', 'trim');
            $this->form_validation->set_rules('dept_name', 'Department name', 'trim');
            if ($this->input->post('dept_name')) {
                 $this->form_validation->set_rules('company_name', 'Company name', 'required');
            }
            $this->form_validation->set_rules('address_line1', 'Address line 1', 'trim|required');
            $this->form_validation->set_rules('address_line2', 'Address line 2', 'trim');
            $this->form_validation->set_rules('postal_code', 'Postal code', 'trim|required|numeric');
            $this->form_validation->set_rules('country_code', 'Country', 'trim|required');
            return $this->form_validation->run($this) !== FALSE;
       }

       public function save_contact() {
            $result = array();
            $contact_info = json_decode(file_get_contents('php://input'));
            $_POST = (array) $contact_info;
            if ($this->_validate_contact()) {
                 $contact = array(
                     'contact_name' => $this->input->post('contact_name', TRUE),
                     'email' => $this->input->post('email', TRUE) ? $this->input->post('email', TRUE) : NULL,
                     'phone_number' => $this->input->post('phone_number', TRUE),
                     'company_name' => $this->input->post('company_name', TRUE) ? $this->input->post('company_name', TRUE) : NULL,
                     'department_name' => $this->input->post('dept_name', TRUE) ? $this->input->post('dept_name', TRUE) : NULL,
                     'address_line1' => $this->input->post('address_line1', TRUE),
                     'address_line2' => $this->input->post('address_line2', TRUE),
                     'postal_code' => $this->input->post('postal_code', TRUE),
                     'country_code' => $this->input->post('country_code', TRUE),
                     'user_id' => $this->session->userdata('partner_user_id')
                 );
                 if ($this->input->post('id')) {
                      //update contact
                      if ($this->contacts_model->update_contact($contact, array('id' => $this->input->post('id', TRUE)))) {
                           if ($this->input->post('share_contact')) {
                                $this->contacts_model->delete_shared_contacts($this->input->post('id', TRUE));
                                if ($this->input->post('orgs')) {
                                     $orgs = $this->input->post('orgs');
                                     $share_orgs = array();
                                     foreach ($orgs as $org) {
                                          $share_orgs[] = array(
                                              'contact_id' => $this->input->post('id', TRUE),
                                              'org_id' => $org->org_id,
                                              "shared_person" => $this->session->userdata('partner_user_id'));
                                     }
                                     if ($share_orgs)
                                          $this->contacts_model->add_shared_contacts($share_orgs);
                                }
                           }else {
                                $this->contacts_model->delete_shared_contacts($this->input->post('id', TRUE));
                           }

                           $result['status'] = 1;
                           $result['msg'] = lang('contact_updated');
                           $result['class'] = 'alert-success';
                      }
                 } else {

                      //add new contact
                      $contact_id = $this->contacts_model->add_contact($contact);

                      if ($contact_id) {
                           if ($this->input->post('share_contact')) {
                                if ($this->input->post('orgs')) {
                                     $orgs = $this->input->post('orgs');
                                     $share_orgs = array();
                                     foreach ($orgs as $org) {
                                          $share_orgs[] = array('contact_id' => $contact_id,
                                              'org_id' => $org->org_id,
                                              "shared_person" => $this->session->userdata('partner_user_id')
                                          );
                                     }
                                     if ($share_orgs)
                                          $this->contacts_model->add_shared_contacts($share_orgs);
                                }
                           }
                           $result['status'] = 1;
                           $result['id'] = $contact_id;
                           $result['msg'] = lang('new_contact_saved');
                           $result['class'] = 'alert-success';
                      }
                 }
            } else {
                 $errors = array();
                 if (form_error('contact_name')) {
                      $errors['contact_name'] = form_error('contact_name');
                 }
                 if (form_error('company_name')) {
                      $errors['company_name'] = form_error('company_name');
                 }
                 if (form_error('email')) {
                      $errors['email'] = form_error('email');
                 }
                 if (form_error('postal_code')) {
                      $errors['postal_code'] = form_error('postal_code');
                 }
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = 'alert-danger';
                 $result['errors'] = $errors;
            }

            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function contactlist_json() {
            $perpage = '';
            $search = '';
            $contactData = json_decode(file_get_contents('php://input'));

            if ($contactData != NULL && isset($contactData->perpage_value)) {

                 $perpage = $contactData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($contactData->filter)) {
                 if ($contactData->filter != NULL) {
                      $search = $contactData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            if (isset($contactData->currentPage)) {

                 $page = $contactData->currentPage;
            } else {
                 $page = 1;
            }
            $user_id = $this->session->userdata('partner_user_id');
            $total_result = $this->contacts_model->get_contactlist_count($user_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $contacts = $this->contacts_model->get_contactlist($user_id, $perpage, $search, $start);
            foreach ($contacts as $contact) {
                 if ($contact->user_id == $user_id) {
                      $contact->is_shared = FALSE;
                 } else {
                      $contact->is_shared = TRUE;
                 }
                 $shared = $this->contacts_model->get_shared_orgs($contact->id);
                 if (count($shared) > 0) {
                      $contact->share_contact = TRUE;
                      $contact->orgs = $shared;
                 } else {
                      $contact->share_contact = FALSE;
                 }
            }
            $result['end'] = (int) ($start + count($contacts));

            $result['contacts'] = $contacts;
            //exit(print_r($orgns_detail));

            if (isset($contactData->c_id) && !empty($contactData->c_id)) {
                 $c_id = (int) $contactData->c_id;
                 $result['recent'] = $this->get_recent_contacts($user_id, $c_id, $search);
            }

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       public function delete_contact() {
            $result = array();
            $user_id = $this->session->userdata('partner_user_id');
            $contactData = json_decode(file_get_contents('php://input'));
            if (isset($contactData->contact_id) && !empty($contactData->contact_id)) {
                 $contact_id = (int) $contactData->contact_id;
            } else {
                 $contact_id = 0;
            }
            if ($this->contacts_model->delete_contact($contact_id, $user_id)) {
                 $result['status'] = 1;
                 $result['msg'] = lang('contact_deleted');
                 $result['class'] = 'alert-success';
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = 'alert-danger';
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_recent_contacts($user_id, $c_id, $search) {
            $r_contacts = $this->contacts_model->list_my_recent($user_id, $c_id, $search);
            return $r_contacts;
       }

       public function save_recent() {
            $result = array();
            $contact_info = json_decode(file_get_contents('php://input'));
            if (isset($contact_info->from) && !empty($contact_info->from)) {
                 $from = (int) $contact_info->from;
            }
            if (isset($contact_info->to) && !empty($contact_info->to)) {
                 $to = (int) $contact_info->to;
            }
            if (isset($from) && isset($to)) {
                 if ($from && $to) {
                      $user = $this->session->userdata('partner_user_id');
                      $data = array(
                          'from_contact_id' => $from,
                          'to_contact_id' => $to,
                          'user_id' => $user
                      );
                      $this->contacts_model->add_recent($data);
                 }
            }
            $result['status'] = 1;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            ;
            exit();
       }

  }
  