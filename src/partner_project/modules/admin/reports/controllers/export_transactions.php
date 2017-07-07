<?php

  class Export_transactions extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model(array('orders/orders_model'));
       }

       public function get_export_transactions_excel() {
            $filename = "export_transactions_" . date('d_m_Y'); //without extension
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
            $user_id = $this->session->userdata('partner_user_id');
            $exportdata = array();
            $exportdata['title'] = "Transations from " . $start_date . " to " . $end_date;
            $exportdata['columns'] = array("Day", "Collection Point", "Delivery location", "Payment Terms", "Price", "Courier Company", "Team", "User", "Status");
            $exportdata['entry'] = array();
            $orders = $this->orders_model->get_transatctions($start_date, $end_date, $user_id,$status);
            foreach ($orders as $value) {
                 $collection = $value->collection_contact_name;
                 $collection = $collection . ' ' . implode(" ", json_decode($value->collection_address));
                 $collection = $collection . ', ' . $value->from_country . ' ' . $value->collection_post_code;
                 $collection = $collection . ', ' . $value->collection_contact_number;
                 if ($value->collection_contact_email)
                      $collection = $collection . ', ' . $value->collection_contact_email;
                 $delivery = $value->delivery_contact_name;
                 $delivery = $delivery . ' ' . implode(" ", json_decode($value->delivery_address));
                 $delivery = $delivery . ', ' . $value->to_country . ' ' . $value->delivery_post_code;
                 $delivery = $delivery . ', ' . $value->delivery_contact_phone;
                 if ($value->delivery_contact_email)
                      $delivery = $delivery . ', ' . $value->delivery_contact_email;
                 $exportdata['entry'][] = array(
                     'day' => date('Y-m-d', strtotime($value->created_date)),
                     'collection' => $collection,
                     'delivery' => $delivery,
                     'payment' => "",
                     'price' => "$" . $value->price,
                     'courier' => $value->courier_name,
                     'team' => $value->team,
                     'user' => $value->username,
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
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value['collection']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value['delivery']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 3, $row, $value['payment']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 4, $row, $value['price']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 5, $row, $value['courier']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 6, $row, $value['team']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 7, $row, $value['user']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 8, $row, $value['status']);

                 $row++;
            }

            foreach (range('A', 'I') as $columnID) {
                 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Save it as an excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
            $objWriter->save('php://output');
            return;
       }

  }
  