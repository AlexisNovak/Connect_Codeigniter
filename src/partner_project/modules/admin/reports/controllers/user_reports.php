<?php

  class User_reports extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('user_reports_model', 'app/services_model', 'reports_model'));
       }

       private function get_export_data() {
            if ($this->input->post('user')) {
                 $user = (int) $this->input->post('user', TRUE);
            } else {
                 $user = NULL;
            }
            if ($this->input->post('username')) {
                 $username = ucfirst($this->input->post('username', TRUE));
            } else {
                 $username = "All Users";
            }
            if ($this->input->post('date')) {
                 $date = $this->input->post('date', TRUE);
                 $dates = explode('-', $date);
                 $start_date = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : "";
                 $end_date = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : "";
            } else {
                 $date = NULL;
                 $start_date = "";
                 $end_date = "";
            }
            if ($this->input->post('org_id')) {
                 $org_id = (int) $this->input->post('org_id', TRUE);
            } else {
                 $org_id = NULL;
            }
            if ($this->input->post('type')) {
                 $type = $this->input->post('type', TRUE);
            } else {
                 $type = "month";
            }
            $columns = array("", "Success Deliveries", "Failed Deliveries");

            switch ($type) {
                 case 'hourly':
                      $title = " User Performance Details of " . $username . " per Hour " . $start_date . " - " . $end_date . "";
                      $time = strtotime($start_date);
                      $lasthour = date('G', strtotime('-1 hour', strtotime($end_date)));
                      $entry = array();
                      $columns[0] = "Hour";
                      do {
                           $hour = date('G', $time);
                           $h = $hour;
                           $entry["$h"]['label'] = $hour;
                           $entry["$h"]['success'] = 0;
                           $entry["$h"]['failed'] = 0;
                           $time = strtotime('+1 hour', $time);
                      } while ($hour != $lasthour);
                      $reportinfo = $this->user_reports_model->user_performance_hour_info($user, $start_date, $end_date, $org_id);
                      break;
                 case 'day':
                      $title = " User Performance Details of " . $username . " per Day " . $start_date . " - " . $end_date . "";
                      $time = strtotime($start_date);
                      $lastday = date('d/m/Y', strtotime($end_date));
                      $entry = array();
                      $columns[0] = "Day";
                      do {
                           $day = date('d/m/Y', $time);
                           $d = $day;
                           $entry["$d"]['label'] = $day;
                           $entry["$d"]['success'] = 0;
                           $entry["$d"]['failed'] = 0;
                           $time = strtotime('+1 day', $time);
                      } while ($day != $lastday);
                      $reportinfo = $this->user_reports_model->user_performance_day_info($user, $start_date, $end_date, $org_id);
                      break;
                 case "week":
                      $title = " User Performance Details of " . $username . " per Week " . $start_date . " - " . $end_date . "";
                      $time = strtotime($start_date);
                      $lastweek = date('W', strtotime($end_date));
                      $entry = array();
                      $columns[0] = "Week";
                      do {
                           $week = date('W', $time);
                           $w = $week;
                           $entry["$w"]['label'] = $week;
                           $entry["$w"]['success'] = 0;
                           $entry["$w"]['failed'] = 0;
                           $time = strtotime('+1 week', $time);
                      } while ($week != $lastweek);
                      $reportinfo = $this->user_reports_model->user_performance_week_info($user, $start_date, $end_date, $org_id);
                      break;
                 case "month":
                      $title = " User Performance Details of " . $username . " per Month " . $start_date . " - " . $end_date . "";
                      $time = strtotime($start_date);
                      $lastmonth = date('F', strtotime($end_date));
                      $entry = array();
                      $columns[0] = "Month";
                      do {
                           $month = date('F', $time);
                           $m = $month;
                           $entry["$m"]['label'] = $month;
                           $entry["$m"]['success'] = 0;
                           $entry["$m"]['failed'] = 0;
                           $time = strtotime('+1 month', $time);
                      } while ($month != $lastmonth);
                      $reportinfo = $this->user_reports_model->user_performance_month_info($user, $start_date, $end_date, $org_id);
                      break;
            }
            foreach ($reportinfo as $row) {
                 if ($row->consignment_status_id == SUCCESS_DELIVERY_STATUS) {
                      $entry[$row->date_label]['success'] = $success[$row->date_label] = $row->deliveries;
                 } elseif ($row->consignment_status_id == FAILED_DELIVERY_STATUS) {
                      $entry[$row->date_label]['failed'] = $failed[$row->date_label] = $row->deliveries;
                 }
            }

            $performance = array(
                "title" => $title,
                "columns" => $columns,
                "entry" => $entry
            );
            return $performance;
       }

       public function get_user_performance_pdf() {
            $performance = $this->get_export_data();
            $filename = "user_performance_details.pdf";
            export_as_pdf($filename, $performance);
       }

       public function get_user_performance_excel() {
            $performance = $this->get_export_data();
            $filename = "user_performance_details";
            export_as_excel($filename, $performance);
       }

       public function getuserperformancedata() {
            $result = array();
            $postdata = json_decode(file_get_contents("php://input"));
            if (isset($postdata->user) && !empty($postdata->user)) {
                 $userob = $postdata->user;
                 $user = (int) $userob->Userid;
            } else {
                 $user = NULL;
            }
            if (isset($postdata->date) && !empty($postdata->date)) {
                 $date = htmlentities($postdata->date);
                 $dates = explode('-', $date);
                 $start_date = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : "";
                 $end_date = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : "";
            } else {
                 $date = NULL;
                 $start_date = "";
                 $end_date = "";
            }
            if (isset($postdata->org_id) && !empty($postdata->org_id)) {
                 $org_id = (int) htmlentities($postdata->org_id);
            } else {
                 $org_id = NULL;
            }
            if (isset($postdata->type) && !empty($postdata->type)) {
                 $type = htmlentities($postdata->type);
            } else {
                 $type = "month";
            }
            if (isset($postdata->updateall) && $postdata->updateall == FALSE) {
                 $update_all = FALSE;
            } else {
                 $update_all = TRUE;
            }
            $label = array();
            $success = array();
            $failed = array();

            switch ($type) {
                 case 'hourly':
                      $time = strtotime($start_date);
                      $lasthour = date('G', strtotime('-1 hour', strtotime($end_date)));
                      $label = array();
                      $success = array();
                      $failed = array();
                      do {
                           $hour = date('G', $time);
                           $label[] = $h = $hour;
                           $success["$h"] = 0;
                           $failed["$h"] = 0;
                           $time = strtotime('+1 hour', $time);
                      } while ($hour != $lasthour);
                      $reportinfo = $this->user_reports_model->user_performance_hour_info($user, $start_date, $end_date, $org_id);
                      break;
                 case 'day':
                      $time = strtotime($start_date);
                      $lastday = date('d/m/Y', strtotime($end_date));
                      $label = array();
                      $success = array();
                      $failed = array();
                      do {
                           $day = date('d/m/Y', $time);
                           $label[] = $d = $day;
                           $success["$d"] = 0;
                           $failed["$d"] = 0;
                           $time = strtotime('+1 day', $time);
                      } while ($day != $lastday);
                      $reportinfo = $this->user_reports_model->user_performance_day_info($user, $start_date, $end_date, $org_id);
                      break;
                 case "week":
                      $time = strtotime($start_date);
                      $lastweek = date('W', strtotime($end_date));
                      $label = array();
                      $success = array();
                      $failed = array();
                      do {
                           $week = date('W', $time);
                           $label[] = $w = $week;
                           $success["$w"] = 0;
                           $failed["$w"] = 0;
                           $time = strtotime('+1 week', $time);
                      } while ($week != $lastweek);
                      $reportinfo = $this->user_reports_model->user_performance_week_info($user, $start_date, $end_date, $org_id);
                      break;
                 case "month":
                      $time = strtotime($start_date);
                      $lastmonth = date('F', strtotime($end_date));
                      do {
                           $month = date('F', $time);
                           $label[] = $m = $month;
                           $success["$m"] = 0;
                           $failed["$m"] = 0;
                           $time = strtotime('+1 month', $time);
                      } while ($month != $lastmonth);
                      $reportinfo = $this->user_reports_model->user_performance_month_info($user, $start_date, $end_date, $org_id);
                      break;
            }
            foreach ($reportinfo as $row) {
                 if ($row->consignment_status_id == SUCCESS_DELIVERY_STATUS) {
                      $success[$row->date_label] = $row->deliveries;
                 } elseif ($row->consignment_status_id == FAILED_DELIVERY_STATUS) {
                      $failed[$row->date_label] = $row->deliveries;
                 }
            }

            $result['performance'] = array(
                "label" => $label,
                "success" => array_values($success),
                "failed" => array_values($failed)
            );

            if ($update_all) {

                 $services = $this->services_model->get_all_service_org_id($org_id);
                 $total_deliveries = (int) $this->user_reports_model->get_total_deliveries($user, $start_date, $end_date, $org_id);
                 $todays_deliveries = (int) $this->reports_model->get_todays_deliveries($org_id);
                 $failed_deliveries = (int) $this->reports_model->get_failed_deliveries($org_id);
                 $todays_spendings = (float) $this->reports_model->get_todays_spendings($org_id);
                 if ($start_date == "" or $end_date == "") {
                      $days = 1;
                 } else {
                      $start_ts = strtotime($start_date);
                      $end_ts = strtotime($end_date);
                      $diff = $end_ts - $start_ts;
                      $days = (int) round($diff / 86400);
                 }

                 $avg_deliveries = round($total_deliveries / $days);
                 $active_day_row = $this->user_reports_model->get_active_day($user, $start_date, $end_date, $org_id);
                 if ($active_day_row) {
                      $active_date = $active_day_row->date_label;
                      $active_day = Date('l', strtotime($active_date));
                 } else {
                      $active_date = "";
                      $active_day = "";
                 }
                 $service_breakdown = array();
                 $service_info = array();
                 $service_total = 0;
                 foreach ($services as $service) {
                      $deliveries = $this->user_reports_model->user_performance_service($service->service_id, $user, $start_date, $end_date, $org_id);

                      $d_count = isset($deliveries->deliveries) ? $deliveries->deliveries : 0;
                      $hash = md5($service->service_id . $service->service_name);
                      $R = hexdec(substr($hash, 0, 2));
                      $G = hexdec(substr($hash, 2, 2));
                      $B = hexdec(substr($hash, 4, 2));
                      $service_breakdown[] = array(
                          "name" => $service->service_name,
                          "y" => $d_count,
                          "color" => "rgba($R,$G,$B,.8)"
                      );
                      $service_info[] = array(
                          "label" => $service->service_name,
                          "value" => $d_count
                      );
                      $service_total+=$d_count;
                 }
                 $result['servicebreakdown'] = $service_breakdown;
                 foreach ($service_info as $key => $s) {
                      if ($service_total) {
                           $service_info[$key]['percentage'] = round(($s['value'] / $service_total) * 100);
                      } else {
                           $service_info[$key]['percentage'] = "0";
                      }
                 }

                 $result['general'] = array(
                     "total_delivery" => $total_deliveries,
                     "today" => array(
                         "total" => $todays_deliveries,
                         "failed" => $failed_deliveries,
                         "spendings" => $todays_spendings,
                     ),
                     "average" => $avg_deliveries,
                     "active_day" => $active_date,
                     "active_week" => $active_day,
                     "services" => $service_info
                 );
                 /*
                  * for day trend graph
                  * 
                  */

                 $label = array();
                 $daytrendlist = array();
                 foreach ($services as $service) {
                      $listname = array();
                      $time = strtotime($start_date);
                      $lasthour = date('G', strtotime('-1 hour', strtotime($end_date)));
                      do {
                           $hour = date('G', $time);
                           $listname["$hour"] = 0;
                           $time = strtotime('+1 hour', $time);
                      } while ($hour != $lasthour);
                      $daytrendinfobyservice = $this->user_reports_model->user_performance_day_trend_per_service($service->service_id, $user, $start_date, $end_date, $org_id);


                      foreach ($daytrendinfobyservice as $row) {
                           $listname[$row->date_label] = $row->deliveries;
                      }
                      $dataset = array();
                      $dataset['name'] = $service->service_name;
                      $hash = md5($service->service_id . $service->service_name);
                      $R = hexdec(substr($hash, 0, 2));
                      $G = hexdec(substr($hash, 2, 2));
                      $B = hexdec(substr($hash, 4, 2));
                      $dataset['color'] = "rgba($R,$G,$B,1)";
//                      $dataset['strokeColor'] = "rgba($R,$G,$B,0.8)";
//                      $dataset['highlightFill'] = "rgba($R,$G,$B,0.75)";
//                      $dataset['highlightStroke'] = "rgba($R,$G,$B,1)";

                      $dataset['data'] = array(
                          $listname['0'] + $listname['1'] + $listname['2'] + $listname['3'] + $listname['4'] + $listname['5'] + $listname['6'] + $listname['7'] + $listname['8'] + $listname['9'],
                          $listname['10'] + $listname['11'] + $listname['12'],
                          $listname['13'] + $listname['14'] + $listname['15'],
                          $listname['16'] + $listname['17'] + $listname['18'],
                          $listname['19'] + $listname['20'] + $listname['21'] + $listname['22'] + $listname['23']
                      );
                      $daytrendlist[] = $dataset;
                 }

                 $dtlabels = array("before 10am", "10am - noon", "noon - 3pm", "3pm - 6pm", "after 6pm");

                 $result['daytrend'] = array(
                     "label" => $dtlabels,
                     "datasets" => $daytrendlist
                 );

                 /*
                  * end for day trend
                  */

                 /*
                  * data for week trend
                  * 
                  */
                 $label = array();
                 $weektrendlist = array();
                 foreach ($services as $service) {
                      $listname = array("0" => 0, "1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0);

                      $weektrendinfobyservice = $this->user_reports_model->user_performance_week_trend_per_service($service->service_id, $user, $start_date, $end_date, $org_id);


                      foreach ($weektrendinfobyservice as $row) {
                           $listname[$row->date_label] = $row->deliveries;
                      }
                      $dataset = array();
                      $dataset['name'] = $service->service_name;
                      $hash = md5($service->service_id . $service->service_name);
                      $R = hexdec(substr($hash, 0, 2));
                      $G = hexdec(substr($hash, 2, 2));
                      $B = hexdec(substr($hash, 4, 2));
                      $dataset['color'] = "rgb($R,$G,$B)";
//                      $dataset['strokeColor'] = "rgba($R,$G,$B,0.8)";
//                      $dataset['highlightFill'] = "rgba($R,$G,$B,0.75)";
//                      $dataset['highlightStroke'] = "rgba($R,$G,$B,1)";

                      $dataset['data'] = array_values($listname);
                      $weektrendlist[] = $dataset;
                 }
                 $result['weektrend'] = array(
                     "label" => array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"),
                     "datasets" => $weektrendlist
                 );

                 /*
                  * 
                  * end of week trend
                  */
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  