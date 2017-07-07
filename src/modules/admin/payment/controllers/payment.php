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
  class payment extends MY_Controller {

       //put your code here
       public function __construct() {
            parent::__construct();
            $this->load->model(
                    array(
                        'account/account_model',
                        'payment_model',
                        'app/organisation_model',
                        'credit/payment_accounts_model',
                        'account/account_details_model',
                    )
            );
       }

       public function index() {
            $this->load->view('payment_list');
            $account = $this->account_model->get_by_id($this->session->userdata('user_id'));
            if ($account->root != 1) {
                 if ($this->input->is_ajax_request()) {
                      exit;
                 } else {
                      redirect('');
                 }
            }
       }

       public function paymethod_json() {
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

            if (isset($orgData->holder_type)) {
                 if ($orgData->holder_type != NULL) {
                      $filter['holder_type'] = $orgData->holder_type;
                 } else {
                      $filter['holder_type'] = NULL;
                 }
            }

            if (isset($orgData->account_type)) {
                 if ($orgData->account_type != NULL) {
                      $filter['account_type'] = $orgData->account_type;
                 } else {
                      $filter['account_type'] = NULL;
                 }
            }

            if (isset($orgData->currentPage)) {

                 $page = $orgData->currentPage;
            } else {
                 $page = 1;
            }


            $total_result = $this->payment_model->getpaymentlist_count($filter);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['page'] = $page;
            $result['start'] = $start + 1;
            $organisation_detail = $this->payment_model->getpaymentlist($perpage, $filter, $start);
            $result['end'] = (int) ($start + count($organisation_detail));

            $result['payment_accounts'] = $organisation_detail;
            //exit(print_r($orgns_detail));

            echo json_encode($result, JSON_NUMERIC_CHECK);
       }

       function accept_payment() {
            $orgData = json_decode(file_get_contents('php://input'));
            if (isset($orgData->payment_id) && $orgData->payment_id != '') {
                 $update = array('status' => 2, 'credit' => 500);
                 $where = array('id' => $orgData->payment_id);
                 $this->payment_model->updateWhere($where, $update, 'payment_accounts');
                 $payment_accounts = $this->payment_model->getOneWhere($where, 'payment_accounts');
                 if ($payment_accounts->holder_type == 2) {
                      $payments = $this->organisation_model->get_service_payment_terms($payment_accounts->account_holder);
                      if ($payments) {
                           $value = str_split($payments->payments);
                           $dataval = ($value[3] * 1) + ($value[2] * 2) + 4 + ($value[0] * 8);
                           $data = array(
                               'payments' => sprintf('%04d', decbin($dataval))
                           );
                           $this->payment_model->updateWhere(array('id' => $payment_accounts->account_holder), $data, 'organizations');
                      }
                 } else if ($payment_accounts->holder_type == 1) {
                      $member_details = $this->payment_model->getOneWhere(array('user_id' => $payment_accounts->account_holder), 'member_details');
                      if ($member_details) {
                           $value = str_split($member_details->payments);
                           $dataval = ($value[3] * 1) + ($value[2] * 2) + 4 + ($value[0] * 8);
                           $data = array(
                               'payments' => sprintf('%04d', decbin($dataval))
                           );
                           $this->payment_model->updateWhere(array('user_id' => $payment_accounts->account_holder), $data, 'member_details');
                      }
                 }
            }
            echo json_encode(array('status' => 1));
       }

       function update($account_id) {
            $account = $this->payment_model->get_by_id($account_id);
            $data = array('account' => $account);
            $this->load->view('payment_update', $data);
       }

       function get_payment($id = 0) {
            $account = $this->payment_model->get_by_id($id);
            $data = array('accounts' => $account);
            echo json_encode($data);
       }

       function update_payment($id = 0) {
            $orgData = json_decode(file_get_contents('php://input'), TRUE);
            unset($orgData['id']);
            unset($orgData['added_on']);
            unset($orgData['approved_on']);
            $status = $this->payment_model->update_by_id($id, $orgData);
            echo json_encode(array('status' => $status));
       }

       function download() {
            $daterange = $this->input->post('daterange');
            $id = $this->input->post('id');
            $data = explode('-', $daterange);
            $from = isset($data[0]) ? date("Y-m-d H:i:s", strtotime($data[0])) : NULL;
            $to = isset($data[1]) ? date("Y-m-d H:i:s", strtotime($data[1] . "+ 1DAY")) : NULL;
            $transactions = $this->payment_model->getTransaction($id, $from, $to);

            $CI = & get_instance();
            $CI->load->library('Excel');
            $objPHPExcel = new Excel();
            $objPHPExcel->getProperties()->setTitle("title")->setDescription("description");
            // Assign cell values
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Payment Details');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Contact Name');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Email');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Amount');

            $row = 3;
            foreach ($transactions as $value) {
                 $column = 0;
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value->delivery_contact_name);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value->delivery_contact_email);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value->price);
                 $row++;
            }

            foreach (range('A', 'C') as $columnID) {
                 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Save it as an excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment; filename="Transactions.xlsx"');
            $objWriter->save('php://output');
            return;
       }

       function export_page($id) {
            $account = $this->payment_model->get_by_id($id);
            $data = array('accounts' => $account);
            $this->load->view('payment_export', $data);
       }

  }
  