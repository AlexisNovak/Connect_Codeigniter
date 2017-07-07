<?php

  class Public_tracking extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('app/organisation_model');
            $this->load->model('app/members_model');

            // Load the necessary stuff...
       }

       function index($org_id = 0) {
        $data['org_id'] = $org_id;
            $data['is_admin'] = $this->members_model->is_admin($org_id);
            $this->load->view('tracking', $data);
       }

       //get tracking status of an organisation
       public function get_tracking_status($org_id) {
            $status = $this->organisation_model->get_tracking_status($org_id);
            $result['status'] = $status;
             $org = $this->organisation_model->getorganisationDetails($org_id);
       if ($status) {
                 $url = site_url('track') . '/' . $org_id;
                 $url = str_replace('partner_project/', '', $url);
                 $result['tracking_info'] = array(
                'info' => isset($org->tracking_intro) ? $org->tracking_intro : NULL,
                'logo' => isset($org->tracking_logo) ? $org->tracking_logo : NULL
            );
            } else {
                 $url = "";
            }
            $result['tracking'] = $url;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       //enable or disable tracking 
       public function enable_tracking() {
            $result = array();
            $postdata = file_get_contents("php://input");
            $org_data = json_decode($postdata);
            if (isset($org_data->org_id) && !empty($org_data->org_id)) {
                 $org_id = (int) $org_data->org_id;
            } else {
                 $org_id = 0;
            }
            if (isset($org_data->enable_tracking) && !empty($org_data->enable_tracking)) {
                 $enable_tracking = 1;
                 $url = site_url('track') . '/' . $org_id;
                 $url = str_replace('partner_project/', '', $url);
            } else {
                 $enable_tracking = 0;
                 $url = "";
            }
            $attributes = array(
                "public_tracking" => $enable_tracking
            );
            if ($this->organisation_model->is_admin($org_id)) {

                 if ($this->organisation_model->edit_organisation($attributes, $org_id)) {
                      $result['status'] = 1;
                      $result['tracking'] = $url;
                      if ($enable_tracking) {
                           $result['msg'] = lang('enable_tracking_suc');
                      } else {
                           $result['msg'] = lang('disable_tracking_suc');
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
 public function add_tracking_info() {
        $result = array();
        $postdata = file_get_contents("php://input");
        $org_data = json_decode($postdata);
        $error = false;
        if (isset($org_data->org_id) && !empty($org_data->org_id)) {
            $org_id = (int) $org_data->org_id;
        } else {
            $error = TRUE;
        }
        if (isset($org_data->info) && !empty($org_data->info)) {
            $info = $org_data->info;
        } else {
            $info = NULL;
        }
        $uploaddir = "../filebox/organisation/tracking/";

        $logo = NULL;
        if (isset($org_data->logo) && !empty($org_data->logo)) {
            $upload = $org_data->logo;
            if ($upload != NULL && is_file($uploaddir . $upload)) {
                $logo = $org_data->logo;
            }
        }
        if (!$error) {
            $data = array(
                'tracking_logo' => $logo,
                'tracking_intro' => $info
            );
            if ($this->organisation_model->edit_organisation($data, $org_id)) {
                $result['status'] = 1;
                $result['class'] = "alert-success";
                $result['msg'] = "Tracking page info updated";
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
  