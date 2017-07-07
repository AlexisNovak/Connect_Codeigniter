<?php

  class Courier_management extends MY_Controller {

       public function __construct() {
            parent::__construct();
            $this->load->model(array('couriers/couriers_model', 'couriers/courier_service_model', 'orders/orders_model'));
       }

       public function index() {
            $data = array();
            $this->load->view('couriers_list', $data);
       }

       public function allcourier_json() {
            $data = $this->orders_model->getAll('couriers');
            echo json_encode(array('couriers' => $data));
       }

       public function courierlist_json() {
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
            $total_result = $this->couriers_model->getcourierlist_count($search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $courier_list = $this->couriers_model->getcourierlist($perpage, $search, $start);
            $result['end'] = (int) ($start + count($courier_list));

            $result['couriers'] = $courier_list;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       public function update_courier($id = 0) {
            $data = array();
            $courier = $this->couriers_model->get_by_id($id);
            if ($courier) {
                 $data['courier'] = true;
                 $data['courier_id'] = $id;
            }
            $this->load->view('view_courier', $data);
       }

       public function get_courier_json($id = 0) {
            $account = $this->couriers_model->get_by_id($id);
            if ($account) {
                 $courier = new stdClass();
                 $courier->profile_company_name = $account->company_name;
                 $courier->settings_email = $account->email;
                 $courier->settings_fullname = $account->fullname;
                 $courier->settings_address = $account->reg_address;
                 $courier->settings_url = $account->url;
                 $courier->settings_billing_address = $account->billing_address;
                 $courier->settings_reg_no = $account->reg_no;
                 $courier->settings_phone = $account->phone;
                 $courier->settings_fax = $account->fax;
                 $courier->settings_description = $account->description;
                 $courier->settings_support_email = $account->support_email;
                 $courier->insured_amount = $account->insured_amount ? $account->insured_amount : "";
                 $courier->insured_policy = $account->insured_policy ? $account->insured_policy : "";
                 $courier->compliance_rating = $account->compliance_id ? $account->compliance_id : "";
                 echo json_encode($courier);
            } else {
                 echo '';
            }
            exit();
       }

       public function updateCourier($courier_id = 0) {
            $couriersData = json_decode(file_get_contents('php://input'));
            $errors = array();
            $error = FALSE;
            $result = array();
            if (isset($couriersData->profile_company_name) && !empty($couriersData->profile_company_name)) {
                 $data['company_name'] = htmlentities($couriersData->profile_company_name);
            } else {
                 $error = true;
                 $errors['profile_company_name_error'] = "Company name must be given";
            }
            if (isset($couriersData->settings_fullname) && !empty($couriersData->settings_fullname)) {
                 $settings_fullname = htmlentities(trim($couriersData->settings_fullname));
                 $data['fullname'] = $settings_fullname;
            } else {
                 $error = TRUE;
                 $errors['settings_fullname_error'] = "Fullname must be given";
            }
            if (isset($couriersData->settings_address) && !empty($couriersData->settings_address)) {
                 $settings_address = htmlentities(trim($couriersData->settings_address));
                 $data['reg_address'] = $settings_address;
            } else {
                 //$error = TRUE;
                 $errors['settings_address_error'] = "Registered address is required";
            }
            if (isset($couriersData->settings_billing_address) && !empty($couriersData->settings_billing_address)) {
                 $settings_billing_address = htmlentities(trim($couriersData->settings_billing_address));
                 $data['billing_address'] = $settings_billing_address;
            } else {
                 // $error = TRUE;
                 $errors['settings_billing_address_error'] = "invalid";
            }
            if (isset($couriersData->settings_url) && !empty($couriersData->settings_url)) {
                 $settings_url = htmlentities(trim($couriersData->settings_url));
                 $data['url'] = $settings_url;
            } else {
                 $error = TRUE;
                 $errors['settings_url_error'] = "invalid";
            }
            if (isset($couriersData->settings_reg_no) && !empty($couriersData->settings_reg_no)) {
                 $settings_reg_no = htmlentities(trim($couriersData->settings_reg_no));
                 $data['reg_no'] = $settings_reg_no;
            }
            if (isset($couriersData->settings_phone) && !empty($couriersData->settings_phone)) {
                 $settings_phone = htmlentities(trim($couriersData->settings_phone));
                 $data['phone'] = $settings_phone;
            }
            if (isset($couriersData->settings_fax) && !empty($couriersData->settings_fax)) {
                 $settings_fax = htmlentities(trim($couriersData->settings_fax));
                 $data['fax'] = $settings_fax;
            }
            if (isset($couriersData->settings_description) && !empty($couriersData->settings_description)) {
                 $settings_description = htmlentities(trim($couriersData->settings_description));
                 $data['description'] = $settings_description;
            }
            if (isset($couriersData->settings_support_email) && !empty($couriersData->settings_support_email)) {
                 $settings_support_email = htmlentities(trim($couriersData->settings_support_email));
                 $data['support_email'] = $settings_support_email;
            }
            if (isset($couriersData->compliance_rating) && !empty($couriersData->compliance_rating)) {
                 $compliance_rating = (int) $couriersData->compliance_rating;
                 $data['compliance_id'] = $compliance_rating;
            }
            if (isset($couriersData->insured_amount) && !empty($couriersData->insured_amount)) {
                 $insured_amount = htmlentities(trim($couriersData->insured_amount));
                 if (is_numeric($insured_amount)) {
                      $data['insured_amount'] = $insured_amount;
                 } else {
                      $error = TRUE;
                      $errors['insured_amount_error'] = "invalid";
                 }
            }

            if (isset($couriersData->insured_policy) && !empty($couriersData->insured_policy)) {
                 $insured_policy = htmlentities(trim($couriersData->insured_policy));
                      $data['insured_policy'] = $insured_policy;                 
            }
            
            if (isset($couriersData->profile_logo) && !empty($couriersData->profile_logo)) {
                 $profile_logo = htmlentities($couriersData->profile_logo);
                 if (is_file('filebox/couriers/' . $profile_logo)) {
                      $logo = base_url('filebox/couriers/' . $profile_logo);
                      $data['logo'] = $logo;
                 }
            }

            if (!$error) {
                 if ($this->couriers_model->update($courier_id, $data)) {
                      $result['status'] = 1;
                      $result['msg'] = "Courier details updated.";
                      $result['class'] = "alert-success";
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = "Something went wrong.";
                      $result['class'] = "alert-danger";
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = "Please clear the errors";
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }
            echo json_encode($result);
            exit();
       }

       public function upload() {
            $data = array();
            $order = time();
            $uploadPath = "filebox/couriers";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            $uploaddir = "filebox/couriers/";
            if (isset($_FILES['file'])) {
                 $error = false;
                 $files = "";
                 $ext = end(explode(".", basename($_FILES['file']['name'])));
                 $config['image_library'] = 'gd2';
                 $config['source_image'] = $_FILES['file']['tmp_name'];
                 $config['new_image'] = $uploaddir . $order . "." . $ext;
                 $config['create_thumb'] = TRUE;
                 $config['thumb_marker'] = "";
                 $config['maintain_ratio'] = TRUE;
                 $config['width'] = 200;
                 $config['height'] = 200;

                 $this->load->library('image_lib', $config);
                 //  $this->crop($config['source_image'], $config['new_image']);

                 if ($this->image_lib->resize()) {
                      $this->square_crop($config['source_image'], $config['new_image'], 150);
                      $files = $order . "." . $ext;
                 } else {
                      $errors = $this->image_lib->display_errors();
                      $error = true;
                 }
                 $data = ($error) ? array('error' => $errors) : array('files' => $files);
                 echo json_encode($data);
                 exit();
            } else {
                 $logo = NULL;
                 $result = array();
                 if ($this->input->get('courier_id')) {
                      $courier_id = (int) $this->input->get('courier_id', TRUE);
                 } else {
                      $courier_id = 0;
                 }
                 if ($courier_id) {
                      $logo = $this->couriers_model->getLogo($courier_id);
                 }
                 if ($logo != NULL) {
                      $result['name'] = $logo;
                      $result['size'] = filesize($uploaddir . end(explode('/', $logo)));
                 }

                 echo json_encode($result);
                 exit();
            }
       }

       function square_crop($src_image, $dest_image, $thumb_size = 100, $jpg_quality = 90) {

            // Get dimensions of existing image
            $image = getimagesize($src_image);

            // Check for valid dimensions
            if ($image[0] <= 0 || $image[1] <= 0)
                 return false;

            // Determine format from MIME-Type
            $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

            // Import image
            switch ($image['format']) {
                 case 'jpg':
                 case 'jpeg':
                      $image_data = imagecreatefromjpeg($src_image);
                      break;
                 case 'png':
                      $image_data = imagecreatefrompng($src_image);
                      break;
                 default:
                      // Unsupported format
                      return false;
                      break;
            }

            // Verify import
            if ($image_data == false)
                 return false;

            // Calculate measurements
            if ($image[0] > $image[1]) {
                 // For landscape images
                 $x_offset = ($image[0] - $image[1]) / 2;
                 $y_offset = 0;
                 $square_size = $image[0] - ($x_offset * 2);
            } else {
                 // For portrait and square images
                 $x_offset = 0;
                 $y_offset = ($image[1] - $image[0]) / 2;
                 $square_size = $image[1] - ($y_offset * 2);
            }

            // Resize and crop
            $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
            if (imagecopyresampled(
                            $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
                    )) {

                 // Create thumbnail
                 switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                      case 'jpg':
                      case 'jpeg':
                           return imagejpeg($canvas, $dest_image, $jpg_quality);
                           break;
                      case 'png':
                           return imagepng($canvas, $dest_image);
                           break;
                      default:
                           // Unsupported format
                           return false;
                           break;
                 }
            } else {
                 return false;
            }
       }

       public function approve() {
            $result = array();
            $couriersData = json_decode(file_get_contents('php://input'));
            if (isset($couriersData->courier_id) && !empty($couriersData->courier_id)) {
                 $courier_id = (int) $couriersData->courier_id;
                 if ($this->couriers_model->update($courier_id, array('is_approved' => 1))) {
                      $result['status'] = 1;
                      $result['msg'] = lang('courier_approved');
                      $result['class'] = 'alert-success';
                 } else {
                      $result['status'] = 0;
                      $result['msg'] = lang('try_again');
                      $result['class'] = 'alert-danger';
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = 'alert-danger';
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function serviceslist_json($courier_id = 0) {
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
            $total_result = $this->courier_service_model->getserviceslist_count($courier_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['service_detail'] = $this->courier_service_model->getserviceslist_by_courier($courier_id, $perpage, $search, $start);
            $result['current_user_id'] = $this->session->userdata('user_id');
            $result['end'] = (int) ($start + count($result['service_detail']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       public function associate_orglist($courier_id = 0) {
            $result = array();
            if ($courier_id) {
                 $result['organisations'] = $this->couriers_model->myorganisations_all($courier_id);
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function assigned_services($courier_id = 0) {
            $result = array();
            $result['services'] = $this->couriers_model->get_services_assigned($courier_id);
            echo json_encode($result);
            exit();
       }

       public function get_deliveries_json($courier_id = 0) {
            $perpage = '';
            $search = NULL;
            $service = "";
            $status = "";
            $ordersData = json_decode(file_get_contents('php://input'));
            if (isset($ordersData->perpage_value)) {

                 $perpage = $ordersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($ordersData->currentPage)) {

                 $page = $ordersData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($ordersData->organisation)) {

                 $org = (int) $ordersData->organisation;
            } else {
                 $org = NULL;
            }
            if (isset($ordersData->filter)) {
                 if ($ordersData->filter != NULL) {
                      $search = $ordersData->filter;
                 }
            }
            if (isset($ordersData->service)) {
                 if ($ordersData->service != NULL) {
                      $service = $ordersData->service;
                 }
            }
            if (isset($ordersData->status)) {
                 if ($ordersData->status != NULL) {
                      $status = $ordersData->status;
                 }
            }
            $total_result = $this->orders_model->getorderslist_count_for_courier($courier_id, $search, $org, $service, $status);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $order_detail = $this->orders_model->getorderslist_for_courier($courier_id, $perpage, $search, $start, $org, $service, $status);
            foreach ($order_detail as $value) {
                 $value->collection_address = implode(' ', json_decode($value->collection_address));
                 $value->delivery_address = implode(' ', json_decode($value->delivery_address));
            }
            $result['order_detail'] = $order_detail;
            $result['end'] = (int) ($start + count($result['order_detail']));
            echo json_encode($result);
            exit();
       }

       public function get_compliance_ratings() {
            $result = array();
            $ratings = $this->couriers_model->get_compliance_ratings_list();
            if ($ratings) {
                 $result['ratings'] = $ratings;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  