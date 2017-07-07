<?php

  class Associates extends CourierController {

       public function __construct() {
            parent::__construct();
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $this->load->view('org_list');
       }

       public function associate_org_detail_list() {
            $result = array();
            $perpage = '';
            $search = '';
            $orgData = json_decode(file_get_contents('php://input'));
            if (isset($orgData->perpage_value)) {

                 $perpage = $orgData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($orgData->currentPage)) {

                 $page = $orgData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($orgData->filter)) {
                 if ($orgData->filter != NULL) {
                      $search = $orgData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $courier_id = $this->session->userdata("courier_id");
            $total_result = $this->couriers_model->myorganisations_count($courier_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $orgs = $this->couriers_model->myorganisations($courier_id, $perpage, $search, $start);
            foreach ($orgs as $org) {
                 $org->preservices = array();
                 $services = $this->couriers_model->get_approved_services($courier_id, $org->org_id);
                 foreach ($services as $service) {
                      $org->preservices[] = $service->service_name;
                 }
            }
            $result['organisations']=$orgs;
            $result['end'] = (int) ($start + count($result['organisations']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  