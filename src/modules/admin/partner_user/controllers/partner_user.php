<?php

  class Partner_user extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('partner_user_model', 'orders/orders_model', 'orders/consignment_pod_model'));
            if (!$this->_is_partner_user()) {
                 redirect('partner_user/unauth');
            }
       }

       private function _is_partner_user() {
            $partner = $this->partner_user_model->is_partner_user();
            return $partner ? TRUE : FALSE;
       }

       public function index() {
            $this->load->view('index');
       }

       public function users() {
            $data = array();
            $this->load->view('users', $data);
       }

       public function userslist_json() {
            $partner = $this->partner_user_model->is_partner_user();
            $partner_id = $partner->partner_id;
            $perpage = '';
            $search = '';
            $membersData = json_decode(file_get_contents('php://input'));
            if (isset($membersData->perpage_value)) {

                 $perpage = $membersData->perpage_value;
            } else {
                 $perpage = 5;
            }
            if (isset($membersData->currentPage)) {

                 $page = $membersData->currentPage;
            } else {
                 $page = 1;
            }
            if (isset($membersData->filter)) {
                 if ($membersData->filter != NULL) {
                      $search = $membersData->filter;
                 } else {
                      $search = NULL;
                 }
            }
            $total_result = $this->partner_user_model->getuserslist_count($partner_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $result['member_detail'] = $this->partner_user_model->getuserslist($partner_id, $search, $perpage, $start);
            $result['end'] = (int) ($start + count($result['member_detail']));
            echo json_encode($result);
       }

       public function orders() {
            $data = array();
            $this->load->view('orders', $data);
       }

       public function orderslist_json() {
            $partner = $this->partner_user_model->is_partner_user();
            $partner_id = $partner->partner_id;
            $perpage = '';
            $search = NULL;
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
            if (isset($ordersData->filter)) {
                 if ($ordersData->filter != NULL) {
                      $search = $ordersData->filter;
                 }
            }
            if (isset($ordersData->status)) {
                 if ($ordersData->status != NULL) {
                      $status = $ordersData->status;
                 }
            }
            $exclude_status = array(C_DRAFT);
            $total_result = $this->partner_user_model->getorderslist_count($partner_id, $search, $status, $exclude_status);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;

            $order_detail = $this->partner_user_model->getorderslist_by_partnerid($partner_id, $perpage, $search, $start, $status, $exclude_status);
            foreach ($order_detail as $value) {
                 $value->collection_address = implode(' ', json_decode($value->collection_address));
                 $value->delivery_address = implode(' ', json_decode($value->delivery_address));
            }
            $result['order_detail'] = $order_detail;
            $result['end'] = (int) ($start + count($result['order_detail']));
            echo json_encode($result);
            exit();
       }

       public function view_order($order_id) {
            $order_id = $this->orders_model->get_order_id($order_id);
            $data = array();
            $order = $this->orders_model->getDetails(array('consignment_id' => $order_id));
            if ($order) {
                 $order->collection_address = implode(' ', json_decode($order->collection_address));
                 $order->delivery_address = implode(' ', json_decode($order->delivery_address));
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
            $this->load->view('view_order', $data);
       }

       public function export() {
            $data = array();
            $data['statuses'] = $this->orders_model->get_statuslist();
            $this->load->view('export', $data);
       }

       public function get_orders_csv() {
            $filename = "partner_orders_" . date('d_m_Y'); //without extension
            if ($this->input->post('group')) {
                 $group = (int) $this->input->post('group', TRUE);
            } else {
                 $group = NULL;
            }
            if ($this->input->post('groupname')) {
                 $groupname = ucfirst($this->input->post('groupname', TRUE));
            } else {
                 $groupname = "All Groups";
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
            if ($this->input->post('status')) {
                 $status = $this->input->post('status', TRUE);
            } else {
                 $status = array(C_DELIVERED);
            }
            $partner = $this->partner_user_model->is_partner_user();
            $partner_id = $partner->partner_id;

            $exportdata = array();
            $exportdata['title'] = "Transations from " . $start_date . " to " . $end_date;
            $exportdata['columns'] = array("Creation Date","Member Name","Member Email", "Organisation Name", "Organisation ID", "Collection Address 2", "Delivery Address 2", "Delivery Fee", "Delivery Status");
            $exportdata['entry'] = array();
            $orders = $this->partner_user_model->get_orders($start_date, $end_date, $partner_id, $status);
            foreach ($orders as $value) {
                 $collection = $delivery = "";
                 $collection_addr = json_decode($value->collection_address);
                 $collection = $collection . ' ' . $collection_addr[1];
                 $collection = $collection . ', ' . $value->from_country . ' ' . $value->collection_post_code;
                 $delivery_addr = json_decode($value->delivery_address);
                 $delivery = $delivery . ' ' . $delivery_addr[1];
                 $delivery = $delivery . ', ' . $value->to_country . ' ' . $value->delivery_post_code;

                 $exportdata['entry'][] = array(
                     'day' => date('Y-m-d', strtotime($value->created_date)),
                     'org_name' => $value->org_name,
                     'fullname'=>$value->fullname,
                     'email'=>$value->email,
                     'org_id' => $value->org_id,
                     'collection' => $collection,
                     'delivery' => $delivery,
                     'price' => "$" . $value->price,
                     'status' => $value->consignment_status,
                 );
            }
          // debug($exportdata['entry']);

            $this->load->library('Excel');
            $objPHPExcel = new Excel();
            $objPHPExcel->getProperties()->setTitle("title")->setDescription("description");
            // Assign cell values
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $exportdata['title']);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $exportdata['columns'][0]);
            $objPHPExcel->getActiveSheet()->setCellValue('B2', $exportdata['columns'][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('C2', $exportdata['columns'][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('D2', $exportdata['columns'][3]);
            $objPHPExcel->getActiveSheet()->setCellValue('E2', $exportdata['columns'][4]);
            $objPHPExcel->getActiveSheet()->setCellValue('F2', $exportdata['columns'][5]);
            $objPHPExcel->getActiveSheet()->setCellValue('G2', $exportdata['columns'][6]);
            $objPHPExcel->getActiveSheet()->setCellValue('H2', $exportdata['columns'][7]);
            $objPHPExcel->getActiveSheet()->setCellValue('I2', $exportdata['columns'][8]);

            $row = 3;
            foreach ($exportdata['entry'] as $value) {
                 $column = 0;
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value['day']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value['fullname']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value['email']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 3, $row, $value['org_name']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 4, $row, $value['org_id']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 5, $row, $value['collection']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 6, $row, $value['delivery']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 7,$row, $value['price']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 8, $row, $value['status']);

                 $row++;
            }
            foreach (range('A', 'I') as $columnID) {
                 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.csv"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->save('php://output');
            return;
       }

       public function unauth() {
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data)) {
                 $result = array();
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = "You are not authenticated to do this";
            } else {
                 $this->load->view('unauthorized');
            }
       }

  }
  