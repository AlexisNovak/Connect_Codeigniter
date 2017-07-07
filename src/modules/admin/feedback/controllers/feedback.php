<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of payment
   *
   * @author nice
   */
  class feedback extends MY_Controller {

       //put your code here
       public function __construct() {
            parent::__construct();
            $this->load->model(
                    array(
                        'account/account_model',
                        'feedback_model',
                        'app/organisation_model',
                        'credit/payment_accounts_model',
                        'account/account_details_model',
                    )
            );
       }

       public function index() {
            $this->load->view('feedback_list');
            $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
            if ($account->root != 1) {
                 if ($this->input->is_ajax_request()) {
                      exit;
                 } else {
                      redirect('');
                 }
            }
       }

       function get_feedback() {
            $orgData = json_decode(file_get_contents('php://input'));
            $id = ($orgData->id) ? $orgData->id : NULL;
            $data = $this->feedback_model->get_single_feedback($id);
            echo json_encode(array('feedback' => $data));
       }

       function view($id) {
            $data['feedback']=  $this->feedback_model->get_single_feedback($id);
            $this->load->view('feedback_view',$data);
       }

       public function feedback_json() {
            $perpage = '';
            $orgData = json_decode(file_get_contents('php://input'));

            if ($orgData != NULL && isset($orgData->perpage_value)) {

                 $perpage = $orgData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($orgData->filter)) {
                 if ($orgData->filter != NULL) {
                      $filter['search'] = $orgData->filter;
                 } else {
                      $filter['search'] = NULL;
                 }
            }

            if (isset($orgData->currentPage)) {

                 $page = $orgData->currentPage;
            } else {
                 $page = 1;
            }


            $total_result = $this->feedback_model->get_feedback_count($filter);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $organisation_detail = $this->feedback_model->get_feedback($perpage, $filter, $start);
            $result['end'] = (int) ($start + count($organisation_detail));

            $result['feedbacks'] = $organisation_detail;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

  }
  