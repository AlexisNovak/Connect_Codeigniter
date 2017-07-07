<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of holidays
   *
   * @author nice
   */
  class holidays extends MY_Controller {

       //put your code here
       public function __construct() {
            parent::__construct();
            $this->load->model('holiday_model');
       }

       public function index() {
            $this->load->view('holiday_list');
       }

       function get_list() {
            $holidays = $this->holiday_model->getAll('holiday_setting');
            $result['holidays'] = $holidays;
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function delete_holiday() {
            $result = array();
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = (int) $post_data->id;
                 if ($this->holiday_model->deleteWhere(array('id' => $id), 'holiday_setting')) {
                      $result['msg'] = "Holiday Deleted Successfully";
                      $result['class'] = "alert-success";
                      $result['status'] = 1;
                 } else {
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-danger";
                      $result['status'] = 0;
                 }
            } else {
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
                 $result['status'] = 0;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function save() {
            $result = array();
            $error = FALSE;
            $errors = array();
            $post_data = json_decode(file_get_contents("php://input"));

            if (isset($post_data->name) && !empty($post_data->name)) {
                 $name = htmlentities($post_data->name);
            } else {
                 $error = true;
                 $errors['name'] = "Error in name";
            }


            if (isset($post_data->date) && !empty($post_data->date)) {
                 $date = $post_data->date;
            } else {
                 $error = true;
                 $errors['date'] = "Error in date";
            }

            if (!$error) {
//                 $user_id = $this->session->userdata('user_id');
                 $rs = $this->holiday_model->insertRow(array('name' => $name, 'date' => date("Y-m-d", strtotime($date))),'holiday_setting');
                 if ($rs) {
                      $result['msg'] = "New Holiday added successfully";
                      $result['class'] = "alert-success";
                      $result['status'] = 1;
                 } else {
                      $result['msg'] = lang('try_again');
                      $result['class'] = "alert-danger";
                      $result['status'] = 0;
                 }
            } else {
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
                 $result['status'] = 0;
                 $result['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  