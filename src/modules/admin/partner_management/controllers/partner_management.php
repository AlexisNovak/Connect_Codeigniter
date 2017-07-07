<?php

  class Partner_management extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('account/account_model', 'partner_model'));
       }

//display basic layout for partner module
       public function index() {
            $data = array();
            $user_id = $this->session->userdata('user_id');
            if (!$this->account_model->is_partner($user_id)) {
                 redirect('app/organisation');
            }
            $this->load->view('partners_list', $data);
       }

       public function view_partner($partner_id) {
            $data = array();
            $user_id = $this->session->userdata('user_id');
            $partner = $this->partner_model->get_partner($partner_id, $user_id);
            if ($partner) {
                 $data['partner'] = true;
            }
            if (!$this->account_model->is_partner($user_id)) {
                 redirect('app/organisation');
            }
            $this->load->view('partner_view', $data);
       }

       public function assign_user() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->partner_id) && !empty($post_data->partner_id)) {
                 $partner_id = $post_data->partner_id;
            } else {
                 $error = TRUE;
                 $errors['partner_id'] = "Invalid";
            }
            if (isset($post_data->userid) && !empty($post_data->userid)) {
                 $userid = $post_data->userid;
            } else {
                 $error = TRUE;
                 $errors['userid'] = "Invalid";
            }
            if (!$error) {
                 $partner_user = $this->partner_model->get_partner_user($userid);
                 if (empty($partner_user) || (isset($partner_user->partner_id) && $partner_user->partner_id == $partner_id)) {
                      if ($this->partner_model->update_partner(array("partner_user" => $userid), $partner_id)) {
                           $result['status'] = 1;
                           $result['msg'] = lang('assign_partner_user_suc');
                           $result['class'] = 'alert-success';
                      } else {
                           $result['status'] = 0;
                           $result['msg'] = lang('try_again');
                           $result['class'] = 'alert-danger';
                      }
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('assign_partner_user_err');
                      $result['class'] = 'alert-danger';
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = 'alert-danger';
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function partnerlist_json() {
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
            $total_result = $this->partner_model->getpartnerlist_by_user_id_count($user_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $partner_detail = $this->partner_model->getpartnerlist_by_user_id($user_id, $perpage, $search, $start);
            $result['end'] = (int) ($start + count($partner_detail));
            $orgns_detail = array();

            $result['partners'] = $partner_detail;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       //upload color scheme css file to filebox/partner/color_scheme and return file name
       public function upload_file() {
            $uploadPath = "filebox/partner/color_scheme";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            if (empty($_FILES)) {
                 echo false;
            }

            $config['upload_path'] = 'filebox/partner/color_scheme';
            $config['file_name'] = 'partner_' . time();
            $config['allowed_types'] = 'css';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                 echo false;
            } else {
                 $data = $this->upload->data();
                 echo json_encode(array("files" => $data['file_name']));
            }
       }

       //update partner details
       public function update_partner($partner_id = 0) {
            $error = false;
            $errors = array();
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            $owner = $this->session->userdata('user_id');
            $partner = $this->partner_model->get_partner($partner_id, $owner);
            $is_edit = FALSE;
            if ($partner) {
                 $is_edit = TRUE;
            }
            if (isset($post_data->p_name) && !empty($post_data->p_name)) {
                 $p_name = htmlentities($post_data->p_name);
            } else {
                 $error = true;
                 $errors['p_name_error'] = "Partner name must be given";
            }
            if (isset($post_data->p_url) && !empty($post_data->p_url)) {
                 $p_url = htmlentities($post_data->p_url);
                 if (!validate_url($p_url)) {
                      $error = true;
                      $errors['p_url_error'] = "Invalid URL";
                 }
            } else {
                 $error = true;
                 $errors['p_url_error'] = "URL must be given";
            }
            if (isset($post_data->p_shortname) && !empty($post_data->p_shortname)) {
                 $p_shortname = htmlentities($post_data->p_shortname);
                 if (!preg_match('/^([A-Z]+)$/', $p_shortname)) {
                      $error = true;
                      $errors['p_shortname_error'] = "Only capital letters allowed";
                 }

                 if (!$is_edit && !$this->is_unique_shortname($p_shortname)) {
                      $error = true;
                      $errors['p_shortname_error'] = "Shortname must be unique";
                 }
            } else {
                 $error = true;
                 $errors['p_shortname_error'] = "Shortname must be given";
            }
            if (isset($post_data->p_domain) && !empty($post_data->p_domain)) {
                 $p_domain = htmlentities($post_data->p_domain);
            } else {
                 $error = true;
                 $errors['p_domain_error'] = "Domain must be specified";
            }
            if (isset($post_data->p_color) && !empty($post_data->p_color)) {
                 $p_color = htmlentities($post_data->p_color);
            } else {
                 $p_color = "";
            }
            if (!$error) {
                 if ($partner) {
                      $data = array(
                          'partner_name' => $p_name,
                          'partner_url' => $p_url,
                          'domain' => $p_domain
                      );
                      if ($p_color !== "") {
                           $data['color_scheme'] = $p_color;
                      }
                      $rs = $this->partner_model->update_partner($data, $partner->partner_id);
                      if ($p_color != $partner->color_scheme && $p_color != "") {
                           if (is_file("filebox/partner/color_scheme/" . $partner->color_scheme))
                                unlink("filebox/partner/color_scheme/" . $partner->color_scheme);
                      }
                      $msg = "Partner details updated";
                 } else {
                      $data = array(
                          'partner_name' => $p_name,
                          'partner_url' => $p_url,
                          'shortname' => $p_shortname,
                          'domain' => $p_domain,
                          'color_scheme' => $p_color,
                          'status' => 1,
                          'owner' => $owner
                      );
                      $rs = $this->partner_model->add_partner($data);
                      $msg = "Partner details added";
                 }
                 if ($rs) {
                      $result['status'] = 1;
                      $result['msg'] = $msg;
                      $result['class'] = 'alert-success';
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = "Something went wrong!";
                      $result['class'] = 'alert-danger';
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Something went wrong!";
                 $result['class'] = 'alert-danger';
                 $result['errors'] = $errors;
            }
            echo json_encode($result);
            exit();
       }

       //get the partner details as json
       public function get_partner_json($partner_id) {
            $owner = $this->session->userdata('user_id');
            $partner = $this->partner_model->get_partner($partner_id, $owner);
            if ($partner) {
                 $iframe_src = site_url('partner_project/partner/' . $partner->shortname);
                 switch ($partner->domain) {
                      case 'http':$iframe_src = str_replace("https://", 'http://', $iframe_src);
                           $iframe_src2 = NULL;
                           $both = FALSE;
                           break;
                      case 'https':$iframe_src = str_replace("http://", 'https://', $iframe_src);
                           $iframe_src2 = NULL;
                           $both = FALSE;
                           break;
                      case 'both':$iframe_src = str_replace("https://", 'http://', $iframe_src);
                           $iframe_src2 = str_replace("http://", 'https://', $iframe_src);
                           $both = TRUE;
                 }
                 $json = array(
                     'partner_id' => $partner->partner_id,
                     'p_name' => $partner->partner_name,
                     'p_url' => $partner->partner_url,
                     'p_shortname' => $partner->shortname,
                     'p_domain' => $partner->domain,
                     'p_color' => $partner->color_scheme,
                     'p_color_name' => $partner->color_scheme,
                     'p_color_path' => $partner->color_scheme ? base_url('filebox/partner/color_scheme/' . $partner->color_scheme) : "",
                     'p_download_path' => $partner->color_scheme ? site_url('partner_management/download_css/' . $partner->color_scheme) : "",
                     'src' => $iframe_src,
                     'sec' => $both,
                     'src2' => $iframe_src2,
                     'default_css' => site_url('partner_management/download_css/')
                 );
            } else {
                 $json = array();
            }
            echo json_encode($json);
            exit();
       }

       public function remove_css() {
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->partner) && !empty($post_data->partner)) {
                 $partner_id = (int) $post_data->partner;
            } else {
                 $partner_id = 0;
            }
            $owner = $this->session->userdata('user_id');
            $partner = $this->partner_model->get_partner($partner_id, $owner);
            if ($partner->color_scheme != "" && is_file("filebox/partner/color_scheme/" . $partner->color_scheme)) {
                 $data['color_scheme'] = "";
                 $rs = $this->partner_model->update_partner($data, $partner_id);
                 if ($rs)
                      unlink("filebox/partner/color_scheme/" . $partner->color_scheme);

                 $result['status'] = 1;
                 $result['msg'] = "Color Scheme removed!";
                 $result['class'] = 'alert-success';
            }else {
                 $result['status'] = 0;
                 $result['msg'] = "Something went wrong!!";
                 $result['class'] = 'alert-danger';
            }
            echo json_encode($result);
            exit();
       }

       //check partner shortname already exsist
       public function is_unique_shortname($shortname) {
            if (!$this->partner_model->get_by_shortname($shortname)) {
                 return true;
            }
            return FALSE;
       }

       public function download_css($css = "") {
            $this->load->helper('download');
            if (is_file("filebox/partner/color_scheme/" . $css)) {
                 $filename = $css;
                 $data = file_get_contents("filebox/partner/color_scheme/" . $css); // Read the file's contents                
            } else {
                 $filename = 'style.css';
                 $data = file_get_contents("resource/styles/style.css"); // Read the file's contents                
            }
            force_download($filename, $data);
            exit();
       }

  }
  