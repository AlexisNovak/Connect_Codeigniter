<?php

  class CourierAccount extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model('couriers_model');
            $this->load->helper(array('account/ssl', 'photo'));
       }

       public function account_profile() {
            maintain_ssl($this->config->item("ssl_enabled"));
            $data = array();
            $data['courier'] = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $this->load->view("account_profile", $data);
       }

       public function account_settings() {
            maintain_ssl($this->config->item("ssl_enabled"));
            $data = array();
            $data['courier'] = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $this->load->view("account_settings", $data);
       }

       public function account_password() {
            maintain_ssl($this->config->item("ssl_enabled"));
            $data = array();
            $data['courier'] = $this->couriers_model->get_by_id($this->session->userdata("courier_id"));
            $this->load->view("account_password", $data);
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

                 $result = array();
                 $courier_id = $this->session->userdata("courier_id");
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

//       public function crop($img_path, $img_thumb) {
//
//            $config['image_library'] = 'gd2';
//            $config['source_image'] = $img_path;
//            $config['create_thumb'] = TRUE;
//            $config['maintain_ratio'] = TRUE;
//
//            $img = imagecreatefromjpeg($img_path);
//            $_width = imagesx($img);
//            $_height = imagesy($img);
//
//            $img_type = '';
//            $thumb_size = 200;
//
//            if ($_width > $_height) {
//                 // wide image
//                 $config['width'] = intval(($_width / $_height) * $thumb_size);
//                 if ($config['width'] % 2 != 0) {
//                      $config['width'] ++;
//                 }
//                 $config['height'] = $thumb_size;
//                 $img_type = 'wide';
//            } else if ($_width < $_height) {
//                 // landscape image
//                 $config['width'] = $thumb_size;
//                 $config['height'] = intval(($_height / $_width) * $thumb_size);
//                 if ($config['height'] % 2 != 0) {
//                      $config['height'] ++;
//                 }
//                 $img_type = 'landscape';
//            } else {
//                 // square image
//                 $config['width'] = $thumb_size;
//                 $config['height'] = $thumb_size;
//                 $img_type = 'square';
//            }
//
//            $this->load->library('image_lib');
//            $this->image_lib->initialize($config);
//            $this->image_lib->resize();
//
//            // reconfigure the image lib for cropping
//            $conf_new = array(
//                'image_library' => 'gd2',
//                'source_image' => $img_thumb,
//                'create_thumb' => TRUE,
//                'maintain_ratio' => TRUE,
//                'width' => $thumb_size,
//                'height' => $thumb_size
//            );
//
//            if ($img_type == 'wide') {
//                 $conf_new['x_axis'] = ($config['width'] - $thumb_size) / 2;
//                 $conf_new['y_axis'] = 0;
//            } else if ($img_type == 'landscape') {
//                 $conf_new['x_axis'] = 0;
//                 $conf_new['y_axis'] = ($config['height'] - $thumb_size) / 2;
//            } else {
//                 $conf_new['x_axis'] = 0;
//                 $conf_new['y_axis'] = 0;
//            }
//
//            $this->image_lib->initialize($conf_new);
//
//            $this->image_lib->crop();
//       }

       function getAccountDetails() {
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if ($this->is_logged_in()) {
                 $account = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
                 $courier = new stdClass();
                 $courier->profile_company_name = $account->company_name;
                 $courier->profile_logo = $account->logo;
                 //return details as a JSON object
                 echo json_encode($courier);
            } else {
                 echo '';
            }
       }

       function getDetails() {
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if ($this->is_logged_in()) {
                 $account = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
                 $courier = new stdClass();
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
                 echo json_encode($courier);
            } else {
                 echo '';
            }
       }

       function getInfo() {
            maintain_ssl($this->config->item("ssl_enabled"));
            // Redirect unauthenticated users to signin page
            if ($this->is_logged_in()) {
                 $account = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
                 $courier = new stdClass();
                 $courier->settings_email = $account->email;
                 $courier->company_name = $account->company_name;
                 $courier->fullname = $account->fullname;
                 $courier->address = $account->reg_address;
                 $courier->url = $account->url;
                 $courier->billing_address = $account->billing_address;
                 $courier->reg_no = $account->reg_no;
                 $courier->phone = $account->phone;
                 $courier->fax = $account->fax;
                 $courier->description = $account->description;
                 $courier->support_email = $account->support_email;
                 $courier->logo = ($account->logo != NULL) ? $account->logo : base_url("resource/images/default_logo.png");
                 echo json_encode($courier);
            } else {
                 echo '';
            }
       }

       public function updateProfile() {
            $couriersData = json_decode(file_get_contents('php://input'));
            $courier_id = $this->session->userdata('courier_id');
            $errors = array();
            $error = FALSE;
            if (isset($couriersData->profile_company_name) && !empty($couriersData->profile_company_name)) {
                 $company_name = htmlentities($couriersData->profile_company_name);
            } else {
                 $error = true;
            }
            if (isset($couriersData->profile_logo) && !empty($couriersData->profile_logo)) {
                 $profile_logo = htmlentities($couriersData->profile_logo);
                 if (is_file('filebox/couriers/' . $profile_logo)) {
                      $logo = base_url('filebox/couriers/' . $profile_logo);
                      $result['dp'] = base_url('filebox/couriers/' . $profile_logo . '?' . time());
                      $result['upload_success'] = 1;
                 } else {
                      $logo = NULL;
                      $errors['profile_logo_error'] = "Upload failed";
                 }
            } else {
                 $result['dp'] = base_url('resource/images/default_logo.png?' . time());
                 $logo = NULL;
            }
            if (!$error) {
                 $data = array(
                     "company_name" => $company_name,
                     "logo" => $logo
                 );
                 if ($this->couriers_model->update($courier_id, $data)) {
                      $result['success'] = "1";
                 } else {
                      $result['success'] = 0;
                 }
            }
            $result['msg'] = lang('profile_updated');
            $result['class'] = "alert-success";
            $result['errors'] = $errors;
            $result['couriername'] = $company_name;
            $result['logo'] = $logo;
            $data['profile_info'] = lang('profile_updated');
            echo json_encode($result);
            exit();
       }

       function email_check($email) {
            return $this->couriers_model->get_by_email($email) ? TRUE : FALSE;
       }

       public function updateSettings() {
            $couriersData = json_decode(file_get_contents('php://input'));
            $courier_id = $this->session->userdata('courier_id');
            $errors = array();
            $error = FALSE;
            $result = array();
//            if (isset($couriersData->settings_email) && !empty($couriersData->settings_email)) {
//                 $email = htmlentities(trim($couriersData->settings_email));
//                 $account = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
//                 if ($email !== $account->email && $account->is_approved == 0) {
//                      if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
//                           if ($this->email_check($email) === TRUE) {
//                                $error = TRUE;
//                                $errors['settings_email_error'] = "Already exist";
//                           } else {
//                                $data['email'] = $email;
//                           }
//                      } else {
//                           $error = TRUE;
//                           $errors['settings_email_error'] = "Invalid";
//                      }
//                 } elseif ($email !== $account->email && $account->is_approved == 0) {
//                      $errors['settings_email_error'] = "You can't update this email. Its already approved by 6connect admin";
//                 }
//            } else {
//                 $error = TRUE;
//                 $errors['settings_email_error'] = "invalid";
//            }
            if (isset($couriersData->settings_fullname) && !empty($couriersData->settings_fullname)) {
                 $settings_fullname = htmlentities(trim($couriersData->settings_fullname));
                 $data['fullname'] = $settings_fullname;
            } else {
                 $error = TRUE;
                 $errors['settings_fullname_error'] = "invalid";
            }
            if (isset($couriersData->settings_address) && !empty($couriersData->settings_address)) {
                 $settings_address = htmlentities(trim($couriersData->settings_address));
                 $data['reg_address'] = $settings_address;
            } else {
                 $error = TRUE;
                 $errors['settings_address_error'] = "invalid";
            }
            if (isset($couriersData->settings_billing_address) && !empty($couriersData->settings_billing_address)) {
                 $settings_billing_address = htmlentities(trim($couriersData->settings_billing_address));
                 $data['billing_address'] = $settings_billing_address;
            } else {
                 $error = TRUE;
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
            if (isset($couriersData->insured_policy) && !empty($couriersData->insured_policy)) {
                 $insured_policy = intval(trim($couriersData->insured_amount));
                 $data['insured_policy'] = $insured_policy;
            }
            if (!$error) {
                 if ($this->couriers_model->update($courier_id, $data)) {
                      $result['success'] = 1;
                 } else {
                      $result['success'] = 0;
                 }
            }
            $result['errors'] = $errors;
            echo json_encode($result);
            exit();
       }

       public function updatePassword() {
            $postdata = file_get_contents("php://input");
            $postdata = json_decode($postdata);
            $errors = array();
            $result = array();
            $account = $this->couriers_model->get_by_id($this->session->userdata('courier_id'));
            if (isset($postdata->password_current_password)) {
                 $cpass = $postdata->password_current_password;
                 $this->load->helper('account/phpass');
                 $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                 if ($hasher->CheckPassword($cpass, $account->password)) {
                      if (isset($postdata->password_new_password) && isset($postdata->password_retype_new_password) && !empty($postdata->password_new_password)) {
                           if (strcmp($postdata->password_new_password, $postdata->password_retype_new_password) != 0) {
                                $errors['new_retype_password_error'] = "Please retype password correctly";
                           } else {
                                if ($this->is_old_pass($postdata->password_new_password)) {
                                     $errors['new_password_error'] = "Please do not use the last 3 old passwords.";
                                } else {
                                     $old_passwords = $this->couriers_model->get_old_password($this->session->userdata('courier_id'));
                                     $old_pass = array($old_passwords->password);
                                     if ($old_passwords->oldpasswords !== NULL) {
                                          $passwords = json_decode($old_passwords->oldpasswords);
                                          $old_pass[] = $passwords[0] ? $passwords[0] : '';
                                     } else {
                                          $old_pass[] = '';
                                     }
                                     if ($this->couriers_model->update_password($this->session->userdata('courier_id'), htmlentities($postdata->password_new_password), json_encode($old_pass))) {
                                          $result['success'] = 1;
                                     } else {
                                          $result['success'] = 0;
                                     }
                                }
                           }
                      } else {
                           $errors['new_password_error'] = "New password field is required";
                      }
                 } else {
                      $errors['current_password_error'] = "Current password is incorrect";
                 }
            } else {
                 $errors['current_password_error'] = "Current password field is required";
            }

            $result['errors'] = $errors;
            echo json_encode($result);
            exit();
       }

       public function is_old_pass($npass) {
            $this->load->helper('account/phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $courier_id = $this->session->userdata('courier_id');
            $old_passwords = $this->couriers_model->get_old_password($courier_id);
            $old_pass = array($old_passwords->password);
            if ($old_passwords->oldpasswords !== NULL) {
                 $passwords = json_decode($old_passwords->oldpasswords);
                 $old_pass[] = $passwords[0] ? $passwords[0] : '';
                 $old_pass[] = $passwords[1] ? $passwords[1] : '';
            }
            foreach ($old_pass as $pass) {
                 if ($hasher->CheckPassword($npass, $pass)) {
                      return TRUE;
                 }
            }

            return FALSE;
       }

  }
  