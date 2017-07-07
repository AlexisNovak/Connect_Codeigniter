<?php

  /*
   * Account_profile Controller
   */

  class Account_profile extends MY_Controller {

       /**
        * Constructor
        */
       function __construct() {
            parent::__construct();

            // Load the necessary stuff...
            $this->load->config('account/account');
            $this->load->helper(array('account/ssl', 'photo'));
            $this->load->library(array('account/authentication', 'account/authorization', 'gravatar'));
            $this->load->model(array('account/account_model', 'account/account_details_model'));
       }

       /**
        * Account profile
        */
       function index($action = NULL) {
            // Enable SSL?
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if (!$this->authentication->is_signed_in()) {
                 redirect('account/sign_in/?continue=' . urlencode(base_url() . 'account/account_profile'));
            }

            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));

            // Retrieve user's gravatar if available
            $data['gravatar'] = $this->gravatar->get_gravatar($data['account']->email);

            // Delete profile picture
            if ($action == 'delete') {
                 unlink(FCPATH . RES_DIR . '/user/profile/' . $data['account_details']->picture); // delete previous picture
                 $this->account_details_model->update($data['account']->id, array('picture' => NULL));
                 redirect('account/account_profile');
            }

            // Setup form validation
            $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
            $this->form_validation->set_rules(array(array('field' => 'profile_username', 'label' => 'lang:profile_username', 'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[24]')));

            // Run form validation
            if ($this->form_validation->run()) {
                 
            }

            $this->load->view('account/account_profile', $data);
       }

       /**
        * Check if a username exist
        *
        * @access public
        * @param string
        * @return bool
        */
       function username_check($username) {
            return $this->account_model->get_by_username($username) ? TRUE : FALSE;
       }

       /*
        *  Delete profile picture		
        */
       /*
        *  Retrieve sign in user details 
        * 
        */

       function getAccountDetails() {
            maintain_ssl($this->config->item("ssl_enabled"));

            // Redirect unauthenticated users to signin page
            if ($this->authentication->is_signed_in()) {
                 $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
                 $account_details = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));

                 //values to auto-populate account settings form
                 $user = new stdClass();
                 $user->profile_username = $account->username;
                 $user->profile_picture = $account_details->picture;
                 $user->settings_email = $account->email;
                 $user->settings_fullname = $account_details->fullname;
                 $user->settings_description = $account_details->description;
                 $user->settings_phone = $account_details->phone_no;
                 $user->settings_fax = $account_details->fax_no;
                 //return details as a JSON object
                 echo json_encode($user);
            } else {
                 echo '';
            }
       }

       /*
        * 
        * upadte account settings
        * 
        */

       function updateProfile() {
            $error = false;
            $errors = array();
            $uploadPath = "filebox/user/profile";
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));

            // Retrieve user's gravatar if available
            $data['gravatar'] = $this->gravatar->get_gravatar($data['account']->email);
            // If user is changing username and new username is already taken

            if ($this->input->post('settings_fullname') && strlen(trim($this->input->post('settings_fullname'))) > 0) {
                 
            } else {
                 $errors['settings_fullname_error'] = lang('profile_fullname_empty');
                 $error = TRUE;
            }

            if ($this->input->post('settings_email')) {

                 // If user is changing email and new email is already taken
                 if (strtolower($this->input->post('settings_email', TRUE)) != strtolower($data['account']->email) && $this->email_check($this->input->post('settings_email', TRUE)) === TRUE) {
                      $error = TRUE;
                      $errors['settings_email_error'] = lang('settings_email_exist');
                 }
            } else {
                 $errors['settings_email_error'] = lang('settings_email_empty_error');
                 $error = TRUE;
            }

            switch ($this->input->post('pic_selection')) {
                 case "gravatar":

                      $this->account_details_model->update($data['account']->id, array('picture' => $data['gravatar']));
                      //redirect(current_url());
                      $result['upload_success'] = 1;
                      break;

                 default:

                      // If user has uploaded a file
                      if (isset($_FILES['account_picture_upload']) && $_FILES['account_picture_upload']['error'] != 4) {
                           // Load file uploading library - http://codeigniter.com/user_guide/libraries/file_uploading.html
                           $this->load->library('upload', array('overwrite' => TRUE, 'upload_path' => 'filebox/user/profile', 'allowed_types' => 'jpg|png|gif', 'max_size' => '800' // kilobytes
                           ));

                           /// Try to upload the file
                           if (!$this->upload->do_upload('account_picture_upload')) {
                                $errors['profile_picture_error'] = $this->upload->display_errors('', '');
                                $error = TRUE;
                           } else {
                                // Get uploaded picture data
                                $picture = $this->upload->data();

                                // Create picture thumbnail - http://codeigniter.com/user_guide/libraries/image_lib.html
                                $this->load->library('image_lib');
                                $this->image_lib->clear();
                                $this->image_lib->initialize(array('image_library' => 'gd2', 'source_image' => 'filebox/user/profile/' . $picture['file_name'], 'new_image' => 'filebox/user/profile/pic_' . md5($data['account']->id) . $picture['file_ext'], 'maintain_ratio' => FALSE, 'quality' => '100%', 'width' => 100, 'height' => 100));

                                // Try resizing the picture
                                if (!$this->image_lib->resize()) {
                                     $errors['profile_picture_error'] = $this->image_lib->display_errors();
                                     $error = TRUE;
                                } else {
                                     $data['account_details']->picture = 'pic_' . md5($data['account']->id) . $picture['file_ext'];
                                     $this->account_details_model->update($data['account']->id, array('picture' => $data['account_details']->picture));
                                     $result['upload_success'] = 1;
                                }

                                // Delete original uploaded file
                                unlink('filebox/user/profile/' . $picture['file_name']);
                                //redirect(current_url());
                           }
                      }

                      break;
            } // end switch
            if (!$error) {
                 $data['account']->username = $this->input->post('settings_fullname', TRUE);
                 $this->account_model->update_username($data['account']->id, $this->input->post('settings_fullname', TRUE));
                 // Update account email
                 $this->account_model->update_email($data['account']->id, $this->input->post('settings_email', TRUE) ? $this->input->post('settings_email', TRUE) : NULL);
                 $attributes = array();
                 $attributes['fullname'] = $this->input->post('settings_fullname', TRUE) ? $this->input->post('settings_fullname', TRUE) : NULL;
                 $attributes['description'] = $this->input->post('settings_description', TRUE) ? $this->input->post('settings_description', TRUE) : NULL;
                 $attributes['phone_no'] = $this->input->post('settings_phone') ? $this->input->post('settings_phone') : NULL;
                 $attributes['fax_no'] = $this->input->post('settings_fax') ? $this->input->post('settings_fax') : NULL;
                 $this->account_details_model->update($data['account']->id, $attributes);
                 $result['success'] = 1;
                 $result['msg'] = lang('profile_updated');
                 $result['class'] = "alert-success";
                 $result['username'] = $this->input->post('settings_fullname', TRUE);
                 $data['profile_info'] = lang('profile_updated');
            } else {
                 $result['msg'] = 'Please clear the errors.';
                 $result['class'] = "alert-danger";
                 $result['errors'] = $errors;
            }
            $picture = $this->account_details_model->getDp();
            if (isset($picture) && strlen(trim($picture)) > 0) {
                 $remote = stristr($picture, 'http'); // do a check here to see if image is from twitter / facebook / remote URL
                 if (!$remote) {
                      $src = base_url('filebox/user/profile/' . $picture . '?t=' . time());
                 } else {
                      $src = $picture;
                 }
            } else {
                 $src = base_url("resource/images/default-person.png");
            }
            $result['dp'] = $src;
            echo json_encode($result);
       }

       public function deleteProfilePic() {
            // Retrieve sign in user
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('user_id'));
            $data['account_details'] = $this->account_details_model->get_by_user_id($this->session->userdata('user_id'));

            if (is_file('filebox/user/profile/' . $data['account_details']->picture))
                 unlink('filebox/user/profile/' . $data['account_details']->picture); // delete previous picture
            $this->account_details_model->update($data['account']->id, array('picture' => NULL));
            $picture = $this->account_details_model->getDp();
            if (isset($picture) && strlen(trim($picture)) > 0) {
                 $remote = stristr($picture, 'http'); // do a check here to see if image is from twitter / facebook / remote URL
                 if (!$remote) {
                      $src = base_url('filebox/user/profile/' . $picture);
                 } else {
                      $src = $picture;
                 }
            } else {
                 $src = base_url("resource/images/default-person.png");
            }
            $json = json_encode(array("status" => 1, "message" => "Profile picture removed", "dp" => $src));
            echo $json;
       }

       /**
        * Check if an email exist
        *
        * @access public
        * @param string
        * @return bool
        */
       function email_check($email) {
            return $this->account_model->get_by_email($email) ? TRUE : FALSE;
       }

  }

  /* End of file account_profile.php */
/* Location: ./application/account/controllers/account_profile.php */