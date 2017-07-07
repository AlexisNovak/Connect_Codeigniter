<?php

class Track extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->language('english');
        $this->load->model('tracking_model');
    }

    public function index($id = NULL) {
//        if ($this->tracking_model->get_tracking_status($id)) {
            $data = array();
//            $data['id'] = $id;
//            $org = $this->tracking_model->get_org($id);
//            if ($org) {
//                if (isset($org->tracking_intro) && $org->tracking_intro != NULL) {
//                    $desp = $org->tracking_intro;
//                } else {
//                    $desp = $org->description;
//                }
//                $data['name'] = $org->name;
//                $data['description'] = $desp;
//                $data['logo'] = $org->tracking_logo;
//            }
            $this->load->view('tracking', $data);
//        } else {
////            show_404();
//        }
    }

    public function orderslist() {
        $result = array();
        $ordersData = json_decode(file_get_contents('php://input'));
        if (isset($ordersData->ids)) {

            $ids = $ordersData->ids;
        } else {
            $ids = NULL;
        }
        if (isset($ordersData->org)) {

            $org = $ordersData->org;
        } else {
            $org = 0;
        }
        $d_list = preg_split("/[\s,]+/", $ids);
        $list = "";
        $i = 1;
        foreach ($d_list as $d) {
            if ($i < 26) {
                $list.="'$d',";
            }
            $i++;
        }
        $list = rtrim($list, ',');
        $orders = $this->tracking_model->get_orderslist($list, $org);
        foreach ($orders as $order) {
            $order->collection_address = implode(' ', json_decode($order->collection_address));
            $order->delivery_address = implode(' ', json_decode($order->delivery_address));
        }
        $result['orders'] = $orders;

        $result['total'] = count($orders);
        echo json_encode($result);
        exit();
    }

}
