<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  require(APPPATH . '/libraries/REST_Controller.php');

  class Company extends REST_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'app/members_model',
                'app/groups_model',
                'app/services_model',
                'orders/orders_model',
                'account/account_model',
                'account/account_details_model',
                'token_key_model',
                'app/organisation_model'
            ));
            $this->load->config('codes');
            $this->load->library(array('account/authentication'));
       }

       public function login_post() {
            $username = $this->post('username');
            if (!$username) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Parameter - No username'), 200);
            }
            $userpass = $this->post('userpass');
            if (!$userpass) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Parameter - No user password'), 200);
            }

            $user = $this->account_model->get_by_username_email($username);
            if ($user) {
                 if (!$this->authentication->check_password($user->password, $userpass)) {
                      $this->response(array('code' => $this->config->item('no_permission'), 'msg' => 'Login Failed invalid username or password'), 200);
                 }
                 $token = $this->token_key_model->get_token($user->id);
                 if ($token) {
                      $this->response(array("code" => $this->config->item('success'), "message" => 'Login success', "AuthCode" => $token->token), 200);
                 }
                 $this->response(array('code' => $this->config->item('no_permission'), 'msg' => 'No access Token Found'), 200);
            } else {
                 $this->response(array('code' => $this->config->item('no_permission'), 'msg' => 'Login Failed. This Username/Email does not exist.'), 200);
            }
            $this->response(array("code" => $this->config->item('success'), "AuthCode" => $token), 200);
       }

       public function adduser_post() {
            $username = $this->post('username');
            if (!$username) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Parameter - No username'), 200);
            }
            if ($this->username_check($username) === TRUE) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Username already exist'), 200);
            }
            $usermail = $this->post('usermail');
            if (!$usermail) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Parameter - No user email'), 200);
            }
            // Check if email already exist
            if ($this->email_check($usermail)) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Email already exist'), 200);
            }
            $userpass = $this->post('userpass');
            if (!$userpass) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Parameter - No user password'), 200);
            }

            // Create user
            $user_id = $this->account_model->create($username, $usermail, $userpass);
            $token = $this->token();
            $data['user_id'] = $user_id;
//            $data['username'] = $username;
//            $data['password'] = md5($userpass);
            $data['token'] = $token;

            $success = $this->token_key_model->insert('token_key', $data);

            // Add user details (auto detected country, language, timezone)
            $this->account_details_model->update($user_id);
            $this->response(array("code" => $this->config->item('success'), "AuthCode" => $token), 200);
       }

       public function updateuser_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            $details = $this->account_model->get_by_id($user->user_id);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }

            $username = $this->post('username');
            if ($username) {
                 if (($username != $details->username) && $this->username_check($username) === TRUE) {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Username already exist'), 200);
                 } else {
                      $this->account_model->update_username($user->user_id, $username);
                      //$this->token_key_model->update_username($authcode, $username);
                 }
            }

            $usermail = $this->post('usermail');
            if ($usermail) {
                 if (strtolower($usermail) !== strtolower($details->email) && ($this->email_check($usermail) === TRUE)) {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Email already exist'), 200);
                 } else {
                      $this->account_model->update_email($user->user_id, $usermail);
                 }
            }
            $userpass = $this->post('userpass');
            if ($userpass) {
                 $this->account_model->update_password($user->user_id, $userpass);
                 //$this->token_key_model->update_password($authcode, $userpass);
            }
            $this->response(array("code" => $this->config->item('success'), "response" => "updated"), 200);
       }

       public function addgroup_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }
            $groupname = $this->post('groupname');
            if (!$groupname) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Group Name'), 200);
            }

            $org_id = (int) $this->post('org_id');
            if (!$org_id) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Organisation ID'), 200);
            }
            if ($groupname && $org_id) {
                 if (!$this->members_model->is_orgadmin($org_id, $user->user_id)) {
                      $this->response(array('code' => $this->config->item('no_permission'), 'message' => 'Not allowed'), 200);
                 }
                 $attributes = array(
                     "name" => $groupname,
                     "status" => 1,
                     "description" => "",
                     "org_id" => $org_id,
                     "created_by" => $user->user_id
                 );
                 $this->groups_model->add_new_group($attributes);
            }
            $this->response(array("code" => $this->config->item('success'), "AuthCode" => "New Group Added"), 200);
       }

       public function suspendgroup_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }

            $groupname = $this->post("groupname");
            if (!$groupname) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Group Name'), 200);
            }
            $groupid = (int) $this->post("groupid");
            if (!$groupid) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Group ID'), 200);
            }
            $org_id = $this->groups_model->getOrg($groupid, $groupname);
            if (!$this->members_model->is_orgadmin($org_id, $user->user_id)) {
                 $this->response(array('code' => $this->config->item('no_permission'), 'message' => 'Not allowed'), 200);
            }
            $this->groups_model->suspend_group(array("id" => $groupid));
            $this->response(array("code" => $this->config->item('success'), "response" => "Suspended"), 200);
       }

       public function assignusertogroup_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }

            $groupid = (int) $this->post("groupid");
            if (!$groupid) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Group ID'), 200);
            }
            $user_id = (int) $this->post("user_id");
            if (!$user_id) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No User ID'), 200);
            }
            $org_id = $this->groups_model->getOrg($groupid);
            if (!$this->members_model->is_orguser($org_id, $user_id)) {
                 $this->response(array('code' => $this->config->item('no_permission'), 'message' => 'Not allowed'), 200);
            }
            $check_group = $this->members_model->member_group_check($org_id, $user_id);
            $group = array('group_id' => $groupid);
            $add_groupdata_to_member = array(
                'group_id' => $groupid,
                'org_id' => $org_id,
                'user_id' => $user_id
            );
            if (!empty($check_group)) {

                 $this->members_model->update_member_group($org_id, $user_id, $group);
                 $this->response(array("code" => $this->config->item('success'), "result" => "assigned member to new group"), 200);
            } else {
                 $this->members_model->add_member_group($add_groupdata_to_member);
                 $this->response(array("code" => $this->config->item('success'), "result" => "assigned member to a group"), 200);
            }
            $this->response(array("code" => $this->config->item('success')), 200);
       }

       public function assignservice_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            $org_id = (int) $this->post('org_id');
            if (!$org_id) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Organisation ID'), 200);
            }
            if (!$this->members_model->is_orgadmin($org_id, $user->user_id)) {
                 $this->response(array('code' => $this->config->item('no_permission'), 'message' => 'Not allowed'), 200);
            }
            $service_id = (int) $this->post('service_id');
            if (!$service_id) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Service ID'), 200);
            }
            $atype = $this->post('atype');
            if (!$atype) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Authorization type no specified'), 200);
            }
            if (strtolower($atype) === 'user') {
                 $user_id = (int) $this->post("user_id");
                 if (!$user_id) {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No User ID'), 200);
                 }

                 if ($this->services_model->is_member_exist($org_id, $user_id, $service_id)) {
                      $this->response(array('code' => $this->config->item('error'), 'message' => 'Already assigned '), 200);
                 } else {
                      $data = array(
                          'service_id' => $service_id,
                          'org_id' => $org_id,
                          'member_id' => $user_id,
                          'status' => 1);
                      if ($this->services_model->addMember($data)) {
                           $this->response(array("code" => $this->config->item('success'), "response" => "Assigned"), 200);
                      }
                 }
            } elseif (strtolower($atype) === 'group') {
                 $group_id = (int) $this->post("group_id");
                 if (!$group_id) {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid input - No Group ID'), 200);
                 }
                 if ($this->services_model->is_group_exist($org_id, $group_id, $service_id)) {
                      $this->response(array('code' => $this->config->item('error'), 'message' => 'Already assigned '), 200);
                 } else {
                      $data = array(
                          'service_id' => $service_id,
                          'org_id' => $org_id,
                          'group_id' => $group_id,
                          'status' => 1);
                      if ($this->services_model->addGroup($data)) {
                           $this->response(array("code" => $this->config->item('success'), "response" => "Assigned"), 200);
                      }
                 }
            } else {
                 $this->response(array('code' => $this->config->item('error'), 'message' => 'Unknown Authorization type '), 200);
            }
            $this->response(array("code" => $this->config->item('success')), 200);
       }

       public function getorders_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if ($user) {
                 $consignmentList = $this->orders_model->getorderslist_for_api($user->user_id, 15, 0);

                 if (!$consignmentList) {
                      $this->response(array('code' => '13', 'message' => 'Consignment with these params could not be found'), 200);
                 }
                 $list = array();
                 foreach ($consignmentList as $consignment) {
                      $consign = new stdClass();
                      $caddress = explode(',', $consignment->collection_address);
                      $daddress = explode(',', $consignment->delivery_address);
                      $consign->consId = $consignment->consignment_id;
                      $consign->ordId = $consignment->public_id;
                      $consign->userId = $consignment->customer_id;
                      $consign->serviceId = $consignment->service_id;
                      $consign->status = $consignment->consignment_status_id;
                      $consign->company_id = "company_id";
                      $consign->orderimage = $consignment->picture;
                      $consign->item = $consignment->consignment_type_id;
                      $consign->quantity = $consignment->quantity;
                      $consign->remarks = $consignment->remarks;
                      $consign->length = $consignment->length;
                      $consign->breadth = $consignment->breadth;
                      $consign->height = $consignment->height;
                      $consign->weight = $consignment->weight;
                      $consign->caddress1 = $caddress[0];
                      $consign->caddress2 = $caddress[1];
                      $consign->czipcode = $consignment->collection_post_code;
                      $consign->country = $consignment->collection_country;
                      $consign->cname = $consignment->collection_contact_name;
                      $consign->cmail = $consignment->collection_contact_email;
                      $consign->cphone = $consignment->collection_contact_number;
                      $consign->daddress1 = $daddress[0];
                      $consign->daddress2 = $daddress[1];
                      $consign->dzipcode = $consignment->delivery_post_code;
                      $consign->dcountry = $consignment->delivery_country;
                      $consign->dname = $consignment->delivery_contact_name;
                      $consign->dmail = $consignment->collection_contact_email;
                      $consign->dphone = $consignment->collection_contact_number;
                      $consign->created_date = $consignment->created_date;
                      $consign->cdate1 = $consignment->collection_date;
                      $consign->cdate2 = $consignment->collection_date_to;
                      $consign->ddate1 = $consignment->delivery_date;
                      $consign->ddate2 = $consignment->delivery_date_to;
                      $list[] = $consign;
                 }
                 $consignmentList = array_map("unserialize", array_unique(array_map("serialize", $list)));
                 $this->response($consignmentList, 200);
            } else {
                 $this->response(array('code' => '2', 'message' => 'Invalid Token ID'), 200);
            }
            $this->response(array("code" => $this->config->item('success')), 200);
       }

       public function neworder_post() {
            $authcode = $this->post("token");
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            $item = (int) $this->post("item");
            if (!$item) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No Item'), 200);
            }
            $serviceid = (int) $this->post("serviceid");
            $quantity = (int) $this->post("quantity");
            if (!$quantity) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No Quantity'), 200);
            }
            $remarks = $this->post("remarks");
            $length = (int) $this->post("length");
            if (!$length) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No length'), 200);
            }
            $breadth = (int) $this->post("breadth");
            if (!$breadth) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No breadth'), 200);
            }
            $height = (int) $this->post("height");
            if (!$height) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No height'), 200);
            }
            $weight = (int) $this->post("weight");
            if (!$weight) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No weight'), 200);
            }
            $caddress1 = $this->post("caddress1");
            if (!$caddress1) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No caddress1'), 200);
            }
            $caddress2 = $this->post("caddress2");
            if (!$caddress2) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No caddress2'), 200);
            }
            $czipcode = $this->post("czipcode");
            if (!$czipcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No czipcode'), 200);
            }
            $ctimezone = $this->post("ctimezone");
            if (!$ctimezone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No ctimezone'), 200);
            }
            $cdate = $this->post("cdate");
            if (!$cdate) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No cdate'), 200);
            }
            $cdate_to = $this->post("cdate_to");
            if (!$cdate_to) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No cdate_to'), 200);
            }
            $ccountry = $this->post("ccountry");
            if (!$ccountry) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No ccountry'), 200);
            }
            $daddress1 = $this->post("daddress1");
            if (!$daddress1) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No daddress1'), 200);
            }
            $daddress2 = $this->post("daddress2");
            if (!$daddress2) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No daddress2'), 200);
            }
            $dzipcode = $this->post("dzipcode");
            if (!$dzipcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No dzipcode'), 200);
            }
            $dtimezone = $this->post("dtimezone");
            if (!$dtimezone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No dtimezone'), 200);
            }
            $ddate = $this->post("ddate");
            if (!$ddate) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No ddate'), 200);
            }
            $ddate_to = $this->post("ddate_to");
            if (!$ddate_to) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No ddate_to'), 200);
            }
            $dcountry = $this->post("dcountry");
            if (!$dcountry) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No dcountry'), 200);
            }
            $dname = $this->post("dname");
            if (!$dname) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No dname'), 200);
            }
            $dmail = $this->post("dmail");
            $dphone = $this->post("dphone");
            if (!$dphone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No dphone'), 200);
            }
            $cname = $this->post("cname");
            if (!$cname) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No cname'), 200);
            }
            $cmail = $this->post("cmail");
            $cphone = $this->post("cphone");
            if (!$cphone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Input - No cphone'), 200);
            }

            $description = '';
            $user_id = $user->user_id;
            $this->load->helper('string');
            do {
                 $public_id = random_string('numeric', 14);
            } while (!$this->orders_model->is_unique_publicid($public_id));

            $order_data = array(
                'private_id' => NULL,
                'public_id' => $public_id,
                'is_read' => 0,
                'consignment_type_id' => $item,
                'description' => $description,
                'price' => 0,
                'customer_id' => $user_id,
                'service_id' => $serviceid,
                'is_service_assigned' => 1,
                'quantity' => $quantity,
                'is_bulk' => 1,
                'length' => $length,
                'breadth' => $breadth,
                'height' => $height,
                'volume' => $length * $breadth * $height,
                'weight' => $weight,
                'collection_address' => $caddress1 . "," . $caddress2,
                'collection_date' => date("Y-m-d H:i:s", strtotime($cdate)),
                'collection_date_to' => date("Y-m-d H:i:s", strtotime($cdate_to)),
                'collection_country' => $ccountry,
                'collection_timezone' => $ctimezone,
                'delivery_address' => $daddress1 . "," . $daddress2,
                'delivery_post_code' => $dzipcode,
                'delivery_country' => $dcountry,
                'delivery_timezone' => $dtimezone,
                'delivery_date' => date("Y-m-d H:i:s", strtotime($ddate)),
                'delivery_date_to' => date("Y-m-d H:i:s", strtotime($ddate_to)),
                'delivery_contact_name' => $dname,
                'delivery_contact_email' => $dmail,
                'delivery_contact_phone' => $dphone,
                'created_user_id' => $user_id,
                'collection_post_code' => $czipcode,
                'collection_contact_name' => $cname,
                'collection_contact_number' => $cname,
                'collection_contact_email' => $cmail,
                'send_notification_to_consignee' => 1,
                'consignment_status_id' => C_DRAFT,
                'remarks' => $remarks
            );
            $insert_id = $this->orders_model->addOrder($order_data);
            if ($insert_id) {
                 $this->generate_barcode($insert_id);
                 $this->generate_barcode($public_id);
                 $this->generate_ciqrcode($insert_id);
            }
            $this->response(array("code" => $this->config->item('success'), "response" => "Added New Order "), 200);
       }

       public function listservices_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }
            $services = $this->services_model->get_services_assigned($user->user_id);
            if (!$services) {
                 $this->response(array('code' => '13', 'message' => 'Services assigned could not be found'), 200);
            }
            $services = array_map("unserialize", array_unique(array_map("serialize", $services)));
            $this->response($services, 200);
       }

       public function updateprofile_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }
            $attributes = array();
            $fullname = $this->post("fullname");
            if ($fullname) {
                 $attributes['fullname'] = $fullname;
            }
            $phone_no = $this->post("phone_no");
            if ($phone_no) {
                 $attributes['phone_no'] = $phone_no;
            }
            $fax_no = $this->post("fax_no");
            if ($fax_no) {
                 $attributes['fax_no'] = $fax_no;
            }
            $country = $this->post("country");
            if ($country) {
                 $attributes['country'] = $country;
            }
            $description = $this->post("description");
            if ($description) {
                 $attributes['description'] = $description;
            }
            if (!empty($attributes)) {
                 $this->account_details_model->update($user->user_id, $attributes);
            }
            $language = $this->post("language");
            if ($language) {
                 $this->account_model->update_language($user->user_id, $language);
            }
            $this->response(array("code" => $this->config->item('success'), "response" => "Profile updated"), 200);
       }

       public function changepassword_post() {
            $authcode = $this->post('access_key');
            if (!$authcode) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access Token Found'), 200);
            }
            $user = $this->token_key_model->getUser($authcode);
            if (!$user) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid Access Token'), 200);
            }
            $userpass = $this->post('password');
            if ($userpass) {
                 $this->account_model->update_password($user->user_id, $userpass);
                 $this->token_key_model->update_password($authcode, $userpass);
                 $this->response(array("code" => $this->config->item('success'), "response" => "Password changed"), 200);
            }

            $this->response(array("code" => $this->config->item('success')), 200);
       }

       public function trackorders_post() {
            $org_id = $this->post('org_id');
            if (!$org_id) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No organisation ID'), 200);
            }
            if(!$this->organisation_model->get_tracking_status($org_id)){
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Tracking not enabled'), 200);
                  
            }
            $ids = $this->post('tracking_ids');
            if (!$ids) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No Tracking ID(s)'), 200);
            }
            $d_list = preg_split("/[\s,]+/", $ids);
            $list = "";
            $i = 1;
            foreach ($d_list as $d) {
                 if ($i < 26) {
                      $list.="'$d',";
                 }
                 $i++;
            }
            $list = rtrim($list, ',');

            $jobs = $this->orders_model->trackjobs($list, $org_id);
            foreach ($jobs as $order) {
                 $order->collection_address = implode(' ', json_decode($order->collection_address));
                 $order->delivery_address = implode(' ', json_decode($order->delivery_address));
            }
            if ($jobs) {
                 $this->response(array("code" => $this->config->item('API_OK'), "jobs" => $jobs), 200);
            } else {
                 $this->response(array("code" => $this->config->item('API_OK'), "message" => 'No jobs found'), 200);
            }
            $this->response(array("code" => $this->config->item('API_FAILED')), 200);
       }

       /*
        * 
        * 
        */

       function generate_barcode($id) {
            $uploadPath = "./filebox/barcode";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            header('Content-Type: image/jpg');
            $this->load->library("barcode39", TRUE);
            $bc = new Barcode39($id);
            $bc->draw("./filebox/barcode/consignment_document_" . $id . ".png");
            return;
       }

       function generate_ciqrcode($file_name = NULL) {
            if (!empty($file_name)) {
                 $this->load->library('ciqrcode');
                 $params['level'] = 'H';
                 $params['size'] = 4;

                 $uploadPath = "./filebox/ciqrcode";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777, TRUE);
                 }

                 $file_path = "./filebox/ciqrcode/" . $file_name . '.png';
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

       private function username_check($username) {
            return $this->account_model->get_by_username($username) ? TRUE : FALSE;
       }

       private function email_check($email) {
            return $this->account_model->get_by_email($email) ? TRUE : FALSE;
       }

       private function token() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            return substr(str_shuffle($characters), 0, 13);
       }

  }
  