<?php

class Orders extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'orders_model',
            'consignment_types_model',
            'consignment_messages_model',
            'consignment_activity_log_model',
            'consignment_attachment_model',
            'app/organisation_model',
            'account/ref_zoneinfo_model',
            'consignment_pod_model',
            'account/account_model',
            'tags_model',
            'app/pre_approved_bidders_model',
            'couriers/courier_service_model',
            'couriers_external_model',
            'couriers/surcharge_items_model',
            'account/account_details_model',
            'credit/payment_accounts_model'
        ));
    }

    public function index() {
        $user = $this->session->userdata("user_id");
        $data['organisations'] = $this->organisation_model->myorganisations($user);
        $count = count($data['organisations']);
        $data['org_count'] = $count;
        $this->load->view('orders_list');
    }

    public function newOrder() {
        $user = $this->session->userdata("user_id");
        $data['organisations'] = $this->organisation_model->myorganisations($user);
        $count = count($data['organisations']);
        $data['org_count'] = $count;
        $this->load->view('new_order', $data);
    }

    public function get_third_partys() {
        $result = array();
        $user = $this->session->userdata("user_id");
        $postdata = json_decode(file_get_contents('php://input'));
        if (isset($postdata->search) && !empty($postdata->search)) {
            $search = htmlentities($postdata->search);
        } else {
            $search = NULL;
        }
        $couriers = $this->couriers_external_model->get_couriers($user, $search);
        if ($couriers) {
            $result['couriers'] = $couriers;
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    function uploadattachments() {
        $data = array();
        $uploadPath = "./filebox/attachments";
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, TRUE);
        }
        $uploaddir = "filebox/attachments/";

        $config['allowed_types'] = '*';
        $config['max_size'] = 0;
        $config['upload_path'] = './' . $uploaddir;
        if (isset($_FILES['attachment'])) {
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('attachment')) {
                $data = array('status' => FALSE, 'error' => "Failed to upload");
            } else {
                $file = $this->upload->file_name;
                $order_id = $this->input->post('order_id');
                $attachment = array(
                    'order_id' => $order_id,
                    'path' => $uploaddir . $file,
                    'name' => $file
                );

                $this->orders_model->insertRow($attachment, 'consignment_attachments');
                $data = array('status' => true, 'error' => '', 'data' => $uploaddir . $file);
            }
        } else {
            $data = array('status' => FALSE, 'error' => 'There was an error uploading your files1');
        }
        echo json_encode($data);
        exit();
    }

    public function downloadAttachment($id) {
        $attach = $this->orders_model->getOneWhere(array('id' => $id), 'consignment_attachments');
        if (file_exists($attach->path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $attach->name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($attach->path));
            readfile($attach->path);
            exit;
        }
    }

    function deleteAttach() {
        $id = $this->input->post('id');
        $this->orders_model->deleteRow($id, 'id', 'consignment_attachments');
        echo json_encode(array('success' => true));
    }

    public function printOrder($order_id) {
        $data = array(
            'orders' => array()
        );
        $order = $this->orders_model->getDetails_print(array(
            'consignment_id' => $order_id
                ));
        if ($order->collection_address)
            $order->caddr = implode(' ', json_decode($order->collection_address));
        if ($order->delivery_address)
            $order->daddr = implode(' ', json_decode($order->delivery_address));
        if ($order->remarks) {
            if (strlen($order->remarks) > 120) {
                $order->remarks = substr($order->remarks, 0, 120);
            }
        }
        $data['orders'][] = $order;
        $html = $this->load->view('print_mode', $data, true);
        // echo $html;
        // exit();
        // this the the PDF filename that user will get to download
        $pdfFilePath = "order_details_" . $order->public_id . ".pdf";
        // load mPDF library
        $this->load->library('m_pdf');
        // actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        $pdf->SetDisplayMode('fullpage');
        // generate the PDF!
        $pdf->WriteHTML($html);
        // offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "D");
    }

    public function print_multiple_order($group_id = 0) {
        $data = array(
            'orders' => array()
        );
        $orders = $this->orders_model->getmorderslist($group_id);
        foreach ($orders as $sorder) {
            $order = $this->orders_model->getDetails_print(array(
                'consignment_id' => $sorder->consignment_id
                    ));
            if ($order->collection_address)
                $order->caddr = implode(' ', json_decode($order->collection_address));
            if ($order->delivery_address)
                $order->daddr = implode(' ', json_decode($order->delivery_address));
            if ($order->remarks) {
                if (strlen($order->remarks) > 120) {
                    $order->remarks = substr($order->remarks, 0, 120);
                }
            }
            $data['orders'][] = $order;
        }
        $html = $this->load->view('print_mode', $data, true);

        // echo $html;
        // exit();
        // this the the PDF filename that user will get to download
        $pdfFilePath = "order_details_" . $group_id . ".pdf";
        // load mPDF library
        $this->load->library('m_pdf');
        // actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        $pdf->SetDisplayMode('fullpage');
        // generate the PDF!
        $pdf->WriteHTML($html);
        // offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "D");
    }

    public function get_order_json($order_id = NULL) {
        $result = array();
        $user = $this->session->userdata("user_id");
        $preference = $this->orders_model->get_template_prference($user);
        if ($order_id === NULL) {
            $c_country = $this->orders_model->getCountry();
            $d_zone = $this->orders_model->getZone(date_default_timezone_get());
            if ($c_country->country != NULL) {
                $zones = $this->ref_zoneinfo_model->get_by_country($c_country->country);
                $c_zone = $zones[0] ? $zones[0] : $d_zone;
            } else {
                $c_zone = $d_zone;
            }
            $result['order'] = array(
                "is_bulk" => FALSE,
                "quantity" => 1,
                'template_type' => $preference,
                "delivery_is_notify" => TRUE,
                "delivery_is_assign" => 1,
                "collect_timezone" => $c_zone,
                "delivery_timezone" => $d_zone,
                "collect_country" => $c_country->country ? $c_country->country : 'sg',
                "delivery_country" => 'sg'
            );
        } else {
            $order_id = $this->orders_model->get_order_id($order_id);
            $where = "C.consignment_id = $order_id AND (C.consignment_status_id =" . C_DRAFT . " OR C.consignment_status_id =" . C_CANCELLED . ") AND C.is_deleted =  0";
            // $where['customer_id'] = $user;
            $order = $this->orders_model->getDetails($where);
            if ($order) {
                $c_address = json_decode($order->collection_address);
                $d_address = json_decode($order->delivery_address);
                $type = $this->consignment_types_model->getType($order->consignment_type_id);
                $service = $this->orders_model->getService($order_id);
                if ($service) {
                    $service->surcharge = $this->surcharge_items_model->get_items($service->service_id);
                    $svalue = str_split($service->payments);
                    $service->payment_terms = ($svalue[0] ? '<span class="credit-label">' . lang('credit_terms_direct') . '</span>' : '')
                            . ($svalue[1] ? '<span class="credit-label">' . lang('credit_terms') . '</span>' : '') .
                            ($svalue[3] ? '<span class="cash-label">' . lang('cash_sender') . '</span>' : '') .
                            ($svalue[2] ? '<span class="cash-label">' . lang('cash_recipient') . '</span>' : '');


                    $s_options = array();

                    if ($svalue[3])
                        $s_options[] = array('id' => 0, 'value' => "___1", 'name' => lang('cash_sender'));
                    if ($svalue[2])
                        $s_options[] = array('id' => 0, 'value' => "__1_", 'name' => lang('cash_recipient'));
                    if ($svalue[1]) {
                        $org_id = $order->org_id;
                        if (!$org_id) {
                            $approved_accounts = $this->payment_accounts_model->get_approved_accounts($user, 1);
                        } else {
                            $approved_accounts = $this->payment_accounts_model->get_approved_accounts($org_id, 2);
                        }
                        foreach ($approved_accounts as $acc) {
                            $s_options[] = array('id' => $acc->id, 'value' => "_1__", 'name' => $acc->account_name, 'credit' => $acc->credit);
                        }
                    }
                    if ($svalue[0])
                        $s_options[] = array('id' => 0, 'value' => "1___", 'name' => lang('credit_terms_direct'));

                    $service->payments = $s_options;
                    $service->limit = 2;
                }
                $c_zone = $this->orders_model->getZone($order->collection_timezone);
                $d_zone = $this->orders_model->getZone($order->delivery_timezone);
                $c_date1 = date("m/d/Y h:m A", strtotime($order->collection_date));
                $c_date2 = date("m/d/Y h:m A", strtotime($order->collection_date_to));
                $d_date1 = date("m/d/Y h:m A", strtotime($order->delivery_date));
                $d_date2 = date("m/d/Y h:m A", strtotime($order->delivery_date_to));
                $result['order'] = array(
                    "consignment_id" => $order->consignment_id,
                    "public_id" => $order->public_id,
                    "org_id" => $order->org_id,
                    'template_type' => $preference,
                    "is_bulk" => $order->is_bulk ? TRUE : FALSE,
                    "delivery_is_notify" => $order->send_notification_to_consignee ? TRUE : FALSE,
                    "delivery_is_assign" => $order->is_third_party ? 3 : ($order->is_service_assigned ? 1 : 2),
                    "third_party_email" => $order->third_party_email,
                    "deadline" => $order->bidding_deadline ? date("m/d/Y h:m A", strtotime($order->bidding_deadline)) : "",
                    "open_bid" => $order->is_open_bid ? true : FALSE,
                    'threshold' => $order->threshold_price,
                    "type" => $type,
                    "typename" => $type->display_name,
                    "quantity" => $order->quantity,
                    "remarks" => $order->remarks,
                    "collect_from_l1" => $c_address[0],
                    "collect_from_l2" => isset($c_address[1]) ? $c_address[1] : "",
                    "collection_zipcode" => $order->collection_post_code,
                    "collect_country" => $order->collection_country,
                    'is_c_restricted_area' => array(
                        'a4' => $order->is_c_restricted_area ? TRUE : FALSE),
                    "collect_date1" => $c_date1,
                    "collect_date2" => $c_date2,
                    "collect_timezone" => $c_zone,
                    "collect_contactname" => $order->collection_contact_name,
                    "collect_email" => $order->collection_contact_email,
                    "collect_phone" => $order->collection_contact_number,
                    'collect_company' => $order->collection_company_name,
                    "delivery_phone" => $order->delivery_contact_phone,
                    "delivery_email" => $order->delivery_contact_email,
                    "delivery_contactname" => $order->delivery_contact_name,
                    'delivery_company' => $order->delivery_company_name,
                    "delivery_timezone" => $d_zone,
                    "deliver_date1" => $d_date1,
                    "deliver_date2" => $d_date2,
                    "delivery_country" => $order->delivery_country,
                    'is_d_restricted_area' => array(
                        'a4' => ($order->is_d_restricted_area & 1) ? TRUE : FALSE,
                        'a3' => ($order->is_d_restricted_area >> 1 & 1) ? TRUE : FALSE,
                        'a2' => ($order->is_d_restricted_area >> 2 & 1) ? TRUE : FALSE,
                        'a1' => ($order->is_d_restricted_area >> 3 & 1) ? TRUE : FALSE),
                    "delivery_zipcode" => $order->delivery_post_code,
                    "delivery_address_l1" => $d_address[0],
                    "delivery_address_l2" => isset($d_address[1]) ? $d_address[1] : "",
                    "assigned_service" => $service,
                    "length" => $order->length ? $order->length : '',
                    "volume" => $order->volume ? $order->volume : '',
                    "breadth" => $order->breadth ? $order->breadth : '',
                    "height" => $order->height ? $order->height : '',
                    "weight" => $order->weight ? $order->weight : '',
                    "upload" => $order->picture ? $order->picture : "",
                    "picture" => $order->picture ? base_url('filebox/orders/' . $order->picture) : "",
                    "remarks" => $order->remarks,
                    "ref" => $order->ref,
                    "tags" => $order->tags
                );
            } else {
                $result['order'] = array();
            }
        }
        $result['count'] = count($result['order']);
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function get_change_info($order_id = NULL) {
        $result = array();
        $user = $this->session->userdata("user_id");
        if ($order_id != NULL) {
            //$order_id = $this->orders_model->get_order_id($order_id);
            $where = array(
                'C.consignment_id' => $order_id,
                'C.is_deleted' => 0
            );
            // $where['customer_id'] = $user;
            $order = $this->orders_model->getDetails($where);
            if ($order) {
                $c_address = json_decode($order->collection_address);
                $d_address = json_decode($order->delivery_address);
                $type = $this->consignment_types_model->getType($order->consignment_type_id);
                $c_zone = $this->orders_model->getZone($order->collection_timezone);
                $d_zone = $this->orders_model->getZone($order->delivery_timezone);
                $c_date1assigned_services_by_org = date("m/d/Y h:m A", strtotime($order->collection_date));
                $c_date2 = date("m/d/Y h:m A", strtotime($order->collection_date_to));
                $d_date1 = date("m/d/Y h:m A", strtotime($order->delivery_date));
                $d_date2 = date("m/d/Y h:m A", strtotime($order->delivery_date_to));
                $result['order'] = array(
                    "consignment_id" => $order->consignment_id,
                    "public_id" => $order->public_id,
                    "is_bulk" => $order->is_bulk ? TRUE : FALSE,
                    "type" => $type,
                    "typename" => $type->display_name,
                    "collect_from_l1" => $c_address[0],
                    "collect_from_l2" => isset($c_address[1]) ? $c_address[1] : "",
                    "collection_zipcode" => $order->collection_post_code,
                    "collect_country" => $order->collection_country,
                    'is_c_restricted_area' => array(
                        'a1' => ($order->is_c_restricted_area >> 1 & 1) ? TRUE : FALSE,
                        'a2' => ($order->is_c_restricted_area >> 1 & 1) ? TRUE : FALSE,
                        'a3' => ($order->is_c_restricted_area >> 1 & 1) ? TRUE : FALSE,
                        'a4' => ($order->is_c_restricted_area >> 1 & 1) ? TRUE : FALSE),
                    "collect_date1" => $c_date1,
                    "collect_date2" => $c_date2,
                    "collect_timezone" => $c_zone,
                    "collect_contactname" => $order->collection_contact_name,
                    "collect_email" => $order->collection_contact_email,
                    "collect_phone" => $order->collection_contact_number,
                    "delivery_phone" => $order->delivery_contact_phone,
                    "delivery_email" => $order->delivery_contact_email,
                    "delivery_contactname" => $order->delivery_contact_name,
                    "delivery_timezone" => $d_zone,
                    "deliver_date1" => $d_date1,
                    "deliver_date2" => $d_date2,
                    "delivery_country" => $order->delivery_country,
                    'is_d_restricted_area' => $order->is_d_restricted_area ? TRUE : FALSE,
                    "delivery_zipcode" => $order->delivery_post_code,
                    "delivery_address_l1" => $d_address[0],
                    "delivery_address_l2" => isset($d_address[1]) ? $d_address[1] : "",
                    "length" => $order->length ? $order->length : '',
                    "volume" => $order->volume ? $order->volume : '',
                    "breadth" => $order->breadth ? $order->breadth : '',
                    "height" => $order->height ? $order->height : '',
                    "weight" => $order->weight ? $order->weight : '',
                    "deadline" => $order->bidding_deadline
                );
            } else {
                $result['order'] = array();
            }
        }
        $result['count'] = count($result['order']);
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function orderslist_count() {
        $user_id = $this->session->userdata('user_id');
        $usr = $this->account_model->get_by_id($user_id);
        if ($usr->root) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $total_result = $this->orders_model->getorderslist_count($user_id, NULL, NULL, NULL, NULL, NULL, $flag);
        $result['count'] = $total_result;
        echo json_encode($result);
        exit();
    }

    public function orderslist_json() {
        $perpage = '';
        $search = NULL;
        $service = "";
        $status = "";
        $team = "";
        $cgroup_id = NULL;
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
        if (isset($ordersData->team)) {
            if ($ordersData->team != NULL) {
                $team = $ordersData->team;
            }
        }
        if (isset($ordersData->cgroup_id)) {
            if ($ordersData->cgroup_id != NULL) {
                $cgroup_id = $ordersData->cgroup_id;
            }
        }
        $user_id = $this->session->userdata('user_id');
        $usr = $this->account_model->get_by_id($user_id);
        if ($usr->root) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        if ($cgroup_id != NULL) {
            $total_result = $this->orders_model->getmorderslist_count($user_id, $search, $status, $cgroup_id, $flag);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;

            $order_detail = $this->orders_model->getmorderslist_by_userid($user_id, $perpage, $search, $start, $status, $cgroup_id, $flag);
            foreach ($order_detail as $value) {
                $value->collection_address = implode(' ', json_decode($value->collection_address));
                $value->delivery_address = implode(' ', json_decode($value->delivery_address));
            }
            $result['order_detail'] = $order_detail;
            $result['current_user_id'] = $user_id;
            $result['end'] = (int) ($start + count($result['order_detail']));
        } else {
            $exclude_status = array(
                C_GETTING_BID
            );
            $total_result = $this->orders_model->getorderslist_count($user_id, $search, $org, $service, $status, $team, $flag, $exclude_status);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;

            $order_detail = $this->orders_model->getorderslist_by_userid($user_id, $perpage, $search, $start, $org, $service, $status, $team, $flag, $exclude_status);
            foreach ($order_detail as $value) {
                $value->collection_address = implode(' ', json_decode($value->collection_address));
                $value->delivery_address = implode(' ', json_decode($value->delivery_address));
            }
            $result['order_detail'] = $order_detail;
            $result['current_user_id'] = $user_id;
            $result['end'] = (int) ($start + count($result['order_detail']));
        }
        echo json_encode($result);
        exit();
    }

    public function bidding_orderslist_count() {
        $result = array();
        $user_id = $this->session->userdata('user_id');
        $where = 'C.is_for_bidding = 1';

        $exclude_status = array(
            C_DRAFT
        );

        $usr = $this->account_model->get_by_id($user_id);
        if ($usr->root) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $result['total'] = $this->orders_model->getorderslist_count($user_id, NULL, NULL, "", 0, "", $flag, $exclude_status, $where);
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function bidding_orderslist_json() {
        $perpage = '';
        $search = NULL;
        $service = "";
        $team = "";
        $cgroup_id = NULL;
        $where = 'C.is_for_bidding = 1';

        $exclude_status = array(
            C_DRAFT
        );

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
        if (isset($ordersData->category)) {
            if ($ordersData->category != NULL) {
                $category = $ordersData->category;
                switch ($category) {
                    case "open":
                        $where = "C.is_for_bidding = 1 AND C.bidding_deadline > NOW() AND C.consignment_status_id = " . C_GETTING_BID;
                        break;
                    case "closed":
                        $where = "C.is_for_bidding = 1 AND C.consignment_status_id <> " . C_GETTING_BID;
                        break;
                    case "expired":
                        $where = "C.is_for_bidding = 1 AND C.bidding_deadline < NOW() AND C.consignment_status_id = " . C_GETTING_BID;
                        break;
                    default:
                        $where = 'C.is_for_bidding = 1';
                        break;
                }
            }
        }

        $status = 0;
        if (isset($ordersData->team)) {
            if ($ordersData->team != NULL) {
                $team = $ordersData->team;
            }
        }
        if (isset($ordersData->cgroup_id)) {
            if ($ordersData->cgroup_id != NULL) {
                $cgroup_id = $ordersData->cgroup_id;
            }
        }
        $user_id = $this->session->userdata('user_id');
        $usr = $this->account_model->get_by_id($user_id);
        if ($usr->root) {
            $flag = 1;
        } else {
            $flag = 0;
        }

        $total_result = $this->orders_model->getorderslist_count($user_id, $search, $org, $service, $status, $team, $flag, $exclude_status, $where);
        $lastpage = ceil($total_result / $perpage);
        if ($page > $lastpage) {
            $page = 1;
        }
        $start = ($page - 1) * $perpage;
        $result['total'] = $total_result;
        $result['start'] = $start + 1;
        $result['page'] = $page;
//debug($where);
        $order_detail = $this->orders_model->getorderslist_by_userid($user_id, $perpage, $search, $start, $org, $service, $status, $team, $flag, $exclude_status, $where);
        foreach ($order_detail as $value) {
            $value->collection_address = implode(' ', json_decode($value->collection_address));
            $value->delivery_address = implode(' ', json_decode($value->delivery_address));
        }
        $result['order_detail'] = $order_detail;
        $result['current_user_id'] = $user_id;
        $result['end'] = (int) ($start + count($result['order_detail']));
        echo json_encode($result);
        exit();
    }

    public function messageslist_json() {
        $result = array();
        $perpage = '';
        $search = '';
        $biddersData = json_decode(file_get_contents('php://input'));
        if (isset($biddersData->perpage_value)) {

            $perpage = $biddersData->perpage_value;
        } else {
            $perpage = 5;
        }
        if (isset($biddersData->order_id)) {

            $order_id = $biddersData->order_id;
        } else {
            $order_id = 0;
        }
        if (isset($biddersData->currentPage)) {

            $page = $biddersData->currentPage;
        } else {
            $page = 1;
        }
        $order_id = $this->orders_model->get_order_id($order_id);

        $messages = $this->consignment_messages_model->getmessages($order_id);
        $result['total'] = $this->consignment_messages_model->get_message_count($order_id);
        $result['reply'] = $this->consignment_messages_model->get_reply_count($order_id);
        $result['messages'] = $messages;
        echo json_encode($result);
        exit();
    }

    public function add_reply() {
        $result = array();
        $perpage = '';
        $search = '';
        $biddersData = json_decode(file_get_contents('php://input'));
        if (isset($biddersData->msg_id)) {

            $msg_id = (int) $biddersData->msg_id;
        } else {
            $msg_id = 0;
        }
        if (isset($biddersData->reply)) {

            $reply = $biddersData->reply;
        } else {
            $reply = "";
        }

        $messages = $this->consignment_messages_model->add_reply($msg_id, array(
            'reply' => $reply,
            'updated_date' => mdate('%Y-%m-%d %H:%i:%s', now())
                ));
        if ($messages) {
            $msg_row = $this->consignment_messages_model->get_order($msg_id);
            $this->send_mail_for_courier($msg_row->job_id, N_COMMENT_RESPONSE);
        }
        $result['messages'] = $messages;
        echo json_encode($result);
        exit();
    }

    public function add_comment() {
        $result = array();
        $perpage = '';
        $search = '';
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
        $order_id = $this->orders_model->get_order_id($order_id);

        if ($this->consignment_messages_model->add_comment(array(
                    "order_id" => $order_id,
                    "comment" => $comment
                ))) {
            $this->send_mail_for_courier($order_id, N_COMMENT_FROM_OWNER, 1);
            $last = $this->consignment_messages_model->get_last_comment($order_id);
        }
        $result['last'] = $last;
        echo json_encode($result);
        exit();
    }

    public function bidderlist_json() {
        $perpage = '';
        $search = '';
        $biddersData = json_decode(file_get_contents('php://input'));
        if (isset($biddersData->perpage_value)) {

            $perpage = $biddersData->perpage_value;
        } else {
            $perpage = 5;
        }
        if (isset($biddersData->order_id)) {

            $order_id = $biddersData->order_id;
        } else {
            $order_id = 0;
        }
        if (isset($biddersData->currentPage)) {

            $page = $biddersData->currentPage;
        } else {
            $page = 1;
        }
        if (isset($biddersData->filter)) {
            if ($biddersData->filter != NULL) {
                $search = $biddersData->filter;
            } else {
                $search = NULL;
            }
        }
        $user_id = $this->session->userdata('user_id');
        $order_id = $this->orders_model->get_order_id($order_id);
        $total_result = $this->orders_model->getbidderslist_count($order_id, $search);
        $lastpage = ceil($total_result / $perpage);
        if ($page > $lastpage) {
            $page = 1;
        }
        $start = ($page - 1) * $perpage;
        $result['total'] = $total_result;
        $result['start'] = $start + 1;
        $result['page'] = $page;
        $bidders = array();
        $bidders = $this->orders_model->getbidderslist_by_userid($order_id, $perpage, $search, $start);
        $notify = $this->orders_model->getbidderslist_totalcount($order_id);
        $result['notify'] = $notify;
        $result['bidders'] = $bidders;
        $result['current_user_id'] = $user_id;
        $result['end'] = (int) ($start + count($result['bidders']));
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function accept_bid() {
        $biddersData = json_decode(file_get_contents('php://input'));
        if (isset($biddersData->bid_id)) {
            $bid_id = (int) $biddersData->bid_id;
        } else {
            $bid_id = 0;
        }
        if (isset($biddersData->order_id)) {

            $order_id = $biddersData->order_id;
        } else {
            $order_id = 0;
        }
        $order_id = $this->orders_model->get_order_id($order_id);

        $result = array();
        $bid = $this->orders_model->get_bid($bid_id);
        if ($bid) {
            if ($this->orders_model->accept_bid($order_id, $bid_id, $bid->price, $bid->row_id)) {
                $this->consignment_activity_log_model->add_activity(array(), $bid_id, TRUE);
                $this->send_mail_for_courier($order_id, N_BID_WON);
                $result['status'] = 1;
                $result['msg'] = lang('accept_bid_success');
                $result['class'] = "alert-success";
            } else {
                $result['status'] = 0;
                $result['msg'] = lang('accept_bid_failed');
                $result['class'] = "alert-danger";
            }
        } else {
            $result['status'] = 0;
            $result['msg'] = lang('accept_bid_failed');
            $result['class'] = "alert-danger";
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
        $total_result = $this->consignment_activity_log_model->getloglist_count($order_id, $search);
        $lastpage = ceil($total_result / $perpage);
        if ($page > $lastpage) {
            $page = 1;
        }
        $start = ($page - 1) * $perpage;
        $result['total'] = $total_result;
        $result['start'] = $start + 1;
        $result['page'] = $page;
        $loglist = array();
        $loglist = $this->consignment_activity_log_model->getloglist_by_orderid($order_id, $perpage, $search, $start);

        $result['loglist'] = $loglist;
        $result['current_user_id'] = $user_id;
        $result['end'] = (int) ($start + count($result['loglist']));
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    function attachlist_json() {
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

        $order_id = $this->orders_model->get_order_id($order_id);

        $user_id = $this->session->userdata('user_id');
        $total_result = $this->consignment_attachment_model->getlist_count($order_id, $search);
        $lastpage = ceil($total_result / $perpage);
        if ($page > $lastpage) {
            $page = 1;
        }
        $start = ($page - 1) * $perpage;
        $result['total'] = $total_result;
        $result['start'] = $start + 1;
        $result['page'] = $page;
        $loglist = $this->consignment_attachment_model->getlist_by_orderid($order_id, $perpage, $search, $start);

        $result['attachmentslist'] = $loglist;
        $result['current_user_id'] = $user_id;
        $result['end'] = (int) ($start + count($result['attachmentslist']));
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function remove_photo() {
        if ($this->input->post('image')) {
            $image_path = $this->input->post('image', TRUE);
            if (file_exists("filebox/orders/$image_path")) {
                unlink("filebox/orders/$image_path");
            }
        }
        return;
    }

    public function upload() {
        $data = array();
        $order = time();
        if (isset($_FILES['file'])) {
            $error = false;
            $files = "";
            $uploaddir = "filebox/orders/";
            $uploadPath = "filebox/orders";
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, TRUE);
            }
            $ext = end(explode(".", basename($_FILES['file']['name'])));
            $config['image_library'] = 'gd2';
            $config['source_image'] = $_FILES['file']['tmp_name'];
            $config['new_image'] = $uploaddir . $order . "." . $ext;
            $config['create_thumb'] = TRUE;
            $config['thumb_marker'] = "";
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 200;
            $config['height'] = 200;

            $this->load->library('image_lib', $config);

            if ($this->image_lib->resize()) {
                $files = $order . "." . $ext;
            } else {
                $errors = $this->image_lib->display_errors();
                $error = true;
            }
            $data = ($error) ? array(
                'error' => $errors
                    ) : array(
                'files' => $files
                    );
        } else {
            $data = array(
                'error' => 'File not uploaded'
            );
        }

        echo json_encode($data);
        exit();
    }

    public function confirmOrder($public_id = 0) {
        $result = array();
        $order_id = $this->orders_model->get_order_id($public_id);
        $status = $this->orders_model->is_direct_assign($order_id);
        if ($status) {
            $updatestatus = C_PENDING_ACCEPTANCE;
            $consignment_status = "Pending Acceptance";
            $this->send_mail_for_courier($order_id, N_DIRECT_ASSIGN);
        } else {
            $updatestatus = C_GETTING_BID;
            $consignment_status = "Getting Bid";
            $this->send_mail_for_courier($order_id, N_CLOSED_BID, 1);
        }
        if ($this->orders_model->updateStatus($order_id, $updatestatus)) {
            // $this->consignment_activity_log_model->add_activity(array("order_id" => $order_id, "activity" => "Order confirmed by you"));
            $result['consignment_status'] = $consignment_status;
            $result['msg'] = lang('confirm_order_success');
            $result['class'] = "alert-success";
            $result['status'] = 1;
        } else {
            $result['msg'] = lang('try_again');
            $result['class'] = "alert-warning";
            $result['status'] = 0;
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function deleteOrder($order_id = 0) {
        $order_id = (int) $order_id;
        $data = array(
            "is_deleted" => 1,
            'deleted_date' => mdate('%Y-%m-%d %H:%i:%s', now())
        );
        if ($this->orders_model->updateOrder($data, $order_id)) {
            $result['msg'] = lang('order_deleted');
            $result['class'] = "alert-success";
            $result['status'] = 1;
        } else {
            $result['msg'] = lang('try_again');
            $result['class'] = "alert-warning";
            $result['status'] = 0;
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function saveOrder() {
        $error = false;
        $errors = array();
        $error_part1 = $error_part2 = $error_part3 = 0;
        $result = array();
        $is_edit = false;
        $is_pre_approved_service = FALSE;
        $auto_approve = false;
        $orderdata = json_decode(file_get_contents('php://input'));
        $price = 0;
        if (isset($orderdata->consignment_id) && !empty($orderdata->consignment_id)) {
            $consignment_id = (int) $orderdata->consignment_id;
            $is_edit = true;
            if (isset($orderdata->public_id) && !empty($orderdata->public_id)) {
                $public_id = $orderdata->public_id;
            } else {
                $public_id = 0;
            }
        }
        $custom = false;
        if (isset($orderdata->type) && !empty($orderdata->type)) {
            $type = (int) $orderdata->type->consignment_type_id;
            if ($type == CUSTOM_ITEM) {
                $custom = true;
            }
        } else {
            $error = true;
            $errors['type'] = "Invalid";
            $error_part1 = 1;
        }

        if (isset($orderdata->is_bulk) && !empty($orderdata->is_bulk)) {
            $is_bulk = true;
        } else {
            $is_bulk = false;
        }
        if ($is_bulk || $custom) {
            if (isset($orderdata->height) && !empty($orderdata->height)) {
                $height = (float) $orderdata->height;
            } else {
                $error = true;
                $errors['height'] = "Invalid";
                $error_part1 = 1;
            }
            if (isset($orderdata->breadth) && !empty($orderdata->breadth)) {
                $breadth = (float) $orderdata->breadth;
            } else {
                $error = true;
                $errors['breadth'] = "Invalid";
                $error_part1 = 1;
            }
            if (isset($orderdata->length) && !empty($orderdata->length)) {
                $length = (float) $orderdata->length;
            } else {
                $error = true;
                $errors['length'] = "Invalid";
                $error_part1 = 1;
            }
            if (isset($orderdata->volume) && !empty($orderdata->volume)) {
                $volume = (float) str_replace(',', "", $orderdata->volume);
            } else {
                $error = true;
                $errors['volume'] = "Invalid";
                $error_part1 = 1;
            }
            if (isset($orderdata->weight) && !empty($orderdata->weight)) {
                $weight = (float) $orderdata->weight;
            } else {
                $error = true;
                $errors['weight'] = "Invalid";
                $error_part1 = 1;
            }
        } else {
            $height = 0;
            $breadth = 0;
            $length = 0;
            $volume = 0;
            $weight = 0;
        }
        if (isset($orderdata->org_id) && !empty($orderdata->org_id)) {
            $org_id = (int) $orderdata->org_id;
        } else {
            $org_id = 0;
        }
        if (isset($orderdata->quantity) && !empty($orderdata->quantity)) {
            $quantity = (int) $orderdata->quantity;
            if ($quantity <= 0) {
                $error = true;
                $errors['quantity'] = "Must be a positive value";
                $error_part1 = 1;
            }
        } else {
            $error = true;
            $errors['quantity'] = "Invalid";
            $error_part1 = 1;
        }
        $collect_address = array();
        if (isset($orderdata->collect_from_l1) && !empty($orderdata->collect_from_l1)) {
            $collect_address[] = htmlentities(trim(str_replace(",", " ", $orderdata->collect_from_l1)));
        } else {
            $error = true;
            $errors['collect_from_l1'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->collect_from_l2) && !empty($orderdata->collect_from_l2)) {
            $collect_address[] = htmlentities(str_replace(",", " ", trim($orderdata->collect_from_l2)));
        } else {
            $error = true;
            $errors['collect_from_l2'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->collection_zipcode) && !empty($orderdata->collection_zipcode)) {
            if (is_numeric($orderdata->collection_zipcode)) {
                $collection_zipcode = htmlentities(trim($orderdata->collection_zipcode));
            } else {
                $error = true;
                $errors['collection_zipcode'] = "Postal code must be integer";
                $error_part1 = 1;
            }
        } else {
            $collection_zipcode = "";
        }
        if (isset($orderdata->collect_country) && !empty($orderdata->collect_country)) {
            $collect_country = htmlentities(trim($orderdata->collect_country));
        } else {
            $error = true;
            $errors['collect_country'] = "Invalid";
            $error_part1 = 1;
        }
        $is_c_restricted_area = 0;
        if (isset($orderdata->is_c_restricted_area->a1) && $orderdata->is_c_restricted_area->a1 == true) {
            $is_c_restricted_area +=8;
        }
        if (isset($orderdata->is_c_restricted_area->a2) && $orderdata->is_c_restricted_area->a2 == true) {
            $is_c_restricted_area +=4;
        }
        if (isset($orderdata->is_c_restricted_area->a3) && $orderdata->is_c_restricted_area->a3 == true) {
            $is_c_restricted_area +=2;
        }
        if (isset($orderdata->is_c_restricted_area->a4) && $orderdata->is_c_restricted_area->a4 == true) {
            $is_c_restricted_area +=1;
        }
        if (isset($orderdata->collect_contactname) && !empty($orderdata->collect_contactname)) {
            $collect_contactname = htmlentities(trim($orderdata->collect_contactname));
        } else {
            $error = true;
            $errors['collect_contactname'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->collect_email) && !empty($orderdata->collect_email)) {
            $collect_email = htmlentities(trim($orderdata->collect_email));
        } else {
            $collect_email = '';
        }
        if (isset($orderdata->collect_company) && !empty($orderdata->collect_company)) {
            $collect_company = htmlentities(trim($orderdata->collect_company));
        } else {
            $collect_company = '';
        }
        if (isset($orderdata->collect_phone) && !empty($orderdata->collect_phone)) {
            $collect_phone = htmlentities(trim($orderdata->collect_phone));
        } else {
            $error = true;
            $errors['collect_phone'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->collect_date1) && !empty($orderdata->collect_date1)) {
            $collect_date1 = $orderdata->collect_date1;
            $collect_date_from = date("Y-m-d H:i:s", strtotime($collect_date1));
        } else {
            $error = true;
            $errors['collect_date1'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->collect_date2) && !empty($orderdata->collect_date2)) {
            $collect_date2 = $orderdata->collect_date2;
            $collect_date_to = date("Y-m-d H:i:s", strtotime($collect_date2));
            if ($collect_date_to < $collect_date_from) {
                $error = true;
                $errors['collect_date2'] = "Must be greater than from date";
                $error_part1 = 1;
            }
        } else {
            $error = true;
            $errors['collect_date2'] = "Invalid";
            $error_part1 = 1;
        }

        $delivery_address = array();
        if (isset($orderdata->delivery_address_l1) && !empty($orderdata->delivery_address_l1)) {
            $delivery_address[] = htmlentities(trim(str_replace(",", " ", $orderdata->delivery_address_l1)));
        } else {
            $error = true;
            $errors['delivery_address_l1'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_address_l2) && !empty($orderdata->delivery_address_l2)) {
            $delivery_address[] = htmlentities(str_replace(",", " ", trim($orderdata->delivery_address_l2)));
        } else {
            $error = true;
            $errors['delivery_address_l2'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_zipcode) && !empty($orderdata->delivery_zipcode)) {
            $delivery_zipcode = htmlentities(trim($orderdata->delivery_zipcode));
            if (is_numeric($orderdata->delivery_zipcode)) {
                $delivery_zipcode = htmlentities(trim($orderdata->delivery_zipcode));
            } else {
                $error = true;
                $errors['delivery_zipcode'] = "Postal code must be integer";
                $error_part1 = 1;
            }
        } else {
            $delivery_zipcode = "";
        }
        if (isset($orderdata->delivery_country) && !empty($orderdata->delivery_country)) {
            $delivery_country = htmlentities(trim($orderdata->delivery_country));
        } else {
            $error = true;
            $errors['delivery_country'] = "Invalid";
            $error_part1 = 1;
        }
        $is_d_restricted_area = 0;
        if (isset($orderdata->is_d_restricted_area->a1) && $orderdata->is_d_restricted_area->a1 == true) {
            $is_d_restricted_area +=8;
        }
        if (isset($orderdata->is_d_restricted_area->a2) && $orderdata->is_d_restricted_area->a2 == true) {
            $is_d_restricted_area +=4;
        }
        if (isset($orderdata->is_d_restricted_area->a3) && $orderdata->is_d_restricted_area->a3 == true) {
            $is_d_restricted_area +=2;
        }
        if (isset($orderdata->is_d_restricted_area->a4) && $orderdata->is_d_restricted_area->a4 == true) {
            $is_d_restricted_area +=1;
        }

        if (isset($orderdata->deliver_date1) && !empty($orderdata->deliver_date1)) {
            $delivery_date1 = $orderdata->deliver_date1;
            $delivery_date_from = date("Y-m-d H:i:s", strtotime($delivery_date1));
        } else {
            $error = true;
            $errors['deliver_date1'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->deliver_date2) && !empty($orderdata->deliver_date2)) {
            $delivery_date2 = $orderdata->deliver_date2;
            $delivery_date_to = date("Y-m-d H:i:s", strtotime($delivery_date2));
            if ($delivery_date_to < $delivery_date_from) {
                $error = true;
                $errors['deliver_date2'] = "Must be greater than from date";
                $error_part1 = 1;
            }
            if ($collect_date_from > $delivery_date_to) {
                $error = true;
                $errors['collect_date1'] = "Time must be before Delivery Time";
                $error_part1 = 1;
            }
        } else {
            $error = true;
            $errors['deliver_date2'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_contactname) && !empty($orderdata->delivery_contactname)) {
            $delivery_contactname = htmlentities(trim($orderdata->delivery_contactname));
        } else {
            $error = true;
            $errors['delivery_contactname'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_email) && !empty($orderdata->delivery_email)) {
            $delivery_email = htmlentities(trim($orderdata->delivery_email));
        } else {
            $delivery_email = '';
        }
        if (isset($orderdata->delivery_company) && !empty($orderdata->delivery_company)) {
            $delivery_company = htmlentities(trim($orderdata->delivery_company));
        } else {
            $delivery_company = '';
        }
        if (isset($orderdata->delivery_phone) && !empty($orderdata->delivery_phone)) {
            $delivery_phone = htmlentities(trim($orderdata->delivery_phone));
        } else {
            $error = true;
            $errors['delivery_phone'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_is_notify) && !empty($orderdata->delivery_is_notify)) {
            $delivery_is_notify = (int) $orderdata->delivery_is_notify;
        } else {
            $delivery_is_notify = 0;
        }
        $is_third_party = 0;
        if (isset($orderdata->delivery_is_assign) && $orderdata->delivery_is_assign == 2) {
            $delivery_is_assign = 0;
            $assigned_service = 0;
            $is_for_bidding = 1;
            $threshold = 0;
            if (isset($orderdata->open_bid) && !empty($orderdata->open_bid)) {
                $open_bid = 1;
            } else {
                if ($org_id == 0) {
                    $open_bid = 1;
                } else {
                    $open_bid = 0;
                }
            }
            if (isset($orderdata->deadline) && !empty($orderdata->deadline)) {
                $deadline_date = date("Y-m-d H:i:s", strtotime($orderdata->deadline));
            } else {
                $error = true;
                $errors['deadline'] = "Invalid";
                $error_part2 = 1;
            }
        } elseif (isset($orderdata->delivery_is_assign) && $orderdata->delivery_is_assign == 1) {
            $delivery_is_assign = 1;
            $is_for_bidding = 0;
            $open_bid = 0;
            $deadline_date = NULL;

            if (isset($orderdata->assigned_service) && !empty($orderdata->assigned_service)) {
                $assigned_service = (int) $orderdata->assigned_service->service_id;
                $is_pre_approved_service = $orderdata->assigned_service->org_id ? true : FALSE;
                $auto_approve = $orderdata->assigned_service->auto_approve ? true : FALSE;
                if (isset($orderdata->price) && !empty($orderdata->price)) {
                    $price = $orderdata->price;
                }
            } else {
                $error = true;
                $errors['assigned_service'] = "Please select one service";
                $error_part2 = 1;
            }
            if (isset($orderdata->threshold) && !empty($orderdata->threshold)) {
                $threshold = $orderdata->threshold;
            } else {
                $error = true;
                $errors['threshold'] = "Invalid";
                $error_part3 = 1;
            }
        } else {
            $is_third_party = 1;
            $delivery_is_assign = 1;
            $is_for_bidding = 0;
            $open_bid = 0;
            $deadline_date = NULL;
            $threshold = 0;
            if (isset($orderdata->third_party_email) && !empty($orderdata->third_party_email)) {
                $third_party_email = $orderdata->third_party_email;
                $assigned_service = 0;
                $is_pre_approved_service = FALSE;
                $auto_approve = FALSE;
                if (!valid_email($orderdata->third_party_email)) {
                    $error = TRUE;
                    $errors['third_party_email'] = "Please provide valid email ID";
                    $error_part2 = 1;
                }
            } else {
                $error = TRUE;
                $errors['third_party_email'] = "Please provide third party email ID";
                $error_part2 = 1;
            }
        }
        if (isset($orderdata->remarks) && !empty($orderdata->remarks)) {
            $remarks = htmlentities(trim($orderdata->remarks));
        } else {
            $remarks = '';
        }
        if (isset($orderdata->payment_mode) && !empty($orderdata->payment_mode)) {
            $payment_json = $orderdata->payment_mode;
            $payment_mode = htmlentities(trim($payment_json->value));
            $payment_mode = str_replace('_', "0", $payment_mode);
            $payment_mode = bindec($payment_mode) ? bindec($payment_mode) : 1;
            $account_id = $payment_json->id ? $payment_json->id : 0;
        } else {
            $payment_mode = 1;
            $account_id = 0;
        }
        if (isset($orderdata->ref) && !empty($orderdata->ref)) {
            $ref = htmlentities(trim($orderdata->ref));
        } else {
            $ref = NULL;
        }
        if (isset($orderdata->tags) && !empty($orderdata->tags)) {
            $tags = htmlentities(trim($orderdata->tags));
            $this->tags_model->add_tags($tags, 1);
        } else {
            $tags = NULL;
        }
        if (isset($orderdata->collect_timezone) && !empty($orderdata->collect_timezone)) {
            $collect_timezone = $orderdata->collect_timezone->zoneinfo;
        } else {
            $error = true;
            $errors['collect_timezone'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->delivery_timezone) && !empty($orderdata->delivery_timezone)) {
            $delivery_timezone = $orderdata->delivery_timezone->zoneinfo;
        } else {
            $error = true;
            $errors['delivery_timezone'] = "Invalid";
            $error_part1 = 1;
        }
        if (isset($orderdata->upload) && !empty($orderdata->upload)) {
            if (is_file(FCPATH . 'filebox/orders/' . $orderdata->upload)) {
                $picture = $orderdata->upload;
            }
        }

        if (isset($orderdata->draft) && $orderdata->draft == TRUE) {
            $status = C_DRAFT;
        } else {
            if ($delivery_is_assign) {
                $status = C_PENDING_ACCEPTANCE;
            } else {
                $status = C_GETTING_BID;
            }
        }
        if (!$error) {
            $user_id = $this->session->userdata('user_id');
            if ($is_edit) {
                $order_data = array(
                    'consignment_type_id' => $type,
                    'service_id' => $assigned_service,
                    "org_id" => $org_id,
                    'is_service_assigned' => $delivery_is_assign,
                    "is_for_bidding" => $is_for_bidding,
                    'is_open_bid' => $open_bid,
                    'bidding_deadline' => $deadline_date,
                    'threshold_price' => $threshold,
                    'quantity' => $quantity,
                    'is_bulk' => $is_bulk,
                    'length' => $length,
                    'breadth' => $breadth,
                    'height' => $height,
                    'volume' => $volume,
                    'weight' => $weight,
                    'price' => $price,
                    'collection_address' => json_encode($collect_address),
                    'is_c_restricted_area' => $is_c_restricted_area,
                    'collection_date' => $collect_date_from,
                    'collection_date_to' => $collect_date_to,
                    'collection_country' => $collect_country,
                    'collection_timezone' => $collect_timezone,
                    'delivery_address' => json_encode($delivery_address),
                    'is_d_restricted_area' => $is_d_restricted_area,
                    'delivery_post_code' => $delivery_zipcode,
                    'delivery_country' => $delivery_country,
                    'delivery_timezone' => $delivery_timezone,
                    'delivery_date' => $delivery_date_from,
                    'delivery_date_to' => $delivery_date_to,
                    'delivery_contact_name' => $delivery_contactname,
                    'delivery_contact_email' => $delivery_email,
                    'delivery_contact_phone' => $delivery_phone,
                    'delivery_company_name' => $delivery_company,
                    'collection_post_code' => $collection_zipcode,
                    'collection_contact_name' => $collect_contactname,
                    'collection_contact_number' => $collect_phone,
                    'collection_contact_email' => $collect_email,
                    'collection_company_name' => $collect_company,
                    'send_notification_to_consignee' => $delivery_is_notify,
                    'consignment_status_id' => $status,
                    'is_third_party' => $is_third_party,
                    'remarks' => $remarks,
                    'payment_type' => $payment_mode,
                    'payment_acount_id' => $account_id,
                    'ref' => $ref,
                    'tags' => $tags
                );
                if (isset($picture)) {
                    $order_data['picture'] = $picture;
                }
                if ($this->orders_model->updateOrder($order_data, $consignment_id)) {

                    $insert_id = $consignment_id;
                    if ($is_third_party) {
                        $third_party_info = array(
                            'email' => $third_party_email,
                            'user_id' => $user_id
                        );
                        $this->couriers_external_model->add_job($third_party_info, $insert_id);
                    }
                    $this->consignment_activity_log_model->add_activity(array(
                        "order_id" => $insert_id,
                        "activity" => "Order edited by the you"
                    ));
                    if ($status == C_PENDING_ACCEPTANCE || $status == C_COLLECTING) {
                        $this->courier_service_model->update_service($assigned_service, array(
                            'is_new' => 0
                        ));
                        if ($status == C_PENDING_ACCEPTANCE)
                            $this->consignment_activity_log_model->add_activity(array(
                                "order_id" => $insert_id,
                                "activity" => "Order status updated to - " . C_PENDING_ACCEPTANCE_NAME
                            ));
                        if ($status == C_COLLECTING)
                            $this->consignment_activity_log_model->add_activity(array(
                                "order_id" => $insert_id,
                                "activity" => "Order status updated to - " . C_COLLECTING_NAME
                            ));
                        $result['new'] = true;
                        if (!$is_third_party) {
                            $this->send_mail_for_courier($insert_id, N_DIRECT_ASSIGN);
                        } else {
                            $this->send_mail_for_courier($insert_id, N_THIRD_PARTY, 1);
                        }
                    } else if ($status == C_GETTING_BID) {
                        $this->consignment_activity_log_model->add_activity(array(
                            "order_id" => $insert_id,
                            "activity" => "Order status updated to - " . C_GETTING_BID_NAME
                        ));
                        $result['new'] = true;
                        if ($open_bid) {
                            $this->send_mail_for_courier($insert_id, N_OPEN_BID, 1);
                        } else {
                            $this->send_mail_for_courier($insert_id, N_CLOSED_BID, 1);
                        }
                    }
                    $result['msg'] = "Order updated successfully";
                }
            } else {
                $description = '';
                $this->load->helper('string');
                do {
                    $public_id = random_string('numeric', 14);
                } while (!$this->orders_model->is_unique_publicid($public_id));
                // do {
                // $private_id = random_string('alnum', 10);
                // } while (!$this->orders_model->is_unique_privateid($private_id));

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
                    'price' => $price,
                    'threshold_price' => $threshold,
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
                    'is_c_restricted_area' => $is_c_restricted_area,
                    'collection_date' => $collect_date_from,
                    'collection_date_to' => $collect_date_to,
                    'collection_country' => $collect_country,
                    'collection_timezone' => $collect_timezone,
                    'delivery_address' => json_encode($delivery_address),
                    'is_d_restricted_area' => $is_d_restricted_area,
                    'delivery_post_code' => $delivery_zipcode,
                    'delivery_country' => $delivery_country,
                    'delivery_timezone' => $delivery_timezone,
                    'delivery_date' => $delivery_date_from,
                    'delivery_date_to' => $delivery_date_to,
                    'delivery_contact_name' => $delivery_contactname,
                    'delivery_contact_email' => $delivery_email,
                    'delivery_contact_phone' => $delivery_phone,
                    'delivery_company_name' => $delivery_company,
                    'created_user_id' => $user_id,
                    'collection_post_code' => $collection_zipcode,
                    'collection_contact_name' => $collect_contactname,
                    'collection_contact_number' => $collect_phone,
                    'collection_contact_email' => $collect_email,
                    'collection_company_name' => $collect_company,
                    'send_notification_to_consignee' => $delivery_is_notify,
                    'consignment_status_id' => $status,
                    'remarks' => $remarks,
                    'payment_type' => $payment_mode,
                    'payment_acount_id' => $account_id,
                    'is_third_party' => $is_third_party,
                    'ref' => $ref,
                    'tags' => $tags
                );
                if (isset($picture)) {
                    $order_data['picture'] = $picture;
                }
                $insert_id = $this->orders_model->addOrder($order_data);
                if ($insert_id) {
                    $this->consignment_activity_log_model->add_activity(array(
                        "order_id" => $insert_id,
                        "activity" => "New order added to the system"
                    ));
                    $this->generate_barcode(CONSIGNMENT_PREFIX . $insert_id);
                    // $this->generate_barcode($private_id);
                    $this->generate_barcode($public_id);
                    $this->generate_ciqrcode($insert_id);
                    if ($is_third_party) {
                        $third_party_info = array(
                            'email' => $third_party_email,
                            'user_id' => $user_id
                        );
                        $this->couriers_external_model->add_job($third_party_info, $insert_id);
                    }
                    if ($status == C_PENDING_ACCEPTANCE || $status == C_COLLECTING) {
                        $this->courier_service_model->update_service($assigned_service, array(
                            'is_new' => 0
                        ));
                        if ($status == C_PENDING_ACCEPTANCE)
                            $this->consignment_activity_log_model->add_activity(array(
                                "order_id" => $insert_id,
                                "activity" => "Order status updated to - " . C_PENDING_ACCEPTANCE_NAME
                            ));
                        if ($status == C_COLLECTING)
                            $this->consignment_activity_log_model->add_activity(array(
                                "order_id" => $insert_id,
                                "activity" => "Order status updated to - " . C_COLLECTING_NAME
                            ));
                        $result['new'] = true;
                        if (!$is_third_party) {
                            $this->send_mail_for_courier($insert_id, N_DIRECT_ASSIGN);
                        } else {
                            $this->send_mail_for_courier($insert_id, N_THIRD_PARTY, 1);
                        }
                    } else if ($status == C_GETTING_BID) {
                        $this->consignment_activity_log_model->add_activity(array(
                            "order_id" => $insert_id,
                            "activity" => "Order status updated to - " . C_GETTING_BID_NAME
                        ));
                        $result['new'] = true;
                        if ($open_bid) {
                            $this->send_mail_for_courier($insert_id, N_OPEN_BID, 1);
                        } else {
                            $this->send_mail_for_courier($insert_id, N_CLOSED_BID, 1);
                        }
                    }
                }
                $result['msg'] = "New order added successfully";
            }

            if ($insert_id) {
                $result['status'] = 1;
                $result['class'] = "alert-success";
                $result['id'] = $insert_id;
                $result['public_id'] = $public_id;
            } else {
                $result['status'] = 0;
                $result['class'] = "alert-danger";
                $result['msg'] = lang('try_again');
                $result['id'] = 0;
            }
        } else {
            $result['status'] = 0;
            $result['class'] = "alert-warning";
            $result['msg'] = lang('clear_error');
            $result['errors'] = $errors;
            if ($error_part1) {
                $result['part'] = 1;
            } else if ($error_part2) {
                $result['part'] = 2;
            } else if ($error_part3) {
                $result['part'] = 3;
            } else {
                $result['part'] = 1;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    public function add_change_request() {
        $error = false;
        $errors = array();
        $result = array();
        $is_edit = false;
        $orderdata = json_decode(file_get_contents('php://input'));

        if (isset($orderdata->consignment_id) && !empty($orderdata->consignment_id)) {
            $consignment_id = (int) $orderdata->consignment_id;
            if (isset($orderdata->public_id) && !empty($orderdata->public_id)) {
                $public_id = $orderdata->public_id;
            } else {
                $public_id = 0;
            }
        }
        $custom = false;
        if (isset($orderdata->type) && !empty($orderdata->type)) {
            $type = (int) $orderdata->type->consignment_type_id;
            if ($type == CUSTOM_ITEM) {
                $custom = true;
            }
        } else {
            $error = true;
            $errors['type'] = "Invalid";
        }

        if (isset($orderdata->is_bulk) && !empty($orderdata->is_bulk)) {
            $is_bulk = true;
        } else {
            $is_bulk = false;
        }
        if ($is_bulk || $custom) {
            if (isset($orderdata->height) && !empty($orderdata->height)) {
                $height = (float) $orderdata->height;
            } else {
                $error = true;
                $errors['height'] = "Invalid";
            }
            if (isset($orderdata->breadth) && !empty($orderdata->breadth)) {
                $breadth = (float) $orderdata->breadth;
            } else {
                $error = true;
                $errors['breadth'] = "Invalid";
            }
            if (isset($orderdata->length) && !empty($orderdata->length)) {
                $length = (float) $orderdata->length;
            } else {
                $error = true;
                $errors['length'] = "Invalid";
            }
            if (isset($orderdata->volume) && !empty($orderdata->volume)) {
                $volume = (float) str_replace(',', "", $orderdata->volume);
            } else {
                $error = true;
                $errors['volume'] = "Invalid";
            }
            if (isset($orderdata->weight) && !empty($orderdata->weight)) {
                $weight = (float) $orderdata->weight;
            } else {
                $error = true;
                $errors['weight'] = "Invalid";
            }
        } else {
            $height = 0;
            $breadth = 0;
            $length = 0;
            $volume = 0;
            $weight = 0;
        }

        $collect_address = array();
        if (isset($orderdata->collect_from_l1) && !empty($orderdata->collect_from_l1)) {
            $collect_address[] = htmlentities(trim(str_replace(",", " ", $orderdata->collect_from_l1)));
        } else {
            $error = true;
            $errors['collect_from_l1'] = "Invalid";
        }
        if (isset($orderdata->collect_from_l2) && !empty($orderdata->collect_from_l2)) {
            $collect_address[] = htmlentities(str_replace(",", " ", trim($orderdata->collect_from_l2)));
        } else {
            $error = true;
            $errors['collect_from_l2'] = "Invalid";
        }
        if (isset($orderdata->collection_zipcode) && !empty($orderdata->collection_zipcode)) {
            if (is_numeric($orderdata->collection_zipcode)) {
                $collection_zipcode = htmlentities(trim($orderdata->collection_zipcode));
            } else {
                $error = true;
                $errors['collection_zipcode'] = "Postal code must be integer";
            }
        } else {
            $collection_zipcode = "";
        }
        if (isset($orderdata->collect_country) && !empty($orderdata->collect_country)) {
            $collect_country = htmlentities(trim($orderdata->collect_country));
        } else {
            $error = true;
            $errors['collect_country'] = "Invalid";
        }
        $is_c_restricted_area = 0;
        if (isset($orderdata->is_c_restricted_area->a1) && $orderdata->is_c_restricted_area->a1 == true) {
            $is_c_restricted_area +=8;
        }
        if (isset($orderdata->is_c_restricted_area->a2) && $orderdata->is_c_restricted_area->a2 == true) {
            $is_c_restricted_area +=4;
        }
        if (isset($orderdata->is_c_restricted_area->a3) && $orderdata->is_c_restricted_area->a3 == true) {
            $is_c_restricted_area +=2;
        }
        if (isset($orderdata->is_c_restricted_area->a4) && $orderdata->is_c_restricted_area->a4 == true) {
            $is_c_restricted_area +=1;
        }
        if (isset($orderdata->collect_contactname) && !empty($orderdata->collect_contactname)) {
            $collect_contactname = htmlentities(trim($orderdata->collect_contactname));
        } else {
            $error = true;
            $errors['collect_contactname'] = "Invalid";
        }
        if (isset($orderdata->collect_email) && !empty($orderdata->collect_email)) {
            $collect_email = htmlentities(trim($orderdata->collect_email));
        } else {
            $collect_email = '';
        }
        if (isset($orderdata->collect_phone) && !empty($orderdata->collect_phone)) {
            $collect_phone = htmlentities(trim($orderdata->collect_phone));
        } else {
            $error = true;
            $errors['collect_phone'] = "Invalid";
        }
        if (isset($orderdata->collect_date1) && !empty($orderdata->collect_date1)) {
            $collect_date1 = $orderdata->collect_date1;
            $collect_date_from = date("Y-m-d H:i:s", strtotime($collect_date1));
        } else {
            $error = true;
            $errors['collect_date1'] = "Invalid";
        }
        if (isset($orderdata->collect_date2) && !empty($orderdata->collect_date2)) {
            $collect_date2 = $orderdata->collect_date2;
            $collect_date_to = date("Y-m-d H:i:s", strtotime($collect_date2));
            if ($collect_date_to < $collect_date_from) {
                $error = true;
                $errors['collect_date2'] = "Must be greater than from date";
            }
        } else {
            $error = true;
            $errors['collect_date2'] = "Invalid";
        }

        $delivery_address = array();
        if (isset($orderdata->delivery_address_l1) && !empty($orderdata->delivery_address_l1)) {
            $delivery_address[] = htmlentities(trim(str_replace(",", " ", $orderdata->delivery_address_l1)));
        } else {
            $error = true;
            $errors['delivery_address_l1'] = "Invalid";
        }
        if (isset($orderdata->delivery_address_l2) && !empty($orderdata->delivery_address_l2)) {
            $delivery_address[] = htmlentities(str_replace(",", " ", trim($orderdata->delivery_address_l2)));
        } else {
            $error = true;
            $errors['delivery_address_l2'] = "Invalid";
        }
        if (isset($orderdata->delivery_zipcode) && !empty($orderdata->delivery_zipcode)) {
            $delivery_zipcode = htmlentities(trim($orderdata->delivery_zipcode));
            if (is_numeric($orderdata->delivery_zipcode)) {
                $delivery_zipcode = htmlentities(trim($orderdata->delivery_zipcode));
            } else {
                $error = true;
                $errors['delivery_zipcode'] = "Postal code must be integer";
            }
        } else {
            $delivery_zipcode = "";
        }
        if (isset($orderdata->delivery_country) && !empty($orderdata->delivery_country)) {
            $delivery_country = htmlentities(trim($orderdata->delivery_country));
        } else {
            $error = true;
            $errors['delivery_country'] = "Invalid";
        }
        if (isset($orderdata->is_d_restricted_area) && !empty($orderdata->is_d_restricted_area)) {
            $is_d_restricted_area = 1;
        } else {
            $is_d_restricted_area = 0;
        }
        if (isset($orderdata->deliver_date1) && !empty($orderdata->deliver_date1)) {
            $delivery_date1 = $orderdata->deliver_date1;
            $delivery_date_from = date("Y-m-d H:i:s", strtotime($delivery_date1));
        } else {
            $error = true;
            $errors['deliver_date1'] = "Invalid";
        }
        if (isset($orderdata->deliver_date2) && !empty($orderdata->deliver_date2)) {
            $delivery_date2 = $orderdata->deliver_date2;
            $delivery_date_to = date("Y-m-d H:i:s", strtotime($delivery_date2));
            if ($delivery_date_to < $delivery_date_from) {
                $error = true;
                $errors['deliver_date2'] = "Must be greater than from date";
            }
            if ($collect_date_from > $delivery_date_to) {
                $error = true;
                $errors['collect_date1'] = "Time must be before Delivery Time";
            }
        } else {
            $error = true;
            $errors['deliver_date2'] = "Invalid";
        }
        if (isset($orderdata->delivery_contactname) && !empty($orderdata->delivery_contactname)) {
            $delivery_contactname = htmlentities(trim($orderdata->delivery_contactname));
        } else {
            $error = true;
            $errors['delivery_contactname'] = "Invalid";
        }
        if (isset($orderdata->delivery_email) && !empty($orderdata->delivery_email)) {
            $delivery_email = htmlentities(trim($orderdata->delivery_email));
        } else {
            $delivery_email = '';
        }
        if (isset($orderdata->delivery_phone) && !empty($orderdata->delivery_phone)) {
            $delivery_phone = htmlentities(trim($orderdata->delivery_phone));
        } else {
            $error = true;
            $errors['delivery_phone'] = "Invalid";
        }

        if (isset($orderdata->collect_timezone) && !empty($orderdata->collect_timezone)) {
            $collect_timezone = $orderdata->collect_timezone->zoneinfo;
        } else {
            $error = true;
            $errors['collect_timezone'] = "Invalid";
        }
        if (isset($orderdata->delivery_timezone) && !empty($orderdata->delivery_timezone)) {
            $delivery_timezone = $orderdata->delivery_timezone->zoneinfo;
        } else {
            $error = true;
            $errors['delivery_timezone'] = "Invalid";
        }
        if (isset($orderdata->deadline) && !empty($orderdata->deadline)) {
            $deadline_date = date("Y-m-d H:i:s", strtotime($orderdata->deadline));
        } else {
            $error = true;
            $errors['deadline'] = "Invalid";
        }
        $status = C_GETTING_BID;

        if (!$error) {
            $order_data = array(
                'consignment_type_id' => $type,
                'is_bulk' => $is_bulk,
                'length' => $length,
                'breadth' => $breadth,
                'height' => $height,
                'volume' => $volume,
                'weight' => $weight,
                'collection_address' => json_encode($collect_address),
                'is_c_restricted_area' => $is_c_restricted_area,
                'collection_date' => $collect_date_from,
                'collection_date_to' => $collect_date_to,
                'collection_country' => $collect_country,
                'collection_timezone' => $collect_timezone,
                'delivery_address' => json_encode($delivery_address),
                'is_d_restricted_area' => $is_d_restricted_area,
                'delivery_post_code' => $delivery_zipcode,
                'delivery_country' => $delivery_country,
                'delivery_timezone' => $delivery_timezone,
                'delivery_date' => $delivery_date_from,
                'delivery_date_to' => $delivery_date_to,
                'delivery_contact_name' => $delivery_contactname,
                'delivery_contact_email' => $delivery_email,
                'delivery_contact_phone' => $delivery_phone,
                'collection_post_code' => $collection_zipcode,
                'collection_contact_name' => $collect_contactname,
                'collection_contact_number' => $collect_phone,
                'collection_contact_email' => $collect_email,
                'consignment_status_id' => $status,
                'bidding_deadline' => $deadline_date
            );
            if ($this->orders_model->updateOrder($order_data, $consignment_id)) {
                $insert_id = $consignment_id;
                $this->consignment_activity_log_model->add_activity(array(
                    "order_id" => $insert_id,
                    "activity" => "Order edited by you"
                ));
                $this->orders_model->cancel_bids($consignment_id);
                $this->send_mail_for_courier($insert_id, N_ORDER_CHANGED, 1);
                $result['msg'] = "Order updated successfully";
            }
            if ($insert_id) {
                $result['status'] = 1;
                $result['class'] = "alert-success";
                $result['id'] = $insert_id;
                $result['public_id'] = $public_id;
            } else {
                $result['status'] = 0;
                $result['class'] = "alert-danger";
                $result['msg'] = lang('try_again');
                $result['id'] = 0;
            }
        } else {
            $result['status'] = 0;
            $result['class'] = "alert-warning";
            $result['msg'] = lang('clear_error');
            $result['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    public function view_order($order_id) {
        $order_id = $this->orders_model->get_order_id($order_id);
        $data = array();
        $order = $this->orders_model->getDetails(array(
            'consignment_id' => $order_id
                ));
        if ($order) {
            $order->collection_address = implode(' ', json_decode($order->collection_address));
            $order->delivery_address = implode(' ', json_decode($order->delivery_address));
            if ($order->is_for_bidding) {
                $order->couriers = $this->orders_model->get_closed_biders($order_id);
            }
        }
        $data['order'] = $order;
        if ($pods = $this->consignment_pod_model->get_pods($order_id)) {
            $data['pods'] = array();
            foreach ($pods as $pod) {
                if ($pod->is_signature) {
                    $data['signature'] = $pod;
                } else {
                    $data['pods'][] = $pod;
                }
            }
        }
        $this->load->view('order_view', $data);
    }

    public function edit_order($order_id) {
        $data = array();
        $user = $this->session->userdata("user_id");
        $data['organisations'] = $this->organisation_model->myorganisations($user);
        $data['org_count'] = count($data['organisations']);
        $order_id = $this->orders_model->get_order_id($order_id);
        $tags = "";
        $ref = "";
        $order = $this->orders_model->getTags($order_id);
        if ($order) {
            $tags = $order->tags;
            $ref = $order->ref;
        }

        $data['tags'] = $tags;
        $data['ref'] = $ref;
        $this->load->view('edit_view', $data);
    }

    public function change_request($order_id = 0) {
        $data = array();
        $where = array(
            'C.consignment_id' => $order_id,
            'C.consignment_status_id' => C_GETTING_BID,
            'C.is_deleted' => 0
        );
        $data['order'] = $this->orders_model->getDetails($where);
        $this->load->view('order/change_request', $data);
    }

    public function consignment_types() {
        $result = array();
        $user_id = $this->session->userdata('user_id');
        $this->load->model("consignment_types_model");
        $organisations = $this->consignment_types_model->myorganisation($user_id);

        $result['types'] = $this->consignment_types_model->getMyTypeList($organisations);
        echo json_encode($result);
        exit();
    }

    public function countrylist() {
        $result = array();
        $this->load->model("account/ref_country_model");
        $countries = $this->ref_country_model->get_all();
        $index = 0;
        foreach ($countries as $key => $country) {
            if ($country->code == 'sg') {
                $index = $key;
                break;
            }
        }
        if ($index) {
            $singapore = $countries[$index];
            unset($countries[$index]);
            array_unshift($countries, $singapore);
        }
        $result['countries'] = $countries;
        echo json_encode($result);
        exit();
    }

    public function timezones() {
        $post_data = json_decode(file_get_contents('php://input'));
        if (isset($post_data->country)) {
            $country = htmlentities($post_data->country);
        } else {
            $country = NULL;
        }
        $result = array();
        if (is_null($country)) {
            $result['timezones'] = $this->ref_zoneinfo_model->get_all();
        } else {
            $result['timezones'] = $this->ref_zoneinfo_model->get_by_country($country);
        }
        echo json_encode($result);
        exit();
    }

    public function assigned_services() {
        $result = array();
        $this->load->model("app/services_model");
        $user = $this->session->userdata('user_id');
        $result['services'] = $this->services_model->get_services_assigned($user);
        echo json_encode($result);
        exit();
    }

    public function services_list() {
        $result = array();
        $this->load->model("app/services_model");
        $user = $this->session->userdata('user_id');
        $result['services'] = $this->services_model->get_services_all($user);
        echo json_encode($result);
        exit();
    }

    public function assigned_services_by_org() {
        $result = array();
        $org_id = -1; // independent of organisation
        $where = NULL;
        $type = 0;
        $term = '0000';
        $consignment_type = -1;
        // read input values
        $getdata = json_decode(file_get_contents('php://input'));
        // get the orgaisation filter if its assigned
        if (isset($getdata->org_id) && !empty($getdata->org_id)) {
            $org_id = (int) $getdata->org_id;
        }
        if (isset($getdata->type) && !empty($getdata->type)) {
            $type = (int) $getdata->type;
        }
        if (isset($getdata->term) && !empty($getdata->term)) {
            $term = $getdata->term;
        }
        if (isset($getdata->consignment_type) && $getdata->consignment_type != -1) {
            $consignment_type = (int) $getdata->consignment_type;
        }
        // filter services according to the collection time period
        if (isset($getdata->collection_time) && !empty($getdata->collection_time)) {
            $collection_time = $getdata->collection_time;
            $dates = explode('-', $collection_time);
            // collection time start
            $start_time = date("H:i:s", strtotime($dates[0]));
            // collection time end
            $end_time = date("H:i:s", strtotime($dates[1]));
            // find out collection beginning day
            $beginday = date("m/d/Y", strtotime($dates[0]));
            // fiond out collection ending day
            $lastday = date("m/d/Y", strtotime($dates[1]));

            // get the working days between collection period
            $work_days = $this->getWorkingDays($beginday, $lastday);
            // create a where clause if collection period is provided
            $where = "(( CS.start_time BETWEEN '" . $start_time . "' AND '" . $end_time . "' "
                    . " OR CS.end_time BETWEEN '$start_time' AND '" . $end_time . "'"
                    . " OR (CS.start_time <= '" . $start_time . "' AND CS.end_time >= '" . $end_time . "')"
                    . ")";
            foreach ($work_days as $value) {
                $where .= " AND CS.week_" . $value . "=1";
            }
            $where .= " )";
        }

        if (isset($getdata->delivery_time) && !empty($getdata->delivery_time)) {
            $delivery_time = $getdata->delivery_time;
            $ddates = explode('-', $delivery_time);
            $d_from = isset($ddates[0]) ? $ddates[0] : NULL;
            $d_to = isset($ddates[1]) ? $ddates[1] : NULL;
        } else {
            $d_to = NULL;
        }
        $delivery_timedata = explode("-", $delivery_time);
        $datetime1 = new DateTime($delivery_timedata[1]);
        $datetime2 = new DateTime($delivery_timedata[0]);
        $interval = $datetime1->diff($datetime2);
        $servicedelivery = array('90-minute');
        $hours = $interval->format('%H');
        $total = 2;

        if (intval($hours) >= 3) {
            $total = 6;
            $servicedelivery[] = '3-hour';
        }
        if (intval($hours) >= 6) {
            $total = 6;
            $servicedelivery[] = '6-hour';
        }

//            $datetime3 = date_create(date('Y-m-d'));
        $interval1 = $datetime1->diff($datetime2);
        $days = $interval1->format('%a');
        if (intval($days) >= 1 || $hours > 6) {
            if ($total < 3) {
                $servicedelivery[] = '3-hour';
            }
            if ($total < 6) {
                $servicedelivery[] = '6-hour';
            }
            $servicedelivery[] = 'next-day';
        }

        // filter services according to the collection country
        if (isset($getdata->c_country) && !empty($getdata->c_country)) {
            $c_country = htmlentities($getdata->c_country);
            if ($where !== NULL) {
                $where .= " AND (CS.origin LIKE '%" . $c_country . "%' )";
            } else {
                $where = "(CS.origin LIKE '%" . $c_country . "%' )";
            }
        }
        // filter services according to the destination
        if (isset($getdata->d_country) && !empty($getdata->d_country)) {
            $d_country = htmlentities($getdata->d_country);
            if ($where !== NULL) {
                $where .= " AND (CS.destination LIKE '%" . $d_country . "%' OR CS.destination LIKE '%all%')";
            } else {
                $where = "(CS.destination LIKE '%" . $d_country . "%' OR CS.destination LIKE '%all%')";
            }
        }
        $this->load->model("app/services_model");
        $user = $this->session->userdata('user_id');

        $status = true;
        if ($org_id != -1) {
            // get whether organisation allow use public services
            $status = $this->organisation_model->get_use_public_status($org_id);
        }

        if ($org_id != -1) {
            $opayments = $this->organisation_model->get_service_payment_terms($org_id);
        } else {
            $opayments = new stdClass();
            $opayments->payments = "0000";
        }
        $upayments = $this->account_details_model->get_service_payment_terms($user);
        if ($opayments && $upayments) {
            $payment = $opayments->payments | $upayments->payments;
            if ($term === "0000") {
                if ($payment !== "0000") {

                    $terms_array = str_split($payment);
                    $payment_str = "";
                    if ($terms_array[0] != '0') {
                        if ($payment_str != "") {
                            $payment_str.=" OR CS.payments LIKE '1___'";
                        } else {
                            $payment_str = "CS.payments LIKE '1___'";
                        }
                    }
                    if ($terms_array[1] != '0') {
                        if ($payment_str != "") {
                            $payment_str.=" OR CS.payments LIKE '_1__'";
                        } else {
                            $payment_str = "CS.payments LIKE '_1__'";
                        }
                    }
                    if ($terms_array[2] != '0') {
                        if ($payment_str != "") {
                            $payment_str.=" OR CS.payments LIKE '__1_'";
                        } else {
                            $payment_str = "CS.payments LIKE '__1_'";
                        }
                    }
                    if ($terms_array[3] != '0') {
                        if ($payment_str != "") {
                            $payment_str.=" OR CS.payments LIKE '___1'";
                        } else {
                            $payment_str = "CS.payments LIKE '___1'";
                        }
                    }
                    if ($payment_str != "") {
                        $payment_str = "(" . $payment_str . ")";
                    }
                    $payment_term = $payment_str;
                } else {
                    $payment_term = "CS.payments LIKE '____'";
                }
            } else {
                $payment_term = "CS.payments LIKE '" . $term . "'";
            }
            if ($where !== NULL && $payment_term != "") {
                $where .= " AND " . $payment_term;
            } else {
                $where .= " " . $payment_term;
            }
            $value = str_split($payment);
            $payment_options = array(array('value' => "0000", 'name' => 'All'));

            if ($value[3])
                $payment_options[] = array('value' => "___1", 'name' => lang('cash_sender'));
            if ($value[2])
                $payment_options[] = array('value' => "__1_", 'name' => lang('cash_recipient'));
            if ($value[1])
                $payment_options[] = array('value' => "_1__", 'name' => lang('credit_terms'));
            if ($value[0])
                $payment_options[] = array('value' => "1___", 'name' => lang('credit_terms_direct'));
        }

        $locations = array();
        $location = NULL;
        if (isset($getdata->location->loc)) {
            switch ($getdata->location->loc) {
                case 1:
                    $location = 8;
                    break;
                case 2:
                    $location = 4;
                    break;
                case 3:
                    $location = 2;
                    break;
                case 4:
                    $location = 1;
                    break;
                default :
                    break;
            }
        }

        $clocation = NULL;
        if (isset($getdata->location->clocation)) {
            switch ($getdata->location->clocation) {
                case 1:
                    $clocation = 8;
                    break;
                case 2:
                    $clocation = 4;
                    break;
                case 3:
                    $clocation = 2;
                    break;
                case 4:
                    $clocation = 1;
                    break;
                default :
                    break;
            }
        }
        $collect_back = null;
        if ($getdata->location->collect_back) {
            $collect_back = 16;
            $locations[] = 16;
        }
        if ($location != NULL) {
            $locations[] = $location;
            if ($clocation != '' && $location != $clocation) {
                $locations[] = $clocation;
            }
        } else {
            if ($clocation != '') {
                $locations[] = $clocation;
            }
        }


        // get the services list
        $services = $this->services_model->get_services_assigned($user, $org_id, $where, $status, $type, $consignment_type, $locations, $servicedelivery, $collect_back);
        $result_services = array();
        foreach ($services as $service) {
            $service->surcharge = $this->surcharge_items_model->get_items($service->service_id);
            $svalue = str_split($service->payments);
            $service->payment_terms = ($svalue[0] ? '<span class="credit-label">' . lang('credit_terms_direct') . '</span>' : '')
                    . ($svalue[1] ? '<span class="credit-label">' . lang('credit_terms') . '</span>' : '') .
                    ($svalue[3] ? '<span class="cash-label">' . lang('cash_sender') . '</span>' : '') .
                    ($svalue[2] ? '<span class="cash-label">' . lang('cash_recipient') . '</span>' : '');


            $s_options = array();

            if ($svalue[3])
                $s_options[] = array('id' => 0, 'value' => "___1", 'type' => lang('cash_sender'), 'name' => lang('cash_sender'));
            if ($svalue[2])
                $s_options[] = array('id' => 0, 'value' => "__1_", 'type' => lang('cash_recipient'), 'name' => lang('cash_recipient'));
            if ($svalue[1]) {
                if ($org_id != null) {
                    $approved_accounts = $this->payment_accounts_model->get_approved_accounts($user, 1);
                } else {
                    $approved_accounts = $this->payment_accounts_model->get_approved_accounts($org_id, 2);
                }
                foreach ($approved_accounts as $acc) {
                    $s_options[] = array('id' => $acc->id, 'type' => 'Credit Account', 'value' => "_1__", 'name' => $acc->account_name, 'credit' => $acc->credit);
                }
            }
            if ($svalue[0]) {
                if ($org_id != null) {
                    $approved_accounts = $this->payment_accounts_model->get_approved_accounts($user, 1);
                } else {
                    $approved_accounts = $this->payment_accounts_model->get_approved_accounts($org_id, 2);
                }
                foreach ($approved_accounts as $acc) {
                    $s_options[] = array('id' => $acc->id, 'type' => lang('credit_terms_direct'), 'value' => "_1__", 'name' => $acc->account_name, 'credit' => $acc->credit);
                }
            }

//                      $s_options[] = array('id' => 0, 'value' => "_1__", 'name' => lang('credit_terms_direct'));

            $service->payments = $s_options;
            $service->limit = 2;
            if ($service->time_to_deliver !== null) {
                $times = explode('--', $service->time_to_deliver);
                $str = '+ ' . round($times[0]) . ' days ' . '+ ' . round($times[1]) . ' hours ';
                $date = date('Y-m-d h:i:s', strtotime($str));
                $service->time_to_deliver = $date;
            }
            if ($d_to) {
                if (date('Y-m-d H:i:s', strtotime($d_to)) >= $service->time_to_deliver) {
                    $result_services[] = $service;
                }
            } else {
                $result_services[] = $service;
            }
        }

        $result['services'] = $result_services;
        // check whether selected organisation allow open bidding
        $result['open_bid'] = (bool) $this->organisation_model->is_open_bid($org_id);
        // check whether selected organisation use public services
        $result['use_public'] = $status;
        // return the number of pre-approved couriers
        $result['c_count'] = $this->pre_approved_bidders_model->get_bidders_list_count($org_id, NULL);
        // return the number of pre-approved couriers
        $result['payments'] = $payment_options;
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function getWorkingDays($startDate, $endDate) {
        $days = array();
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        if ($begin > $end) {

            return $days;
        } else {
            $no_days = 0;
            $weekends = 0;
            while ($begin <= $end) {
                $what_day = date("N", $begin);
                $days[] = ($what_day != 7) ? $what_day : 0;
                $begin += 86400; // +1 day
            }
            ;

            return $days;
        }
    }

    public function statusList() {
        $result = array();
        $result['status'] = $this->orders_model->get_statuslist();
        echo json_encode($result);
        exit();
    }

    public function teamList() {
        $ordersData = json_decode(file_get_contents('php://input'));
        $org = 0;
        if (isset($ordersData->organisation)) {
            $org = (int) $ordersData->organisation;
        }
        $result = array();
        $user = $this->session->userdata('user_id');
        $usr = $this->account_model->get_by_id($user);
        if ($usr->root) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $result['teams'] = $this->orders_model->get_teamlist($user, $flag, $org);
        echo json_encode($result);
        exit();
    }

    function generate_barcode($id) {
        $uploadPath = "./filebox/barcode";

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, TRUE);
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

            $uploadPath = "./filebox/ciqrcode";
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, TRUE);
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

    function mass_consignment_template() {
        $this->load->helper('download');
        if (file_exists("resource/templates/mass_consignment_vip_template.xlsx")) {
            $data = file_get_contents("resource/templates/mass_consignment_vip_template.xlsx"); // Read the file's contents
            $name = 'Mass delivery upload template.xlsx';
            force_download($name, $data);
        } else
            show_error('Template not found.');
    }

    public function get_converted_time() {
        $result = array();
        $post_data = json_decode(file_get_contents('php://input'));
        $error = FALSE;
        if (isset($post_data->time)) {
            $time = htmlentities($post_data->time);
        } else {
            $error = true;
        }
        if (isset($post_data->timezone)) {
            $timezone = htmlentities($post_data->timezone);
        } else {
            $error = true;
        }
        if (!$error) {
            $dates = explode('-', $time);
            $dfrom = convert_time($dates[0], $timezone);
            $dto = convert_time($dates[1], $timezone);
            $result['date'] = $dfrom . " - " . $dto;
            $result['start_date'] = date('d-M-y', strtotime($dfrom));
            $result['start_time'] = date('g:ia', strtotime($dfrom));
            $result['end_date'] = date('d-M-y', strtotime($dto));
            $result['end_time'] = date('g:ia', strtotime($dto));
            echo json_encode($result);
        }
        exit();
    }

    public function approve_price($public_id = 0, $approve = true) {
        $result = array();
        $job_id = $this->orders_model->get_order_id($public_id);
        if ($approve) {
            $price = $this->orders_model->get_price_change($job_id);
            $data = array(
                'price' => $price,
                'change_price' => NULL,
                'consignment_status_id' => C_PENDING,
                'is_confirmed' => 1
            );

            if ($this->orders_model->updateOrder($data, $job_id)) {
                $this->consignment_activity_log_model->add_activity(array(
                    "order_id" => $job_id,
                    "activity" => "Order status updated to - " . C_PENDING_NAME
                ));
                $this->send_mail_for_courier($job_id, N_PRICE_APPROVED, 1);
                $result['status'] = 1;
                $result['msg'] = "Price approved successfully";
                $result['class'] = "alert-success";
            } else {
                $result['status'] = 0;
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
            }
        } else {
            $data = array(
                'change_price' => NULL
            );

            $pricechange = $this->orders_model->get_price_change($job_id);
            $price = $this->orders_model->get_price($job_id);
            if ($this->orders_model->updateOrder($data, $job_id)) {
                $this->consignment_activity_log_model->add_activity(array(
                    "order_id" => $job_id,
                    "activity" => "Price Change( $" . $pricechange . " > $" . $price . ") request rejected by user"
                ));
                // $this->orders_model->cancel_bids($job_id);
                $this->send_mail_for_courier($job_id, N_PRICE_REJECTED, 1);
                $result['status'] = 1;
                $result['msg'] = "Price rejected successfully";
                $result['class'] = "alert-success";
            } else {
                $result['status'] = 0;
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
            }
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    function cancel_order() {
        $result = array();
        $orderdata = json_decode(file_get_contents('php://input'));
        if (isset($orderdata->consignment_id) && !empty($orderdata->consignment_id)) {
            $consignment_id = (int) $orderdata->consignment_id;
        } else {
            $consignment_id = 0;
        }
        $order = $this->orders_model->getDetails(array('consignment_id' => $consignment_id));
        if ($order->is_confirmed == 0) {
            $this->send_mail_for_courier($consignment_id, N_CANCEL_ORDER);
            $data = array(
                'consignment_status_id' => C_CANCELLED,
                'private_id' => '',
                'service_id' => 0,
                'price' => 0,
                'is_confirmed' => 0,
                'change_price' => NULL
            );
            if ($this->orders_model->updateOrder($data, $consignment_id)) {
                $this->consignment_activity_log_model->add_activity(array(
                    "order_id" => $consignment_id,
                    "activity" => "Order status updated to - " . C_CANCELLED_NAME
                ));
                $this->orders_model->cancel_bids($consignment_id);
                $result['status'] = 1;
                $result['msg'] = "Order Cancelled successfully";
                $result['class'] = "alert-success";
            } else {
                $result['status'] = 0;
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
            }
        } else {

            $data = array(
                'cancel_request' => 1,
                'requested_on' => date('Y-m-d H:i:s')
            );
            if ($this->orders_model->updateOrder($data, $consignment_id)) {
                $this->consignment_activity_log_model->add_activity(array(
                    "order_id" => $consignment_id,
                    "activity" => "Cancel request added "
                ));
                $couriers = $this->orders_model->get_courier($consignment_id);
                if ($couriers) {
                    $courier = $couriers[0];
                    $content = lang('order_cancel_request_email_content');
                    $link_title = lang('order_cancel_request_email_link_title');
                    $link = site_url('couriers/dashboard#/assigned_orders/view_order/' . $order->public_id);
                    $to = $courier->email;
                    $to_name = $courier->name;
                    $subject = lang('6connect_email_notification');
                    $message = array(
                        'title' => lang('order_cancel_request_email_title'),
                        'name' => $to_name,
                        'content' => $content,
                        'link_title' => $link_title,
                        'link' => $link
                    );
                    save_mail($to, $to_name, $subject, $message, 2);
                }
                $result['status'] = 1;
                $result['msg'] = "Order Cancel request added successfully";
                $result['class'] = "alert-success";
            } else {
                $result['status'] = 0;
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
            }
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

}

