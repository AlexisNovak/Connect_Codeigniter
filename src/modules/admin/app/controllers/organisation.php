<?php

  class Organisation extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('app/organisation_model');
            $this->load->model('app/members_model');
            $this->load->model('app/services_model');
            $this->load->model('account/account_model');

            // Load the necessary stuff...
       }

       function index() {
            $this->load->view('app/organisation_list');
       }

       public function get_total_org() {
            $result = array();
            $user_id = $this->session->userdata('user_id');
            $result['total'] = $this->organisation_model->getorganisationlist_by_user_id_count($user_id, NULL);
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function organisationlist_json() {
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
            $user_id = $this->session->userdata('user_id');
            $total_result = $this->organisation_model->getorganisationlist_by_user_id_count($user_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $organisation_detail = $this->organisation_model->getorganisationlist_by_user_id($user_id, $perpage, $search, $start);
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
                     'org_name' => $org_detail->org_name,
                     'org_shortname' => $org_detail->org_shortname,
                     'Description' => $org_detail->Description,
                     'Website' => $org_detail->Website,
                     'Adminusers' => rtrim($admin_users, ','),
                     'scount' => $org_detail->scount ? 0 : 1
                 );
            }
            $result['organisations'] = $orgns_detail;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       function myorganisation_list() {
            $result = array();
            $user = $this->session->userdata("user_id");
            // $organisations = $this->organisation_model->myorganisations($user);
            $usr = $this->account_model->get_by_id($user);
            if ($usr->root) {
                 $flag = true;
            } else {
                 $flag = FALSE;
            } $organisations = $this->organisation_model->myorganisations($user);
            $total_result = count($organisations);
            switch ($total_result) {
                 case 0: $result['any'] = false;
                      $result['msg'] = "You are not a part of any organization";
                      $result['class'] = "alert-danger";
                      break;
                 case 1: $result['any'] = true;
                      $result['many'] = false;
                      $result['org_id'] = $organisations[0]->org_id;
                      $result['organisations'] = $organisations;
                      break;
                 default :$result['any'] = true;
                      $result['many'] = true;
                      $result['organisations'] = $organisations;
                      break;
            }
            //  $result['organisations'] = $organisations;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function allorganisations() {
            $result = array();
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->search) && !empty($data->search)) {
                 $search = $data->search;
            } else {
                 $search = NULL;
            }
            $organisations = $this->organisation_model->allorganisations($search, TRUE);
            $result['organisations'] = $organisations;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function myorganisation_list_all() {
            $result = array();
            $user = $this->session->userdata("user_id");
            $usr = $this->account_model->get_by_id($user);
            if ($usr->root) {
                 $flag = true;
            } else {
                 $flag = FALSE;
            }
            $organisations = $this->organisation_model->myorganisations_all($user, $flag);
            $total_result = count($organisations);
            switch ($total_result) {
                 case 0: $result['any'] = false;
                      $result['msg'] = "You are not a part of any organization";
                      $result['class'] = "alert-danger";
                      break;
                 case 1: $result['any'] = true;
                      $result['many'] = false;
                      $result['org_id'] = $organisations[0]->org_id;
                      $result['organisations'] = $organisations;
                      break;
                 default :$result['any'] = true;
                      $result['many'] = true;
                      $result['organisations'] = $organisations;
                      break;
            }
            //  $result['organisations'] = $organisations;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function myorganisations() {
            $result = array();
            $user = $this->session->userdata("user_id");
            $organisations = $this->organisation_model->myorganisations($user);
            $total_result = count($organisations);
            switch ($total_result) {
                 case 0: $result['any'] = false;
                      $result['msg'] = "You are not a part of any organization";
                      $result['class'] = "alert-danger";
                      break;
                 case 1: $result['any'] = true;
                      $result['many'] = false;
                      $result['org_id'] = $organisations[0]->org_id;
                      break;
                 default :$result['any'] = true;
                      $result['many'] = true;
                      $result['organisations'] = $organisations;
                      break;
            }echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       function admin_organisations() {
            $result = array();
            $user = $this->session->userdata("user_id");
            $organisations = $this->organisation_model->admin_organisations($user);
            $total_result = count($organisations);
            switch ($total_result) {
                 case 0: $result['any'] = false;
                      $result['msg'] = "You are not a part of any organization";
                      $result['class'] = "alert-danger";
                      break;
                 case 1: $result['any'] = true;
                      $result['many'] = false;
                      $result['org_id'] = $organisations[0]->org_id;
                      $result['organisations'] = $organisations;
                      break;
                 default :$result['any'] = true;
                      $result['many'] = true;
                      $result['organisations'] = $organisations;
                      break;
            } echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function request_allowed_organisations() {
            $result = array();
            $user = $this->session->userdata("user_id");
            $organisations = $this->organisation_model->request_allowed_organisations($user);
            $total_result = count($organisations);
            switch ($total_result) {
                 case 0: $result['any'] = false;
                      $result['msg'] = "You are not a part of any organization";
                      $result['class'] = "alert-danger";
                      break;
                 case 1: $result['any'] = true;
                      $result['many'] = false;
                      $result['org_id'] = $organisations[0]->org_id;
                      $result['organisations'] = $organisations;
                      break;
                 default :$result['any'] = true;
                      $result['many'] = true;
                      $result['organisations'] = $organisations;
                      break;
            } echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       /*
        * build a form to create new organisation
        * 
        */

       public function create() {
            if (true) {
                 $this->load->view("app/new_organisation_form");
            } else {
                 
            }
       }

       public function edit($id) {
            if (true) {
                 $this->load->view("app/edit_organisation_form");
            } else {
                 
            }
       }

       public function getOrganisation($id, $intro = false) {
            $data['organisation'] = $this->organisation_model->getorganisationDetails($id);
            $data['is_admin'] = $this->members_model->is_admin($id);
            $data['is_member'] = $this->members_model->is_member($id);
            $data['org_id'] = $id;
            if ($intro) {
                 $this->load->view("app/neworg", $data);
            } else {
                 $this->load->view("app/organisation_details", $data);
            }
       }

//function to retrieve organisation details
       public function getOrganisationDetails() {
            $postdata = file_get_contents("php://input");
            $org_data = json_decode($postdata);
            $errors = array();
            $is_edit = false;
            if (isset($org_data->id) && !empty($org_data->id)) {
                 $org_id = (int) $org_data->id;
                 $organisation = $this->organisation_model->getorganisationDetails($org_id);
                 if ($organisation) {
                      $organisation->allow_api = $organisation->allow_api ? true : FALSE;
                 }
                 echo json_encode($organisation);
            }
       }

       /*
        * add new organisation
        * 
        */

       public function save() {
            $postdata = file_get_contents("php://input");
            $org_data = json_decode($postdata);
            $error = false;
            $errors = array();
            $is_edit = false;
            if (isset($org_data->id) && !empty($org_data->id)) {
                 $org_id = (int) $org_data->id;
                 $is_edit = true;
            }
            if (isset($org_data->name) && !empty($org_data->name)) {
                 $name = htmlentities($org_data->name);
            } else {
                 $error = true;
                 $errors['name_error'] = lang("name_error");
            }
            if (isset($org_data->shortname) && !empty($org_data->shortname)) {
                 $shortname = htmlentities($org_data->shortname);
            } else {
                 $error = true;
                 $errors['shortname_error'] = lang("shortname_error");
            }
            if (isset($org_data->website) && !empty($org_data->website)) {
                 $website = htmlentities($org_data->website);
                 $search = array('http://', 'ftp://', 'https://');
                 $replace = array('', '', '');
                 $domain = str_replace($search, $replace, $website);
                 if (strpos($domain, "www.") < 1) {
                      $domain = str_replace('www.', '', $domain);
                 }
                 $regex = "/^([a-z0-9][a-z0-9\-]{1,63})\.[a-z\.]{2,6}$/i";

                 if (preg_match($regex, $domain)) {
                      
                 } else {
                      $error = true;
                      $errors['website_error'] = lang("website_error");
                 }
            } else {
                 $website = NULL;
            }
            if (isset($org_data->description) && !empty($org_data->description)) {
                 $description = htmlentities($org_data->description);
            } else {
                 $description = NULL;
            }
            if (isset($org_data->upload) && !empty($org_data->upload)) {
                 $upload = htmlentities($org_data->upload);
            } else {
                 $upload = NULL;
            }

            if (!$error) {
                 if ($is_edit) {
                      if ($this->organisation_model->is_admin($org_id)) {
                           $attributes = array(
                               "name" => $name,
                               "shortname" => $shortname,
                               "website" => $website,
                               "description" => $description
                           );
                           if ($upload != NULL && is_file('filebox/organisation/' . $upload)) {
                                $old_upload = $this->organisation_model->get_last_avatar($org_id);

                                $src = base_url('filebox/organisation/' . $upload);
                                if ($old_upload && $old_upload != $upload && $old_upload != 'default_org.jpg') {
                                     unlink('filebox/organisation/' . $old_upload);
                                }
                                $attributes['avatar'] = $upload;
                           }
                           $result = $this->organisation_model->edit_organisation($attributes, $org_id);
                           $json_array = array('status' => 1, "result" => $result);
                           if (isset($src)) {
                                $json_array['avatar'] = $src;
                           }
                           $json = json_encode($json_array);
                      } else {
                           $json = json_encode(array('status' => 2, "result" => array("msg" => "Not authorised")));
                      }
                 } else {
                      do {
                           $hash_code = substr(md5(uniqid(mt_rand(), true)), 0, 16);
                      } while (!$this->organisation_model->is_unique_hash($hash_code));
                      $attributes = array(
                          "name" => $name,
                          "shortname" => $shortname,
                          'hash_code' => $hash_code,
                          "website" => $website,
                          "description" => $description,
                          "status" => 1,
                          "user_id" => $this->session->userdata('user_id')
                      );
                      $result = $this->organisation_model->add_new_organisation($attributes);
                      $json = json_encode(array('status' => 1, "result" => $result));
                 }
            } else {

                 $json = json_encode(array('status' => 0, "result" => $errors));
            }
            echo $json;
       }

       /*
        * function to display organisation products
        */

       public function products() {
            $this->load->view("app/products_list");
       }

       /*
        * function to display organisation services
        */

       public function services($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);

            $data['organisation'] = $this->organisation_model->getorganisationDetails($org_id);
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $data['is_member'] = $this->members_model->is_member($org_id);
            $this->load->view("app/services_list", $data);
       }

       /*
        * function to display organisation api
        */

       public function api($org_id) {
            $data['org_id'] = $org_id;
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $data['accesskey'] = $this->organisation_model->get_accesskey($org_id);
            $this->load->view("app/orgapi", $data);
       }

       public function get_access_key($org_id) {
            $result = array();
            $result['accesskey'] = $this->organisation_model->get_accesskey($org_id);
            echo json_encode($result);
            exit();
       }

       public function groups($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $this->load->view('groups_list', $data);
       }
       
       public function parceltypes($org_id) {
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $this->load->view('parceltypes', $data);
       }

       /*
        * function to display organisation settings
        */

       public function settings() {
            $this->load->view("app/settings");
       }

       /*
        * function to display organisation lead activities
        */

       public function activity($org_id) {
            $this->load->view("app/activity_list");
       }

       public function uploaddp($org_id = NULL) {
            $data = array();
            $uploadPath = "filebox/organisation";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            $uploaddir = "filebox/organisation/";
            if (isset($_FILES['file_upload'])) {
                 $error = false;
                 $files = "";
                 $org = time();
                 $ext = end(explode(".", basename($_FILES['file_upload']['name'])));

                 $config['image_library'] = 'gd2';
                 $config['source_image'] = $_FILES['file_upload']['tmp_name'];
                 $config['new_image'] = $uploaddir . $org . "." . $ext;
                 $config['create_thumb'] = TRUE;
                 $config['thumb_marker'] = "";
                 $config['maintain_ratio'] = TRUE;
                 $config['width'] = 100;
                 $config['height'] = 100;

                 $this->load->library('image_lib', $config);

                 if ($this->image_lib->resize()) {
                      $files = $org . "." . $ext;
                 } else {
                      $errors = $this->image_lib->display_errors();
                      $error = true;
                 }
                 $data = ($error) ? array('error' => $errors) : array('files' => $files);
            } else if ($org_id != NULL) {

                 $organisation = $this->organisation_model->getorganisationDetails($org_id);
                 $logo = isset($organisation->avatar) ? $organisation->avatar : NULL;
                 if ($logo != NULL) {
                      $data['name'] = $logo;
                      $data['size'] = filesize($uploaddir . $logo);
                 }
            } else {
                 $data = array('error' => 'There was an error uploading your files1');
            }

            echo json_encode($data);
            exit();
       }

       public function upload_tracking_logo($org_id = NULL) {
            $data = array();
            $uploadPath = "filebox/organisation/tracking";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            $uploaddir = "filebox/organisation/tracking/";
            if (isset($_FILES['file_upload'])) {
                 $error = false;
                 $files = "";
                 $org = time();
                 $ext = end(explode(".", basename($_FILES['file_upload']['name'])));

                 $config['image_library'] = 'gd2';
                 $config['source_image'] = $_FILES['file_upload']['tmp_name'];
                 $config['new_image'] = $uploaddir . $org . "." . $ext;
                 $config['create_thumb'] = TRUE;
                 $config['thumb_marker'] = "";
                 $config['maintain_ratio'] = TRUE;
                 $config['height'] = 60;

                 $this->load->library('image_lib', $config);

                 if ($this->image_lib->resize()) {
                      $files = $org . "." . $ext;
                 } else {
                      $errors = $this->image_lib->display_errors();
                      $error = true;
                 }
                 $data = ($error) ? array('error' => $errors) : array('files' => $files);
            } else if ($org_id != NULL) {

                 $organisation = $this->organisation_model->getorganisationDetails($org_id);
                 $logo = isset($organisation->tracking_logo) ? $organisation->tracking_logo : NULL;
                 $name = isset($organisation->tracking_logo) ? base_url() . $uploaddir . $organisation->tracking_logo : NULL;
                 if ($logo != NULL) {
                      $data['name'] = $name;
                      $data['size'] = filesize($uploaddir . $logo);
                 }
            } else {
                 $data = array('error' => 'There was an error uploading your files1');
            }

            echo json_encode($data);
            exit();
       }

       public function get_use_public_status($org_id) {
            $status = $this->organisation_model->get_use_public_status($org_id);
            $result['status'] = $status;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_service_count($org_id) {
            $total_result = $this->services_model->getserviceslist_count($org_id, NULL);
            $result['total'] = $total_result;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function enable_use_public() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $org_data = json_decode($postdata);
            if (isset($org_data->org_id) && !empty($org_data->org_id)) {
                 $org_id = (int) $org_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($org_data->use_public) && !empty($org_data->use_public)) {
                 $use_public = 1;
            } else {
                 $use_public = 0;
            }
            $attributes = array(
                "use_public_service" => $use_public
            );
            if ($this->organisation_model->is_admin($org_id)) {

                 if ($this->organisation_model->edit_organisation($attributes, $org_id)) {
                      $result['status'] = 1;
                      if ($use_public) {
                           $result['msg'] = lang('allow_use_public_suc');
                      } else {
                           $result['msg'] = lang('not_allow_use_public_suc');
                      }
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['class'] = "alert-danger";
                      $result['msg'] = lang('try_again');
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_api_status($org_id) {
            $status = $this->organisation_model->get_api_status($org_id);
            $result['status'] = $status;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function enable_api() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $org_data = json_decode($postdata);
            if (isset($org_data->org_id) && !empty($org_data->org_id)) {
                 $org_id = (int) $org_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($org_data->allow_api) && !empty($org_data->allow_api)) {
                 $allow_api = 1;
            } else {
                 $allow_api = 0;
            }
            $attributes = array(
                "allow_api" => $allow_api
            );
            if ($this->organisation_model->is_admin($org_id)) {

                 if ($this->organisation_model->edit_organisation($attributes, $org_id)) {
                      $result['status'] = 1;
                      if ($allow_api) {
                           $result['msg'] = lang('enable_suc');
                      } else {
                           $result['msg'] = lang('disable_suc');
                      }
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['class'] = "alert-danger";
                      $result['msg'] = lang('try_again');
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function reset_hashcode($org_id) {
            if ($this->organisation_model->is_admin($org_id)) {
                 do {
                      $hash_code = substr(md5(uniqid(mt_rand(), true)), 0, 16);
                 } while (!$this->organisation_model->is_unique_hash($hash_code));
                 $attributes = array(
                     "hash_code" => $hash_code
                 );
                 if ($this->organisation_model->edit_organisation($attributes, $org_id)) {
                      $result['status'] = 1;
                      $result['msg'] = lang('reset_hash_suc');
                      $result['class'] = "alert-success";
                      $result['accesskey'] = $hash_code;
                 } else {
                      $result['status'] = 0;
                      $result['class'] = "alert-danger";
                      $result['msg'] = lang('try_again');
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

  /* End of file organisation.php */
  /* Location: ./system/application/controllers/app/organisation.php */
?>