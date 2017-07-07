<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of mainadmin
   *
   * @author nice
   */
  class mainadmin extends MY_Controller{
       //put your code here
       public function __construct() {
            parent::__construct();
             $this->load->model(array(
                'couriers/couriers_model',
                'courier_service_model',
                'couriers/surcharge_items_model',
                'organisation_model'
            ));
       }
       
       function services(){            
            $data = array();
            $this->load->view('allservices_list', $data);
       }
       function serviceslist_json(){            
            $perpage = '';
            $search = '';
            $category = NULL;
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
            if (isset($servicesData->category)) {
                 if ($servicesData->category != NULL) {
                      $catg = $servicesData->category;
                      switch ($catg) {
                           case "0":$category = NULL;
                                break;
                           case "1":$category = 1;
                                break;
                           case "2":$category = 2;
                                break;
                           case "3":$category = 3;
                                break;
                      }
                 }
            }
            $total_result = $this->courier_service_model->getallserviceslist_count($search, $category);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            $services = $this->courier_service_model->getallserviceslist_by_courier($perpage, $search, $start, $category);
            foreach ($services as $service) {
                 $service->type = ucfirst($service->type);
                 $sdays = array();
                 if ($service->week_0 == 1) {
                      $sdays[] = $days[0];
                 }
                 if ($service->week_1 == 1) {
                      $sdays[] = $days[1];
                 }
                 if ($service->week_2 == 1) {
                      $sdays[] = $days[2];
                 }
                 if ($service->week_3 == 1) {
                      $sdays[] = $days[3];
                 }
                 if ($service->week_4 == 1) {
                      $sdays[] = $days[4];
                 }
                 if ($service->week_5 == 1) {
                      $sdays[] = $days[5];
                 }
                 if ($service->week_6 == 1) {
                      $sdays[] = $days[6];
                 }
                 $service->days = implode(', ', $sdays);
                 $service->cutoff = $service->start_time . ' - ' . $service->end_time;
                 unset($service->week_0);
                 unset($service->week_1);
                 unset($service->week_2);
                 unset($service->week_3);
                 unset($service->week_4);
                 unset($service->week_5);
                 unset($service->week_6);
                 unset($service->start_time);
                 unset($service->end_time);
                 $origin = $this->courier_service_model->get_service_origin($service->id);
                 if ($origin) {
                      $service->origin = $origin->country;
                 }
                 $d_list = explode(',', $service->destination);
                 $destinations = $this->courier_service_model->get_service_destination($d_list);
                 if ($destinations) {
                      $destination = array();
                      foreach ($destinations as $value) {
                           $destination[] = $value->country;
                      }
                      $service->destination = implode(', ', $destination);
                 } else {
                      $service->destination = 'Anywhere';
                 }
                 if (strlen($service->description) > 150) {
                      $length = strpos($service->description, ' ', 150);
                      $service->description = substr($service->description, 0, $length) . '...';
                 }
            }
            $result['service_detail'] = $services;
            $result['end'] = (int) ($start + count($result['service_detail']));
            echo json_encode($result);
       }
  }
  