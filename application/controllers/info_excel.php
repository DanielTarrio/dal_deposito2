<?php
//load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Users list');

        // load database
        $this->load->database();

        // load model
        $this->load->model('userModel');

        // get all users in array formate
        $users = $this->userModel->get_users();

        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($users);

        $filename='just_some_random_name.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
- See more at: https://arjunphp.com/how-to-use-phpexcel-with-codeigniter/#sthash.aeTWcVoJ.dpuf
?>