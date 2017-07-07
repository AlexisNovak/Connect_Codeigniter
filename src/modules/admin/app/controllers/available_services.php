<?php

  class Available_services extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'services_model',
                'request_courier_service_model',
                'couriers/courier_service_model',
                'couriers/surcharge_items_model',
                'app/members_model'
            ));
       }

       public function index() {
            $data = array();
            $user_id = $this->session->userdata('user_id');
            $root = $this->members_model->is_rootadmin($user_id);
            $data['root'] = $root;
            $this->load->view('available_services', $data);
       }

       public function edit() {
            $user_id = $this->session->userdata('user_id');
            if ($this->members_model->is_rootadmin($user_id)) {
                 $this->load->view('edit_service');
            }
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
            $user_id = $this->session->userdata('user_id');
            if ($this->members_model->is_rootadmin($user_id)) {
                 $post_data = json_decode(file_get_contents("php://input"));
                 if (isset($post_data->id) && !empty($post_data->id)) {
                      $id = $post_data->id;
                 } else {
                      $error = TRUE;
                      $errors['id'] = "Invalid ID";
                 }
                 if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                      $service_id = $post_data->service_id;
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
                      $start_time = $post_data->start_time;
                 } else {
                      $error = TRUE;
                      $errors['start_time'] = "Invalid";
                 }
                 if (isset($post_data->end_time) && !empty($post_data->end_time)) {
                      $end_time = $post_data->end_time;
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
                          'status' => 1,
                          'org_status' => 1,
                          'is_public' => $is_public,
                          'auto_approve' => $auto_approve,
                          'payment_terms' => $payment_term
                      );
                      if ($this->courier_service_model->update_service($id, $data)) {
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
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('clear_error');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function view($id = 0) {
            $data = array();
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
                 $service_details['name'] = $service->display_name;
                 $service_details['start time'] = $service->start_time;
                 $service_details['end time'] = $service->end_time;
                 $service_details['working days'] = implode(', ', $sdays);
                 $service_details['payment term'] = $service->payment_terms;
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
                 $data['service'] = $service_details;
                 $data['items'] = $this->surcharge_items_model->get_items($id);
            }
            $this->load->view('view_service', $data);
       }

       public function available_service_list() {
            $result = array();
            $perpage = '';
            $search = '';
            $sort = '';
            $sort_direction = '';
            $serviceData = json_decode(file_get_contents('php://input'));
            if (isset($serviceData->perpage_value)) {

                 $perpage = $serviceData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($serviceData->currentPage)) {

                 $page = $serviceData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($serviceData->type)) {
                 $type = $serviceData->type;
            } else {
                 $type = NULL;
            }
            if (isset($serviceData->filter)) {
                 if ($serviceData->filter != NULL) {
                      $search = $serviceData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $org_id = -1;
            if (isset($serviceData->org_id)) {
                 if ($serviceData->org_id != NULL) {
                      $org_id = (int) $serviceData->org_id;
                 }
            }
            if (isset($serviceData->sort->field)) {
                 $sort = $serviceData->sort->field;
                 $sort_direction = $serviceData->sort->direction;
            }

            $total_result = $this->services_model->service_count($search, $type, $org_id);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $services = $this->services_model->services($perpage, $search, $start, $org_id, $type, $sort, $sort_direction);
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            foreach ($services as $service) {
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
            }
            $result['services'] = $services;
            $result['end'] = (int) ($start + count($result['services']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function request_service() {
            $error = false;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            $result = array();
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = (int) $post_data->org_id;
            } else {
                 $errors['org_id'] = "Organisation must be selected";
                 $error = true;
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $service_id = (int) $post_data->service_id;
            } else {
                 $errors['service_id'] = "Service must be selected";
                 $error = true;
            }
            if (isset($post_data->courier_id) && !empty($post_data->courier_id)) {
                 $courier_id = (int) $post_data->courier_id;
            } else {
                 $errors['courier_id'] = "courier must be selected";
                 $error = true;
            }
            if (!$error) {
                 if ($this->request_courier_service_model->is_available_to_request($service_id)) {
                      $request = array(
                          'service_id' => $service_id,
                          'courier_id' => $courier_id,
                          'org_id' => $org_id,
                          'status' => 1
                      );
                      $request_id = $this->request_courier_service_model->add_request($request);
                      if ($request_id) {
                           $result['status'] = 1;
                           $result['msg'] = lang('a_s_request_success');
                           $result['class'] = "alert-success";
                           if ($this->request_courier_service_model->is_auto_approve($service_id)) {
                                $this->request_courier_service_model->approve_service($request_id, $service_id, $org_id, $courier_id);
                           } else {
                                $courier = $this->request_courier_service_model->get_courier($courier_id);
                                $to = $courier->email;
                                $to_name = $courier->name;
                                $subject = '6connect email notification';
                                $message = array(
                                    'title' => lang('a_s_request_mail_title'),
                                    'name' => $to_name,
                                    'content' => lang('a_s_request_mail_content'),
                                    'link_title' => '',
                                    'link' => ''
                                );
                                save_mail($to, $to_name, $subject, $message, 2);
                           }
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = lang('try_again');
                           $result['class'] = "alert-danger";
                      }
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('a_s_request_failed');
                      $result['class'] = "alert-warning";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('clear_error');
                 $result['class'] = "alert-warning";
                 $result['error'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function can_use_this() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = (int) $post_data->org_id;
            } else {
                 $org_id = "";
//                 $error = TRUE;
//                 $errors['org_id'] = "Organisation must be selected";
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $service_id = (int) $post_data->service_id;
            } else {
                 $error = TRUE;
                 $errors['service_id'] = "Service must be selected";
            }
            if (!$error) {
                 $user_id = $this->session->userdata('user_id');
                 $row_count = $this->services_model->can_use_this_service($user_id, $org_id, $service_id);
                 if ($row_count > 0) {
                      $result['status'] = 1;
                 } else {
                      $result['status'] = 2;
                 }
            } else {
                 $result['status'] = 0;
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_parcel_prices($service_id) {
            $result = $this->courier_service_model->get_service_prices($service_id);
            $result['prices'] = $result;
            echo json_encode($result);
       }

  }
  