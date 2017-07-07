<?php

  class Reports extends CourierController {

       function __construct() {
            parent::__construct();
            $this->load->model(array('invoice_model'));
       }

       public function index() {
            $data = array();
            $this->load->view('reports/reports_main', $data);
       }

       public function invoice() {
            $data = array();
            $courier_id = $this->session->userdata('courier_id');
            $data['users'] = $this->invoice_model->get_customers($courier_id);
            $data['organisations'] = $this->invoice_model->get_organisations($courier_id);
            $exclude_stat = array(C_DRAFT, C_GETTING_BID);
            $data['statuses'] = $this->invoice_model->get_statuslist($exclude_stat);
            //debug($data);
            $this->load->view('reports/invoice', $data);
       }

       public function generate_invoice() {
            $courier_id = $this->session->userdata('courier_id');
            $customers = NULL;
            $org_id = NULL;
            $pdf = 0;
            $excel = 0;
            $customerType = 0;
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
            if ($this->input->post('category')) {
                 $customerType = $this->input->post('category');
                 if ($customerType == 1) {
                      if ($this->input->post('customers')) {
                           $customers = $this->input->post('customers');
                      }
                 } else if ($customerType == 2) {
                      if ($this->input->post('org')) {
                           $org_id = $this->input->post('org');
                           $org_customers = $this->invoice_model->get_org_customers($org_id, $courier_id);
                           foreach ($org_customers as $custom) {
                                $customers[] = $custom->id;
                           }
                      }
                 }
            }
            if ($this->input->post('status')) {
                 $status = $this->input->post('status', TRUE);
            } else {
                 $status = array(C_DELIVERED);
            }
            if ($this->input->post('file_type')) {
                 $archiveType = $this->input->post('file_type');
                 $pdf = in_array('pdf', $archiveType) ? 1 : 0;
                 $excel = in_array('excel', $archiveType) ? 1 : 0;
            }
            $files = array();
            $createdDate = date('Y-m-d H:i:s');
            if ($customerType == 1) {
                 foreach ($customers as $customer) {
                      $totalInvoice = random_string('numeric', 6);
                      $invoiceNumber = str_replace(' ', 0, sprintf("%4d%2d-%2d", date('Y'), date('m'), $totalInvoice));

                      $filename = $customer . "_invoice_" . time(); //without extension            
                      $invoice_data = array(
                          'invoice_no' => $invoiceNumber,
                          'courier_id' => $courier_id,
                          'customer_id' => $customer,
                          'created_date' => $createdDate,
                          'is_deleted' => 0,
                          'type' => $customerType,
                          'from_date' => $start_date,
                          'to_date' => $end_date,
                          'file_name' => $filename,
                          'pdf' => $pdf,
                          'excel' => $excel
                      );
                      $this->invoice_model->add_invoice($invoice_data);
                      $exportdata = array();
                      $exportdata['title'] = "Invoice No : " . $invoiceNumber;
                      $exportdata['date'] = Date('Y-m-d');
                      $user = $this->invoice_model->get_user($customer);
                      $exportdata['user'] = $user->fullname . '(' . $user->email . ')';
                      $exportdata['columns'] = array("Date", "Consignment No.", "Organisation Name", "Collection Address", "Delivery Address", "Delivery Status", "Delivery Fee");
                      $exportdata['entry'] = array();
                      $orders = $this->invoice_model->get_orders($start_date, $end_date, $courier_id, $status, $org_id, array($customer));
                      foreach ($orders as $value) {
                           $collection = $delivery = "";
                           $collection_addr = json_decode($value->collection_address);
                           $collection = $collection . ' ' . $collection_addr[0] . ' ' . $collection_addr[1];
                           $collection = $collection . ', ' . $value->from_country . ' ' . $value->collection_post_code;
                           $delivery_addr = json_decode($value->delivery_address);
                           $delivery = $delivery . ' ' . $delivery_addr[0] . ' ' . $delivery_addr[1];
                           $delivery = $delivery . ', ' . $value->to_country . ' ' . $value->delivery_post_code;
                           $exportdata['entry'][] = array(
                               'day' => date('Y-m-d', strtotime($value->created_date)),
                               'consignment_no' => $value->public_id . " ",
                               'org_name' => $value->org_name ? $value->org_name : '-',
                               'collection' => $collection,
                               'delivery' => $delivery,
                               'price' => $value->price,
                               'status' => $value->consignment_status,
                           );
                      }
                      if ($pdf == 1) {
                           $path = "filebox/invoices/" . $courier_id . '/' . date('Y') . '/' . date('m') . "/pdf";
                           $this->create_pdf_file($exportdata, $path, $filename);
                           $files[] = $path . "/" . $filename . '.pdf';
                      } if ($excel == 1) {
                           $path = "filebox/invoices/" . $courier_id . '/' . date('Y') . '/' . date('m') . "/excel";
                           $this->create_excel_file($exportdata, $path, $filename);
                           $files[] = $path . "/" . $filename . '.xlsx';
                      }
                 }
            } else {
                 foreach ($org_id as $single_org) {
                      $totalInvoice = random_string('numeric', 6);
                      $invoiceNumber = str_replace(' ', 0, sprintf("%4d%2d-%2d", date('Y'), date('m'), $totalInvoice));

                      $filename = $single_org . "_invoice_" . time(); //without extension            
                      $invoice_data = array(
                          'invoice_no' => $invoiceNumber,
                          'courier_id' => $courier_id,
                          'customer_id' => $single_org,
                          'created_date' => $createdDate,
                          'is_deleted' => 0,
                          'type' => $customerType,
                          'from_date' => $start_date,
                          'to_date' => $end_date,
                          'file_name' => $filename,
                          'pdf' => $pdf,
                          'excel' => $excel
                      );
                      $this->invoice_model->add_invoice($invoice_data);
                      $exportdata = array();
                      $exportdata['title'] = "Invoice No : " . $invoiceNumber;
                      $exportdata['date'] = Date('Y-m-d');
                      $organisation = $this->invoice_model->get_org($single_org);
                      $exportdata['user'] = $organisation->name;
                      $exportdata['columns'] = array("Date", "Consignment No.", "User", "Collection Address", "Delivery Address", "Delivery Status", "Delivery Fee");
                      $exportdata['entry'] = array();
                      $orders = $this->invoice_model->get_orders($start_date, $end_date, $courier_id, $status, array($single_org), NULL);
                      foreach ($orders as $value) {
                           $collection = $delivery = "";
                           $collection_addr = json_decode($value->collection_address);
                           $collection = $collection . ' ' . $collection_addr[0] . ' ' . $collection_addr[1];
                           $collection = $collection . ', ' . $value->from_country . ' ' . $value->collection_post_code;
                           $delivery_addr = json_decode($value->delivery_address);
                           $delivery = $delivery . ' ' . $delivery_addr[0] . ' ' . $delivery_addr[1];
                           $delivery = $delivery . ', ' . $value->to_country . ' ' . $value->delivery_post_code;
                           $exportdata['entry'][] = array(
                               'day' => date('Y-m-d', strtotime($value->created_date)),
                               'consignment_no' => $value->public_id . " ",
                               'org_name' => $value->fullname . ' (' . $value->email . ')',
                               'collection' => $collection,
                               'delivery' => $delivery,
                               'price' => $value->price,
                               'status' => $value->consignment_status,
                           );
                      }
                      if ($pdf == 1) {
                           $path = "filebox/invoices/" . $courier_id . '/' . date('Y') . '/' . date('m') . "/pdf";
                           $this->create_pdf_file($exportdata, $path, $filename);
                           $files[] = $path . "/" . $filename . '.pdf';
                      } if ($excel == 1) {
                           $path = "filebox/invoices/" . $courier_id . '/' . date('Y') . '/' . date('m') . "/excel";
                           $this->create_excel_file($exportdata, $path, $filename);
                           $files[] = $path . "/" . $filename . '.xlsx';
                      }
                 }
            }
            if (!empty($files)) {
                 $this->load->library('zip');
                 $currentTime = date('Ymd_Gis', time());
                 $zipfile = $currentTime . '.zip';
                 foreach ($files as $v) {
                      $this->zip->read_file($v);
                 }
                 $uploadPath = "filebox/invoices" . "/" . $courier_id . "/" . date('Y') . "/" . date('m') . "/zip";
                 if (!file_exists($uploadPath)) {
                      mkdir($uploadPath, 0777, TRUE);
                 }
                 $this->zip->archive($uploadPath . "/" . $zipfile);
                 $this->zip->download($zipfile);
                 $this->zip->clear_data();
            }
       }

       public function create_excel_file($exportdata, $uploadPath, $filename) {
            $this->load->library('Excel');
            $objPHPExcel = new Excel();
            $objPHPExcel->getProperties()->setTitle("title")->setDescription("description");
            // Assign cell values
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $exportdata['title']);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $row = 3;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Date');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $exportdata['date']);
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $row . ':G' . $row);
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Bill To');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $exportdata['user']);
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $row . ':G' . $row);
            $row++;
            $row++;
            $column = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $exportdata['columns'][0]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $exportdata['columns'][1]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $exportdata['columns'][2]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 3, $row, $exportdata['columns'][3]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 4, $row, $exportdata['columns'][4]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 5, $row, $exportdata['columns'][5]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 6, $row, $exportdata['columns'][6]);
            $row++;
            $total = 0;
            foreach ($exportdata['entry'] as $value) {
                 $column = 0;
                 $total+=$value['price'];
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value['day']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value['consignment_no']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value['org_name']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 3, $row, $value['collection']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 4, $row, $value['delivery']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 5, $row, $value['status']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 6, $row, "$" . $value['price']);

                 $row++;
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Total');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':F' . $row);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, "$" . $total);
            foreach (range('A', 'G') as $columnID) {
                 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="' . $filename);
//            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            $objWriter->save($uploadPath . "/" . $filename . '.xlsx');
            // $objWriter->save('php://output');
            return;
       }

       public function create_pdf_file($exportdata, $uploadPath, $filename) {
            $data['invoice'] = $exportdata;
            $html = $this->load->view('reports/invoice_pdf', $data, true);
            //load mPDF library
            $this->load->library('m_pdf');
            //actually, you can pass mPDF parameter on this load() function
            $pdf = $this->m_pdf->load();
            //generate the PDF!
            $pdf->WriteHTML($html);
            //offer it to user via browser download! (The PDF won't be saved on your server HDD)
            if (!file_exists($uploadPath)) {
                 mkdir($uploadPath, 0777, TRUE);
            }
            $pdf->Output($uploadPath . '/' . $filename . '.pdf', "F");
            // $pdf->Output($filename, "D");
            return;
       }

       public function invoicelist() {
            $this->load->view('reports/invoicelist');
       }

       public function invoiceslist_json() {
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

            if (isset($ordersData->filter)) {
                 if ($ordersData->filter != NULL) {
                      $search = $ordersData->filter;
                 }
            }

            $courier_id = $this->session->userdata('courier_id');
            $total_result = $this->invoice_model->getinvoicelist_count_for_courier($courier_id, $search);
            $lastpage = ceil($total_result / $perpage);
            if ($page > $lastpage) {
                 $page = 1;
            }
            $start = ($page - 1) * $perpage;
            $result['total'] = $total_result;
            $result['start'] = $start + 1;
            $result['page'] = $page;
            $invoices = $this->invoice_model->getinvoicelist_for_courier($courier_id, $search, $perpage, $start);
            $invoicelist = array();
            foreach ($invoices as $invoice) {
                 if ($invoice->pdf == 1) {
                      $path = 'filebox/invoices/' . $invoice->courier_id . '/' . date('Y', strtotime($invoice->created_date)) . '/' . date('m', strtotime($invoice->created_date)) . '/pdf/' . $invoice->file_name . '.pdf';
                      if (file_exists($path)) {
                           $pdflink = base_url($path);
                      } else {
                           $pdflink = "";
                      }
                 } else {
                      $pdflink = "";
                 }
                 if ($invoice->excel == 1) {
                      $path = 'filebox/invoices/' . $invoice->courier_id . '/' . date('Y', strtotime($invoice->created_date)) . '/' . date('m', strtotime($invoice->created_date)) . '/excel/' . $invoice->file_name . '.xlsx';
                      if (file_exists($path)) {
                           $excellink = base_url($path);
                      } else {
                           $excellink = "";
                      }
                 } else {
                      $excellink = "";
                 }
                 $invoicelist[] = array(
                     'id' => $invoice->id,
                     'invoice_date' => date('d-m-Y', strtotime($invoice->created_date)),
                     'customer' => ($invoice->type == 1) ? $invoice->username . ' (' . $invoice->email . ')' : $invoice->org_name,
                     'period' => date('d-m-Y', strtotime($invoice->from_date)) . ' - ' . date('d-m-Y', strtotime($invoice->to_date)),
                     'pdflink' => $pdflink,
                     'excellink' => $excellink,
                     'is_deleted' => $invoice->is_deleted,
                     "filename" => $invoice->file_name,
                     "pdf" => $invoice->pdf,
                     "excel" => $invoice->excel
                 );
            }
            $result['invoices'] = $invoicelist;
            $result['end'] = (int) ($start + count($result['invoices']));
            echo json_encode($result);
            exit();
       }

       public function delete_invoice() {
            $result = array();
            $courier_id = $this->session->userdata('courier_id');
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = (int) $post_data->id;
            } else {
                 $id = 0;
            }
            $invoice = $this->invoice_model->get_invoive($id);
            if ($invoice) {
                 if ($invoice->pdf == 1) {
                      $path = 'filebox/invoices/' . $invoice->courier_id . '/' . date('Y', strtotime($invoice->created_date)) . '/' . date('m', strtotime($invoice->created_date)) . '/pdf/' . $invoice->customer_id . '/' . $invoice->file_name . '.pdf';
                      if (file_exists($path)) {
                           unlink($path);
                      }
                 }
                 if ($invoice->excel == 1) {
                      $path = 'filebox/invoices/' . $invoice->courier_id . '/' . date('Y', strtotime($invoice->created_date)) . '/' . date('m', strtotime($invoice->created_date)) . '/excel/' . $invoice->customer_id . '/' . $invoice->file_name . '.xlsx';
                      if (file_exists($path)) {
                           unlink($path);
                      }
                 }
                 $update_data = array('is_deleted' => 1, 'deleted_date' => date('Y-m-d H:i:s'), 'deleted_user_id' => $courier_id);
                 if ($this->invoice_model->update_invoice($update_data, $id)) {
                      $result['status'] = 1;
                      $result['class'] = "alert-success";
                      $result['msg'] = "Invoice deleted successfully.";
                 } else {
                      $result['status'] = 0;
                      $result['class'] = "alert-danger";
                      $result['msg'] = lang('try_again');
                 }
            } else {
                 $result['status'] = 0;
                 $result['class'] = "alert-danger";
                 $result['msg'] = lang('try_again');
            }
            echo json_encode($result, JSON_NUMERIC_CHECK);
            exit();
       }

  }

?>