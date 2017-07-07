<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  require(APPPATH . '/libraries/REST_Controller.php');

  class Order extends REST_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array(
                'app/organisation_model',
                'orders/orders_model',
                'orders/consignment_activity_log_model'
            ));
            $this->load->config('api/codes');
       }

       public function create_post($key = NULL) {
             $key = $this->post('access_key');
            if ($key) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'No access key found'), 200);
            }
            if (!$this->organisation_model->is_allow_api($key)) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Not allowed to push delivery request'), 200);
            }
            // $org_id = $this->organisation_model->get_id_by_accesskey($key);
            $custom = FALSE;


            $type = $this->post('type');
            if ($type) {
                 if ($type == CUSTOM_ITEM) {
                      $custom = true;
                 }
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Invalid consignment type'), 200);
            }
            $is_bulk = $this->post('is_bulk');
            if ($is_bulk) {
                 $is_bulk = true;
            } else {
                 $is_bulk = false;
            }
            if ($is_bulk || $custom) {
                 $height = $this->post('height');
                 if ($height) {
                      $height = (float) $height;
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Height must be given'), 200);
                 }
                 $breadth = $this->post('breadth');
                 if ($breadth) {
                      $breadth = (float) $breadth;
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Breadth must be given'), 200);
                 }
                 $length = $this->post('length');
                 if ($length) {
                      $length = (float) $length;
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Length must be given'), 200);
                 }
                 $volume = $this->post('volume');
                 if ($volume) {
                      $volume = (float) str_replace(',', "", $volume);
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Volume must be given'), 200);
                 }
                 $weight = $this->post('weight');
                 if ($weight) {
                      $weight = (float) $weight;
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Weight must be given'), 200);
                 }
            } else {
                 $height = 0;
                 $breadth = 0;
                 $length = 0;
                 $volume = 0;
                 $weight = 0;
            }
            $quantity = $this->post('quantity');
            if ($quantity) {
                 $quantity = (int) $quantity;
                 if ($quantity <= 0) {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Quantity must be a positive value'), 200);
                 }
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Quantity must be a given'), 200);
            }
            $collect_address = array();
            $collect_from_l1 = $this->post('collect_from_l1');
            if ($collect_from_l1) {
                 $collect_address[] = htmlentities(trim(str_replace(",", " ", $collect_from_l1)));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection address must be given'), 200);
            }
            $collect_from_l2 = $this->post('collect_from_l2');
            if ($collect_from_l2) {
                 $collect_address[] = htmlentities(trim(str_replace(",", " ", $collect_from_l2)));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection address must be given'), 200);
            }
            $collection_zipcode = $this->post('collection_zipcode');
            if ($collection_zipcode) {
                 if (is_numeric($collection_zipcode)) {
                      $collection_zipcode = htmlentities(trim($collection_zipcode));
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection postal code must be integer'), 200);

                      $errors['collection_zipcode'] = "Postal code must be integer";
                 }
            }
            $collect_country = $this->post('collect_country');
            if ($collect_country) {
                 $collect_country = htmlentities(trim($collect_country));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection country must be given'), 200);
            }
            $collect_contactname = $this->post('collect_contactname');
            if ($collect_contactname) {
                 $collect_contactname = htmlentities(trim($collect_contactname));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection contact name must be given'), 200);
            }
            $collect_email = $this->post('collect_email');
            if ($collect_email) {
                 $collect_email = htmlentities(trim($collect_email));
            } else {
                 $collect_email = '';
            }
            $collect_phone = $this->post('collect_phone');
            if ($collect_phone) {
                 $collect_phone = htmlentities(trim($collect_phone));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection phone number must be given'), 200);
            }
            $collect_date = $this->post('collect_date');
            if ($collect_date) {
                 $dates = explode('-', $collect_date);
                 $collect_date_from = date("Y-m-d H:i:s", strtotime($dates[0]));
                 $collect_date_to = date("Y-m-d H:i:s", strtotime(isset($dates[1]) ? $dates[1] : ''));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection date must be given'), 200);
            }


            $delivery_address = array();
            $delivery_from_l1 = $this->post('delivery_address_l1');
            if ($delivery_from_l1) {
                 $delivery_address[] = htmlentities(trim(str_replace(",", " ", $delivery_from_l1)));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery address must be given'), 200);
            }
            $delivery_from_l2 = $this->post('delivery_address_l2');
            if ($delivery_from_l2) {
                 $delivery_address[] = htmlentities(trim(str_replace(",", " ", $delivery_from_l2)));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery address must be given'), 200);
            }
            $delivery_zipcode = $this->post('delivery_zipcode');
            if ($delivery_zipcode) {
                 if (is_numeric($delivery_zipcode)) {
                      $delivery_zipcode = htmlentities(trim($delivery_zipcode));
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery postal code must be integer'), 200);

                      $errors['delivery_zipcode'] = "Postal code must be integer";
                 }
            }
            $delivery_country = $this->post('delivery_country');
            if ($delivery_country) {
                 $delivery_country = htmlentities(trim($delivery_country));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery country must be given'), 200);
            }
            $delivery_contactname = $this->post('delivery_contactname');
            if ($delivery_contactname) {
                 $delivery_contactname = htmlentities(trim($delivery_contactname));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery contact name must be given'), 200);
            }
            $delivery_email = $this->post('delivery_email');
            if ($delivery_email) {
                 $delivery_email = htmlentities(trim($delivery_email));
            } else {
                 $delivery_email = '';
            }
            $delivery_phone = $this->post('delivery_phone');
            if ($delivery_phone) {
                 $delivery_phone = htmlentities(trim($delivery_phone));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery phone number must be given'), 200);
            }
            $delivery_date = $this->post('deliver_date');
            if ($delivery_date) {
                 $dates = explode('-', $delivery_date);
                 $delivery_date_from = date("Y-m-d H:i:s", strtotime($dates[0]));
                 $delivery_date_to = date("Y-m-d H:i:s", strtotime(isset($dates[1]) ? $dates[1] : ''));
            } else {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery date must be given'), 200);
            }
            $delivery_is_notify = $this->post('delivery_is_notify');
            if ($delivery_is_notify) {
                 $delivery_is_notify = 1;
            } else {
                 $delivery_is_notify = 0;
            }
            $delivery_is_assign = $this->post('delivery_is_assign');
            if ($delivery_is_assign) {
                 $delivery_is_assign = 0;
                 $assigned_service = 0;
                 $is_for_bidding = 1;
                 $open_bid = $this->post('open_bid');
                 if ($open_bid) {
                      $open_bid = 1;
                 } else {
                      $open_bid = 0;
                 }
                 $deadline = $this->post('deadline');
                 if ($deadline) {
                      $deadline_date = date("Y-m-d H:i:s", strtotime($deadline));
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Tender deadline must be given'), 200);
                 }
            } else {
                 $delivery_is_assign = 1;
                 $is_for_bidding = 0;
                 $open_bid = 0;
                 $deadline_date = NULL;
                 $assigned_service = $this->post('assigned_service');
                 if ($assigned_service) {
                      $assigned_service = (int) $assigned_service;
                 } else {
                      $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Service must be given'), 200);
                 }
            }
            $remarks = $this->post('remarks');
            if ($remarks) {
                 $remarks = htmlentities(trim($remarks));
            } else {
                 $remarks = '';
            }
            $collect_timezone = $this->post('collect_timezone');
            if (!$collect_timezone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Collection timezone must be given'), 200);
            }
            $delivery_timezone = $this->post('delivery_timezone');
            if (!$delivery_timezone) {
                 $this->response(array('code' => $this->config->item('invalid_input'), 'message' => 'Delivery timezone must be given'), 200);
            }
            $org_id = $this->organisation_model->get_id_by_accesskey($key);

            $draft = $this->post('draft');
            if ($draft) {
                 $status = C_DRAFT;
            } else {
                 if ($delivery_is_assign) {
                      $status = C_PENDING_ACCEPTANCE;
                 } else {
                      $status = C_GETTING_BID;
                 }
            }
            $description = '';
            $user_id = $this->session->userdata('partner_user_id');
            $this->load->helper('string');
            do {
                 $public_id = random_string('numeric', 14);
            } while (!$this->orders_model->is_unique_publicid($public_id));

            $private_id = "";
            $c_group_id = uniqid();
            $order_data = array(
                'private_id' => $private_id,
                'public_id' => $public_id,
                "org_id" => $org_id,
                'is_read' => 0,
                'c_group_id' => $c_group_id,
                'consignment_type_id' => $type,
                'description' => $description,
                'price' => 0,
                'customer_id' => $user_id,
                'service_id' => $assigned_service,
                'is_service_assigned' => $delivery_is_assign,
                "is_for_bidding" => $is_for_bidding,
                'is_open_bid' => $open_bid,
                'bidding_deadline' => $deadline_date,
                'quantity' => $quantity,
                'is_bulk' => $is_bulk,
                'length' => $length,
                'breadth' => $breadth,
                'height' => $height,
                'volume' => $volume,
                'weight' => $weight,
                'collection_address' => json_encode($collect_address),
                'collection_date' => $collect_date_from,
                'collection_date_to' => $collect_date_to,
                'collection_country' => $collect_country,
                'collection_timezone' => $collect_timezone,
                'delivery_address' => json_encode($delivery_address),
                'delivery_post_code' => $delivery_zipcode,
                'delivery_country' => $delivery_country,
                'delivery_timezone' => $delivery_timezone,
                'delivery_date' => $delivery_date_from,
                'delivery_date_to' => $delivery_date_to,
                'delivery_contact_name' => $delivery_contactname,
                'delivery_contact_email' => $delivery_email,
                'delivery_contact_phone' => $delivery_phone,
                'created_user_id' => $user_id,
                'collection_post_code' => $collection_zipcode,
                'collection_contact_name' => $collect_contactname,
                'collection_contact_number' => $collect_phone,
                'collection_contact_email' => $collect_email,
                'send_notification_to_consignee' => $delivery_is_notify,
                'consignment_status_id' => $status,
                'remarks' => $remarks
            );
            $insert_id = $this->orders_model->addOrder($order_data);
            if ($insert_id) {
               $this->consignment_activity_log_model->add_activity(array("order_id" => $insert_id, "activity" => "New order added to the system"));
                             $this->generate_barcode(CONSIGNMENT_PREFIX . $insert_id);
                 $this->generate_barcode($public_id);
                 $this->generate_ciqrcode($insert_id);
                 if ($status == C_PENDING_ACCEPTANCE) {
                      $this->send_mail_for_courier($insert_id, N_DIRECT_ASSIGN);
                 } else if ($status == C_GETTING_BID) {
                      $this->send_mail_for_courier($insert_id, N_CLOSED_BID, 1);
                 }
                 $this->response(array("code" => $this->config->item('success'), "tracking_id" => $public_id), 200);
            }
       }

       public function send_mail_for_courier($order_id = 0, $type = 0, $settings = 0) {
            /*
             * send email notification to courier
             */
            $this->load->model('orders/orders_model');
            $this->load->model('account/notification_model');
            if ($type == N_COMMENT_RESPONSE) {
                 $couriers = $this->orders_model->get_biders($order_id);
            } else if ($type == N_CLOSED_BID) {
                 $couriers = $this->orders_model->get_closed_biders($order_id);
            } else {
                 $couriers = $this->orders_model->get_courier($order_id);
            }
            foreach ($couriers as $courier) {
                 $public_id = $this->orders_model->get_public_id($order_id);
                 $where = array('account_id' => $courier->courier_id, 'type' => 1, 'notification_id' => $type);
                 if ($this->notification_model->get_notification($where) || $settings == 1) {
                      switch ($type) {
                           case N_DIRECT_ASSIGN:
                                $title = sprintf(lang('direct_assign_email_title'), $public_id);
                                $content = lang('direct_assign_email_content');
                                $link_title = lang('direct_assign_email_link_title');
                                $link = site_url('couriers/dashboard#/assigned_orders/view_order/' . $public_id);
                                break;
                           case N_COMMENT_RESPONSE:
                                $title = lang('comment_response_email_title');
                                $content = lang('comment_response_email_content');
                                $link_title = lang('comment_response_email_link_title');
                                $link = "";
                                break;
                           case N_BID_WON:
                                $title = lang('bid_won_email_title');
                                $content = lang('bid_won_email_content');
                                $link_title = lang('bid_won_email_link_title');
                                $link = site_url();
                                break;
                           case N_CANCEL_ORDER:
                                $title = lang('cancel_order_email_title');
                                $content = lang('cancel_order_email_content');
                                $link_title = lang('cancel_order_email_link_title');
                                $link = site_url();
                                break;
                           case N_CLOSED_BID:$title = lang('closed_bid_email_title');
                                $content = lang('closed_bid_email_content');
                                $link_title = lang('closed_bid_email_link_title');
                                $link = site_url();
                                break;
                           default :$title = '';
                                $content = '';
                                $link_title = '';
                                $link = '';
                      }
                      $to = $courier->email;
                      $to_name = $courier->name;
                      $subject = '6connect email notification';
                      $message = array(
                          'title' => $title,
                          'name' => $to_name,
                          'content' => $content,
                          'link_title' => $link_title,
                          'link' => $link);
                      save_mail($to, $to_name, $subject, $message,2);
                 }
            }
       }

       /*
        * 
        * 
        */

       function generate_barcode($id) {
            $uploadPath = "../filebox/barcode";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777,TRUE);
                 }
                 header('Content-Type: image/jpg');
            $this->load->library("barcode39", TRUE);
            $bc = new Barcode39($id);
            $bc->draw("./filebox/barcode/consignment_document_" . $id . ".png");
            return;
       }

       function generate_ciqrcode($file_name = NULL) {
            if (!empty($file_name)) {
                 $this->load->library('ciqrcode');
                 $params['level'] = 'H';
                 $params['size'] = 4;

                 $uploadPath = "../filebox/ciqrcode";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777,TRUE);
                 }

                 $file_path = "./filebox/ciqrcode/" . $file_name . '.png';
                 if (!file_exists($file_path)) {
                      $params['savename'] = $file_path;
                      $params['data'] = $file_name;
                      $this->ciqrcode->generate($params);
                 }

                 return base_url() . $file_path;
            } else {
                 return '';
            }
       }

  }
  