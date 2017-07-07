<?php

  class Multiorders extends MY_Controller {

       public $collection_info;
       public $delivery_info;
       public $error_data;
       public $file_info;
       public $type;

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'orders_model',
                'consignment_types_model',
                'account/ref_country_model',
                'account/ref_zoneinfo_model',
                'consignment_activity_log_model',
                'couriers/couriers_model'
            ));
            $this->load->library('form_validation');
       }

       public function multiOrder() {
            $this->load->model('app/organisation_model');
            $user = $this->session->userdata("user_id");
            $data['organisations'] = $this->organisation_model->myorganisations($user);
            $count = count($data['organisations']);
            $data['org_count'] = $count;
            $this->load->view('multi_order', $data);
       }

       public function view_orders($c_group_id = 0) {
            $data = array();
            $data['id'] = $c_group_id;
            $data['courier'] = $this->orders_model->get_courier_by_cgroup_id($c_group_id);
            $this->load->view('multiple_orders_list', $data);
       }

       private function _validate_collection_info($orderdata, $confirm = FALSE) {
            $error = FALSE;
            $error_part1 = 0;
            $error_part3 = 0;
            $collect_address = array();
            if (isset($orderdata->collect_from_l1) && !empty($orderdata->collect_from_l1)) {
                 $collect_address[] = htmlentities(trim(str_replace(",", " ", $orderdata->collect_from_l1)));
            } else {
                 $error = true;
                 $errors['collect_from_l1'] = "Invalid";
            }
            if (isset($orderdata->collect_from_l2) && !empty($orderdata->collect_from_l2)) {
                 $collect_address[] = htmlentities(str_replace(",", " ", trim($orderdata->collect_from_l2)));
            } else {
                 $error = true;
                 $errors['collect_from_l2'] = "Invalid";
            }
            if (isset($orderdata->collection_zipcode) && !empty($orderdata->collection_zipcode)) {
                 if (is_numeric($orderdata->collection_zipcode)) {
                      $collection_zipcode = htmlentities(trim($orderdata->collection_zipcode));
                 } else {
                      $error = true;
                      $errors['collection_zipcode'] = "Postal code must be integer";
                 }
            } else {
                 $error = true;
                 $errors['collection_zipcode'] = "Invalid";
            }
            if (isset($orderdata->collect_country) && !empty($orderdata->collect_country)) {
                 $collect_country = htmlentities(trim($orderdata->collect_country));
            } else {
                 $error = true;
                 $errors['collect_country'] = "Invalid";
            }
            if (isset($orderdata->is_c_restricted_area) && !empty($orderdata->is_c_restricted_area)) {
                 $is_c_restricted_area = 1;
            } else {
                 $is_c_restricted_area = 0;
            }
            if (isset($orderdata->collect_contactname) && !empty($orderdata->collect_contactname)) {
                 $collect_contactname = htmlentities(trim($orderdata->collect_contactname));
            } else {
                 $error = true;
                 $errors['collect_contactname'] = "Invalid";
            }
            if (isset($orderdata->collect_email) && !empty($orderdata->collect_email)) {
                 $collect_email = htmlentities(trim($orderdata->collect_email));
            } else {
                 $collect_email = '';
            }
            if (isset($orderdata->collect_company) && !empty($orderdata->collect_company)) {
                 $collect_company = htmlentities(trim($orderdata->collect_company));
            } else {
                 $collect_company = '';
            }
            if (isset($orderdata->collect_phone) && !empty($orderdata->collect_phone)) {
                 $collect_phone = htmlentities(trim($orderdata->collect_phone));
            } else {
                 $error = true;
                 $errors['collect_phone'] = "Invalid";
            }
            if (isset($orderdata->collect_date1) && !empty($orderdata->collect_date1)) {
                 $collect_date1 = $orderdata->collect_date1;
                 $collect_date_from = date("Y-m-d H:i:s", strtotime($collect_date1));
            } else {
                 $error = true;
                 $errors['collect_date1'] = "Invalid";
            }
            if (isset($orderdata->collect_date2) && !empty($orderdata->collect_date2)) {
                 $collect_date2 = $orderdata->collect_date2;
                 $collect_date_to = date("Y-m-d H:i:s", strtotime($collect_date2));
                 if ($collect_date_to < $collect_date_from) {
                      $error = true;
                      $errors['collect_date2'] = "Must be greater than from date";
                 }
            } else {
                 $error = true;
                 $errors['collect_date2'] = "Invalid";
            }
            if (isset($orderdata->collect_timezone) && !empty($orderdata->collect_timezone)) {
                 $collect_timezone = $orderdata->collect_timezone->zoneinfo;
            } else {
                 $error = true;
                 $errors['collect_timezone'] = "Invalid";
            }
            if (isset($orderdata->payment_mode) && !empty($orderdata->payment_mode)) {
                 $payment_json = $orderdata->payment_mode;
                 $payment_mode = htmlentities(trim($payment_json->value));
                 $payment_mode = str_replace('_', "0", $payment_mode);
                 $payment_mode = bindec($payment_mode) ? bindec($payment_mode) : 1;
                 $account_id = $payment_json->id ? $payment_json->id : 0;
            } else {
                 $payment_mode = 1;
                 $account_id = 0;
            }
            if (isset($orderdata->template_type) && !empty($orderdata->template_type)) {
                 $this->type = $orderdata->template_type;
            } else {
                 $this->type = 0;
            }
            if (!$confirm) {
                 if (isset($orderdata->upload) && !empty($orderdata->upload)) {
                      if (is_file(FCPATH . 'filebox/massconsignment/' . $orderdata->upload)) {
                           $this->file_info = $orderdata->upload;
                      } else {
                           $error = true;
                           $errors['upload'] = "Invalid";
                      }
                 } else {
                      $error = true;
                      $errors['upload'] = "Invalid";
                 }
            }
            if (isset($orderdata->org_id) && !empty($orderdata->org_id)) {
                 $org_id = (int) $orderdata->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($orderdata->delivery_is_assign) && $orderdata->delivery_is_assign == 2) {
                 $delivery_is_assign = 0;
                 $c_status = C_GETTING_BID;
                 $service_id = 0;
                 $is_for_bidding = 1;
                 $assigned_service = NULL;
                 if (isset($orderdata->open_bid) && !empty($orderdata->open_bid)) {
                      $open_bid = 1;
                 } else {
                      $open_bid = 0;
                 }
                 if (isset($orderdata->deadline) && !empty($orderdata->deadline)) {
                      $deadline_date = date("Y-m-d H:i:s", strtotime($orderdata->deadline));
                 } else {
                      if ($error) {
                           $error_part1 = 1;
                      }
                      $error_part3 = 1;
                      $error = true;
                      $errors['deadline'] = "Invalid";
                 }
            } else {
                 $delivery_is_assign = 1;
                 $open_bid = 0;
                 $is_for_bidding = 0;
                 $deadline_date = NULL;
                 if (isset($orderdata->assigned_service) && !empty($orderdata->assigned_service)) {
                      $assigned_service = $orderdata->assigned_service;
                      $service_id = (int) $orderdata->assigned_service->service_id;
                      $c_status = C_PENDING_ACCEPTANCE;
                 } else {
                      if ($error) {
                           $error_part1 = 1;
                      }
                      $error_part3 = 1;
                      $error = true;
                      $errors['assigned_service'] = "Please select one service";
                 }
            }
            if (!$error) {
                 $this->collection_info = array(
                     'service_id' => $service_id,
                     "assigned_service" => $assigned_service,
                     "org_id" => $org_id,
                     'delivery_is_assign' => $delivery_is_assign,
                     'is_for_bidding' => $is_for_bidding,
                     'is_c_restricted_area' => $is_c_restricted_area,
                     'is_open_bid' => $open_bid,
                     'deadline_date' => $deadline_date,
                     'collection_address' => json_encode($collect_address),
                     'collection_date' => $collect_date_from,
                     'collection_date_to' => $collect_date_to,
                     'collection_country' => $collect_country,
                     'collection_timezone' => $collect_timezone,
                     'collection_post_code' => $collection_zipcode,
                     'collection_contact_name' => $collect_contactname,
                     'collection_contact_number' => $collect_phone,
                     'collection_contact_email' => $collect_email,
                     'collection_company_name' => $collect_company,
                     'payment_type' => $payment_mode,
                     'payment_acount_id' => $account_id,
                     'consignment_status' => $c_status
                 );
                 return TRUE;
            } else {
                 $this->error_data['collect'] = $errors;
                 if ($error_part1) {
                      $this->error_data['part'] = 1;
                 } else
                 if ($error_part3) {
                      $this->error_data['part'] = 3;
                 } else {
                      $this->error_data['part'] = 1;
                 }
                 return FALSE;
            }
       }

       function _new_validate_mass_consignment() {
            $this->form_validation->reset_form_validation();
            $this->form_validation->set_error_delimiters(' ', ' ');
            $this->form_validation->set_rules('delivery_address', 'delivery address', 'trim|required');
            $this->form_validation->set_rules('delivery_city', 'delivery city', 'trim|required');
            $this->form_validation->set_rules('delivery_post_code', 'delivery post code', 'trim|min_length[2]|numeric|max_length[12]|required');
            $this->form_validation->set_rules('consignment_type_id', 'parcel type', 'numeric|callback_is_type');
            $this->form_validation->set_rules('delivery_country', 'delivery country', 'trim|required|callback_is_country');
            $this->form_validation->set_rules('delivery_date_from', 'delivery date from', 'trim|required');
            $this->form_validation->set_rules('delivery_time_from', 'delivery time from', 'trim|required');
            $this->form_validation->set_rules('delivery_date_to', 'delivery date to', 'trim|required');
            $this->form_validation->set_rules('delivery_time_to', 'delivery time to', 'trim|required');
            $this->form_validation->set_rules('delivery_timezone', 'timezone', 'trim');
            $this->form_validation->set_rules('delivery_contact_name', 'contact name', 'trim|required');
            $this->form_validation->set_rules('delivery_contact_email', 'contact email', 'trim|valid_email');
            $this->form_validation->set_rules('delivery_contact_phone', 'contact number', 'trim|numeric|required');
            $this->form_validation->set_rules('remarks', 'remarks', 'trim');
            if ($this->input->post('consignment_type_id') == 0) {
                 $this->form_validation->set_rules('height', 'height', 'trim|numeric|required');
                 $this->form_validation->set_rules('length', 'length', 'trim|numeric|required');
                 $this->form_validation->set_rules('breadth', 'breadth', 'trim|numeric|required');
                 $this->form_validation->set_rules('weight', 'weight', 'trim|numeric|required');
            }
            return ($this->form_validation->run($this) == FALSE) ? FALSE : TRUE;
       }

       public function is_country($name) {
            $country = $this->ref_country_model->get($name);
            if ($country) {
                 $_POST['country'] = $country->country;
                 return TRUE;
            } else {
                 $this->form_validation->set_message('is_country', 'Unknown country');
                 return FALSE;
            }
       }

       public function is_type() {
            $type_id = $this->input->post('consignment_type_id');
            $type = $this->consignment_types_model->getType($type_id);
            if ($type) {
                 return TRUE;
            } else {
                 $this->form_validation->set_message('is_type', 'Unknown parcel type');
                 return FALSE;
            }
       }

       /**
        * 
        */
       public function processOrder() {
            set_time_limit(0);
            $result = array();
            $error = false;
            $orderdata = json_decode(file_get_contents('php://input'));
            if ($this->_validate_collection_info($orderdata)) {
                 $file_info = $this->file_info;
                 $type = $this->type;
                 if ($type == 1) {
                      $filepath = 'filebox/massconsignment/';
                      $inputFileName = $filepath . $file_info;
                      $consignments = $this->read_consignment_from_file($inputFileName);
                      if (!empty($consignments) && count($consignments) > 1) {
                           $delivery_info = array();
                           foreach ($consignments as $k => $consignment) {
                                if ($k >= 2 && $k < 252) {
                                     $delivery_info[] = array(
                                         "consignment_type_id" => isset($consignment['A']) ? $consignment['A'] : 0,
                                         'ref' => isset($consignment['B']) ? $consignment['B'] : "",
                                         'remarks' => isset($consignment['C']) ? $consignment['C'] : "",
                                         'tags' => isset($consignment['D']) ? $consignment['D'] : "",
                                         'length' => isset($consignment['E']) ? $consignment['E'] : "",
                                         'breadth' => isset($consignment['F']) ? $consignment['F'] : "",
                                         'height' => isset($consignment['G']) ? $consignment['G'] : "",
                                         'weight' => isset($consignment['H']) ? $consignment['H'] : "",
                                         'delivery_address' => isset($consignment['I']) ? $consignment['I'] : "",
                                         'delivery_city' => isset($consignment['J']) ? $consignment['J'] : "",
                                         'delivery_country' => isset($consignment['K']) ? ucfirst($consignment['K']) : "",
                                         'delivery_post_code' => isset($consignment['L']) ? $consignment['L'] : "",
                                         'is_d_restricted_area' => isset($consignment['M']) ? ((strtoupper($consignment['M']) == "Y") ? 1 : 0) : 0,
                                         'delivery_date_from' => isset($consignment['N']) ? date("Y-m-d", strtotime($consignment['N'])) : "",
                                         'delivery_date_to' => isset($consignment['O']) ? date("Y-m-d", strtotime($consignment['O'])) : "",
                                         'delivery_time_from' => isset($consignment['P']) ? date("H:i:s", strtotime($consignment['P'])) : "",
                                         'delivery_time_to' => isset($consignment['Q']) ? date("H:i:s", strtotime($consignment['Q'])) : "",
                                         'delivery_contact_name' => isset($consignment['R']) ? $consignment['R'] : "",
                                         'delivery_contact_phone' => isset($consignment['S']) ? $consignment['S'] : "",
                                         'delivery_contact_email' => isset($consignment['T']) ? $consignment['T'] : "",
                                         'delivery_company_name' => isset($consignment['U']) ? $consignment['U'] : "",
                                         'send_notification_to_consignee' => isset($consignment['V']) ? ((strtoupper($consignment['V']) == "Y") ? 1 : 0) : 0
                                     );
                                }
                           }

                           $c_group_id = uniqid();
                           $inform_courier = false;
                           $service_id = $this->collection_info['service_id'];
                           $order_count = 0;
                           $order_id = 0;

                           foreach ($delivery_info as $row => $file_data) {
                                $_POST = $file_data;
                                if ($this->_new_validate_mass_consignment()) {
                                     $delivery = (array) ($file_data);
                                     $description = '';
                                     $user_id = $this->session->userdata('user_id');
                                     $this->load->helper('string');
                                     $country = $this->ref_country_model->get($delivery['delivery_country']);
                                     $zones = $this->ref_zoneinfo_model->get_by_country($country->code);
                                     do {
                                          $public_id = random_string('numeric', 14);
                                     } while (!$this->orders_model->is_unique_publicid($public_id));
                                     $private_id = "";
                                     $order_data = array(
                                         'private_id' => $private_id,
                                         'public_id' => $public_id,
                                         "org_id" => $this->collection_info['org_id'],
                                         'is_read' => 0,
                                         'c_group_id' => $c_group_id,
                                         'consignment_type_id' => $delivery['consignment_type_id'],
                                         'description' => $description,
                                         'price' => 0,
                                         'customer_id' => $user_id,
                                         'service_id' => $service_id,
                                         'is_service_assigned' => $this->collection_info['delivery_is_assign'],
                                         "is_for_bidding" => $this->collection_info['is_for_bidding'],
                                         'is_open_bid' => $this->collection_info['is_open_bid'],
                                         'bidding_deadline' => $this->collection_info['deadline_date'],
                                         'quantity' => 1,
                                         'is_bulk' => ($delivery['consignment_type_id'] == 0) ? 1 : 0,
                                         'length' => isset($delivery['length']) ? round($delivery['length'], 3) : 0,
                                         'breadth' => isset($delivery['breadth']) ? round($delivery['breadth'], 3) : 0,
                                         'height' => isset($delivery['height']) ? round($delivery['height'], 3) : 0,
                                         'volume' => round($delivery['length'] * $delivery['breadth'] * $delivery['height'], 3),
                                         'weight' => isset($delivery['weight']) ? round($delivery['weight'], 3) : 0,
                                         'collection_address' => $this->collection_info['collection_address'],
                                         'collection_date' => $this->collection_info['collection_date'],
                                         'collection_date_to' => $this->collection_info['collection_date_to'],
                                         'collection_country' => $this->collection_info['collection_country'],
                                         'is_c_restricted_area' => $this->collection_info['is_c_restricted_area'],
                                         'collection_timezone' => $this->collection_info['collection_timezone'],
                                         'delivery_address' => json_encode(array(
                                             $delivery['delivery_address'],
                                             $delivery['delivery_city']
                                         )),
                                         'delivery_post_code' => $delivery['delivery_post_code'],
                                         'delivery_country' => $country->code,
                                         'is_d_restricted_area' => $delivery['is_d_restricted_area'],
                                         'delivery_timezone' => isset($zones[0]) ? $zones[0]->zoneinfo : "",
                                         'delivery_date' => $delivery['delivery_date_from'] . " " . $delivery['delivery_time_from'],
                                         'delivery_date_to' => $delivery['delivery_date_to'] . " " . $delivery['delivery_time_to'],
                                         'delivery_contact_name' => $delivery['delivery_contact_name'],
                                         'delivery_contact_email' => $delivery['delivery_contact_email'],
                                         'delivery_contact_phone' => $delivery['delivery_contact_phone'],
                                         'delivery_company_name' => $delivery['delivery_company_name'],
                                         'created_user_id' => $user_id,
                                         'collection_post_code' => $this->collection_info['collection_post_code'],
                                         'collection_contact_name' => $this->collection_info['collection_contact_name'],
                                         'collection_contact_number' => $this->collection_info['collection_contact_number'],
                                         'collection_contact_email' => $this->collection_info['collection_contact_email'],
                                         'collection_company_name' => $this->collection_info['collection_company_name'],
                                         'send_notification_to_consignee' => $delivery['send_notification_to_consignee'],
                                         'consignment_status_id' => $this->collection_info['consignment_status'],
                                         'payment_type' => $this->collection_info['payment_type'],
                                         'payment_acount_id' => $this->collection_info['payment_acount_id'],
                                         'remarks' => $delivery['remarks'],
                                         'ref' => $delivery['ref'],
                                         'tags' => $delivery['tags']
                                     );
                                     $insert_id = $this->orders_model->addOrder($order_data);
                                     if ($insert_id) {
                                          if (empty($order_id))
                                               $order_id = $insert_id;
                                          $order_count++;
                                          $this->consignment_activity_log_model->add_activity(array(
                                              "order_id" => $insert_id,
                                              "activity" => "New order added to the system"
                                          ));
                                          $this->generate_barcode(CONSIGNMENT_PREFIX . $insert_id);
                                          $this->generate_barcode($public_id);
                                          $this->generate_ciqrcode($insert_id);
                                          if ($order_data['consignment_status_id'] == C_PENDING_ACCEPTANCE) {
                                               $inform_courier = true;
                                          }

                                          $result['group_id'] = $c_group_id;
                                          $result['new'] = true;
                                     }
                                }
                           }
                           if ($inform_courier) {
                                $owner = $this->orders_model->get_owner($order_id);
                                if (is_array($owner))
                                     $owner = $owner[0];

                                $courier = $this->couriers_model->get_by_service($service_id);
                                $content = sprintf(lang('direct_assign_email_multipleorder_titile'), $order_count, $owner->name);
                                $link_title = lang('direct_assign_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders');
                                $to = $courier->email;
                                $to_name = $courier->company_name;
                                $subject = lang('6connect_email_notification');
                                $message = array(
                                    'title' => '',
                                    'name' => $to_name,
                                    'content' => $content,
                                    'link_title' => $link_title,
                                    'link' => $link
                                );

                                $extname = substr($file_info, strrpos($file_info, "."));
                                $attachmentFilename = $filepath . $public_id . $extname;
                                rename($inputFileName, $attachmentFilename);
                                save_mail($to, $to_name, $subject, $message, 2, '', '', array(
                                    realpath($attachmentFilename)
                                ));
                           }
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New mass order processed successfully";
                      }
                      else {
                           $result['status'] = 0;
                           $result['class'] = "alert-warning";
                           $result['msg'] = "No data found in the uploaded file.";
                      }
                      if (is_file($inputFileName))
                           unlink($inputFileName);
                 } else {
                      $template_id = NULL;
                      $inputFileName = 'filebox/massconsignment/' . $file_info;
                      $consignments = $this->read_consignment_from_file($inputFileName);
                      if (!empty($consignments)) {
                           $template_id = $consignments[1]['B'];
                      }
                      $result['status'] = 1;
                      $result['class'] = "alert-success";
                      $result['msg'] = "Custom template processed successfully";
                 }
                 if ($type)
                      $this->orders_model->update_template_prference($this->session->userdata('user_id'), $type);
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-warning";
                 $result['msg'] = lang('clear_error');
                 $result['errors'] = $this->error_data;
            }
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
       }

       public function upload_and_process() {
            $error_free = array();
            $uploadPath = "filebox/massconsignment/";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }

            $uploadPath1 = "filebox/file/";
            if (!file_exists($uploadPath1)) {
                 mkdir($uploadPath1, 0777, TRUE);
            }
            if (empty($_FILES)) {
                 echo false;
            }

            $config['upload_path'] = 'filebox/massconsignment/';
            $config['allowed_types'] = 'xls|xlsx';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                 echo json_encode(array(
                     'status' => FALSE,
                     'error' => $this->upload->display_errors()
                 ));
            } else {
                 $data = $this->upload->data();
                 if ($this->input->post('type')) {
                      $type = $this->input->post('type', TRUE);
                 } else {
                      $type = 0;
                 }
                 if ($type == 1) {
                      $inputFileName = 'filebox/massconsignment/' . $data['file_name'];
                      $consignments = $this->read_consignment_from_file($inputFileName);
                      if (!empty($consignments)) {
                           $delivery_info = array();
                           $total_rec = 0;
                           $total_found = -1;
                           foreach ($consignments as $k => $consignment) {
                                $total_found++;
                                if ($k >= 2 && $k < 252) {
                                     $total_rec++;
                                     $delivery_info[] = array(
                                         "consignment_type_id" => isset($consignment['A']) ? $consignment['A'] : 0,
                                         'ref' => isset($consignment['B']) ? $consignment['B'] : "",
                                         'remarks' => isset($consignment['C']) ? $consignment['C'] : "",
                                         'tags' => isset($consignment['D']) ? $consignment['D'] : "",
                                         'length' => isset($consignment['E']) ? $consignment['E'] : "",
                                         'breadth' => isset($consignment['F']) ? $consignment['F'] : "",
                                         'height' => isset($consignment['G']) ? $consignment['G'] : "",
                                         'weight' => isset($consignment['H']) ? $consignment['H'] : "",
                                         'delivery_address' => isset($consignment['I']) ? $consignment['I'] : "",
                                         'delivery_city' => isset($consignment['J']) ? $consignment['J'] : "",
                                         'delivery_country' => isset($consignment['K']) ? ucfirst($consignment['K']) : "",
                                         'delivery_post_code' => isset($consignment['L']) ? $consignment['L'] : "",
                                         'is_d_restricted_area' => isset($consignment['M']) ? strtoupper($consignment['M']) : "N",
                                         'delivery_date_from' => isset($consignment['N']) ? date("Y-m-d", strtotime($consignment['N'])) : "",
                                         'delivery_date_to' => isset($consignment['O']) ? date("Y-m-d", strtotime($consignment['O'])) : "",
                                         'delivery_time_from' => isset($consignment['P']) ? date("H:i:s", strtotime($consignment['P'])) : "",
                                         'delivery_time_to' => isset($consignment['Q']) ? date("H:i:s", strtotime($consignment['Q'])) : "",
                                         'delivery_contact_name' => isset($consignment['R']) ? $consignment['R'] : "",
                                         'delivery_contact_phone' => isset($consignment['S']) ? $consignment['S'] : "",
                                         'delivery_contact_email' => isset($consignment['T']) ? $consignment['T'] : "",
                                         'delivery_company_name' => isset($consignment['U']) ? $consignment['U'] : "",
                                         'send_notification_to_consignee' => isset($consignment['V']) ? strtoupper($consignment['V']) : "N"
                                     );
                                }
                           }
                           if ($total_found > 250) {
                                $exceed = 1;
                           } else {
                                $exceed = 0;
                           }
                           $error_data = array();
                           $err_rec = 0;
                           foreach ($delivery_info as $row => $file_data) {
                                $_POST = $file_data;
                                $from_date_valid = !$this->is_date_valid($file_data['delivery_date_from']);
                                $to_date_valid = !$this->is_date_valid($file_data['delivery_date_to']);
                                if (!$this->_new_validate_mass_consignment() || $from_date_valid || $to_date_valid) {
                                     $err_rec++;
                                     $errors = form_error('consignment_type_id') . form_error('is_type') . form_error('delivery_address') . form_error('delivery_post_code') . form_error('delivery_country') . form_error('is_country') . form_error('delivery_date') . form_error('delivery_timezone') . form_error('delivery_contact_name') . form_error('delivery_contact_email') . form_error('delivery_contact_phone') . form_error('length') . form_error('height') . form_error('breadth') . form_error('weight')
                                             . ($from_date_valid ? lang('multiple_order_from_date_invalid') : '' ) . ($to_date_valid ? lang('multiple_order_to_date_invalid') : '');
                                     $error_data[$row] = $errors;
                                     $delivery_info[$row]['error'] = $errors;
                                } else {
                                     $delivery_info[$row]['error'] = "";
                                     $error_free[] = $file_data;
                                }
                           }
                           if ($err_rec > 0) {
                                $link = $this->create_error_log($delivery_info);
                                $download_url = site_url('orders/multiorders/download_error_log/' . $link);
                           }
                      } else {
                           $delivery_info = array();
                           $error_data = array();
                      }
                      $view = '';
                      $sno = 1;
                      foreach ($error_free as $key => $delivery) {
                           $view = $view . '<tr>' . '<td>' . $delivery['consignment_type_id'] . '</td>
            <td>' . $delivery['ref'] . '</td>
            <td>' . $delivery['remarks'] . '</td>
            <td>' . $delivery['tags'] . '</td>
            <td>' . $delivery['length'] . '</td>
            <td>' . $delivery['breadth'] . '</td>
            <td>' . $delivery['height'] . '</td>
            <td>' . $delivery['weight'] . '</td>
            <td>' . $delivery['delivery_address'] . '</td>
            <td>' . $delivery['delivery_city'] . '</td>
            <td>' . $delivery['delivery_country'] . '</td>
            <td>' . $delivery['delivery_post_code'] . '</td>
            <td>' . $delivery['is_d_restricted_area'] . '</td>
            <td>' . $delivery['delivery_date_from'] . '</td>
            <td>' . $delivery['delivery_date_to'] . '</td>
            <td>' . $delivery['delivery_time_from'] . '</td>
            <td>' . $delivery['delivery_time_to'] . '</td>
            <td> ' . $delivery['delivery_contact_name'] . '</td>
            <td>' . $delivery['delivery_contact_phone'] . '</td>
            <td>' . $delivery['delivery_contact_email'] . '</td>
            <td>' . $delivery['delivery_company_name'] . '</td>
            <td>' . $delivery['send_notification_to_consignee'] . '</td></tr>';
                           $sno++;
                      }
                      $result = array(
                          "files" => $data['file_name'],
                          'total' => $total_rec,
                          'err_rec' => $err_rec,
                          "error_free" => $view,
                          'exceed' => $exceed
                      );
                      if (isset($link)) {
                           $result['link'] = sprintf(lang('error_log_p'), anchor($download_url, lang('error_log'), array(
                               "target" => '_blank'
                           )));
                      }
                      echo json_encode($result);
                 } else {
                      $template_id = NULL;
                      $inputFileName = 'filebox/massconsignment/' . $data['file_name'];
                      $consignments = $this->read_consignment_from_file($inputFileName);
                      if (!empty($consignments)) {
                           $template_id = $consignments[1]['B'];
                      }
                      echo json_encode(array(
                          "files" => $data['file_name'],
                          "template" => $template_id
                      ));
                 }
                 exit();
            }
       }

       function generate_barcode($id) {
            $uploadPath = "filebox/barcode";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            header('Content-Type: image/jpg');
            $this->load->library("barcode39", TRUE);
            $bc = new Barcode39($id);
            $bc->draw("filebox/barcode/consignment_document_" . $id . ".png");
            return;
       }

       function generate_ciqrcode($file_name = NULL) {
            if (!empty($file_name)) {
                 $this->load->library('ciqrcode');
                 $params['level'] = 'H';
                 $params['size'] = 4;

                 $uploadPath = "filebox/ciqrcode";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777, TRUE);
                 }

                 $file_path = "filebox/ciqrcode/" . $file_name . '.png';
                 if (!file_exists($file_path)) {
                      $params['savename'] = $file_path;
                      $params['data'] = $file_name;
                      $this->ciqrcode->generate($params);
                 }

                 return base_url() . $file_path;
            } else {
                 return '';
            }
       }

       public function read_consignment_from_file($file_path) {
            $this->load->library('Excel');

            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(false);
            $objPHPExcel = $objReader->load($file_path);
            $sheet = $objPHPExcel->getSheet(0);

            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;

            $consignment_datas = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);

            return $consignment_datas;
       }

       function mass_consignment_template() {
            $this->load->helper('download');
            if (file_exists("resource/templates/6connect_multiorder_template.xlsx")) {
                 $data = file_get_contents("resource/templates/6connect_multiorder_template.xlsx"); // Read the file's contents
                 $name = '6connect_mass_consignment_template.xlsx';
                 force_download($name, $data);
            } else
                 show_error('Template not found.');
       }

       function download_error_log($file) {
            $this->load->helper('download');
            if (file_exists('filebox/error_log/' . $file)) {
                 $data = file_get_contents('filebox/error_log/' . $file); // Read the file's contents
                 $name = 'mass_delivery_upload_error_log.xlsx';
                 force_download($name, $data);
            } else
                 show_error('Template not found.');
       }

       public function create_error_log($delivery_info) {
            $uploadPath = "filebox/error_log/";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }

            $this->load->library('Excel');
            $objPHPExcel = new Excel();
            $objPHPExcel->getProperties()
                    ->setTitle("title")
                    ->setDescription("description");
            // Assign cell values
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Parcel Type');
            $objPHPExcel->getActiveSheet()->setCellValue('b1', 'Alt. track numbers');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Remarks');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tags');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Length');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Width');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Height');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Weight');
            $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Address 1');
            $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Address 2');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Country');
            $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Postal Code');
            $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Restricted');
            $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Date (From)');
            $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Date (To)');
            $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Time (From)');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Time (To)');
            $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Name');
            $objPHPExcel->getActiveSheet()->setCellValue('S1', 'Phone');
            $objPHPExcel->getActiveSheet()->setCellValue('T1', 'Email');
            $objPHPExcel->getActiveSheet()->setCellValue('U1', 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValue('V1', 'Email Notification');
            $objPHPExcel->getActiveSheet()->setCellValue('W1', 'Errors');
            $objPHPExcel->getActiveSheet()
                    ->getStyle('W1')
                    ->applyFromArray(array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'FAD4D2'
                            )
                        )
            ));

            $row = 2;
            foreach ($delivery_info as $key => $value) {
                 $column = 0;
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value['consignment_type_id']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value['ref']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value['remarks']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 3, $row, $value['tags']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 4, $row, $value['length']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 5, $row, $value['breadth']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 6, $row, $value['height']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 7, $row, $value['weight']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 8, $row, $value['delivery_address']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 9, $row, $value['delivery_city']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 10, $row, $value['delivery_country']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 11, $row, $value['delivery_post_code']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 12, $row, $value['is_d_restricted_area']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 13, $row, $value['delivery_date_from']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 14, $row, $value['delivery_date_to']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 15, $row, $value['delivery_time_from']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 16, $row, $value['delivery_time_to']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 17, $row, $value['delivery_contact_name']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 18, $row, $value['delivery_contact_phone']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 19, $row, $value['delivery_contact_email']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 20, $row, $value['delivery_company_name']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 21, $row, $value['send_notification_to_consignee']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 22, $row, $value['error']);
                 if ($value['error']) {
                      $objPHPExcel->getActiveSheet()
                              ->getStyle('V' . $row)
                              ->applyFromArray(array(
                                  'fill' => array(
                                      'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                      'color' => array(
                                          'rgb' => 'FAD4D2'
                                      )
                                  )
                      ));
                 }
                 $row++;
                 foreach (range('A', 'U') as $columnID) {
                      $objPHPExcel->getActiveSheet()
                              ->getColumnDimension($columnID)
                              ->setAutoSize(true);
                 }
            }

            // Save it as an excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $file_name = time() . '.xls';
            $objWriter->save('filebox/error_log/' . $file_name);
            return $file_name;
       }

       private function is_date_valid($date) {
            return strtotime($date) > now();
       }

  }
  