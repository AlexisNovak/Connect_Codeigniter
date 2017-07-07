<?php

  defined('BASEPATH') or exit('No direct script access allowed');

  require (APPPATH . '/libraries/REST_Controller.php');

  class Courier extends REST_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'account/account_model',
                'app/services_model',
                'app/organisation_model',
                'app/service_requests_model',
                'app/service_bids_model',
                'app/service_request_log_model',
                'app/service_request_messages_model',
                'app/request_courier_service_model',
                'couriers/couriers_model',
                'couriers/courier_email_confirmation_model',
                'couriers/courier_service_model',
                'couriers/consignment_bids_model',
                'couriers/job_acknowledgement_model',
                'couriers/jobstates_model',
                'orders/orders_model',
                'orders/consignment_messages_model',
                'orders/consignment_pod_model',
                'orders/consignment_activity_log_model'
            ));
            $this->load->config('codes');
            $this->lang->load('english');
       }

       function check_password($password_hash, $password) {
            $this->load->helper('account/phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            return $hasher->CheckPassword($password, $password_hash) ? TRUE : FALSE;
       }

       public function login_post() {
            $email = $this->post('email');
            if (!$email) {
                 $this->response(array(
                     'code' => $this->config->item('invalid_input'),
                     'message' => 'Invalid Parameter - No email'
                         ), 200);
            }
            $password = $this->post('password');
            if (!$password) {
                 $this->response(array(
                     'code' => $this->config->item('invalid_input'),
                     'message' => 'Invalid Parameter - No password'
                         ), 200);
            }

            $courier = $this->couriers_model->get_by_email($email);
            if ($courier) {
                 if (!$this->check_password($courier->password, $password)) {
                      $this->response(array(
                          'code' => $this->config->item('no_permission'),
                          'msg' => 'Login Failed invalid email or password'
                              ), 200);
                 }
                 $token = $courier->access_key;
                 if ($token) {
                      $this->response(array(
                          "code" => $this->config->item('API_OK'),
                          "message" => 'Login success',
                          "id" => $courier->courier_id,
                          "AuthCode" => $token
                              ), 200);
                 }
                 $this->response(array(
                     'code' => $this->config->item('no_permission'),
                     'msg' => 'No access Token Found'
                         ), 200);
            } else {
                 $this->response(array(
                     'code' => $this->config->item('no_permission'),
                     'msg' => 'Login Failed. This Email does not exist.'
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "AuthCode" => $token
                    ), 200);
       }

       public function register_post() {
            $email = $this->post('email');
            $name = $this->post('name');
            $password = $this->post('password');
            $url = $this->post('url');
            $description = $this->post('description');
            $logo = $this->post('logo');
            if (!$email) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No email'
                         ), 200);
            }
            if (!valid_email($email)) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - Not valid email'
                         ), 200);
            }
            if ($this->email_check($email) && !$this->invited($email)) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - Already Registered Email'
                         ), 200);
            }
            if (!$name) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No display name'
                         ), 200);
            }
            if (!$password) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No password'
                         ), 200);
            }
            if (!$url) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No 6parcels URL'
                         ), 200);
            }
            // if (!validate_url($url)) {
            // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'Invalid input - Not valid URL'), 200);
            // }
            if (!$description) {
                 $description = "";
            }
            if (!$logo) {
                 $logo = NULL;
            }

            do {
                 $token = $this->token();
            } while ($this->access_key_check($token));
            if ($this->invited($email)) {
                 $courier = $this->couriers_model->get_by_email($email);
                 $courier_id = $courier->courier_id;
                 $this->couriers_model->update($courier_id, array(
                     "company_name" => $name,
                     "access_key" => $token,
                     'is_invited' => 0
                 ));
                 $this->couriers_model->update_password($courier_id, $password);
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $token
                         ), 200);
            } else {
                 // Create courier
                 $courier_id = $this->couriers_model->create($name, $email, $password, $token, $url, $description, $logo);
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $token
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to register courier"
                    ), 200);
       }

       public function update_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $name = $this->post('company_name');
            $url = $this->post('url');
            $reg_address = $this->post('reg_address');
            $billing_address = $this->post('billing_address');
            $fullname = $this->post('fullname');
            $reg_no = $this->post('reg_no');
            $phone = $this->post('phone');
            $fax = $this->post('fax');
            $support_email = $this->post('support_email');
            $description = $this->post('description');
            $logo = $this->post('logo');
            if (!$name) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No display name'
                         ), 200);
            }
            if (!$url) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No 6parcels URL'
                         ), 200);
            }
            // if (!validate_url($url)) {
            // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'Invalid input - Not valid URL'), 200);
            // }
            if (!$reg_address) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No registered address'
                         ), 200);
            }
            if (!$billing_address) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No billing address'
                         ), 200);
            }
            if (!$fullname) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No fullname'
                         ), 200);
            }
            if (!$reg_no) {
                 $reg_no = "";
            }
            if (!$description) {
                 $description = "";
            }
            if (!$phone) {
                 $phone = "";
            }
            if (!$fax) {
                 $fax = "";
            }
            if (!$logo) {
                 $logo = NULL;
            }
            if (!$support_email) {
                 $support_email = "";
            }
            $data = array(
                "company_name" => $name,
                "url" => $url,
                "description" => $description,
                "reg_address" => $reg_address,
                "billing_address" => $billing_address,
                "fullname" => $fullname,
                "reg_no" => $reg_no,
                "phone" => $phone,
                "fax" => $fax,
                "support_email" => $support_email,
                "logo" => $logo
            );

            if ($this->couriers_model->update_by_access_key($authcode, $data)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $courier->access_key
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to update"
                    ), 200);
       }

       public function getprofile_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $profile = $this->couriers_model->getprofile($courier->courier_id);
            if ($profile) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "courier" => $profile
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => 'Failed to retrieve profile'
                    ), 200);
       }

       public function resetaccesskey_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            do {
                 $token = $this->token();
            } while ($this->access_key_check($token));
            $data = array(
                "access_key" => $token
            );
            if ($this->couriers_model->update_by_access_key($authcode, $data)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $token
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "failed to reset"
                    ), 200);
       }

       public function pushservices_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $services = $this->post("services");
            if (is_array($services)) {
                 $services = json_encode($services);
            }
            $services = json_decode($services);
            if (!$services) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No services'
                         ), 200);
            }
            foreach ($services as $sval) {
                 $svalue = (array) $sval;
                 $service_id = $svalue['service_id'];
                 if (!$service_id)
                      continue;
                 $company = (int) $svalue['company'];
                 if (!$company) {
                      $company = 0;
                      $bidding = 1;
                 } else {
                      if (!$this->organisation_model->is_exist_org($company)) {
                           continue;
                      }
                      $bidding = 0;
                 }
                 $display_name = $svalue['display_name'];
                 if (!$display_name)
                      continue;
                
                 $price = $svalue['price'];
                 if ($price)
                      $price = 0;
                 $start_time = $svalue['start_time'];
                 if (!$start_time)
                      continue;
                 $end_time = $svalue['end_time'];
                 if (!$end_time)
                      continue;
                 $week = $svalue['week'];
                 $week_0 = 0;
                 $week_1 = 0;
                 $week_2 = 0;
                 $week_3 = 0;
                 $week_4 = 0;
                 $week_5 = 0;
                 $week_6 = 0;

                 if ($week) {
                      if (strpos($week, '0') !== FALSE) {
                           $week_0 = 1;
                      }
                      if (strpos($week, '1') !== FALSE) {
                           $week_1 = 1;
                      }
                      if (strpos($week, '2') !== FALSE) {
                           $week_2 = 1;
                      }
                      if (strpos($week, '3') !== FALSE) {
                           $week_3 = 1;
                      }
                      if (strpos($week, '4') !== FALSE) {
                           $week_4 = 1;
                      }
                      if (strpos($week, '5') !== FALSE) {
                           $week_5 = 1;
                      }
                      if (strpos($week, '6') !== FALSE) {
                           $week_6 = 1;
                      }
                 }
                 $description = $svalue['description'];
                 if (!$description) {
                      $description = "";
                 }
                 $data = array(
                     'service_id' => $service_id,
                     "org_id" => $company,
                     'display_name' => $display_name,
                     'start_time' => $start_time,
                     'end_time' => $end_time,
                     'week_0' => $week_0,
                     'week_1' => $week_1,
                     'week_2' => $week_2,
                     'week_3' => $week_3,
                     'week_4' => $week_4,
                     'week_5' => $week_5,
                     'week_6' => $week_6,
                     'description' => $description,
                     'is_for_bidding' => $bidding,
                     'status' => 1,
                     'org_status' => 1,
                     'courier_id' => $courier->courier_id
                 );
                 $this->courier_service_model->suspend_service($service_id, $courier->courier_id);
                 if ($this->courier_service_model->push_service($data)) {
                      
                 }
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "access_key" => $authcode
                    ), 200);
       }

       public function publishservices_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $services = $this->post("services");
            if (is_array($services)) {
                 $services = json_encode($services);
            }
            $services = json_decode($services);
            if (!$services) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No services'
                         ), 200);
            }
            foreach ($services as $sval) {
                 $svalue = (array) $sval;
                 $service_id = $svalue['service_id'];
                 if (!$service_id)
                      continue;
                 $display_name = $svalue['display_name'];
                 if (!$display_name)
                      continue;
               
                $start_time = $svalue['start_time'];
                 if (!$start_time)
                      continue;
                 $end_time = $svalue['end_time'];
                 if (!$end_time)
                      continue;
                 $week = $svalue['week'];
                 $week_0 = 0;
                 $week_1 = 0;
                 $week_2 = 0;
                 $week_3 = 0;
                 $week_4 = 0;
                 $week_5 = 0;
                 $week_6 = 0;

                 if ($week) {
                      if (strpos($week, '0') !== FALSE) {
                           $week_0 = 1;
                      }
                      if (strpos($week, '1') !== FALSE) {
                           $week_1 = 1;
                      }
                      if (strpos($week, '2') !== FALSE) {
                           $week_2 = 1;
                      }
                      if (strpos($week, '3') !== FALSE) {
                           $week_3 = 1;
                      }
                      if (strpos($week, '4') !== FALSE) {
                           $week_4 = 1;
                      }
                      if (strpos($week, '5') !== FALSE) {
                           $week_5 = 1;
                      }
                      if (strpos($week, '6') !== FALSE) {
                           $week_6 = 1;
                      }
                 }
                 $description = $svalue['description'];
                 if (!$description) {
                      $description = "";
                 }
                 $auto_approve = $svalue['auto_approval'];
                 $data = array(
                     'service_id' => $service_id,
                     "org_id" => 0,
                     'display_name' => $display_name,
                     'start_time' => $start_time,
                     'end_time' => $end_time,
                     'week_0' => $week_0,
                     'week_1' => $week_1,
                     'week_2' => $week_2,
                     'week_3' => $week_3,
                     'week_4' => $week_4,
                     'week_5' => $week_5,
                     'week_6' => $week_6,
                     'description' => $description,
                     'is_for_bidding' => 0,
                     'status' => 1,
                     'org_status' => 1,
                     'courier_id' => $courier->courier_id,
                     'is_public' => 1,
                     'auto_approve' => $auto_approve
                 );
                 $this->courier_service_model->suspend_service($service_id, $courier->courier_id);
                 if ($this->courier_service_model->push_service($data)) {
                      
                 }
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "access_key" => $authcode
                    ), 200);
       }

       public function listservices_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $search = $this->post('search');
            $services = $this->courier_service_model->list_services($courier->courier_id, $search);
            if ($services)
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "services" => $services
                         ), 200);
            else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "services" => 'No services found'
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "access_key" => $authcode
                    ), 200);
       }

       public function removeservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $service_id = $this->post('service_id');
            if (!$service_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no service ID'
                         ), 200);
            }

            if ($this->courier_service_model->removeservice($service_id, $courier->courier_id)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "failed to remove this service"
                    ), 200);
       }

       public function suspendservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }

            $company = $this->post('company');
            if (!$company) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no company ID'
                         ), 200);
            }
            if ($this->courier_service_model->suspendservice($company, $courier->courier_id)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "failed to remove this service"
                    ), 200);
       }

       public function activateservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }

            $company = $this->post('company');
            if (!$company) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no company ID'
                         ), 200);
            }
            if ($this->courier_service_model->activateservice($company, $courier->courier_id)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "failed to remove this service"
                    ), 200);
       }

       public function assignservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $service_id = $this->post('service_id');
            if (!$service_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no service ID'
                         ), 200);
            }
            $company = (int) $this->post('company');
            if (!$company) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no company ID'
                         ), 200);
            }
            if (!$this->organisation_model->is_exist_org($company)) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - invalid company ID'
                         ), 200);
            }
            if ($this->courier_service_model->assignservice($service_id, $company)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "failed to assign this service"
                    ), 200);
       }

       public function liststatuses_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $statuses = $this->orders_model->get_statuslist();
            if ($statuses) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "statuses" => $statuses
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED')
                    ), 200);
       }

       public function listjobs_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $company = $this->post('company');
            if (!$company) {
                 $company = NULL;
            }
            $search = $this->post('search');
            if (!$search) {
                 $search = NULL;
            }
            $username = $this->post("username");
            if (!$username) {
                 $username = NULL;
            }
            $collection_address = $this->post('collection_address');
            if (!$collection_address) {
                 $collection_address = "";
            }
            $delivery_address = $this->post('delivery_address');
            if (!$delivery_address) {
                 $delivery_address = "";
            }
            $date_from = NULL;
            $collection_date_from = $this->post('collection_date_from');
            if ($collection_date_from) {
                 $date_from = $collection_date_from;
            }
            $date_to = NULL;
            $collection_date_to = $this->post('collection_date_to');
            if ($collection_date_to) {
                 $date_to = $collection_date_to;
            }
            $collection_time_from = $this->post('collection_time_from');
            if ($collection_time_from && $date_from) {
                 $date_from = $date_from . " " . $collection_time_from;
            }
            $collection_time_to = $this->post('collection_time_to');
            if ($collection_time_to && $date_to) {
                 $date_to = $date_to . " " . $collection_time_to;
            }
            $ddate_from = NULL;
            $delivery_date_from = $this->post('delivery_date_from');
            if ($delivery_date_from) {
                 $ddate_from = $delivery_date_from;
            }
            $ddate_to = NULL;
            $delivery_date_to = $this->post('delivery_date_to');
            if ($delivery_date_to) {
                 $ddate_to = $delivery_date_to;
            }
            $delivery_time_from = $this->post('delivery_time_from');
            if ($delivery_time_from && $ddate_from) {
                 $ddate_from = $ddate_from . " " . $delivery_time_from;
            }
            $delivery_time_to = $this->post('delivery_time_to');
            if ($delivery_time_to && $ddate_to) {
                 $ddate_to = $ddate_to . " " . $delivery_time_to;
            }

            $expired_date = $this->post('expired_date');
            if (!$expired_date) {
                 $expired_date = "";
            }
            $offset = $this->post('offset');
            if (!$offset) {
                 $offset = 0;
            }
            $limit = $this->post('limit');
            if (!$limit) {
                 $limit = 20;
            }
            $jobs = $this->orders_model->getjobs($search, $company, $username, $collection_address, $delivery_address, $date_from, $date_to, $ddate_from, $ddate_to, $expired_date, $offset, $limit, $courier->courier_id);
            foreach ($jobs as $job) {
                 $collection_address = json_decode($job->collection_address);
                 $job->collection_address = $collection_address[1] ? $collection_address[1] : "";
                 $delivery_address = json_decode($job->delivery_address);
                 $job->delivery_address = $delivery_address[1] ? $delivery_address[1] : "";
            }
            if ($jobs) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => $jobs
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "message" => 'No jobs found'
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED')
                    ), 200);
       }

       public function getjobdetail_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = (int) $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Job ID'
                         ), 200);
            }
            $job_details = $this->orders_model->getjobdetail($job_id);
            if ($job_details) {
                 $job_details->collection_address = implode(' ', json_decode($job_details->collection_address));
                 $job_details->delivery_address = implode(' ', json_decode($job_details->delivery_address));
                 $job_details->pod = array(
                     "signature" => null,
                     "others" => array()
                 );
                 if ($pods = $this->consignment_pod_model->get_pods($job_id)) {
                      foreach ($pods as $pod) {
                           if ($pod->is_signature) {
                                $job_details->pod['signature'] = $pod->pod_image_url;
                           } else {
                                $job_details->pod['others'][] = $pod->pod_image_url;
                           }
                      }
                 }
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => $job_details
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to retrieve job details"
                    ), 200);
       }

       public function bidforjob_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = (int) $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No job ID'
                         ), 200);
            }
            if (!$this->orders_model->is_available_for_bid($job_id)) {

                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'You can\'t bid this job'
                         ), 200);
            }
            $service_id = $this->post('service_id');
            if (!$service_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No service ID'
                         ), 200);
            }
            $service_row_id = $this->courier_service_model->get_service_row_id($service_id);
            if (!$service_row_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - Invalid service ID'
                         ), 200);
            }
            $price = (float) $this->post('price');
            if (!$price) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No price'
                         ), 200);
            }
            $remarks = $this->post('remarks');
            if (!$remarks) {
                 $remarks = NULL;
            }

            $data = array(
                'service_id' => $service_id,
                'service_row_id' => $service_row_id,
                'courier_id' => $courier->courier_id,
                'bidding_price' => $price,
                'remarks' => $remarks,
                'status' => 1
            );
            $bid_id = $this->consignment_bids_model->addbid($data);
            if ($bid_id) {
                 $this->consignment_bids_model->add_bid_consignment_relation($bid_id, $job_id, $courier->company_name);
                 $this->send_mail_for_member($job_id, N_NEW_BID_RECEIVED);
                 $this->response(array(
                     'code' => $this->config->item('API_OK'),
                     'access_key' => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to bid this job"
                    ), 200);
       }

       public function withdrawbid_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $bid_id = (int) $this->post('bid_id');
            if (!$bid_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no job ID'
                         ), 200);
            }
            if ($this->consignment_bids_model->withdrawbid($bid_id, $courier->courier_id, $courier->company_name)) {
                 $this->response(array(
                     'code' => $this->config->item('API_OK'),
                     'access_key' => $authcode
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to withdraw bid"
                    ), 200);
       }

       public function listbidhistory_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $company = $this->post('company');
            if (!$company) {
                 $company = NULL;
            }
            $username = $this->post("username");
            if (!$username) {
                 $username = NULL;
            }
            $collection_address = $this->post('collection_address');
            if (!$collection_address) {
                 $collection_address = "";
            }
            $delivery_address = $this->post('delivery_address');
            if (!$delivery_address) {
                 $delivery_address = "";
            }
            $collection_date_from = $this->post('collection_date_from');
            if (!$collection_date_from) {
                 $collection_date_from = "";
            }
            $collection_date_to = $this->post('collection_date_to');
            if (!$collection_date_to) {
                 $collection_date_to = "";
            }
            $collection_time_from = $this->post('collection_time_from');
            if (!$collection_time_from) {
                 $collection_time_from = "";
            }
            $collection_time_to = $this->post('collection_time_to');
            if (!$collection_time_to) {
                 $collection_time_to = "";
            }
            $expired_date = $this->post('expired_date');
            if (!$expired_date) {
                 $expired_date = "";
            }
            $offset = $this->post('offset');
            if (!$offset) {
                 $offset = 0;
            }
            $limit = $this->post('limit');
            if (!$limit) {
                 $limit = 20;
            }
            $jobs = $this->consignment_bids_model->getbidhistory($courier->courier_id, $company, $username, $collection_address, $delivery_address, $collection_date_from, $collection_date_to, $collection_time_from, $collection_time_to, $expired_date, $offset, $limit);
            if ($jobs) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => $jobs
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => 'No jobs found'
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to retrieve bid history"
                    ), 200);
       }

       public function listwonjob_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $company = $this->post('company');
            if (!$company) {
                 $company = NULL;
            }
            $search = $this->post('search');
            if (!$search) {
                 $search = NULL;
            }
            $username = $this->post("username");
            if (!$username) {
                 $username = NULL;
            }
            $collection_address = $this->post('collection_address');
            if (!$collection_address) {
                 $collection_address = "";
            }
            $delivery_address = $this->post('delivery_address');
            if (!$delivery_address) {
                 $delivery_address = "";
            }
            $date_from = NULL;
            $collection_date_from = $this->post('collection_date_from');
            if ($collection_date_from) {
                 $date_from = $collection_date_from;
            }
            $date_to = NULL;
            $collection_date_to = $this->post('collection_date_to');
            if ($collection_date_to) {
                 $date_to = $collection_date_to;
            }
            $collection_time_from = $this->post('collection_time_from');
            if ($collection_time_from && $date_from) {
                 $date_from = $date_from . " " . $collection_time_from;
            }
            $collection_time_to = $this->post('collection_time_to');
            if ($collection_time_to && $date_to) {
                 $date_to = $date_to . " " . $collection_time_to;
            }
            $ddate_from = NULL;
            $delivery_date_from = $this->post('delivery_date_from');
            if ($delivery_date_from) {
                 $ddate_from = $delivery_date_from;
            }
            $ddate_to = NULL;
            $delivery_date_to = $this->post('delivery_date_to');
            if ($delivery_date_to) {
                 $ddate_to = $delivery_date_to;
            }
            $delivery_time_from = $this->post('delivery_time_from');
            if ($delivery_time_from && $ddate_from) {
                 $ddate_from = $ddate_from . " " . $delivery_time_from;
            }
            $delivery_time_to = $this->post('delivery_time_to');
            if ($delivery_time_to && $ddate_to) {
                 $ddate_to = $ddate_to . " " . $delivery_time_to;
            }

            $expired_date = $this->post('expired_date');
            if (!$expired_date) {
                 $expired_date = "";
            }
            $offset = $this->post('offset');
            if (!$offset) {
                 $offset = 0;
            }
            $limit = $this->post('limit');
            if (!$limit) {
                 $limit = 20;
            }
            $status_id = $this->post('status_id');
            if (!$status_id) {
                 $status_id = NULL;
            }
            $is_confirmed = $this->post("is_confirmed");
            if (strlen($is_confirmed) == 0)
                 $is_confirmed = -1;

            $jobs = $this->orders_model->getwonjobs($courier->courier_id, $search, $company, $username, $collection_address, $delivery_address, $date_from, $date_to, $ddate_from, $ddate_to, $expired_date, $status_id, $offset, $limit, $is_confirmed);
            foreach ($jobs as $job) {
                 $job->collection_address = implode(' ', json_decode($job->collection_address));
                 $job->delivery_address = implode(' ', json_decode($job->delivery_address));
            }
            if ($jobs) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => $jobs
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_OK')
                    ), 200);
       }

       public function listjobmessages_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No job ID'
                         ), 200);
            }
            $messages = $this->consignment_messages_model->listjobmessages($job_id, $courier->courier_id);
            if ($messages) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "messages" => $messages
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "messages" => "No messages there"
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to list won jobs"
                    ), 200);
       }

       public function leavejobmessages_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No job ID'
                         ), 200);
            }
            $message = $this->post('message');
            if (!$message) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No message'
                         ), 200);
            }
            $data = array(
                'courier_id' => $courier->courier_id,
                'job_id' => $job_id,
                'question' => $message,
                'reply' => NULL
            );
            if ($this->consignment_messages_model->addmessage($data)) {
                 $this->send_mail_for_member($job_id, N_COMMENT_FROM_COURIER);
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to leave message"
                    ), 200);
       }

       public function acknowledgejob_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post("job_id");
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No job ID'
                         ), 200);
            }
            $consignment_id = $this->post("consignment_id");
            if (!$consignment_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No consignment ID'
                         ), 200);
            }

            $wonbid = $this->consignment_bids_model->getwonbid($job_id);
            if ($wonbid) {
                 $ack = array(
                     'bid_id' => $wonbid->bid_id,
                     'job_id' => $job_id,
                     'consignment_id' => $consignment_id,
                     'price' => $wonbid->bidding_price,
                     'remarks' => $wonbid->remarks,
                     'is_approved' => 0,
                     'courier_id' => $courier->courier_id
                 );
                 $this->job_acknowledgement_model->acknowledge($ack);
                 $confirm = 1;
                 $status = C_PENDING;
            } else {
                 $confirm = 1;
                 $status = C_PENDING;
                 $price = $this->post('price');
                 if (!$price) {
                      $this->response(array(
                          'code' => $this->config->item('API_ERROR'),
                          'message' => 'Invalid Input - No price'
                              ), 200);
                 }
                 $threshold = $this->orders_model->get_threshold($job_id);
                 if ($price > $threshold && $threshold != -1) {
                      $this->orders_model->updateOrder(array(
                          'change_price' => $price
                              ), $job_id);
                      $confirm = 0;
                      $threshold_break = true;
                      $status = C_PRICE_APPROVAL;
                      $this->send_mail_for_member($job_id, N_THRESHOLD, 1);
                 } else {
                      $this->orders_model->updateOrder(array(
                          'price' => $price
                              ), $job_id);
                 }
            }

            $data = array(
                'private_id' => $consignment_id,
                'consignment_status_id' => $status,
                'is_confirmed' => $confirm
            );

            if ($this->orders_model->updateOrder($data, $job_id)) {
                 if ($status == C_PENDING)
                      $this->consignment_activity_log_model->add_activity(array(
                          "order_id" => $job_id,
                          "activity" => "Order status updated to - " . C_PENDING_NAME
                      ));
                 if ($status == C_PRICE_APPROVAL)
                      $this->consignment_activity_log_model->add_activity(array(
                          "order_id" => $job_id,
                          "activity" => "Order status updated to - " . C_PRICE_APPROVAL_NAME
                      ));
                 header('Content-Type: image/jpg');
                 $this->load->library("barcode39", TRUE);
                 $bc = new Barcode39($consignment_id);
                 $bc->draw("./filebox/barcode/consignment_document_" . $consignment_id . ".png");
                 if (isset($threshold_break)) {
                      $this->response(array(
                          'code' => $this->config->item('API_ERROR'),
                          'message' => 'delivery will be pending customer\'s approval on the price to proceed'
                              ), 200);
                 }
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_FAILED'),
                     "message" => "Failed to acknowledge"
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED')
                    ), 200);
       }

       // public function acknowledgejob1_post() {
       // $authcode = $this->post('access_key');
       // if (!$authcode) {
       // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'No access Token Found'), 200);
       // }
       // $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
       // if (!$courier) {
       // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'Invalid Access Token'), 200);
       // }
       // $job_id = $this->post("job_id");
       // if (!$job_id) {
       // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'Invalid Input - No job ID'), 200);
       // }
       // $consignment_id = $this->post("consignment_id");
       // if (!$consignment_id) {
       // $this->response(array('code' => $this->config->item('API_ERROR'), 'message' => 'Invalid Input - No consignment ID'), 200);
       // }
       //
	// $wonbid = $this->consignment_bids_model->getwonbid($job_id);
       // if ($wonbid) {
       // $data = array('bid_id' => $wonbid->bid_id,
       // 'job_id' => $job_id,
       // 'consignment_id' => $consignment_id,
       // 'price' => $wonbid->bidding_price,
       // 'remarks' => $wonbid->remarks,
       // 'is_approved' => 0,
       // 'courier_id' => $courier->courier_id
       // );
       // if ($this->job_acknowledgement_model->acknowledge($data)) {
       // $this->response(array("code" => $this->config->item('API_OK'), "access_key" => $authcode), 200);
       // } else {
       // $this->response(array("code" => $this->config->item('API_FAILED'), "message" => "Failed to acknowledge"), 200);
       // }
       // } else {
       // $this->response(array("code" => $this->config->item('API_OK'), "message" => "Not won this job"), 200);
       // }
       // $this->response(array("code" => $this->config->item('API_FAILED')), 200);
       // }
       public function adjustprice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post("job_id");
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No job ID'
                         ), 200);
            }
            $price = $this->post("price");
            if (!$price) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No price'
                         ), 200);
            }
            $remarks = $this->post("remark");
            if (!$remarks) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No remarks'
                         ), 200);
            }
            $wonjob = $this->job_acknowledgement_model->getwonbid($job_id);
            if ($wonjob) {
                 if ($wonjob->price == $price)
                      $this->response(array(
                          "code" => $this->config->item('API_FAILED'),
                          "message" => "Same price"
                              ), 200);
                 $data = array(
                     "price" => $price,
                     "remarks" => $remarks
                 );
                 if ($this->job_acknowledgement_model->adjustprice($job_id, $data)) {
                      $this->response(array(
                          "code" => $this->config->item('API_OK')
                              ), 200);
                 }
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to adjust price"
                    ), 200);
       }

       public function listpricehistory_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No job ID'
                         ), 200);
            }
            $records = $this->consignment_bids_model->getpricehistory($job_id, $courier->courier_id);
            if ($records) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "records" => $records
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to retrieve price history"
                    ), 200);
       }

       public function submitjobstate_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $job_id = $this->post('job_id');
            if (!$job_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No job ID'
                         ), 200);
            }
            $status_code = $this->post('status_code');
            if (!$status_code) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No status code'
                         ), 200);
            }
            if (!in_array($status_code, array(
                        '101',
                        '102',
                        '301',
                        '311',
                        '321',
                        '401',
                        '402',
                        '501',
                        '601'
                    ))) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - Unknown status code'
                         ), 200);
            }
            $status_name = $this->post('status_name');
            if (!$status_name) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Input - No status name'
                         ), 200);
            }
            $description = $this->post('description');
            if (!$description) {
                 $description = "";
            }
            $job = $this->orders_model->get_job_permission($job_id);
            $o_courier = $job ? $job->courier_id : -1;
            if ($o_courier != $courier->courier_id) {
                 $this->response(array(
                     "code" => $this->config->item('API_FAILED'),
                     "message" => "Failed to submit job state. No permission"
                         ), 200);
            }
            $data = array(
                'job_id' => $job_id,
                'status_code' => $status_code,
                'status_name' => $status_name,
                'status_description' => $description,
                'user_type' => 2,
                'changed_user_id' => $courier->courier_id
            );
            if ($this->jobstates_model->addjobtrack($data)) {
                 $this->orders_model->updateOrder(array(
                     'consignment_status_id' => $status_code
                         ), $job_id);
                 $this->consignment_activity_log_model->add_activity(array(
                     "order_id" => $job_id,
                     "activity" => "Order status updated to - " . $status_name
                 ));
                 $this->send_mail_for_member($job_id, N_ORDER_STATUS_UPDATE);
                 $this->response(array(
                     "code" => $this->config->item('API_OK')
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to submit job state"
                    ), 200);
       }

       public function checkuserexists_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $username = $this->post("username");
            if (!$username) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no username'
                         ), 200);
            }
            $result = $this->account_model->is_exist_user($username);
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "exists" => $result
                    ), 200);

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to check user exist"
                    ), 200);
       }

       public function checkcompanyexists_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $company = $this->post("company_id");
            if (!$company) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no company ID'
                         ), 200);
            }
            $result = $this->organisation_model->is_exist_org($company);
            if ($result) {
                 $exists = TRUE;
                 $name = $result;
            } else {
                 $exists = FALSE;
                 $name = "";
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "exists" => $exists,
                "name" => $name
                    ), 200);
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to check company exist"
                    ), 200);
       }

       function email_check($email) {
            return $this->couriers_model->get_by_email($email) ? TRUE : FALSE;
       }

       function access_key_check($token) {
            return $this->couriers_model->courier_by_token($token) ? TRUE : FALSE;
       }

       function invited($email) {
            $row = $this->couriers_model->get_by_email($email);
            return $row ? (($row->is_invited) ? TRUE : FALSE) : FALSE;
       }

       private function token() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            return substr(str_shuffle($characters), 0, 13);
       }

       public function send_mail_for_member($order_id = 0, $type = 0, $settings = 0) {

            /*
             * send email notification to courier
             */
            $this->load->model('orders/orders_model');
            $this->load->model('account/notification_model');
            if ($type == N_NEW_SERVICE_BID) {
                 $members = $this->service_requests_model->get_owners($order_id);
            } else {
                 $members = $this->orders_model->get_owner($order_id);
            }
            $order = $this->orders_model->getjobdetail($order_id);
            foreach ($members as $member) {
                 $where = array(
                     'account_id' => $member->user_id,
                     'type' => 0,
                     'notification_id' => $type
                 );
                 if ($this->notification_model->get_notification($where) || $settings) {

                      switch ($type) {
                           case N_COMMENT_FROM_COURIER:
                                $title = lang('courier_comment_email_title');
                                $content = lang('courier_comment_email_content');
                                $link_title = lang('courier_comment_email_link_title');
                                $link = "";
                                break;
                           case N_ORDER_STATUS_UPDATE:
                                $title = lang('status_update_email_title');
                                $content = sprintf(lang('status_update_email_content'), $order->consignment_status);
                                $link_title = lang('status_update_email_link_title');
                                $link = "";
                                break;
                           case N_NEW_BID_RECEIVED:
                                $title = lang('new_bid_email_title');
                                $content = lang('new_bid_email_content');
                                $link_title = lang('new_bid_email_link_title');
                                $link = "";
                                break;
                           case N_NEW_SERVICE_BID:
                                $title = lang('service_bid_email_title');
                                $content = lang('service_bid_email_content');
                                $link_title = lang('service_bid_email_link_title');
                                $link = "";
                                break;
                           case N_THRESHOLD:
                                $title = lang('threshold_email_title');
                                $content = lang('threshold_email_content');
                                $link_title = lang('threshold_email_link_title');
                                $link = "";
                                break;
                           default:
                                $title = '';
                                $content = '';
                                $link_title = '';
                                $link = '';
                      }
                      $to = $member->email;
                      $to_name = $member->name;
                      $subject = '6connect email notification';
                      $message = array(
                          'title' => $title,
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => $link_title,
                          'link' => $link
                      );
                      save_mail($to, $to_name, $subject, $message,1);
                 }
                 return;
            }

            /*
             * end email send
             */
            return;
       }

       /*
        * service request related apis
        */

       public function listservicerequest_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $search = $this->post('search');
            if (!$search) {
                 $search = NULL;
            }
            $requests = $this->service_requests_model->get_request_list($search);
            if ($requests) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "requests" => $requests
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "message" => 'No request found'
                    ), 200);
       }

       public function bidservicerequest_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $req_id = (int) $this->post('req_id');
            if (!$req_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No request ID'
                         ), 200);
            }
            if (!$this->service_requests_model->is_available_for_bid($req_id)) {

                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'You can\'t bid this request'
                         ), 200);
            }
            $service_id = $this->post('service_id');
            if (!$service_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No service ID'
                         ), 200);
            }
            $service_row_id = $this->courier_service_model->get_service_row_id($service_id);
            if (!$service_row_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - Invalid service ID'
                         ), 200);
            }

            $remarks = $this->post('remarks');
            if (!$remarks) {
                 $remarks = NULL;
            }

            $data = array(
                'req_id' => $req_id,
                'service_id' => $service_row_id,
                'courier_id' => $courier->courier_id,
                'status' => 1
            );
            $bid_id = $this->service_bids_model->addbid($data);
            if ($bid_id) {
                 $this->send_mail_for_member($req_id, N_NEW_SERVICE_BID);
                 $this->service_request_log_model->add_activity(array(
                     'request_id' => $req_id,
                     'activity' => "New bid from " . $courier->company_name
                 ));
                 $this->response(array(
                     'code' => $this->config->item('API_OK'),
                     'access_key' => $authcode,
                     'bid_id' => $bid_id
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to bid this request"
                    ), 200);
       }

       public function withdrawservicerequestbid_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $bid_id = (int) $this->post('bid_id');
            if (!$bid_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - no bid ID'
                         ), 200);
            }
            if ($this->service_bids_model->withdrawbid($bid_id, $courier->courier_id)) {
                 $request = $this->service_bids_model->get_request_id($bid_id);
                 $this->service_request_log_model->add_activity(array(
                     'request_id' => $request->req_id,
                     'activity' => "Bid withdrawn by " . $courier->company_name
                 ));
                 $this->response(array(
                     'code' => $this->config->item('API_OK'),
                     'access_key' => $authcode
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to withdraw bid"
                    ), 200);
       }

       public function listservicerequestbids_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $bids = $this->service_bids_model->get_request_bids($courier->courier_id);
            if ($bids) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "bids" => $bids
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "jobs" => 'No jobs found'
                         ), 200);
            }

            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to retrieve bid history"
                    ), 200);
       }

       public function listrequestmessages_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $req_id = $this->post('req_id');
            if (!$req_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No request ID'
                         ), 200);
            }
            $messages = $this->service_request_messages_model->list_req_messages($req_id, $courier->courier_id);
            if ($messages) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "messages" => $messages
                         ), 200);
            } else {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "messages" => "No messages there"
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to list messages"
                    ), 200);
       }

       public function leaverequestmessage_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $req_id = $this->post('req_id');
            if (!$req_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No request ID'
                         ), 200);
            }
            $message = $this->post('message');
            if (!$message) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No message'
                         ), 200);
            }
            $data = array(
                'courier_id' => $courier->courier_id,
                'request_id' => $req_id,
                'question' => $message,
                'reply' => NULL,
                'type' => 2
            );
            if ($this->service_request_messages_model->add_message($data)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to leave message"
                    ), 200);
       }

       public function listepreservicerequests_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $search = $this->post('search');
            if (!$search) {
                 $search = NULL;
            }
            $requests = $this->request_courier_service_model->get_requests($courier->courier_id, $search);
            if ($requests) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "requests" => $requests,
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "msg" => "No requests found",
                "access_key" => $authcode
                    ), 200);
       }

       public function approvepreservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $req_id = $this->post('req_id');
            if (!$req_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No request ID'
                         ), 200);
            }
            $request = $this->request_courier_service_model->get_request($req_id);
            if ($request) {
                 $this->request_courier_service_model->approve_service($request->request_id, $request->service_id, $request->org_id, $courier->courier_id);
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "msg" => "Request approved",
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to approve"
                    ), 200);
       }

       public function rejectpreservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $req_id = $this->post('req_id');
            if (!$req_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No request ID'
                         ), 200);
            }
            $remarks = $this->post('remarks');
            if (!$remarks) {
                 $remarks = NULL;
            }
            if ($this->request_courier_service_model->reject_request($req_id, $remarks, $courier->courier_id)) {
                 $this->response(array(
                     "code" => $this->config->item('API_OK'),
                     "msg" => "Request rejected",
                     "access_key" => $authcode
                         ), 200);
            }
            $this->response(array(
                "code" => $this->config->item('API_FAILED'),
                "message" => "Failed to reject"
                    ), 200);
       }

       public function updatePOD_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'No access Token Found'
                         ), 200);
            }
            $courier = $this->couriers_model->getCourier_by_token_with_approval($authcode);
            if (!$courier) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid Access Token'
                         ), 200);
            }
            $consignment_id = $this->post('consignment_id');
            if (!$consignment_id) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No consignment ID'
                         ), 200);
            }

            $job = $this->orders_model->get_job_permission($consignment_id);
            $o_courier = $job ? $job->courier_id : -1;
            if ($o_courier != $courier->courier_id) {
                 $this->response(array(
                     "code" => $this->config->item('API_FAILED'),
                     "message" => "Failed to update POD. No permission"
                         ), 200);
            }
            $filename = $this->post('filename');
            if (!$filename) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No filename'
                         ), 200);
            }
            $pod_image = $this->post('pod_image');
            if (!$pod_image) {
                 $this->response(array(
                     'code' => $this->config->item('API_ERROR'),
                     'message' => 'Invalid input - No image'
                         ), 200);
            }
            $upload_dir = "filebox/pod/";
            $originalFilename = $filename;
            $exploded = explode('.', $originalFilename);
            $extension = end($exploded);
            if (!file_exists($upload_dir)) {
                 @mkdir($upload_dir, 0777, true);
            }
            $output_file = $upload_dir . time() . '.' . $extension;
            $ifp = fopen($output_file, "wb");
            $encoded = htmlspecialchars_decode($pod_image);
            fwrite($ifp, base64_decode($encoded));
            fclose($ifp);

            if (!file_exists($output_file)) {
                 $this->response(array(
                     "code" => $this->config->item('API_FAILED'),
                     "message" => "Could not write file on server. "
                         ), 200);
            }
            $is_sign = $this->post('is_sign');
            if (isset($is_sign) && ($is_sign == 1 || strtolower($is_sign) == "true")) {
                 $is_signature = 1;
            } else {
                 $is_signature = 0;
            }
            $remarks = $this->post('remarks');
            if (!$remarks) {
                 $remarks = NULL;
            }
            $data = array();
            $data['pod_image_url'] = base_url($output_file);
            $data['is_signature'] = $is_signature;
            $data['courier_id'] = $courier->courier_id;
            if ($is_signature) {
                 $this->consignment_pod_model->add_signature($consignment_id, $data);
            } else {
                 if ($this->consignment_pod_model->get_count_by_consignment_id($consignment_id) < 3) {
                      $this->consignment_pod_model->add_pod($consignment_id, $data);
                 } else {
                      $this->response(array(
                          "code" => $this->config->item('API_FAILED'),
                          "message" => "No more images can add"
                              ), 200);
                 }
            }
            $this->response(array(
                "code" => $this->config->item('API_OK'),
                "msg" => "POD updated",
                "access_key" => $authcode
                    ), 200);
       }

  }
  