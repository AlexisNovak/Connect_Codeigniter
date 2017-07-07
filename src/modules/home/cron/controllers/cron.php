<?php

  class Cron extends MX_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('orders/orders_model');
            $this->load->model('app/service_requests_model');
            $this->load->model('app/services_model');
            $this->load->model('mailqueue_model');
       }

       public function sendnotifications($hash = "") {
            //$hash - avoid execution by direct link
            if ($hash == '9d3bb895f22bf0afa958d68c2a58ded7') {
                 $mails = array();
                 $expired_orders = $this->orders_model->get_expired_orders();
                 if ($expired_orders) {
                      foreach ($expired_orders as $order) {
                           $couriers = $this->orders_model->get_biders($order->consignment_id);
                           foreach ($couriers as $courier) {
                                $mail = $this->load->view('templates/emailtemplate', array(
                                    'to' => $courier->email,
                                    'to_name' => $courier->name,
                                    'subject' => '6connect email notification',
                                    'message' => array(
                                        'title' => 'Order expired',
                                        'name' => $courier->name,
                                        'content' => array('Tender ' . $order->public_id . ' expired'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                           $owners = $this->orders_model->get_owner($order->consignment_id);
                           foreach ($owners as $owner) {
                                $mail = $this->load->view('templates/emailtemplate', array(
                                    'to' => $owner->email,
                                    'to_name' => $owner->name,
                                    'subject' => '6connect email notification',
                                    'message' => array(
                                        'title' => 'Order expired',
                                        'name' => $owner->name,
                                        'content' => array('Tender ' . $order->public_id . ' expired'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                      }
                 }
                 $expiring_orders = $this->orders_model->get_expiring_orders();
                 if ($expiring_orders) {
                      foreach ($expiring_orders as $order) {
                           $owners = $this->orders_model->get_owner($order->consignment_id);
                           foreach ($owners as $owner) {
                                $mail = array(
                                    'to' => $owner->email,
                                    'to_name' => $owner->name,
                                    'subject' => '6connect email notification',
                                    'message' => $this->load->view('templates/emailtemplate', array(
                                        'title' => 'Order expire soon',
                                        'name' => $owner->name,
                                        'content' => array('Tender ' . $order->public_id . ' will expire soon'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                      }
                 }

                 $expired_tenders = $this->service_requests_model->get_expired_tenders();
                 if ($expired_tenders) {
                      foreach ($expired_tenders as $stender) {
                           $couriers = $this->service_requests_model->get_biders($stender->req_id);
                           foreach ($couriers as $courier) {
                                $mail = array(
                                    'to' => $courier->email,
                                    'to_name' => $courier->name,
                                    'subject' => '6connect email notification',
                                    'message' => $this->load->view('templates/emailtemplate', array(
                                        'title' => 'Service tender expired',
                                        'name' => $courier->name,
                                        'content' => array('Tender \'' . $stender->name . '\' expired'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                           $owners = $this->service_requests_model->get_owners($stender->req_id);
                           foreach ($owners as $owner) {
                                $mail = array(
                                    'to' => $owner->email,
                                    'to_name' => $owner->name,
                                    'subject' => '6connect email notification',
                                    'message' => $this->load->view('templates/emailtemplate', array(
                                        'title' => 'Service tender expired',
                                        'name' => $owner->name,
                                        'content' => array('Tender \'' . $stender->name . '\' expired'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                      }
                 }


                 $expiring_tenders = $this->service_requests_model->get_expiring_tenders();
                 if ($expiring_tenders) {
                      foreach ($expiring_tenders as $tender) {
                           $owners = $this->service_requests_model->get_owners($tender->req_id);
                           foreach ($owners as $owner) {
                                $mail = array(
                                    'to' => $owner->email,
                                    'to_name' => $owner->name,
                                    'subject' => '6connect email notification',
                                    'message' => $this->load->view('templates/emailtemplate', array(
                                        'title' => 'Tender expire soon',
                                        'name' => $owner->name,
                                        'content' => array('Tender \'' . $tender->name . '\' will expire soon'),
                                        'link_title' => '',
                                        'link' => ''), TRUE)
                                );
                                array_push($mails, $mail);
                           }
                      }
                 }
                 if ($mails) {
                      foreach ($expired_orders as $order) {
                           $this->orders_model->updateOrder(array('inform_expire' => 2), $order->consignment_id);
                      }
                      foreach ($expiring_orders as $order) {
                           $this->orders_model->updateOrder(array('inform_expire' => 1), $order->consignment_id);
                      }
                      foreach ($expired_tenders as $tender) {
                           $this->service_requests_model->updateExpireInfo(array('inform_expire' => 2), $tender->req_id);
                      }
                      foreach ($expiring_tenders as $order) {
                           $this->service_requests_model->updateExpireInfo(array('inform_expire' => 1), $tender->req_id);
                      }
                      foreach ($mails as $m) {
                           send_mail($m['to'], $m['to_name'], $m['subject'], $m['message']);
                      }
                 }
            } else {
                 show_404();
            }
       }

       public function send_mailqueue($hash = "") {
            //$hash - avoid execution by direct link
            if ($hash == '9d3bb895f22bf0afa958d68c2a58ded7') {
                 $mails = $this->mailqueue_model->get_mailqueue();
                 foreach ($mails as $m) {
                      $message = $this->load->view('templates/emailtemplate', json_decode($m['message']), TRUE);
                      $attachment = $m['attachment'];
                      if ($attachment !== NULL) {
                           $attachment = json_decode($m['attachment']);
                      }
                      if (send_mail($m['to'], $m['name'], $m['subject'], $message, $m['cc'], $m['bcc'], $attachment)) {
                           $this->mailqueue_model->update_mailqueue(array('status' => 2), $m['id']);
                      }
                 }
            }
       }

       public function update_service_time_to_deliver($hash = "") {
            //$hash - avoid execution by direct link
            if ($hash == '9d3bb895f22bf0afa958d68c2a58ded7') {
                 $services = $this->services_model->get_services();
                 foreach ($services as $service) {
                      $orders = 0;
                      $days = 0;
                      $hours = 0;
                      $times = $this->services_model->get_deliveries($service->service_id);
                      if ($times) {
                           foreach ($times as $time) {
                                $orders++;
                                $time_to_deliver = date_diff(date_create($time->collection_time), date_create($time->delivery_time));
                                $days+=$time_to_deliver->d;
                                $hours+=$time_to_deliver->h;
                           }
                      }
                      if ($orders) {
                           $avg_hours = $hours / $orders;
                           $avg_days = $days / $orders;
                           if ($avg_hours > 24) {
                                $avg_days = $avg_days + ($avg_hours / 24);
                                $avg_hours = $avg_hours % 24;
                           }
                           $this->services_model->update_services($service->service_id, array('time_to_deliver' => number_format($avg_days, 1) . "--" . number_format($avg_hours, 1)));
                      }
                 }
            }
       }

       public function send_remainderdermail($hash = "") {
            //$hash - avoid execution by direct link
            if ($hash == '9d3bb895f22bf0afa958d68c2a58ded7') {
                 $mails = $this->mailqueue_model->get_notdelivered();
                 foreach ($mails as $m) {
                      $message = $this->load->view('templates/emailtemplate', json_decode($m['message']), TRUE);
                      $attachment = $m['attachment'];

                      if ($attachment !== NULL) {
                           $attachment = json_decode($m['attachment']);
                      }
                      if (send_mail($m['to'], $m['name'], $m['subject'], $message, $m['cc'], $m['bcc'], $attachment)) {
                           $this->mailqueue_model->update_mailqueue(array('status' => 2), $m['id']);
                      }
                 }
            }
       }

       function test() {
            $messages = array(
                'title' => '',
                'name' => '',
                'content' => "
                                         Good news Your following delivery is <b>Delivered</b> successfully.<br/><br><br>
                                         
                                        <div style='clear:both;'>
                                        <table style='border: none;'>
                                             <tr><td  style='width:200px'>Tracking ID:</td><td>public_id </td></tr>
                                             <tr><td  style='width:200px'>Item Type:</td><td>display_name X quantity </td></tr>
                                             <tr><td  style='width:200px'>From:</td><td>collection_address</td></tr>
                                             <tr><td  style='width:200px'>To:</td><td>delivery_address </td></tr>
                                             <tr><td  style='width:200px'>Service Used:</td><td>display_name </td></tr>
                                        </table>
                                        <br><br>
                                        <h3 style='color:rgb(52, 73, 94) '>We need your Feedback</h3>
                                        <br><br>
                                        Thank you for using 6connect, and we hope that the assigned courier has done a good job to ensure your item(s) were delivered on-time and safely.
                                        <br><br>
                                        To help us improve, we'd like to ask you a few questions about your recent delivery experience with us. It'll only take 1 minute, and your answers will help us make 6connect even better.
                                        <br><br>
                                        To get started...
                                        <br><br>
                                        <div style='width:80%;margin-left:10%;color:rgb(52, 73, 94) '>
                                        Based on the delivery service, how will you rate your experience?
                                        <br><br>
                                        <div style='width:100%'>
                                        <div style='float:left'>Very bad</div><div  style='float:right'>Excellent</div>
                                        </div>
                                        <div style='clear:both;'>
                                        <table style='border: none;width: 100%;padding-top: 10px;' class='ratings'>
                                             <tr>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>0</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>1</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>2</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>3</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>4</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>5</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>6</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>7</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>8</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>9</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 10%;'><a href='" . site_url() . "'><div style='padding: 5px 30%;background-color: #F0C028;'>10</div></a></td>
                                             </tr>
                                        </table>
                                        </div>
                                        </div>
                                        
                                        </div>
                                        
                                         <br></div>",
                'link_title' => '',
                'link' => '');
            $this->load->view('templates/emailtemplate', json_decode(json_encode($messages)));
//            $attachment = $m['attachment'];
//
//            if ($attachment !== NULL) {
//                 $attachment = json_decode($m['attachment']);
//            }
//            if (send_mail($m['to'], $m['name'], $m['subject'], $message, $m['cc'], $m['bcc'], $attachment)) {
//                 $this->mailqueue_model->update_mailqueue(array('status' => 2), $m['id']);
//            }
       }

  }
  