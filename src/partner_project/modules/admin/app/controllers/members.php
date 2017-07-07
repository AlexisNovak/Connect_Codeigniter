<?php

  class Members extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('app/members_model', 'account/account_details_model', 'account/account_model'));
       }

       function index($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            $this->load->view('app/members_list', $data);
       }

       //function to add new member to organisation

       function addMember($orgid) {
            $memberData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($memberData->Userid)) {
                 $user_id = (int) $memberData->Userid;
                 if ($this->members_model->is_member_exist($orgid, $user_id)) {
                      $result['status'] = 2;
                      $result['msg'] = "Already joined with this organisation";
                      $result['class'] = "alert-warning";
                 } else {
                      $data = array(
                          'org_id' => $orgid,
                          'user_id' => $user_id,
                          'note' => "",
                          'role_id' => 2, //member
                          'status' => 'active');
                      if ($this->members_model->addMember($data)) {
                           $result['status'] = 1;
                           $result['class'] = "alert-success";
                           $result['msg'] = "New member added to this organisation";
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

       //function to invite new member to organisation

       function inviteMember($orgid) {
            $memberData = json_decode(file_get_contents('php://input'));
            $result = array();
            if (isset($memberData->email) && valid_email($memberData->email)) {
                 $email = htmlentities($memberData->email);
                 if (false) {
                      $result['status'] = 2;
                      $result['msg'] = "Already joined with this organisation";
                      $result['class'] = "alert-warning";
                 } else {
                      $email_array = explode('@', $email);
                      $fullname = $email_array[0] ? $email_array[0] : 'Anonymous';
                      $user_id = $this->account_model->create($fullname, $email, NULL, TRUE);
                      $this->account_details_model->update($user_id, array('fullname' => $fullname));
                      $data = array(
                          'org_id' => $orgid,
                          'user_id' => $user_id,
                          'note' => "",
                          'role_id' => 2, //member
                          'status' => 'pending');
                      $this->members_model->addMember($data);
                      $this->load->helper('string');
                      $token = random_string('unique');
                      $data = array(
                          "org_id" => $orgid,
                          "email" => $email,
                          "token" => $token,
                          "status" => 1,
                          "sent_by" => $this->session->userdata("partner_user_id")
                      );
                      $this->load->model("app/invitations_model");
                      if ($this->invitations_model->addInvite($data)) {
                           $account_details = $this->account_details_model->get_name_by_user_id($this->session->userdata('partner_user_id'));
                           $sender = $account_details->fullname ? $account_details->fullname : $account_details->username;
                           $this->load->model('app/organisation_model');
                           $organisation = $this->organisation_model->getorganisationDetails($orgid);
                           $org_name = $organisation->name;
                           $to = $email;
                           $to_name = $email;
                           $subject = $sender . ' invited to join the organization "' . $org_name . '" on 6Connect';
                           $link = site_url('account/sign_up?t=' . $token);
                           $link = str_replace(IFRAME_FOLDER . '/', '', $link);
                           $message = array(
                               'title' => '',
                               'name' => $to_name,
                               'content' => array(
                                   'You have been invited by ' . $sender . ' to join his organisation ' . $org_name . ' so that both of you can effortlessly manage your logistics needs',
                                   'Please click <a href="' . $link . '">here</a> to join now.',
                                   'No worries if you are not a member yet, we will get your started in seconds with no cost involved :)',
                                   'Why use 6Connect',
                                   '6Connect believes that delivery services nowadays are full of resource wastage, lack of information transparency and unjust to the real folks doing the real delivery works.',
                                   'We want to bring fairness and clarify to this industry and make deliveries as simple as sending a email.'
                               ),
                               'link_title' => '',
                               'link' => '');
                           $mail_result = save_mail($to, $to_name, $subject, $message,1);
                           $result['mail'] = $mail_result;
                           if ($mail_result) {
                                $result['status'] = 1;
                                $result['msg'] = "Invitation has been sent";
                                $result['class'] = "alert-success";
                           } else {
                                $result['status'] = 0;
                                $result['msg'] = "Unable to send invitation";
                                $result['class'] = "alert-danger";
                           }
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = "The invitation cannot be processed";
                           $result['class'] = "alert-danger";
                      }
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Provide a valid email id";
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
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
            if (isset($membersData->filter)) {
                 if ($membersData->filter != NULL) {
                      $search = $membersData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->members_model->getmemberslist_count($org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['member_detail'] = $this->members_model->getmemberslist_by_orgid($org_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('partner_user_id');
            $result['end'] = (int) ($start + count($result['member_detail']));
            echo json_encode($result);
       }

       function get_detail() {
            $postdata = file_get_contents("php://input");
            $member_data = json_decode($postdata);
            if (isset($member_data)) {
                 $member_detail = $this->members_model->get_detail_by_userid($member_data->member_id, $member_data->org_id);
                 $member_detail[0]->current_user_id = $this->session->userdata('partner_user_id');
                 echo json_encode($member_detail, JSON_NUMERIC_CHECK);
            }
       }

       public function update_member() {
            $postdata = file_get_contents("php://input");
            $member_data = json_decode($postdata);
            $result = array();
            $mid = (int) $member_data->user_id;
            if ($mid == $this->session->userdata('partner_user_id')) {
                 $check_self_updation = $this->members_model->is_self($member_data->org_id, $mid, $member_data->role_id);
                 if ($check_self_updation) {
                      $result['reload'] = 1;
                 }
            }

            $check_admin = $this->members_model->is_superadmin($member_data->org_id, $mid);
            if ($check_admin) {
                 $updatedata = array('note' => $member_data->note, 'status' => $member_data->status);
            } else {
                 $updatedata = array('note' => $member_data->note, 'status' => $member_data->status, "role_id" => $member_data->role_id);
            }

            if (isset($member_data->group)) {
                 $check_group = $this->members_model->member_group_check($member_data->org_id, $mid);
                 $group = array('group_id' => (int) $member_data->group);
                 $add_groupdata_to_member = array(
                     'group_id' => $member_data->group,
                     'org_id' => $member_data->org_id,
                     'user_id' => $mid
                 );
                 if (!empty($check_group)) {

                      $result['group'] = $this->members_model->update_member_group($member_data->org_id, $mid, $group);
                 } else {
                      $result['group'] = $this->members_model->add_member_group($add_groupdata_to_member);
                 }
            }
            $result['update'] = $this->members_model->update_member($member_data->org_id, $mid, $updatedata);

            echo json_encode($result);
       }

       public function delete_member() {
            $postdata = file_get_contents("php://input");
            $member_data = json_decode($postdata);
            $result = $this->members_model->delete_members($member_data->member_id, $member_data->org_id);

            return $result;
       }

       function allmemberslist() {
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
            if (!$member_detail) {
                 if (valid_email($str)) {
                      $result['valid'] = TRUE;
                 } else {
                      $result['valid'] = FALSE;
                 }
            }
            $result['members'] = $member_detail;
            echo json_encode($result);
            exit();
       }

       function orgmemberslist() {
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
            $member_detail = $this->members_model->getallorgmemberslist($str, $org_id);
            echo json_encode($member_detail);
       }

       function orgmemberslistwithgroup() {
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
            $member_detail = $this->members_model->getallorgmemberslistwithgroup($str, $org_id);
            foreach ($member_detail as $member) {
                 if ($member->groupname == NULL) {
                      $member->groupname = "Not in Group";
                 }
            }
            echo json_encode($member_detail);
       }

  }

  /* End of file home.php */
/* Location: ./system/application/controllers/home.php */