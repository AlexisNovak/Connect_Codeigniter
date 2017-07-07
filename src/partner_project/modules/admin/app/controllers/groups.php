<?php

  class Groups extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('app/groups_model', 'app/members_model', 'account/account_details_model'));
       }

       function index($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            $this->load->view('app/groups_list', $data);
       }

       public function team_count($org_id = 0) {
            $result = array();
            $result['total'] = $this->groups_model->getgroupslist_count($org_id, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function save() {
            $postdata = file_get_contents("php://input");
            $group_add_data = json_decode($postdata);
            $errors = array();
            $is_edit = false;
            if (isset($group_add_data->id) && !empty($group_add_data->id)) {
                 $group_id = (int) $group_add_data->id;
                 $is_edit = true;
            }
            if (isset($group_add_data->org_id) && !empty($group_add_data->org_id)) {
                 $org_id = (int) $group_add_data->org_id;
            } else {
                 $errors['org'] = lang("group_org_error");
            }
            if (isset($group_add_data->name) && !empty($group_add_data->name)) {
                 $group_name = htmlentities($group_add_data->name);
            } else {
                 $errors['name'] = lang("group_name_error");
            }
            if (isset($group_add_data->code) && !empty($group_add_data->code)) {
                 $group_code = htmlentities($group_add_data->code);
            } else {
                 $errors['code'] = lang("group_code_error");
            }
            if (isset($group_add_data->description) && !empty($group_add_data->description)) {
                 $description = htmlentities($group_add_data->description);
            } else {
                 $description = '';
            }

            if (isset($group_add_data->status) && !empty($group_add_data->status)) {
                 $group_status = htmlentities($group_add_data->status);
            } else {
                 $group_status = NULL;
            }
            if (count($errors) == 0) {
                 if ($is_edit) {
                      $attributes = array();
                      $attributes["name"] = $group_name;
                      $attributes["code"] = $group_code;
                      $attributes["description"] = $description;
                      $result_array['result'] = $this->groups_model->update_groups($group_id, $attributes);
                      $result_array['msg'] = lang('group_edit_success');
                      $result_array['class'] = "alert-success";
                      $result_array['status'] = 1;
                      $json = json_encode($result_array);
                 } else {
                      $attributes = array(
                          "name" => $group_name,
                          "status" => 1,
                          "code" => $group_code,
                          "description" => $description,
                          "org_id" => $org_id,
                          "created_by" => $this->session->userdata("partner_user_id")
                      );
                      $result = $this->groups_model->add_new_group($attributes);
                      if (isset($result)) {
                           $result_array['status'] = 1;
                           $result_array['result'] = 1;
                           $result_array['msg'] = lang('group_add_success');
                           $result_array['class'] = "alert-success";
                           $json = json_encode($result_array);
                      }
                 }
            } else {
                 $result_array['status'] = 0;
                 $result_array['errors'] = $errors;
                 $result_array['msg'] = lang('group_errors');
                 $result_array['class'] = "alert-danger";
                 $json = json_encode($result_array);
            }
            echo $json;
       }

       //function to add new group to organisation

       function addGroup($orgid) {
            $groupData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($groupData->Userid)) {
                 $user_id = (int) $groupData->Userid;
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
                      if ($this->groups_model->addGroup($data)) {
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New group added to this organisation";
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

       function orggroupslist() {
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
            $group_detail = $this->groups_model->get_all_org_groups_list($str, $org_id);
            echo json_encode($group_detail);
       }

       function get_all_groups_org($org_id) {
            $result = $this->groups_model->get_all_group_org_id($org_id);
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
            if (isset($groupsData->filter)) {
                 if ($groupsData->filter != NULL) {
                      $search = $groupsData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->groups_model->getgroupslist_count($org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['group_detail'] = $this->groups_model->getgroupslist_by_orgid($org_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('partner_user_id');
            $result['end'] = (int) ($start + count($result['group_detail']));
            echo json_encode($result);
       }

       function allgroupslist_json() {
            if (isset($_GET['name']) && !empty($_GET['name'])) {
                 $str = htmlentities($_GET['name']);
            } else {
                 $str = "";
            }
            $group_detail = $this->groups_model->getallgroupslist($str);
            echo json_encode($group_detail);
       }

       function get_detail() {
            $postdata = file_get_contents("php://input");
            $group_data = json_decode($postdata);
            if (isset($group_data)) {
                 $group_detail = $this->groups_model->get_detail_by_groupid($group_data->group_id, $group_data->org_id);
                 $services = array();
                 if ($group_detail) {
                      $status_array = array("1" => "Active", "2" => "Suspended");
                      $group_detail->statusText = $status_array[$group_detail->status];
                      $services = $this->groups_model->get_assigned_services($group_data->group_id, $group_data->org_id);
                      $members = $this->groups_model->get_assigned_members($group_data->group_id, $group_data->org_id);
                 }
                 echo json_encode(array("details" => $group_detail, "services" => $services, "members" => $members));
            }
       }

       function get_assigned_members() {
            $postdata = file_get_contents("php://input");
            $group_data = json_decode($postdata);
            if (isset($group_data)) {
                 $members = $this->groups_model->get_assigned_members($group_data->group_id, $group_data->org_id);
                 echo json_encode(array("members" => $members));
            }
       }

       public function update_group() {
            $postdata = file_get_contents("php://input");
            $group_data = json_decode($postdata);
            $result = array();
            if ($group_data->group_id == $this->session->userdata('partner_user_id')) {
                 $check_self_updation = $this->members_model->is_self($group_data->org_id, $group_data->group_id, $group_data->role);
                 if ($check_self_updation) {
                      $result['reload'] = 1;
                 }
            }

            $check_admin = $this->members_model->is_superadmin($group_data->org_id, $group_data->group_id);
            if ($check_admin) {
                 $updatedata = array('note' => $group_data->note, 'status' => $group_data->status);
            } else {
                 $updatedata = array('note' => $group_data->note, 'status' => $group_data->status, "role_id" => $group_data->role);
            }
            $check_scheme = $this->groups_model->group_scheme_check($group_data->org_id, $group_data->group_id);
            if (isset($group_data->scheme)) {
                 $scheme = array('scheme_id' => $group_data->scheme);
                 $add_schemedata_to_group = array('scheme_id' => $group_data->scheme, 'org_id' => $group_data->org_id, 'user_id' => $group_data->group_id);
                 if (!empty($check_scheme)) {

                      $result['scheme'] = $this->groups_model->update_group_scheme($group_data->org_id, $group_data->group_id, $scheme);
                 } else {
                      $result['scheme'] = $this->groups_model->add_group_scheme($add_schemedata_to_group);
                 }
            }
            $result['update'] = $this->groups_model->update_group($group_data->org_id, $group_data->group_id, $updatedata);

            echo json_encode($result);
       }

       public function suspend_group() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $group_data = json_decode($postdata);
            $error = false;
            if (isset($group_data->group_id) && !empty($group_data->group_id)) {
                 $group_id = (int) $group_data->group_id;
            } else {
                 $error = TRUE;
            }
            if (isset($group_data->org_id) && !empty($group_data->org_id)) {
                 $org_id = (int) $group_data->org_id;
            } else {
                 $error = TRUE;
            }
            if (isset($group_data->status) && !empty($group_data->status)) {
                 $status = (int) $group_data->status;
            } else {
                 $error = TRUE;
            }
            if (!$error) {
                 $where = array(
                     "id" => $group_id,
                     "org_id" => $org_id
                 );
                 if ($status == 1) {
                      $result = $this->groups_model->suspend_group($where);
                 } elseif ($status == 2) {
                      $result = $this->groups_model->activate_group($where);
                 }
            }
            echo json_encode($result);
       }

       public function delete_group() {
            $result = 0;
            $postdata = file_get_contents("php://input");
            $group_data = json_decode($postdata);
            if (isset($group_data->group_id) && !empty($group_data->group_id)) {
                 $group_id = (int) $group_data->group_id;
                 $result = $this->groups_model->delete_group($group_id);
                 if (isset($group_data->org_id) && !empty($group_data->org_id)) {
                      $org_id = (int) $group_data->org_id;
                      $this->groups_model->delete_service_groups($group_id, $org_id);
                 }
            }
            return $result;
       }

       function allgroupslist() {
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
            $group_detail = $this->groups_model->getallgroupslist($str, $org_id);
            echo json_encode($group_detail);
       }

       function get_members() {
            $result = array();
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
            $member_detail = $this->members_model->getallmemberslist($str, $org_id);
            $result['members'] = $member_detail;
            echo json_encode($result);
            exit();
       }

       public function add_member_to_group() {
            $error = FALSE;
            $postdata = file_get_contents("php://input");
            $member_data = json_decode($postdata);
            $result = array();
            if (isset($member_data->user_id) && !empty($member_data->user_id)) {
                 $mid = (int) $member_data->user_id;
            } else {
                 $error = true;
            }
            if (isset($member_data->group) && !empty($member_data->group)) {
                 $group_id = (int) $member_data->group;
            } else {
                 $error = true;
            }
            if (isset($member_data->org_id) && !empty($member_data->org_id)) {
                 $org_id = (int) $member_data->org_id;
            } else {
                 $error = true;
            }
            if (!$error) {
                 $check_group = $this->members_model->member_group_check($org_id, $mid);
                 $group = array('group_id' => $group_id);
                 $member = array(
                     'group_id' => $group_id,
                     'org_id' => $org_id,
                     'user_id' => $mid
                 );
                 if (!empty($check_group)) {

                      $result['group'] = $this->members_model->update_member_group($org_id, $mid, $group);
                 } else {
                      $result['group'] = $this->members_model->add_member_group($member);
                 }
                 $result['status'] = 1;
                 $result['class'] = "alert-success";
                 $result['msg'] = "New member added to this group";
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result);
            exit();
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */

