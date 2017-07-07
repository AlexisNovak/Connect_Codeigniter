<?php

  class Services extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('app/services_model',
                'app/members_model',
                'account/account_details_model',
                'couriers/courier_service_model',
                'app/organisation_model')
            );
       }

       function index($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            $this->load->view('app/services_list', $data);
       }

       public function services_count($org_id = 0) {
            $result = array();
            $result['total'] = $this->services_model->getserviceslist_count($org_id, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function memberslist_json($org_id) {
            $perpage = '';
            $search = '';
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
            if (isset($membersData->service)) {

                 $service = $membersData->service;
            } else {
                 $service = 0;
            }
            if (isset($membersData->filter)) {
                 if ($membersData->filter != NULL) {
                      $search = $membersData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->services_model->getmemberslist_count($service, $org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['member_detail'] = $this->services_model->getmemberslist_by_orgid($service, $org_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('partner_user_id');
            $result['end'] = (int) ($start + count($result['member_detail']));
            echo json_encode($result);
       }

       //function to add new member to organisation

       function addMember($orgid) {
            $memberData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($memberData->Userid) && isset($memberData->service_id)) {
                 $user_id = (int) $memberData->Userid;
                 $service_id = (int) $memberData->service_id;
                 if ($this->services_model->is_member_exist($orgid, $user_id, $service_id)) {
                      $result['status'] = 2;
                      $result['msg'] = "Already joined with this service";
                      $result['class'] = "alert-warning";
                 } else {
                      $data = array(
                          'service_id' => $service_id,
                          'org_id' => $orgid,
                          'member_id' => $user_id,
                          'status' => 1);
                      if ($this->services_model->addMember($data)) {
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New member added to this service";
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = "Something went wrong.";
                           $result['class'] = "alert-danger";
                      }
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Not yet selected any member";
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result);
       }

       function groupslist_json($org_id) {
            $perpage = '';
            $search = '';
            $groupsData = json_decode(file_get_contents('php://input'));
            if (isset($groupsData->perpage_value)) {

                 $perpage = $groupsData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($groupsData->currentPage)) {

                 $page = $groupsData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($groupsData->service)) {

                 $service = $groupsData->service;
            } else {
                 $service = 0;
            }
            if (isset($groupsData->filter)) {
                 if ($groupsData->filter != NULL) {
                      $search = $groupsData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->services_model->getgroupslist_count($service, $org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['group_detail'] = $this->services_model->getgroupslist_by_orgid($service, $org_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('partner_user_id');
            $result['end'] = (int) ($start + count($result['group_detail']));
            echo json_encode($result);
       }

       //function to add new group to organisation

       function addGroup($orgid) {
            $groupData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($groupData->id) && isset($groupData->service_id)) {
                 $group_id = (int) $groupData->id;
                 $service_id = (int) $groupData->service_id;
                 if ($this->services_model->is_group_exist($orgid, $group_id, $service_id)) {
                      $result['status'] = 2;
                      $result['msg'] = "Already joined with this service";
                      $result['class'] = "alert-warning";
                 } else {
                      $data = array(
                          'service_id' => $service_id,
                          'org_id' => $orgid,
                          'group_id' => $group_id,
                          'status' => 1);
                      if ($this->services_model->addGroup($data)) {
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New group added to this service";
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = "Something went wrong.";
                           $result['class'] = "alert-danger";
                      }
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Not yet selected any group";
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result);
       }

       public function save() {
            $postdata = file_get_contents("php://input");
            $service_add_data = json_decode($postdata);
            $errors = array();
            $is_edit = false;
            if (isset($service_add_data->id) && !empty($service_add_data->id)) {
                 $service_id = (int) $service_add_data->id;
                 $is_edit = true;
            }
            if (isset($service_add_data->org_id) && !empty($service_add_data->org_id)) {
                 $org_id = (int) $service_add_data->org_id;
            } else {
                 $errors['org'] = lang("service_org_error");
            }
            if (isset($service_add_data->name) && !empty($service_add_data->name)) {
                 $service_name = htmlentities($service_add_data->name);
            } else {
                 $errors['name'] = lang("service_name_error");
            }

            if (isset($service_add_data->description) && !empty($service_add_data->description)) {
                 $description = htmlentities($service_add_data->description);
            } else {
                 $description = NULL;
            }

            if (isset($service_add_data->status) && !empty($service_add_data->status)) {
                 $service_status = htmlentities($service_add_data->status);
            } else {
                 $service_status = NULL;
            }
            if (count($errors) == 0) {
                 if ($is_edit) {
                      $attributes = array();
                      $attributes["name"] = $service_name;
                      $attributes["description"] = $description;
                      $result_array['result'] = $this->services_model->update_services($service_id, $attributes);
                      $result_array['msg'] = lang('service_edit_success');
                      $result_array['class'] = "alert-success";
                      $result_array['status'] = 1;
                      $json = json_encode($result_array);
                 } else {
                      $this->load->helper('string');
                      do {
                           $service_universal_id = random_string('alnum', 10);
                      } while ($this->services_model->not_exist_universal_id($service_universal_id));

                      $attributes = array(
                          "name" => $service_name,
                          "status" => 1,
                          "universal_id" => $service_universal_id,
                          "description" => $description,
                          "org_id" => $org_id,
                          "created_by" => $this->session->userdata("partner_user_id")
                      );

                      $result = $this->services_model->add_new_service($attributes);
                      if (isset($result)) {
                           $result_array['status'] = 1;
                           $result_array['result'] = 1;
                           $result_array['msg'] = lang('service_add_success');
                           $result_array['class'] = "alert-success";
                           $json = json_encode($result_array);
                      }
                 }
            } else {
                 $result_array['status'] = 0;
                 $result_array['errors'] = $errors;
                 $result_array['msg'] = lang('service_errors');
                 $result_array['class'] = "alert-danger";
                 $json = json_encode($result_array);
            }
            echo $json;
       }

       public function limit_use() {
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->org_id) && !empty($post_data->org_id)) {
                 $org_id = $post_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($post_data->service_id) && !empty($post_data->service_id)) {
                 $service_id = $post_data->service_id;
            } else {
                 $service_id = 0;
            }
            if (isset($post_data->limit_use) && !empty($post_data->limit_use)) {
                 $limit_use = $post_data->limit_use;
                 $msg = lang('allow_limit_use');
            } else {
                 $limit_use = 0;
                 $msg = lang('not_allow_limit_use');
            }
            $updatedata = array('limit_use' => $limit_use);
            if ($this->services_model->update_services($service_id, $updatedata)) {
                 $result['status'] = 1;
                 $result['class'] = "alert-success";
                 $result['msg'] = $msg;
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       //function to add new service to organisation

       function addService($orgid) {
            $serviceData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($serviceData->Userid)) {
                 $user_id = (int) $serviceData->Userid;
                 if ($this->members_model->is_member_exist($orgid, $user_id)) {
                      $result['status'] = 2;
                      $result['msg'] = "Already joined with this organisation";
                      $result['class'] = "alert-warning";
                 } else {
                      $data = array(
                          'org_id' => $orgid,
                          'user_id' => $user_id,
                          'note' => "",
                          'role_id' => 3, //referral
                          'status' => 'active');
                      if ($this->services_model->addService($data)) {
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New service added to this organisation";
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = "Something went wrong.";
                           $result['class'] = "alert-danger";
                      }
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Not yet selected any service";
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result);
       }

       function get_all_services_org($org_id) {
            $result = $this->services_model->get_all_service_org_id($org_id);
            echo json_encode($result);
       }

       function serviceslist_json($org_id) {
            $perpage = '';
            $search = '';
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
            $total_result = $this->services_model->getserviceslist_count($org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['service_detail'] = $this->services_model->getserviceslist_by_orgid($org_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('partner_user_id');
            $result['end'] = (int) ($start + count($result['service_detail']));
            echo json_encode($result);
       }

       function allserviceslist_json() {
            if (isset($_GET['name']) && !empty($_GET['name'])) {
                 $str = htmlentities($_GET['name']);
            } else {
                 $str = "";
            }
            $service_detail = $this->services_model->getallserviceslist($str);
            echo json_encode($service_detail);
       }

       function get_detail() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            if (isset($service_data)) {
                 $id = $service_data->service_id;
                 $service = $this->services_model->get_detail_by_serviceid($id, $service_data->org_id);
                 if ($service) {
                      $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
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
                      $service_details['id'] = $service->id;
                      $service_details['display_name'] = $service->display_name;
                      $service_details['price'] = $service->price . ' SGD';
                      $service_details['start_time'] = $service->start_time;
                      $service_details['end_time'] = $service->end_time;
                      $service_details['threshold_price'] = $service->threshold_price;
                      $service_details['service_id'] = $service->service_id;
                      $service_details['courier_name'] = $service->courier_name;
                      $service_details['courier_id'] = $service->courier_id;
                      $service_details['working_days'] = implode(', ', $sdays);
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
                      $service_details['status'] = $service->status;
                      $service_details['statusText'] = ($service->status == 1) ? 'Active' : "Suspended";
                      $service_details['limit_use'] = $service->limit_use ? TRUE : FALSE;
                 }
                 $result["details"] = $service_details;
            }
            echo json_encode($result);
            exit();
       }

       function add_threshold() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $service_id = 0;
            }
            if (isset($service_data->threshold) && !empty($service_data->threshold)) {
                 $threshold = $service_data->threshold;
            } else {
                 $threshold = 0;
            }
            $price = $this->services_model->get_price_by_serviceid($service_id);
            if ($threshold < $price) {
                 $result['status'] = 0;
                 $result['msg'] = 'Threshold price cannot be lower than the service price.';
                 $result['class'] = "alert-danger";
            } else {
                 if ($this->services_model->update_services($service_id, array('threshold_price' => $threshold))) {
                      $result['status'] = 1;
                      $result['msg'] = lang('threshold_update_suc');
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-danger";
                 }
            }
            echo json_encode($result);
            exit();
       }

       public function update_service() {
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $result = array();
            if ($service_data->service_id == $this->session->userdata('partner_user_id')) {
                 $check_self_updation = $this->members_model->is_self($service_data->org_id, $service_data->service_id, $service_data->role);
                 if ($check_self_updation) {
                      $result['reload'] = 1;
                 }
            }

            $check_admin = $this->members_model->is_superadmin($service_data->org_id, $service_data->service_id);
            if ($check_admin) {
                 $updatedata = array('note' => $service_data->note, 'status' => $service_data->status);
            } else {
                 $updatedata = array('note' => $service_data->note, 'status' => $service_data->status, "role_id" => $service_data->role);
            }
            $check_scheme = $this->services_model->service_scheme_check($service_data->org_id, $service_data->service_id);
            if (isset($service_data->scheme)) {
                 $scheme = array('scheme_id' => $service_data->scheme);
                 $add_schemedata_to_service = array('scheme_id' => $service_data->scheme, 'org_id' => $service_data->org_id, 'user_id' => $service_data->service_id);
                 if (!empty($check_scheme)) {

                      $result['scheme'] = $this->services_model->update_service_scheme($service_data->org_id, $service_data->service_id, $scheme);
                 } else {
                      $result['scheme'] = $this->services_model->add_service_scheme($add_schemedata_to_service);
                 }
            }
            $result['update'] = $this->services_model->update_service($service_data->org_id, $service_data->service_id, $updatedata);

            echo json_encode($result);
       }

       public function delete_service() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
                 $result['staatus'] = $this->services_model->delete_service($service_id);
            }
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
                 $total_result = $this->services_model->getserviceslist_count($org_id, NULL);
                 if ($total_result == 0) {
                      $attributes = array(
                          "use_public_service" => 1
                      );
                      $this->organisation_model->edit_organisation($attributes, $org_id);
                      $result['reload'] = true;
                 }
            }
            echo json_encode($result);
            exit();
       }

       public function approve_service() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
                 $org_id = (int) $service_data->org_id;
                 $result = $this->services_model->approve_service($service_id, $org_id);

                 $org_name = $this->organisation_model->getName($org_id);
                 $email = $this->services_model->getCourier_with_service($service_id);
                 if ($result) {
                      $to = $email->email;
                      $to_name = $email->email;
                      $subject = 'Service Approved';
                      $message = '<div style="margin:10px;border: 1px solid #eee;background: #fff;padding:50px;width:400px;text-align:center;">'
                              . '<h2>New Service \'' . $email->display_name . '\' Approved by  \'' . $org_name->name . '\' Admin</h2>'
                              . '</div>';
                      $message = array(
                          'title' => '',
                          'name' => $to_name,
                          'content' => '',
                          'link_title' => '',
                          'link' => '',
                          'link2' => 'New Service \'' . $email->display_name . '\' Approved by  \'' . $org_name->name . '\' Admin');
                      $mail_result = save_mail($to, $to_name, $subject, $message,2);
                 }
            }
            return $result;
       }

       public function reject_service() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
                 $org_id = (int) $service_data->org_id;
                 if (isset($service_data->remark))
                      $remark = "Remark : " . htmlentities($service_data->remark);
                 else {
                      $remark = "";
                 }
                 $result = $this->services_model->reject_service($service_id, $org_id);
                 $org_name = $this->organisation_model->getName($org_id);
                 $email = $this->services_model->getCourier_with_service($service_id);
                 if ($result) {
                      $to = $email->email;
                      $to_name = $email->email;
                      $subject = 'Service Rejected';
                      $message = '<div style="margin:10px;border: 1px solid #eee;background: #fff;padding:50px;width:400px;text-align:center;">'
                              . '<h2>New Service \'' . $email->display_name . '\' Rejected by  \'' . $org_name->name . '\' Admin</h2>'
                              . '<p>' . $remark . '<p>'
                              . '</div>';
                      $message = array(
                          'title' => '',
                          'name' => $to_name,
                          'content' => '',
                          'link_title' => '',
                          'link' => '',
                          'link2' => '<p>New Service \'' . $email->display_name . '\' Rejected by  \'' . $org_name->name . '\' Admin</p>'
                          . '<p>' . $remark . '<p>');
                      $mail_result = save_mail($to, $to_name, $subject, $message,2);
                 }
            }
            return $result;
       }

       public function delete_group() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $error = false;
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->group_id) && !empty($service_data->group_id)) {
                 $group_id = (int) $service_data->group_id;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "service_id" => $service_id,
                     "org_id" => $org_id,
                     "group_id" => $group_id
                 );
                 $result = $this->services_model->delete_group($where);
            }
            echo json_encode($result);
       }

       public function delete_member() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $error = false;
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->member_id) && !empty($service_data->member_id)) {
                 $member_id = (int) $service_data->member_id;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "service_id" => $service_id,
                     "org_id" => $org_id,
                     "member_id" => $member_id
                 );
                 $result = $this->services_model->delete_member($where);
            }
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
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->status) && !empty($service_data->status)) {
                 $status = (int) $service_data->status;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "id" => $service_id,
                     "org_id" => $org_id
                 );
                 if ($status == 1) {
                      $result = $this->services_model->suspend_service($where);
                 } elseif ($status == 2) {
                      $result = $this->services_model->activate_service($where);
                 }
            }
            echo json_encode($result);
       }

       public function suspend_group() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $error = false;
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->group_id) && !empty($service_data->group_id)) {
                 $group_id = (int) $service_data->group_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->status) && !empty($service_data->status)) {
                 $status = (int) $service_data->status;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "service_id" => $service_id,
                     "org_id" => $org_id,
                     "group_id" => $group_id
                 );
                 if ($status == 1) {
                      $result = $this->services_model->suspend_group($where);
                 } elseif ($status == 2) {
                      $result = $this->services_model->activate_group($where);
                 }
            }
            echo json_encode($result);
       }

       public function suspend_member() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $service_data = json_decode($postdata);
            $error = false;
            if (isset($service_data->service_id) && !empty($service_data->service_id)) {
                 $service_id = (int) $service_data->service_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->org_id) && !empty($service_data->org_id)) {
                 $org_id = (int) $service_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($service_data->member_id) && !empty($service_data->member_id)) {
                 $member_id = (int) $service_data->member_id;
            } else {
                 $error = TRUE;
            } if (isset($service_data->status) && !empty($service_data->status)) {
                 $status = (int) $service_data->status;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "service_id" => $service_id,
                     "org_id" => $org_id,
                     "member_id" => $member_id
                 );
                 if ($status == 1) {
                      $result = $this->services_model->suspend_member($where);
                 } elseif ($status == 2) {
                      $result = $this->services_model->activate_member($where);
                 }
            }
            echo json_encode($result);
       }

       function allserviceslist() {
            $postdata = json_decode(file_get_contents("php://input"));
            if (isset($postdata->search) && !empty($postdata->search)) {
                 $str = htmlentities($postdata->search);
            } else {
                 $str = "";
            }
            if (isset($postdata->org_id) && !empty($postdata->org_id)) {
                 $org_id = (int) htmlentities($postdata->org_id);
            } else {
                 $org_id = NULL;
            }
            $service_detail = $this->services_model->getallserviceslist($str, $org_id);
            echo json_encode($service_detail);
       }

       public function get_courier_profile() {
            $postdata = json_decode(file_get_contents("php://input"));
            if (isset($postdata->courier_id) && !empty($postdata->courier_id)) {
                 $courier_id = (int) htmlentities($postdata->courier_id);
            } else {
                 $courier_id = 0;
            }
            $result = array();
            $courier = $this->services_model->get_courier($courier_id);
            if ($courier) {
                 $until = new DateTime();
                 $interval = new DateInterval('P3M'); //2 months
                 $from = $until->sub($interval);
                 $from_date = $from->format('Y-m-d H:i:s');
                 $orders = $this->services_model->get_deliveries_count_by_status($courier_id, $from_date);
                 $success = 0;
                 $failed = 0;
                 foreach ($orders as $order_cat) {
                      switch ($order_cat->status):
                           case C_FAILED_DELIVERY:$failed = $order_cat->deliveries;
                                break;
                           case C_DELIVERED:$success = $order_cat->deliveries;
                                break;
                      endswitch;
                 }
                 if ($success || $failed) {
                      $total = $success + $failed;
                      $success_rate = ($success / $total) * 100;
                 } else {
                      $success_rate = 0;
                 }
                 $result['status'] = 1;
                 $result['courier'] = array(
                     'logo' => ($courier->logo !== NULL) ? $courier->logo . '?' . time() : outer_base_url("resource/images/default_logo.png") . '?' . time(),
                     'name' => $courier->name,
                     'rating' => $courier->rating,
                     'description' => $courier->description,
                     'completed' => $success,
                     'success_rate' => $success_rate . '%',
                     'compliance_rating' => $courier->compliance_rating ? $courier->compliance_rating : "",
                     'label_class' => $courier->label_class ? $courier->label_class : ""
                 );
                 $result['reviews'] = $this->services_model->get_reviews($courier_id);
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_service_info($id = 0) {
            $data = array();
            $service = $this->courier_service_model->get_details_by_id($id);
            $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
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
                 $service_details['price'] = $service->price . ' SGD';
                 $service_details['start_time'] = $service->start_time;
                 $service_details['end_time'] = $service->end_time;
                 $service_details['working_days'] = implode(', ', $sdays);
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
            }
            echo json_encode($data);
            exit();
       }

  }

  /* End of file services.php */
/* Location: modules/admin/app/controllers/services.php */

