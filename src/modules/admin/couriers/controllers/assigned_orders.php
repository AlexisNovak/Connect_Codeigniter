<?php

  class Assigned_orders extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'couriers_model',
                'orders/orders_model',
                'orders/consignment_messages_model',
                'orders/consignment_activity_log_model',
                'orders/consignment_pod_model',
                'job_acknowledgement_model',
                'consignment_bids_model',
                'jobstates_model',
                'account/account_details_model'
            ));
            if (!$this->is_approved()) {
                 redirect('couriers/not_verified');
            }
       }

       public function index() {
            $data = array();
            $this->load->view('assigned', $data);
       }

       private function reciept($public_id = 0) {
            $order_id = $this->orders_model->get_order_id($public_id);
            if ($order_id == 0) {
                 return FALSE;
            } else {
                 $order = $this->orders_model->getDetails(array(
                     'consignment_id' => $order_id
                 ));
                 $customer = $this->account_details_model->get_by_user_id($order->created_user_id);
                 $org = $this->orders_model->getOneWhere(array('id' => $order->org_id), 'organizations');
                 $item_type = $this->orders_model->getOneWhere(array('consignment_type_id' => $order->consignment_type_id), 'consignment_type');
                 return $this->load->view('reciept/reciept', array('order' => $order, 'customer' => $customer, 'organization' => $org, 'item_type' => $item_type), true);
            }
       }

       public function assigned_services() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $result ['services'] = $this->couriers_model->get_services_assigned($courier_id);
            echo json_encode($result);
            exit();
       }

       public function allstatusList() {
            $result = array();
            $result['status'] = $this->couriers_model->get_all_statuslist(array(C_DRAFT));
            echo json_encode($result);
            exit();
       }

       public function get_assignedorders_json() {
            $perpage = '';
            $search = NULL;
            $service = "";
            $status = "";
            $ordersData = json_decode(file_get_contents('php://input'));
            if (isset($ordersData->perpage_value)) {

                 $perpage = $ordersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($ordersData->currentPage)) {

                 $page = $ordersData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($ordersData->organisation)) {

                 $org = (int) $ordersData->organisation;
            } else {
                 $org = NULL;
            }
            if (isset($ordersData->filter)) {
                 if ($ordersData->filter != NULL) {
                      $search = $ordersData->filter;
                 }
            }
            if (isset($ordersData->service)) {
                 if ($ordersData->service != NULL) {
                      $service = $ordersData->service;
                 }
            }
            if (isset($ordersData->status)) {
                 if ($ordersData->status != NULL) {
                      $status = $ordersData->status;
                 }
            }
            $courier_id = $this->session->userdata('courier_id');
            $total_result = $this->orders_model->getorderslist_count_for_courier($courier_id, $search, $org, $service, $status);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result ['total'] = $total_result;
            $result ['start'] = $start + 1;
            $result ['page'] = $page;
            $order_detail = $this->orders_model->getorderslist_for_courier($courier_id, $perpage, $search, $start, $org, $service, $status);
            foreach ($order_detail as $value) {
                 $value->collection_address = implode(' ', json_decode($value->collection_address));
                 $value->delivery_address = implode(' ', json_decode($value->delivery_address));
            }
            $result ['order_detail'] = $order_detail;
            $result ['end'] = (int) ($start + count($result ['order_detail']));
            echo json_encode($result);
            exit();
       }

       public function view_order($order_id = 0) {
            $courier_id = $this->session->userdata('courier_id');
            $order_id = $this->orders_model->get_order_id($order_id);
            $data = array();
            $order = $this->orders_model->getDetails(array(
                'consignment_id' => $order_id
            ));
            if ($order) {
                 $order->collection_address = implode(' ', json_decode($order->collection_address));
                 $order->delivery_address = implode(' ', json_decode($order->delivery_address));
            }
            $data ['order'] = $order;
            if ($pods = $this->consignment_pod_model->get_pods($order_id)) {
                 $data ['pods'] = array();
                 foreach ($pods as $pod) {
                      if ($pod->is_signature) {
                           $data ['signature'] = $pod;
                      } else {
                           $data ['pods'] [] = $pod;
                      }
                 }
            }
            $data ['messages'] = $this->consignment_messages_model->listjobmessages($order_id, $courier_id);
            $this->load->view('oview', $data);
       }

       public function add_comment() {
            $courier_id = $this->session->userdata('courier_id');
            $result = array();
            $biddersData = json_decode(file_get_contents('php://input'));
            if (isset($biddersData->order_id)) {

                 $order_id = $biddersData->order_id;
            } else {
                 exit();
            }
            if (isset($biddersData->comment)) {

                 $comment = $biddersData->comment;
            } else {
                 $comment = "";
            }
            $data = array(
                'courier_id' => $courier_id,
                'job_id' => $order_id,
                'question' => $comment,
                'reply' => NULL
            );
            $send_mail = FALSE;
            if ($this->consignment_messages_model->addmessage($data)) {
                 $send_mail = TRUE;
                 $result ['status'] = 1;
                 $result ['last'] = '<div class="question">' . '<div class="q_head">' . '<div class="q_title">Ask by you</div>' . '<div class="q_time">' . date('Y-m-d h:i A', now()) . '</div>' . '</div>' . '<div class="q_text">' . '<p>' . $comment . '</p>' . '<div class="q_response">' . '<div class="q_text">' . '<p><strong>Customer not yet responded to your question.</strong></p>' . '</div>' . '</div>' . '</div>' . '</div>';
            }

            echo json_encode($result);
            if ($send_mail) {
                 $this->send_mail_for_member($order_id, N_COMMENT_FROM_COURIER);
            }
            exit();
       }

       public function statusList() {
            $result = array();
            $result ['status'] = $this->couriers_model->get_statuslist();
            echo json_encode($result);
            exit();
       }

       public function accept() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $remarks = '';

            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->order_id) && !empty($post_data->order_id)) {
                 $job_id = $post_data->order_id;
            } else {
                 $error = true;
                 $errors ['order_id'] = "Order id missing";
            }
            if (isset($post_data->private_id) && !empty($post_data->private_id)) {
                 $consignment_id = $post_data->private_id;
            } else {
                 $consignment_id = random_string('nozero', 8);
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks))
                 $remarks = $post_data->remarks;

            $wonbid = $this->consignment_bids_model->getwonbid($job_id);
            if (!$wonbid) {
                 if (isset($post_data->price) && !empty($post_data->price)) {
                      $price = $post_data->price;
                      if (!is_numeric($price)) {
                           $error = true;
                           $errors ['price'] = "Price must be an integer";
                      }
                 } else {
                      $error = true;
                      $errors ['price'] = "Price missing";
                 }
            }
            if ($remarks) {
                 $extra = "<br><br>Courier left the following remarks:<br><em>" . $remarks . '</em>';
            } else {
                 $extra = "";
            }
            if (!$error) {
                 if ($wonbid) {
                      $ack = array(
                          'bid_id' => $wonbid->bid_id,
                          'job_id' => $job_id,
                          'consignment_id' => $consignment_id,
                          'price' => $wonbid->bidding_price,
                          'remarks' => $wonbid->remarks,
                          'is_approved' => 0,
                          'courier_id' => $courier_id
                      );
                      $this->job_acknowledgement_model->acknowledge($ack);
                      $confirm = 1;
                      $status = C_PENDING;

                      $this->send_mail_for_member($job_id, N_ACCEPT, 1, $extra);
                 } else {
                      $confirm = 1;
                      $status = C_PENDING;

                      $threshold = $this->orders_model->get_threshold($job_id);
                      if ($price > $threshold && $threshold != -1) {
                           $this->orders_model->updateOrder(array(
                               'change_price' => $price
                                   ), $job_id);
                           if (!empty($remarks)) {
                                $this->consignment_messages_model->addmessage(array(
                                    'courier_id' => $courier_id,
                                    'job_id' => $job_id,
                                    'question' => $remarks,
                                    'reply' => NULL
                                ));
                           }
                           $confirm = 0;
                           $threshold_break = true;
                           $status = C_PRICE_APPROVAL;
                           $this->send_mail_for_member($job_id, N_THRESHOLD, 1, $extra);
                      } else {
                           $this->orders_model->updateOrder(array(
                               'price' => $price
                                   ), $job_id);
                           $this->send_mail_for_member($job_id, N_ACCEPT, 1, $extra);
                      }
                 }
                 $data = array(
                     'private_id' => $consignment_id,
                     'consignment_status_id' => $status,
                     'is_confirmed' => $confirm
                 );

                 if ($this->orders_model->updateOrder($data, $job_id)) {
                      if ($status == C_PENDING) {
                           $message = "Order status updated to - " . C_PENDING_NAME;
                      } else {
                           $message = "Order status updated to - " . C_PRICE_APPROVAL_NAME;
                      }
                      $accept_log = "Courier accepted the order";
                      if (!empty($remarks)) {
                           $accept_log.=" with a remark - " . $remarks;
                      }
                      $this->consignment_activity_log_model->add_activity(array(
                          "order_id" => $job_id,
                          "activity" => $accept_log
                      ));
                      $this->consignment_activity_log_model->add_activity(array(
                          "order_id" => $job_id,
                          "activity" => $message
                      ));
                      header('Content-Type: image/jpg');
                      $this->load->library("barcode39", TRUE);
                      $bc = new Barcode39($consignment_id);
                      $bc->draw("./filebox/barcode/consignment_document_" . $consignment_id . ".png");
                      $result ['status'] = 1;
                      $result ['class'] = 'alert-success';
                      $result ['msg'] = "Order accepted";
                      if (isset($threshold_break)) {
                           $result ['class'] = 'alert-warning';
                           $result ['message'] = "delivery will be pending customer\'s approval on the price to proceed";
                      }
                 }
            } else {
                 $result ['status'] = 0;
                 $result ['class'] = 'alert-danger';
                 $result ['msg'] = lang('clear_error');
                 $result ['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       function reject_order() {
            $result = array();
            $orderdata = json_decode(file_get_contents('php://input'));
            if (isset($orderdata->order_id) && !empty($orderdata->order_id)) {
                 $consignment_id = (int) $orderdata->order_id;
            } else {
                 $consignment_id = 0;
            }
            $order = $this->orders_model->getDetails(array('consignment_id' => $consignment_id));
            $data = array(
                'consignment_status_id' => C_REJECT,
                'private_id' => '',
                'service_id' => 0,
                'price' => 0,
                'is_confirmed' => 0,
                'change_price' => NULL
            );
            if ($this->orders_model->updateOrder($data, $consignment_id)) {
                 $courier_id = $this->session->userdata('courier_id');
                 $courier = $this->couriers_model->get_by_id($courier_id);
                 $name = ($courier->fullname) ? $courier->fullname : $courier->email;
                 $reason = (isset($orderdata->reason)) ? $orderdata->reason : '';
                 $this->consignment_activity_log_model->add_activity(array(
                     "order_id" => $consignment_id,
                     "activity" => "Order was rejected by " . $name . " . Reason is " . $reason
                 ));
                 $this->orders_model->cancel_bids($consignment_id);
                 $result['status'] = 1;
                 $result['msg'] = "Order rejected successfully";
                 $result['class'] = "alert-success";
                 $order = $this->orders_model->getjobdetail($consignment_id);
                 $owner = $this->orders_model->get_owner($consignment_id);
                 if (is_array($owner))
                      $owner = $owner[0];

                 $to = $owner->email;
                 if (!empty($to)) {
                      $to_name = $owner->name;
                      $public_id = $order->public_id;
                      $order_url = site_url('system/admin_home#/orders/view_order/' . $public_id);
                      $subject = "Order cancelled by courier";
                      $content = "The courier was unable to do the delivery due to " . '"' . $reason . '"';
                      $message = array(
                          'title' => 'Order rejected by courier',
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => lang('view_order_email_title'),
                          'link' => $order_url);
                      save_mail($to, $to_name, $subject, $message, 1);
                 }
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function update_status() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');

            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->order_id) && !empty($post_data->order_id)) {
                 $job_id = $post_data->order_id;
            } else {
                 $error = true;
                 $errors ['order_id'] = "Order id missing";
            }
            if (isset($post_data->status) && !empty($post_data->status)) {
                 $status = json_decode($post_data->status);
                 $status_code = $status->id;
                 $status_name = $status->display_name;
                 if (!in_array($status_code, array(
                             '101',
                             '102',
                             '301',
                             '311',
                             '321',
                             '401',
                             '402',
                             '501',
                             '601',
                             '2',
                             '4',
                             '8',
                             '5'
                         ))) {
                      $error = true;
                      $errors ['status'] = "Invalid status";
                 }
            } else {
                 $error = true;
                 $errors ['status'] = "Status missing";
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks)) {
                 $description = $post_data->remarks;
            } else {
                 if (isset($status_code) && $status_code == C_OTHERS) {
                      $error = true;
                      $errors ['remarks'] = "Give details if status is 'Others'";
                 } else {
                      $description = "";
                 }
            }
            $job = $this->orders_model->get_job_permission($job_id);
            $o_courier = $job ? $job->courier_id : - 1;
            if ($o_courier != $courier_id) {
                 $error = true;
                 $message = "Failed to update status. No permission";
            }
            if (!$error) {
                 $data = array(
                     'job_id' => $job_id,
                     'status_code' => $status_code,
                     'status_name' => $status_name,
                     'status_description' => $description,
                     'user_type' => 2,
                     'changed_user_id' => $courier_id
                 );
                 if ($this->jobstates_model->addjobtrack($data)) {

                      if ($status_code == 5) {
                           if (isset($job_id) && is_numeric($job_id)) {
                                $order = $this->orders_model->getjobdetail($job_id);
                                $collection_address = implode(' ', json_decode($order->collection_address));
                                $delivery_address = implode(' ', json_decode($order->delivery_address));
                                $created_user = $this->couriers_model->get_member_by_id($order->created_user_id);
                                $courier_service = $this->couriers_model->get_courier_service_ById($order->service_id);
                                if (!empty($created_user)) {
                                     $subject = "Congratulations! Your Order is Delivered";
                                     $message = array(
                                         'title' => '',
                                         'name' => ($created_user->username != "") ? $created_user->username : $created_user->email,
                                         'content' => "
                                         Good news Your following delivery is <b>Delivered</b> successfully.<br/><br><br>
                                         
                                        <div style='clear:both;'>
                                        <table style='border: none;'>
                                             <tr><td  style='width:200px'>Tracking ID:</td><td>" . $order->public_id . "</td></tr>
                                             <tr><td  style='width:200px'>Item Type:</td><td>" . $order->display_name . 'X' . $order->quantity . "</td></tr>
                                             <tr><td  style='width:200px'>From:</td><td>" . $collection_address . "</td></tr>
                                             <tr><td  style='width:200px'>To:</td><td>" . $delivery_address . "</td></tr>
                                             <tr><td  style='width:200px'>Service Used:</td><td>" . $courier_service->display_name . "</td></tr>
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
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=0") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>0</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=1") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>1</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=2") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>2</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=3") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>3</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=4") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>4</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=5") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>5</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=6") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>6</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=7") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>7</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=8") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>8</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 9%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=9") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>9</div></a></td>
                                                  <td style='text-align: center;padding: 5px;width: 10%;'><a style='text-decoration : none;' href='" . site_url("rating?email=$created_user->email&public_id=$order->public_id&rate=10") . "'><div style='padding: 5px 30%;background-color: #F0C028;'>10</div></a></td>
                                             </tr>
                                        </table>
                                        </div>
                                        </div>
                                        
                                        </div>
                                        
                                         <br></div>",
                                         'link_title' => '',
                                         'link' => '');
                                     save_mail($created_user->email, $created_user->username, $subject, $message, 2);

                                     if ($order->payment_type == 1 || $order->payment_type == 2) {
                                          $rsubject = "Your 6Connect E-Receipt for your Delivery is ready";
                                          $rmessage = array(
                                              'title' => '',
                                              'name' => ($created_user->username != "") ? $created_user->username : $created_user->email,
                                              'content' => $this->reciept($order->public_id),
                                              'link_title' => '',
                                              'link' => '');
                                          save_mail($created_user->email, $created_user->username, $rsubject, $rmessage, 2);
                                     }
                                }
                           }


                           $delivery_count = $this->orders_model->countOrdersDelivered(array(
                               'consignment_status_id' => $status_code,
                               'changed_user_id' => $courier_id
                                   ), 'consignments');
                           if ($delivery_count == 1) {
                                $subject = "Congratulations on making your 1st delivery!";
                                $courier = $this->couriers_model->get_by_id($courier_id);
                                $message = array(
                                    'title' => '',
                                    'name' => ($courier->fullname != "") ? $courier->fullname : $courier->email,
                                    'content' => "
                                         Congratulations on making your 1st delivery.<br/><br><br>
                                         <img src='" . base_url('resource/images/mail-more.jpg') . "' style='width:70%;'>
                                              <div style='clear:both;'>
                                         You are now eligible for the lucky draw & 10% rebate on your 1st 100 deliveries.*<br><br>
                                         And there's more! Get a chance to win a free dining at Mandarin Orchard Singapore every month. Every month, we are giving away dining vouchers worth over $1,000 to 6 lucky users. <br>
                                         <br>
                                         Start deliver and you will be qualified. The more you deliver, the higher your chance of winning!<br>
                                         <br>
                                         So start now before it ends!<br></div>",
                                    'link_title' => '',
                                    'link' => '');
                                save_mail($courier->email, $courier->fullname, $subject, $message, 2);
                           }
                      }

                      $this->orders_model->updateOrder(array(
                          'consignment_status_id' => $status_code
                              ), $job_id);

                      $update_log = "Order status updated to '$status_name'";
                      if (!empty($description)) {
                           $update_log.=" with a remarks, '$description'";
                      }
                      $this->consignment_activity_log_model->add_activity(array(
                          "order_id" => $job_id,
                          "activity" => $update_log
                      ));
                      $this->send_mail_for_member($job_id, N_ORDER_STATUS_UPDATE);
                      $result ['status'] = 1;
                      $result ['class'] = 'alert-success';
                      $result ['msg'] = "Status updated";
                 } else {
                      $result ['status'] = 0;
                      $result ['class'] = 'alert-warning';
                      $result ['msg'] = lang('try_again');
                 }
            } else {
                 $result ['status'] = 0;
                 $result ['class'] = 'alert-danger';
                 $result ['msg'] = lang('clear_error');
                 if (isset($message)) {
                      $result ['msg'] = $message;
                 }
                 $result ['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function change_price() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');

            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->order_id) && !empty($post_data->order_id)) {
                 $job_id = $post_data->order_id;
            } else {
                 $error = true;
                 $errors ['order_id'] = "Order id missing";
            }
            if (isset($post_data->price) && !empty($post_data->price)) {
                 if (is_numeric($post_data->price)) {
                      $price = $post_data->price;
                 } else {
                      $error = true;
                      $errors ['price'] = "Price must be numeric";
                 }
            } else {
                 $error = true;
                 $errors ['price'] = "Price missing";
            }
            if (isset($post_data->remarks) && !empty($post_data->remarks)) {
                 $remarks = $post_data->remarks;
            } else {
                 $remarks = "";
            }
            if ($remarks) {
                 $extra = "<br><br>Courier left the following remarks:<br><em>" . $remarks . '</em>';
            } else {
                 $extra = "";
            }
            if (!$error) {
                 $threshold = $this->orders_model->get_threshold($job_id);
                 $cprice = $this->orders_model->get_price($job_id);
                 if ($price > $threshold && $price > $cprice) {
                      $data = array(
                          'change_price' => $price,
                          'consignment_status_id' => C_PRICE_APPROVAL,
                          'is_confirmed' => 0
                      );
                      if ($this->orders_model->updateOrder($data, $job_id)) {
                           $this->consignment_activity_log_model->add_activity(array(
                               "order_id" => $job_id,
                               "activity" => "Price change requested by the courier. New price $" . number_format($price, 2)
                           ));

                           $this->consignment_messages_model->addmessage(array(
                               'courier_id' => $courier_id,
                               'job_id' => $job_id,
                               'question' => $remarks,
                               'reply' => NULL
                           ));
                           $result ['status'] = 1;
                           $result ['class'] = 'alert-success';
                           $result ['msg'] = "Price change requested successfully";

                           $courier = $this->couriers_model->get_by_id($courier_id);
                           $order = $this->orders_model->getjobdetail($job_id);
                           $owner = $this->orders_model->get_owner($job_id);
                           if (is_array($owner))
                                $owner = $owner[0];

                           $to = $owner->email;
                           if (!empty($to)) {
                                $to_name = $owner->name;
                                $public_id = $order->public_id;
                                $order_url = site_url('system/admin_home#/orders/view_order/' . $public_id);
                                $cc = $order->delivery_contact_email;
                                if (strcmp($cc, $to) == 0)
                                     $cc = '';

                                $subject = sprintf(lang('order_price_changed_email_subject'), $public_id);
                                $content = sprintf(lang('order_price_changed_email'), $public_id, $cprice, $price, $courier->company_name);
                                $message = array(
                                    'title' => '',
                                    'name' => $to_name,
                                    'content' => $content . $extra,
                                    'link_title' => lang('view_order_email_title'),
                                    'link' => $order_url);
                                save_mail($to, $to_name, $subject, $message, 1, $cc);
                           }
                      } else {
                           $result ['status'] = 0;
                           $result ['class'] = 'alert-warning';
                           $result ['msg'] = lang('try_again');
                      }
                 } else {
                      $data = array(
                          'price' => $price,
                          'change_price' => NULL,
                          'consignment_status_id' => C_PENDING,
                          'is_confirmed' => 1
                      );
                      if ($this->orders_model->updateOrder($data, $job_id)) {
                           $this->consignment_activity_log_model->add_activity(array(
                               "order_id" => $job_id,
                               "activity" => "Price changed by the courier. New price $" . number_format($price, 2)
                           ));
                           $result ['status'] = 1;
                           $result ['class'] = 'alert-success';
                           $result ['msg'] = "Price updated successfully";
                      } else {
                           $result ['status'] = 0;
                           $result ['class'] = 'alert-warning';
                           $result ['msg'] = lang('try_again');
                      }
                 }
            } else {
                 $result ['status'] = 0;
                 $result ['class'] = 'alert-danger';
                 $result ['msg'] = lang('clear_error');
                 if (isset($message)) {
                      $result ['msg'] = $message;
                 }
                 $result ['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function upload() {
            $data = array();
            $order = time();
            if (isset($_FILES ['file'])) {
                 $error = false;
                 $files = "";
                 $uploaddir = "filebox/pod/";
                 $uploadPath = "filebox/pod";
                 $uploadoriginalPath = "filebox/pod/original";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777, TRUE);
                 }
                 if (!file_exists($uploadoriginalPath)) {
                      mkdir($uploadoriginalPath, 0777, TRUE);
                 }
                 $size = getimagesize($_FILES ['file']['tmp_name']);
                 $ext = end(explode(".", basename($_FILES ['file'] ['name'])));
                 $config ['image_library'] = 'gd2';
                 $config ['source_image'] = $_FILES ['file'] ['tmp_name'];
                 $config ['create_thumb'] = TRUE;
                 $config ['thumb_marker'] = "";
                 $config ['maintain_ratio'] = TRUE;
                 $config ['new_image'] = $uploadoriginalPath . '/' . $order . "." . $ext;
                 $config ['width'] = $size[0];
                 $config ['height'] = $size[1];

                 $this->load->library('image_lib');

                 $this->image_lib->clear();
                 $this->image_lib->initialize($config);
                 if ($this->image_lib->resize()) {
                      $files = $order . "." . $ext;
                 }

                 $config ['new_image'] = $uploaddir . $order . "." . $ext;
                 $config ['width'] = 200;
                 $config ['height'] = 200;


                 $this->image_lib->clear();
                 $this->image_lib->initialize($config);
                 if ($this->image_lib->resize()) {
                      $files = $order . "." . $ext;
                 } else {
                      $errors = $this->image_lib->display_errors();
                      $error = true;
                 }
                 $data = ($error) ? array(
                     'error' => $errors
                         ) : array(
                     'files' => base_url($uploaddir . $files)
                 );
            } else {
                 $data = array(
                     'error' => 'File not uploaded'
                 );
            }

            echo json_encode($data);
            exit();
       }

       public function remove_photo() {
            if ($this->input->post('image')) {
                 $image_path = $this->input->post('image', TRUE);
                 $image = end(explode('/', $image_path));
                 if (file_exists("filebox/pod/$image")) {
                      unlink("filebox/pod/$image");
                 }
                 if (file_exists("filebox/pod/original/$image")) {
                      unlink("filebox/pod/original/$image");
                 }
            }
            return;
       }

       public function add_new_pod() {
            $result = array();
            $error = false;
            $errors = array();
            $courier_id = $this->session->userdata('courier_id');
            $podData = json_decode(file_get_contents('php://input'));
            if (isset($podData->order_id) && !empty($podData->order_id)) {
                 $order_id = $podData->order_id;
                 $consignment_id = $this->orders_model->get_order_id($order_id);
                 $job = $this->orders_model->get_job_permission($consignment_id);
                 $o_courier = $job ? $job->courier_id : - 1;
                 if ($o_courier != $courier_id) {
                      $error = TRUE;
                      $message = "Failed to update POD. Please acknowledge the request first.";
                 }
            } else {
                 $error = TRUE;
                 $errors ['order_id'] = "invalid";
            }

            if (isset($podData->pod_image) && !empty($podData->pod_image)) {
                 $pod_image = $podData->pod_image;
            } else {
                 $error = TRUE;
                 $errors ['pod_image'] = "Invalid input - No image";
            }
            if (isset($podData->signature) && !empty($podData->signature)) {
                 $is_signature = 1;
            } else {
                 $is_signature = 0;
            }
            if (isset($podData->remarks) && !empty($podData->remarks)) {
                 $remarks = $podData->remarks;
            } else {
                 $remarks = NULL;
            }
            if (!$error) {
                 $data = array();
                 $data ['pod_image_url'] = $pod_image;
                 $data ['is_signature'] = $is_signature;
                 $data ['courier_id'] = $courier_id;
                 if ($is_signature) {
                      $this->consignment_pod_model->add_signature($consignment_id, $data);
                 } else {
                      if ($this->consignment_pod_model->get_count_by_consignment_id($consignment_id) < 3) {
                           $this->consignment_pod_model->add_pod($consignment_id, $data);
                      } else {
                           $message = "No more images can add";
                      }
                 }
                 $result ['status'] = 1;
                 $result ['class'] = 'alert-success';
                 $result ['msg'] = "POD updated";
                 if (isset($message)) {
                      $result ['class'] = 'alert-warning';
                      $result ['msg'] = $message;
                 }
            } else {
                 $result ['status'] = 0;
                 $result ['class'] = 'alert-danger';
                 $result ['msg'] = lang('clear_error');
                 if (isset($message)) {
                      $result ['msg'] = $message;
                 }
                 $result ['errors'] = $errors;
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function get_pods() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $podData = json_decode(file_get_contents('php://input'));
            if (isset($podData->order_id) && !empty($podData->order_id)) {
                 $order_id = $podData->order_id;
                 $consignment_id = $this->orders_model->get_order_id($order_id);
                 $job = $this->orders_model->get_job_permission($consignment_id);
                 $o_courier = $job ? $job->courier_id : - 1;
                 if ($o_courier == $courier_id) {
                      if ($pods = $this->consignment_pod_model->get_pods($consignment_id)) {
                           $result ['pods'] = array();
                           foreach ($pods as $pod) {
                                if ($pod->is_signature) {
                                     $result ['signature'] = $pod;
                                } else {
                                     $result ['pods'] [] = $pod;
                                }
                           }
                      }
                 }
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function loglist_json() {
            $perpage = '';
            $search = '';
            $loglistData = json_decode(file_get_contents('php://input'));
            if (isset($loglistData->perpage_value)) {

                 $perpage = $loglistData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($loglistData->order_id)) {

                 $order_id = $loglistData->order_id;
            } else {
                 $order_id = 0;
            }
            if (isset($loglistData->currentPage)) {

                 $page = $loglistData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($loglistData->filter)) {
                 if ($loglistData->filter != NULL) {
                      $search = $loglistData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $order_id = $this->orders_model->get_order_id($order_id);

            $user_id = $this->session->userdata('user_id');
            $total_result = $this->consignment_activity_log_model->getloglist_count_for_courier($order_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result ['total'] = $total_result;
            $result ['start'] = $start + 1;
            $result ['page'] = $page;
            $loglist = array();
            $loglist = $this->consignment_activity_log_model->getloglist_by_orderid_for_courier($order_id, $perpage, $search, $start);

            $result ['loglist'] = $loglist;
            $result ['current_user_id'] = $user_id;
            $result ['end'] = (int) ($start + count($result ['loglist']));
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

       public function allow_cancel() {
            $result = array();
            $orderdata = json_decode(file_get_contents('php://input'));
            if (isset($orderdata->order_id) && !empty($orderdata->order_id)) {
                 $consignment_id = (int) $orderdata->order_id;
            } else {
                 $consignment_id = 0;
            }
            $order = $this->orders_model->getDetails(array('consignment_id' => $consignment_id));

            $data = array(
                'consignment_status_id' => C_CANCELLED,
                'private_id' => '',
                'service_id' => 0,
                'price' => 0,
                'is_confirmed' => 0,
                'change_price' => NULL,
                'cancel_request' => 2,
                'requested_on' => date('Y-m-d H:i:s')
            );
            if ($this->orders_model->updateOrder($data, $consignment_id)) {
                 $this->consignment_activity_log_model->add_activity(array(
                     "order_id" => $consignment_id,
                     "activity" => "Cancel request accepted "
                 ));
                 $owners = $this->orders_model->get_owner($consignment_id);
                 if ($owners) {
                      $owner = $owners[0];
                      $content = lang('order_cancel_accept_email_content');
                      $link_title = lang('order_cancel_accept_email_link_title');
                      $link = site_url('system/admin_home#/orders' . (!empty($order->public_id) ? '/view_order/' . $order->public_id : ''));
                      $to = $owner->email;
                      $to_name = $owner->name;
                      $subject = lang('6connect_email_notification');
                      $message = array(
                          'title' => lang('order_cancel_accept_email_title'),
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => $link_title,
                          'link' => $link
                      );
                      save_mail($to, $to_name, $subject, $message, 1);
                 }
                 $result['status'] = 1;
                 $result['msg'] = "Order cancel request accepted successfully";
                 $result['class'] = "alert-success";
            } else {
                 $result['status'] = 0;
                 $result['msg'] = lang('try_again');
                 $result['class'] = "alert-danger";
            }

            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }
  