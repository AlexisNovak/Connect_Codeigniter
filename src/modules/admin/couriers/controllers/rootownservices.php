<?php

  class Rootownservices extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'couriers_model',
                'courier_service_model',
                'surcharge_items_model',
                'app/organisation_model',
                'account/account_model'
            ));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $data = array();
            $this->load->view('courierservices_list', $data);
       }

       public function newservice() {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $this->load->view('new_service', $data);
       }

       public function view($id = 0) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $service = $this->courier_service_model->get_details_by_id($id);
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            if ($service) {
                 $sdays = array();
                 if ($service->week_0 == 1) {
                      $sdays[] = $days[0];
                 }
                 if ($service->week_1 == 1) {
                      $sdays[] = $days[1];
                 }
                 if ($service->week_2 == 1) {
                      $sdays[] = $days[2];
                 }
                 if ($service->week_3 == 1) {
                      $sdays[] = $days[3];
                 }
                 if ($service->week_4 == 1) {
                      $sdays[] = $days[4];
                 }
                 if ($service->week_5 == 1) {
                      $sdays[] = $days[5];
                 }
                 if ($service->week_6 == 1) {
                      $sdays[] = $days[6];
                 }
                 $service_details = array();
                 $service_details['is_public'] = $service->is_public;
                 $service_details['name'] = $service->display_name;
                 $service_details['start time'] = $service->start_time;
                 $service_details['end time'] = $service->end_time;
                 $service_details['payment term'] = $service->payment_terms;
                 $service_details['working days'] = implode(', ', $sdays);
                 $service_details['origin'] = '-';
                 $origin = $this->courier_service_model->get_service_origin($id);
                 if ($origin) {
                      $service_details['origin'] = $origin->country;
                 }
                 $service_details['destination'] = '-';
                 $d_list = explode(',', $service->destination);
                 $destinations = $this->courier_service_model->get_service_destination($d_list);
                 if ($destinations) {
                      $destination = array();
                      foreach ($destinations as $value) {
                           $destination[] = $value->country;
                      }
                      $service_details['destination'] = implode(', ', $destination);
                 } else {
                      $service_details['destination'] = 'Anywhere';
                 }
                 $service_details['description'] = $service->description;
                 $service_details['status'] = ($service->status == 1) ? 'Active' : "Suspended";
                 $service_details['bidding service'] = $service->is_for_bidding ? 'Yes' : 'No';
                 if ($service->is_for_bidding) {
                      $service_details['public service'] = $service->is_public ? 'Yes' : 'No';
                      $service_details['auto approve'] = $service->auto_approve ? 'Yes' : 'No';
                 } else {
                      $service_details['organisation'] = $service->org_name;
                      if ($service->org_status == 2) {
                           $service_details['organisation status'] = 'Approved';
                      }
                      if ($service->org_status == 1) {
                           $service_details['organisation status'] = 'Pending';
                      }
                      if ($service->org_status == 3) {
                           $service_details['organisation status'] = 'Rejected';
                      }
                 }

                 $data['service'] = $service_details;
            }
            $this->load->view('view_service', $data);
       }

       public function countrylist() {
            $result = array();
            $this->load->model("account/ref_country_model");
            $result['countries'] = $this->ref_country_model->get_all();
            echo json_encode($result);
            exit();
       }

       public function countrylist_i() {
            $result = array();
            $this->load->model("account/ref_country_model");
            $all = array(
                array(
                    'code' => "all",
                    'country' => "Anywhere"
                )
            );
            $countries = (array) $this->ref_country_model->get_all_array();
            $result['countries'] = array_merge($all, $countries);
            echo json_encode($result);
            exit();
       }

       function serviceslist_json() {
            $perpage = '';
            $search = '';
            $category = NULL;
            $servicesData = json_decode(file_get_contents('php://input'));
            if (isset($servicesData->perpage_value)) {

                 $perpage = $servicesData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($servicesData->currentPage)) {
                 $page = $servicesData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($servicesData->filter)) {
                 if ($servicesData->filter != NULL) {
                      $search = $servicesData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            if (isset($servicesData->category)) {
                 if ($servicesData->category != NULL) {
                      $catg = $servicesData->category;
                      switch ($catg) {
                           case "0":$category = NULL;
                                break;
                           case "1":$category = 1;
                                break;
                           case "2":$category = 2;
                                break;
                           case "3":$category = 3;
                                break;
                      }
                 }
            }
            $courier_id = $this->session->userdata("courier_id");
            $total_result = $this->courier_service_model->getallserviceslist_count($courier_id, $search, $category);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            $services = $this->courier_service_model->getallserviceslist_by_courier($courier_id, $perpage, $search, $start, $category);
            foreach ($services as $service) {
                 $service->type = ucfirst($service->type);
                 $sdays = array();
                 if ($service->week_0 == 1) {
                      $sdays[] = $days[0];
                 }
                 if ($service->week_1 == 1) {
                      $sdays[] = $days[1];
                 }
                 if ($service->week_2 == 1) {
                      $sdays[] = $days[2];
                 }
                 if ($service->week_3 == 1) {
                      $sdays[] = $days[3];
                 }
                 if ($service->week_4 == 1) {
                      $sdays[] = $days[4];
                 }
                 if ($service->week_5 == 1) {
                      $sdays[] = $days[5];
                 }
                 if ($service->week_6 == 1) {
                      $sdays[] = $days[6];
                 }
                 $service->days = implode(', ', $sdays);
                 $service->cutoff = $service->start_time . ' - ' . $service->end_time;
                 unset($service->week_0);
                 unset($service->week_1);
                 unset($service->week_2);
                 unset($service->week_3);
                 unset($service->week_4);
                 unset($service->week_5);
                 unset($service->week_6);
                 unset($service->start_time);
                 unset($service->end_time);
                 $origin = $this->courier_service_model->get_service_origin($service->id);
                 if ($origin) {
                      $service->origin = $origin->country;
                 }
                 $d_list = explode(',', $service->destination);
                 $destinations = $this->courier_service_model->get_service_destination($d_list);
                 if ($destinations) {
                      $destination = array();
                      foreach ($destinations as $value) {
                           $destination[] = $value->country;
                      }
                      $service->destination = implode(', ', $destination);
                 } else {
                      $service->destination = 'Anywhere';
                 }
                 if (strlen($service->description) > 150) {
                      $length = strpos($service->description, ' ', 150);
                      $service->description = substr($service->description, 0, $length) . '...';
                 }
            }
            $result['service_detail'] = $services;
            $result['current_user_id'] = $this->session->userdata('user_id');
            $result['end'] = (int) ($start + count($result['service_detail']));
            echo json_encode($result);
       }

       public function suspend_service() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $error = false;
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->status) && !empty($service_data->status)) {
                 $status = (int) $service_data->status;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 if ($status == 1) {
                      $result = $this->courier_service_model->suspend_service_by_id($service_id);
                 } elseif ($status == 3) {
                      $result = $this->courier_service_model->activate_service($service_id);
                 }
            }
            debug($this->db->last_query());
            echo json_encode($result);
            exit();
       }

       public function edit($id = 0) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $service = $this->courier_service_model->get_details_by_id($id);
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            if ($service) {
                 $sdays = array();
                 if ($service->week_0 == 1) {
                      $sdays[] = $days[0];
                 }
                 if ($service->week_1 == 1) {
                      $sdays[] = $days[1];
                 }
                 if ($service->week_2 == 1) {
                      $sdays[] = $days[2];
                 }
                 if ($service->week_3 == 1) {
                      $sdays[] = $days[3];
                 }
                 if ($service->week_4 == 1) {
                      $sdays[] = $days[4];
                 }
                 if ($service->week_5 == 1) {
                      $sdays[] = $days[5];
                 }
                 if ($service->week_6 == 1) {
                      $sdays[] = $days[6];
                 }
                 $service_details = array();
                 $service_details['is_public'] = $service->is_public;
                 $service_details['name'] = $service->display_name;
                 $service_details['start time'] = $service->start_time;
                 $service_details['end time'] = $service->end_time;
                 $service_details['payment term'] = $service->payment_terms;
                 $service_details['working days'] = implode(', ', $sdays);
                 $service_details['origin'] = '-';
                 $origin = $this->courier_service_model->get_service_origin($id);
                 if ($origin) {
                      $service_details['origin'] = $origin->country;
                 }
                 $service_details['destination'] = '-';
                 $d_list = explode(',', $service->destination);
                 $destinations = $this->courier_service_model->get_service_destination($d_list);
                 if ($destinations) {
                      $destination = array();
                      foreach ($destinations as $value) {
                           $destination[] = $value->country;
                      }
                      $service_details['destination'] = implode(', ', $destination);
                 } else {
                      $service_details['destination'] = 'Anywhere';
                 }
                 $service_details['description'] = $service->description;
                 $service_details['status'] = ($service->status == 1) ? 'Active' : "Suspended";
                 $service_details['bidding service'] = $service->is_for_bidding ? 'Yes' : 'No';
                 if ($service->is_for_bidding) {
                      $service_details['public service'] = $service->is_public ? 'Yes' : 'No';
                      $service_details['auto approve'] = $service->auto_approve ? 'Yes' : 'No';
                 } else {
                      $service_details['organisation'] = $service->org_name;
                      if ($service->org_status == 2) {
                           $service_details['organisation status'] = 'Approved';
                      }
                      if ($service->org_status == 1) {
                           $service_details['organisation status'] = 'Pending';
                      }
                      if ($service->org_status == 3) {
                           $service_details['organisation status'] = 'Rejected';
                      }
                 }

                 $data['service'] = $service_details;
            }
            $this->load->view('edit_service', $data);
       }

       public function get_service($service_id = 0) {
            $result = array();
            $service = $this->courier_service_model->get_by_id($service_id);
            if ($service) {
                 $service->week_0 = $service->week_0 ? TRUE : FALSE;
                 $service->week_1 = $service->week_1 ? TRUE : FALSE;
                 $service->week_2 = $service->week_2 ? TRUE : FALSE;
                 $service->week_3 = $service->week_3 ? TRUE : FALSE;
                 $service->week_4 = $service->week_4 ? TRUE : FALSE;
                 $service->week_5 = $service->week_5 ? TRUE : FALSE;
                 $service->week_6 = $service->week_6 ? TRUE : FALSE;
                 $service->is_public = $service->is_public ? TRUE : FALSE;
                 $service->auto_approve = $service->auto_approve ? TRUE : FALSE;
                 $service->destination = explode(',', $service->destination);
                 $service->payment_term = explode(',', $service->payment_terms);
                 $result['service'] = $service;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function update_service() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));


            if (isset($post_data->courier_id) && !empty($post_data->courier_id)) {
                 $courier_id = intval($post_data->courier_id);
            } else {
                 $courier_id = NULL;
            }


            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = $post_data->id;
            } else {
                 $error = TRUE;
                 $errors['id'] = "Invalid ID";
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $isvalidservice = $this->couriers_model->getCourierService_by_id($post_data->service_id, isset($id) ? $id : NULL);
                 if ($isvalidservice) {
                      $service_id = $post_data->service_id;
                 } else {
                      $error = TRUE;
                      $errors['service_id'] = "Please enter unique service ID";
                 }
            } else {
                 $error = TRUE;
                 $errors['service_id'] = "Invalid service ID";
            }
            if (isset($post_data->display_name) && !empty($post_data->display_name)) {
                 $display_name = $post_data->display_name;
            } else {
                 $error = TRUE;
                 $errors['display_name'] = "Invalid";
            }
            if (isset($post_data->start_time) && !empty($post_data->start_time)) {
                 $start_time = date('H:i:s', strtotime($post_data->start_time));
            } else {
                 $error = TRUE;
                 $errors['start_time'] = "Invalid";
            }
            if (isset($post_data->end_time) && !empty($post_data->end_time)) {
                 $end_time = date('H:i:s', strtotime($post_data->end_time));
            } else {
                 $error = TRUE;
                 $errors['end_time'] = "Invalid";
            }
            if (isset($post_data->week_0) && !empty($post_data->week_0)) {
                 $week_0 = 1;
            } else {
                 $week_0 = 0;
            }
            if (isset($post_data->week_1) && !empty($post_data->week_1)) {
                 $week_1 = 1;
            } else {
                 $week_1 = 0;
            }
            if (isset($post_data->week_2) && !empty($post_data->week_2)) {
                 $week_2 = 1;
            } else {
                 $week_2 = 0;
            }
            if (isset($post_data->week_3) && !empty($post_data->week_3)) {
                 $week_3 = 1;
            } else {
                 $week_3 = 0;
            }
            if (isset($post_data->week_4) && !empty($post_data->week_4)) {
                 $week_4 = 1;
            } else {
                 $week_4 = 0;
            }
            if (isset($post_data->week_5) && !empty($post_data->week_5)) {
                 $week_5 = 1;
            } else {
                 $week_5 = 0;
            }
            if (isset($post_data->week_6) && !empty($post_data->week_6)) {
                 $week_6 = $post_data->week_6;
            } else {
                 $week_6 = 0;
            }

            if (isset($post_data->is_public) && $post_data->is_public == TRUE) {
                 $is_public = 1;
            } else {
                 $is_public = 0;
            }
            if (isset($post_data->auto_approve) && $post_data->auto_approve == TRUE) {
                 $auto_approve = 1;
            } else {
                 $auto_approve = 0;
            }
            if (isset($post_data->description) && !empty($post_data->description)) {
                 $description = $post_data->description;
            } else {
                 $description = "";
            }
            if (isset($post_data->origin) && !empty($post_data->origin)) {
                 $origin = $post_data->origin;
            } else {
                 $error = TRUE;
                 $errors['origin'] = "Please provide origin";
            }
            if (isset($post_data->destination) && !empty($post_data->destination)) {
                 $destination = $post_data->destination;
                 if (is_array($destination)) {
                      $destination = implode(",", $destination);
                 }
            } else {
                 $error = TRUE;
                 $errors['destination'] = "Please provide destinations";
            }
            if (isset($post_data->payment_term) && !empty($post_data->payment_term)) {
                 $payment_term = $post_data->payment_term;
                 if (is_array($payment_term)) {
                      $payment_term = implode(", ", $payment_term);
                 }
            } else {
                 $payment_term = "";
            }

            if (isset($post_data->status) && !empty($post_data->status)) {
                 $status = intval($post_data->status);
            } else {
                 $status = 0;
            }

            if (isset($post_data->load_limit) && !empty($post_data->load_limit)) {
                 $load_limit = intval($post_data->load_limit);
            } else {
                 $load_limit = NULL;
            }


            $deliveryTime = "90-minute";
            if (isset($post_data->deliverytime) && !empty($post_data->deliverytime)) {
                 $datadeliverytime = $post_data->deliverytime;
                 if (isset($datadeliverytime->data)) {
                      $deliveryTime = $datadeliverytime->data;
                 }
            }

            if (!$error) {
                 $previous = $this->courier_service_model->get_service_row($id);
                 $data = (array) $previous;
                 $data['is_archived'] = 1;
                 $data['is_new'] = 0;
                 $data['archived_on'] = date('Y-m-d H:i:s');
                 unset($data['id']);
                 $new_service = array(
                     'service_id' => $service_id,
                     "org_id" => $previous->org_id,
                     'display_name' => $display_name,
                     'start_time' => $start_time,
                     'end_time' => $end_time,
                     'delivery_time' => $deliveryTime,
                     'week_0' => $week_0,
                     'week_1' => $week_1,
                     'week_2' => $week_2,
                     'week_3' => $week_3,
                     'week_4' => $week_4,
                     'week_5' => $week_5,
                     'week_6' => $week_6,
                     'origin' => $origin,
                     'destination' => $destination,
                     'description' => $description,
                     'is_for_bidding' => $previous->is_for_bidding,
                     'status' => ($status != 0) ? $status : $previous->status,
                     'org_status' => $previous->org_status,
                     'load_limit' => $load_limit,
                     'courier_id' => $previous->courier_id,
                     'is_public' => $is_public,
                     'auto_approve' => $auto_approve,
                     'payment_terms' => $payment_term,
                     'is_new' => 1
                 );
                 if ($courier_id != NULL) {
                      $new_service['courier_id'] = $courier_id;
                 }
                 if ($this->courier_service_model->update_service($id, $new_service, null)) {
                      $this->courier_service_model->push_service($data);
                      $result['status'] = 1;
                      $result['msg'] = "Service updated successfully";
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-warning";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('clear_error');
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function create_service() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));


            if (isset($post_data->courier_id) && !empty($post_data->courier_id)) {
                 $courier_id = intval($post_data->courier_id);
            } else {
                 $courier_id = NULL;
            }
            if ($courier_id != NULL) {
                 $courier_id = $this->session->userdata("courier_id");
                 $courier = $this->couriers_model->get_by_id($courier_id);
                 if (!$courier) {
                      $error = TRUE;
                      $errors['courier'] = "Invalid courier";
                 }
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $isvalidservice = $this->couriers_model->getCourierService_by_id($post_data->service_id);
                 if ($isvalidservice) {
                      $service_id = $post_data->service_id;
                 } else {
                      $error = TRUE;
                      $errors['service_id'] = "Please enter unique service ID";
                 }
            } else {
                 $error = TRUE;
                 $errors['service_id'] = "Invalid service ID";
            }
            if (isset($post_data->org) && !empty($post_data->org)) {
                 $company = $post_data->org;
                 if (!$this->organisation_model->is_exist_org($company)) {
                      $error = TRUE;
                      $errors['org'] = "Invalid organisation";
                 }
                 $bidding = 0;
            } else {
                 $company = 0;
                 $bidding = 1;
            }
            if (isset($post_data->display_name) && !empty($post_data->display_name)) {
                 $display_name = $post_data->display_name;
            } else {
                 $error = TRUE;
                 $errors['display_name'] = "Invalid";
            }

            if (isset($post_data->start_time) && !empty($post_data->start_time)) {
                 $start_time = date('H:i:s', strtotime($post_data->start_time));
            } else {
                 $error = TRUE;
                 $errors['start_time'] = "Invalid";
            }
            if (isset($post_data->end_time) && !empty($post_data->end_time)) {
                 $end_time = date('H:i:s', strtotime($post_data->end_time));
            } else {
                 $error = TRUE;
                 $errors['end_time'] = "Invalid";
            }
            if (isset($post_data->week_0) && !empty($post_data->week_0)) {
                 $week_0 = 1;
            } else {
                 $week_0 = 0;
            }
            if (isset($post_data->week_1) && !empty($post_data->week_1)) {
                 $week_1 = 1;
            } else {
                 $week_1 = 0;
            }
            if (isset($post_data->week_2) && !empty($post_data->week_2)) {
                 $week_2 = 1;
            } else {
                 $week_2 = 0;
            }
            if (isset($post_data->week_3) && !empty($post_data->week_3)) {
                 $week_3 = 1;
            } else {
                 $week_3 = 0;
            }
            if (isset($post_data->week_4) && !empty($post_data->week_4)) {
                 $week_4 = 1;
            } else {
                 $week_4 = 0;
            }
            if (isset($post_data->week_5) && !empty($post_data->week_5)) {
                 $week_5 = 1;
            } else {
                 $week_5 = 0;
            }
            if (isset($post_data->week_6) && !empty($post_data->week_6)) {
                 $week_6 = $post_data->week_6;
            } else {
                 $week_6 = 0;
            }
            if (isset($post_data->is_public) && $company == 0) {
                 $is_public = 1;
            } else {
                 $is_public = 0;
            }
            if (isset($post_data->auto_approve) && $company == 0) {
                 $auto_approve = 1;
            } else {
                 $auto_approve = 0;
            }
            if (isset($post_data->description) && !empty($post_data->description)) {
                 $description = $post_data->description;
            } else {
                 $description = "";
            }
            if (isset($post_data->origin) && !empty($post_data->origin)) {
                 $origin = $post_data->origin;
            } else {
                 $error = TRUE;
                 $errors['origin'] = "Please provide origin";
            }
            if (isset($post_data->destination) && !empty($post_data->destination)) {
                 $destination = $post_data->destination;
                 if (is_array($destination)) {
                      $destination = implode(",", $destination);
                 }
            } else {
                 $error = TRUE;
                 $errors['destination'] = "Please provide destinations";
            }
            if (isset($post_data->payment_term) && !empty($post_data->payment_term)) {
                 $payment_term = $post_data->payment_term;
                 if (is_array($payment_term)) {
                      $payment_term = implode(",", $payment_term);
                 }
            } else {
                 $payment_term = "";
            }



            $deliveryTime = "90-minute";
            if (isset($post_data->deliverytime) && !empty($post_data->deliverytime)) {
                 $datadeliverytime = $post_data->deliverytime;
                 if (isset($datadeliverytime->data)) {
                      $deliveryTime = $datadeliverytime->data;
                 }
            }
            if (!$error) {

                 $data = array(
                     'service_id' => $service_id,
                     "org_id" => $company,
                     'display_name' => $display_name,
                     'start_time' => $start_time,
                     'end_time' => $end_time,
                     'delivery_time' => $deliveryTime,
                     'week_0' => $week_0,
                     'week_1' => $week_1,
                     'week_2' => $week_2,
                     'week_3' => $week_3,
                     'week_4' => $week_4,
                     'week_5' => $week_5,
                     'week_6' => $week_6,
                     'origin' => $origin,
                     'destination' => $destination,
                     'description' => $description,
                     'is_for_bidding' => $bidding,
                     'status' => 1,
                     'org_status' => 1,
                     'courier_id' => ($courier_id != NULL) ? $courier_id : $courier->courier_id,
                     'is_public' => $is_public,
                     'auto_approve' => $auto_approve,
                     'payment_terms' => $payment_term,
                     'is_new' => 1
                 );
                 $this->courier_service_model->suspend_service($service_id, $data['courier_id']);
                 if ($this->courier_service_model->push_service($data)) {
                      $result['status'] = 1;
                      $result['msg'] = "New service added successfully";
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-warning";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('clear_error');
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function bidding_service_list() {
            $result = array();
            $courier_id = $this->session->userdata("courier_id");
            $services = $this->courier_service_model->get_all_services_for_bidding($courier_id);
            if ($services) {
                 $result['services'] = $services;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_items($id) {
            $result = array();
            $items = $this->surcharge_items_model->get_items($id);
            if ($items) {
                 foreach ($items as $item) {
                      switch ($item->location) {
                           case 1: $item->location_name = "Other restricted Area";
                                break;
                           case 2: $item->location_name = "Sentosa";
                                break;
                           case 4: $item->location_name = "CBD";
                                break;
                           case 8: $item->location_name = "Tuas";
                                break;
                           case 16: $item->location_name = "Collect Back";
                                break;
                      }
                 }
                 $result['items'] = $items;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function add_surcharge_item() {
            $result = array();
            $error = false;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            $courier_id = $this->session->userdata("courier_id");
            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = $post_data->id;
                 $is_edit = true;
            } else {
                 $is_edit = FALSE;
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $service_id = $post_data->service_id;
            } else {
                 $error = TRUE;
                 $errors['service_id'] = "Invalid service ID";
            }
            if (isset($post_data->name) && !empty($post_data->name)) {
                 $name = $post_data->name;
            } else {
                 $error = TRUE;
                 $errors['name'] = "Invalid name";
            }
            if (isset($post_data->price) && (!empty($post_data->price) || $post_data->price == '0')) {
                 $price = $post_data->price;
                 if (!is_numeric($price)) {
                      $error = TRUE;
                      $errors['price'] = "Invalid price";
                 }
            } else {
                 $error = TRUE;
                 $errors['price'] = "Invalid price";
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks)) {
                 $remarks = $post_data->remarks;
            } else {
                 $remarks = "";
            }
            if (isset($post_data->location) && !empty($post_data->location)) {
                 $location = $post_data->location;
            } else {
                 $location = 0;
            }
            if (!$error) {
                 if ($is_edit) {
                      $data = array(
                          'item_name' => $name,
                          'unit_price' => $price,
                          'remarks' => $remarks,
                          'location' => $location
                      );
                      if ($this->surcharge_items_model->update_item($id, $data)) {
                           $result['status'] = 1;
                           $result['msg'] = "Item updated successfully";
                           $result['class'] = "alert-success";
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = lang('try_again');
                           $result['class'] = "alert-warning";
                      }
                 } else {
                      $data = array(
                          'item_name' => $name,
                          'unit_price' => $price,
                          'remarks' => $remarks,
                          'service_id' => $service_id,
                          'location' => $location
                      );
                      $get_row = $this->surcharge_items_model->getServiceLoc($data);
                      if (empty($get_row)) {
                           $insert_id = $this->surcharge_items_model->add_item($data);
                           if ($insert_id) {
                                $result['status'] = 1;
                                $result['msg'] = "New item added successfully";
                                $result['class'] = "alert-success";
                                $result['id'] = $insert_id;
                           } else {
                                $result['status'] = 0;
                                $result['msg'] = lang('try_again');
                                $result['class'] = "alert-warning";
                           }
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = "Cannot Add the surcharge item with the same location.";
                           $result['class'] = "alert-warning";
                      }
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
                 $result['error'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function delete_surcharge_item() {
            $result = array();
            $error = false;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = $post_data->id;
            }
            if (isset($id)) {
                 if ($this->surcharge_items_model->delete_item($id)) {
                      $result['status'] = 1;
                      $result['msg'] = "Item deleted successfully";
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-warning";
                 }
            }

            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_parcel_prices($service_id) {
            $result = $this->courier_service_model->get_service_prices($service_id);
            $result['prices'] = $result;
            echo json_encode($result);
       }

       public function add_parcel_price() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            $data = array();

            if (empty($post_data->type) && $post_data->type != '0') {
                 $error = TRUE;
                 $errors['type'] = lang('parcel_type_invalid_type');
            } else if ($post_data->type == '0') {
                 if (empty($post_data->cubicCost)) {
                      $error = TRUE;
                      $errors['cubicCost'] = lang('parcel_type_invalid_volume_cost');
                 }
                 if (empty($post_data->maxVolume)) {
                      $error = TRUE;
                      $errors['maxvolume'] = lang('parcel_type_invalid_volume');
                 }
                 if (empty($post_data->maxWeight)) {
                      $error = TRUE;
                      $errors['maxWeight'] = lang('parcel_type_invalid_weight');
                 }
                 if (empty($post_data->weightCost)) {
                      $error = TRUE;
                      $errors['weightCost'] = lang('parcel_type_invalid_weight_cost');
                 }
            }
            if ($post_data->type != '0' && empty($post_data->price)) {
                 $error = TRUE;
                 $errors['price'] = lang('parcel_type_invalid_price');
            }

            if (!$error) {
                 $service_id = $post_data->service_id;
                 $data = array(
                     'service_id' => $service_id,
                     'type' => $post_data->type,
                     'price' => $post_data->price,
                     'max_volume' => $post_data->maxVolume,
                     'volume_cost' => $post_data->cubicCost,
                     'max_weight' => $post_data->maxWeight,
                     'weight_cost' => $post_data->weightCost
                 );
                 if ($this->courier_service_model->add_price_to_service($service_id, $data))
                      $result['status'] = 0;
                 else
                      $result['status'] = 1;
            }
            else {
                 $result['status'] = 1;
            }

            if ($result['status'] == 0) {
                 $result['msg'] = 'Price added successfully';
                 $result['class'] = 'alert-success';
            } else {
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-warning";
                 $result['errors'] = $errors;
            }

            echo json_encode($result);
       }

       public function delete_parcel_price() {
            $post_data = json_decode(file_get_contents("php://input"));
            if ($this->courier_service_model->delete_parcel_price($post_data->id)) {
                 $result['status'] = 0;
                 $result['msg'] = 'Price deleted successfully';
                 $result['class'] = 'alert-success';
            } else {
                 $result['status'] = 1;
                 $result['msg'] = lang('try_again');
                 $result['class'] = 'alert-warning';
            }
            echo json_encode($result);
       }

       public function get_payments($id = 0) {
            $result = array();
            $payments = $this->courier_service_model->get_service_payment_terms($id);
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
            if (isset($getdata->credit) && $getdata->credit == TRUE) {
                 $value+=4;
                 $credit = 1;
            } else {
                 $credit = 0;
            }
            if (isset($getdata->credit_direct) && $getdata->credit_direct == TRUE) {
                 $value+=8;
                 $credit_direct = 1;
            } else {
                 $credit_direct = 0;
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
            if (isset($getdata->id) && !empty($getdata->id)) {
                 $id = $getdata->id;
            } else {
                 $id = 0;
            }
            $data = array(
                'payments' => sprintf('%04d', decbin($value))
            );
            if ($this->courier_service_model->update_service($id, $data)) {
                 $result['status'] = 1;
                 $result['class'] = "alert-success";
                 $result['msg'] = lang('service_payment_updated');
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  