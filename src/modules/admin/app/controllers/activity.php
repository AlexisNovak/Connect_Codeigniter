<?php

  class Activity extends MY_Controller {

       function __construct() {
            parent::__construct();

            $this->load->model(array('app/log_model'));
       }

       function index() {
            //$this->load->view('app/activity_list');
       }

       function activitylist_json($org_id) {
            $perpage = '';
            $search = '';
            $activityData = json_decode(file_get_contents('php://input'));
            if ($activityData != NULL && isset($activityData->perpage_value)) {

                 $perpage = $activityData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($activityData->currentPage)) {

                 $page = $activityData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($activityData->filter)) {
                 if ($activityData->filter != NULL) {
                      $search = $activityData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->log_model->getactivitylist_by_orgid_count($org_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
           $result['start'] = $start + 1;
             $activities = $this->log_model->getactivitylist_by_orgid($org_id, $perpage, $search, $start);
             $result['end'] = (int) ($start + count($activities));
            $result['activities'] = $activities;

            echo json_encode($result,JSON_NUMERIC_CHECK);
       }

       function get_detail() {
            $postdata = file_get_contents("php://input");
            $activity_data = json_decode($postdata);
            $activity_detail['activity'] = $this->log_model->get_detail_by_id($activity_data->activity_id);
            echo json_encode($activity_detail);
       }

  }
  