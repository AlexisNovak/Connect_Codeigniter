<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  if (!function_exists('export_as_excel')) {

       function export_as_excel($excelFilePath, $exportdata) {
            $CI = & get_instance();
            $CI->load->library('Excel');
            $objPHPExcel = new Excel();
            $objPHPExcel->getProperties()->setTitle("title")->setDescription("description");
          // Assign cell values
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $exportdata['title']);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $exportdata['columns'][0]);
            $objPHPExcel->getActiveSheet()->setCellValue('B2', $exportdata['columns'][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('C2', $exportdata['columns'][2]);

            $row = 3;
            foreach ($exportdata['entry'] as $key => $value) {
                 $column = 0;
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value['label']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value['success']);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column + 2, $row, $value['failed']);
                 $row++;
            }

            foreach (range('A', 'C') as $columnID) {
                 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Save it as an excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment; filename="' . $excelFilePath . '.xlsx"');
            $objWriter->save('php://output');
            return;
       }

  }
  if (!function_exists('export_as_pdf')) {

       function export_as_pdf($pdfFilePath, $exportdata) {
            $CI = & get_instance();
            $CI->load->library('Excel');
            $data['performance'] = $exportdata;
            $html = $CI->load->view('reports/print_userperformance', $data, true);
            //load mPDF library
            $CI->load->library('m_pdf');
            //actually, you can pass mPDF parameter on this load() function
            $pdf = $CI->m_pdf->load();
            //generate the PDF!
            $pdf->WriteHTML($html);
            //offer it to user via browser download! (The PDF won't be saved on your server HDD)
            $pdf->Output($pdfFilePath, "D");
            return;
       }

  }
