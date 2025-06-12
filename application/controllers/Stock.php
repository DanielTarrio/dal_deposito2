<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	function rep_movimiento(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['app']='movimientos';
			$this->load->view('head');
			$this->load->view('reportes/movimientos_view',$data);
		}
   	}

   	function get_movimientos(){

   		//$usr=$this->control->control_login();
	    $id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      $data=$this->stock_model->rep_movimientos($id_deposito,$F_inicio, $F_fin);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}
   	function excel_movimientos(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $data=$this->stock_model->rep_movimientos($id_deposito,$F_inicio, $F_fin);
	    //-------------------------------------------
	    $i=0;

	    $hoja[$i][0]="Movimientos deposito ".$data[0]['deposito']." desde ".$F_inicio." hasta ".$F_fin;
	    reset($data);
	    $i++;
	    $hoja[$i][0]="";
	    $i++;
	    foreach ($data[0] as $v => $d) {
	    	$hoja[$i][$v]=$v;
		}
		reset($data);
		$i++;
	    foreach ($data as $key => $value) {
	    	$hoja[$i]=$value;
	    	$i++;
	    }
	    //-------------------------------------------
	  
	    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Deposito_".$id_deposito."_".$this->control->ParseDMYtoYMD($F_inicio,'/')."_".$this->control->ParseDMYtoYMD($F_fin,'/');
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}

   	//================================================

	function pdf_movimientos(){

   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $dato=$this->stock_model->rep_movimientos($id_deposito,$F_inicio, $F_fin);

	   	$this->load->library('Pdf');
	    global $titulo;
	    global $id_deposito;
	    global $deposito;

	    foreach ($dato as $key => $value) {

			$id_deposito=$value['id_deposito'];
			$deposito=$value['deposito'];
			$id_material=$value['id_material'];
			$descripcion=$value['descripcion'];
			$cantidad=$value['cantidad'];

	    }

	    $titulo=utf8_decode("Movimiento Deposito ".$deposito." desde ".$F_inicio." hasta ".$F_fin);
	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_nro="";

	    $pdf->SetFont('Arial','B',8);
        $pdf->Cell(20,6,'Tipo',1,0,'C');
        $pdf->Cell(20,6,'Fecha',1,0,'C');
        $pdf->Cell(70,6,'Destino/Origen',1,0,'C');
        $pdf->Cell(20,6,'Ped./OC',1,0,'C');
        $pdf->Cell(20,6,'Vale/Rto.',1,0,'C');
        $pdf->Cell(20,6,'Codigo',1,0,'C');
        $pdf->Cell(70,6,'Descripcion',1,0,'C');
        $pdf->Cell(20,6,'Unidad',1,0,'C');
        $pdf->Cell(20,6,'Cantidad',1,0,'C');
        $pdf->ln(6);

	    foreach ($dato as $key => $value) {

	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['tipo']).$i,1,0,'C');
	        $pdf->Cell(20,6,utf8_decode($value['fecha']).$i,1,0,'C');
	        if($value['movimiento']=='S'){
	        	$pdf->Cell(70,6,utf8_decode($value['Zona'].'-'.$value['sector']).$i,1,0,'L');
	        	$pdf->Cell(20,6,utf8_decode($value['nro']).$i,1,0,'C');
	        	$pdf->Cell(20,6,utf8_decode($value['nro_pedido']).$i,1,0,'C');
	        }else{
	        	$pdf->Cell(70,6,utf8_decode($value['proveedor']).$i,1,0,'L');
	        	$pdf->Cell(20,6,utf8_decode($value['id_compra']).$i,1,0,'C');
	        	$pdf->Cell(20,6,utf8_decode($value['remito']).$i,1,0,'C');
	        }
	        $pdf->Cell(20,6,utf8_decode($value['barcode']).$i,1,0,'C');
	        $pdf->Cell(70,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	      
	      	$pdf->ln(6);
	    }

	    $pdf->Output();
   	}

   	//================================================

   	function rep_stock_deposito(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['app']='stock';
			$this->load->view('head');
			$this->load->view('reportes/stock_view',$data);
		}
   	}

   	function get_stock_deposito(){
   		//$usr=$this->control->control_login();
	    $id_deposito = $this->input->get('id_deposito');
	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      $data=$this->stock_model->get_stock_deposito($id_deposito);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}

   	function get_material_deposito(){
   		
   		$id_deposito=$this->input->get('id_deposito');
	    $data = array(
	    	'id_deposito' => $id_deposito,
		    'id_clase' => $this->input->get('id_clase'),
		   	'id_sub_clase' => $this->input->get('id_sub_clase'),
		    'id_material' => $this->input->get('id_material'),
		    'material' => $this->input->get('material'),
		    'barcode' => $this->input->get('barcode')
			);

	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      $data=$this->stock_model->get_material_deposito($data);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}

   	function excel_stock(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
	    $data=$this->stock_model->get_stock_deposito($id_deposito);
	    $i=0;

	    $hoja[0][0]=$data[0]['deposito'];
	    reset($data);
	    $i++;
	    foreach ($data[0] as $v => $d) {
	    	$hoja[$i][$v]=$v;
		}
		reset($data);
		$i++;
	    foreach ($data as $key => $value) {
	    	$hoja[$i]=$value;
	    	$i++;
	    }

	    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Deposito_".$id_deposito;
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}

   	//------------------------

	function pdf_stock(){

   		$this->load->library('Pdf_pedido');
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
	    $dato=$this->stock_model->get_stock_deposito($id_deposito);
	    $i=0;

	   	
	    global $titulo;
	    global $id_deposito;
	    global $deposito;

	    foreach ($dato as $key => $value) {

			//$id_deposito=$value['id_deposito'];
			$deposito=$value['deposito'];

	    }

	    $titulo=utf8_decode("Stock Deposito ".$deposito." al ".date("d/m/Y"));
	    $pdf=new Pdf_pedido();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('P');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_nro="";

	    $pdf->SetFont('Arial','B',8);
        $pdf->Cell(20,6,'Codigo',1,0,'C');
        $pdf->Cell(130,6,'Descripcion',1,0,'C');
        $pdf->Cell(20,6,'Unidad',1,0,'C');
        $pdf->Cell(20,6,'Cantidad',1,0,'C');
        $pdf->ln(6);

	    foreach ($dato as $key => $value) {

	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['barcode']).$i,1,0,'C');
	        $pdf->Cell(130,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	      
	      	$pdf->ln(6);
	    }

	    $pdf->Output();


   	}

   	//------------------------

   	function rep_consumo(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['app']='consumos';
			$this->load->view('head');
			$this->load->view('reportes/consumos_view',$data);
		}
   		
   	}

   	function get_consumo(){

   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $id_material = $this->input->get('id_material');
        $material = $this->input->get('material');
        $barcode = $this->input->get('barcode');
	    $F_inicio = $this->control->ParseDMYtoYMD($F_inicio,'/');
	    $F_fin = $this->control->ParseDMYtoYMD($F_fin,'/');


	    $dato = array(
		    	'id_deposito' => $id_deposito,
		    	'F_inicio' => $F_inicio,
			    'F_fin' => $F_fin,
			    'id_material' => $id_material,
				'material'=> $material,
				'barcode'=> $barcode
	    	);


	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      //$data=$this->stock_model->rep_consumo($id_deposito,$F_inicio, $F_fin, $legajo);
	      $data=$this->stock_model->rep_consumo($dato);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}

   	function excel_consumo(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');

   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    //$legajo = $this->input->get('legajo');
	    $id_material = $this->input->get('id_material');
        $material = $this->input->get('material');
        $barcode = $this->input->get('barcode');
	    $F_inicio = $this->control->ParseDMYtoYMD($F_inicio,'/');
	    $F_fin = $this->control->ParseDMYtoYMD($F_fin,'/');


	    $dato = array(
		    	'id_deposito' => $id_deposito,
		    	'F_inicio' => $F_inicio,
			    'F_fin' => $F_fin,
			//    'legajo' => $legajo,
			    'id_material' => $id_material,
				'material'=> $material,
				'barcode'=> $barcode
	    	);

	    $data=$this->stock_model->rep_consumo($dato);
	    $i=0;

	    $hoja[0][0]='Deposito:';
	    $hoja[0][1]=$data[0]['deposito'];
	    $hoja[1][0]='Inicio';
	    $hoja[2][0]='Fin';
	    $hoja[1][1]=$this->control->ParseDMYtoYMD($F_inicio,'/');
	    $hoja[2][1]=$this->control->ParseDMYtoYMD($F_fin,'/');
	  //  $hoja[3][0]='Legajo:';
	  //  $hoja[3][1]=$data[0]['retira'];
	  //  $hoja[3][2]=$data[0]['apellido_nombre'];
	    $hoja[4][0]='';
	    reset($data);
	    $i=5;
	    $i++;
	    foreach ($data[0] as $v => $d) {
	    	$hoja[$i][$v]=$v;
		}
		reset($data);
		$i++;
	    foreach ($data as $key => $value) {
	    	$hoja[$i]=$value;
	    	$i++;
	    }


	    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Deposito_".$id_deposito;
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}

   	function reporte_anual(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['year']=$this->contable_model->get_YearMovimientos();
	   		$data['app']='consumos anuales';
			$this->load->view('head');
			$this->load->view('reportes/consumo_anual_view',$data);
		}

   	}

   	function anual_x_mes(){
   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
   		$reporte = $this->input->get('reporte');
   		$year_rpte = $this->input->get('year_rpte');

   		$data=$this->stock_model->consumo_anual($id_deposito,$reporte,$year_rpte);

   		$this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;

   	}

   	function anual_x_mes_excel(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
   		$reporte = $this->input->get('reporte');
   		$year_rpte = $this->input->get('year_rpte');

   		$data=$this->stock_model->consumo_anual($id_deposito,$reporte,$year_rpte);

   		$i=0;

	    $hoja[0][0]='Deposito:';
	    $hoja[0][1]=$data[0]['deposito'];
	    $hoja[1][0]='Año:';
	    $hoja[1][1]=$year_rpte;
	    $hoja[4][0]='Deposito';
	    $hoja[4][1]='Codigo';
	    $hoja[4][2]='Descripción';
	    $hoja[4][3]='Und.';
	    $hoja[4][4]='Enero';
	    $hoja[4][5]='Febrero';
	    $hoja[4][6]='Marzo';
	    $hoja[4][7]='Abril';
	    $hoja[4][8]='Mayo';
	    $hoja[4][9]='Junio';
	    $hoja[4][10]='Julio';
	    $hoja[4][11]='Agosto';
	    $hoja[4][12]='Septiembre';
	    $hoja[4][13]='Octubre';
	    $hoja[4][14]='Noviembre';
	    $hoja[4][15]='Diciembre';
	    $fila=5;

	    foreach ($data as $key => $value) {
	    	$hoja[$fila]=$value;
	    	$fila++;
		}

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="InfoAnual";
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');

   	}


   	function imp_consumo(){
   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $id_material = $this->input->get('id_material');
        $material = $this->input->get('material');
        $barcode = $this->input->get('barcode');
	    $F_inicio = $this->control->ParseDMYtoYMD($F_inicio,'/');
	    $F_fin = $this->control->ParseDMYtoYMD($F_fin,'/');


	    $dato = array(
		    	'id_deposito' => $id_deposito,
		    	'F_inicio' => $F_inicio,
			    'F_fin' => $F_fin,
			    'id_material' => $id_material,
				'material'=> $material,
				'barcode'=> $barcode
	    	);


	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      //$data=$this->stock_model->rep_consumo($id_deposito,$F_inicio, $F_fin, $legajo);
	      $dato=$this->stock_model->rep_consumo($dato);
	      
	      $this->load->library('Pdf');
	    global $titulo;
	    global $id_deposito;
	    global $deposito;

	    foreach ($dato as $key => $value) {

	    	$deposito=$value['deposito'];
	    	$fecha=$value['fecha'];
	    	$tipo=$value['tipo'];
	    	$nro=$value['nro'];
	    	$id_material=$value['id_material'];
	    	$barcode=$value['barcode'];
	    	$descripcion=$value['descripcion'];
	    	$unidad=$value['unidad'];
	    	$cantidad=$value['cantidad'];
	    	$mod_usuario=$value['mod_usuario'];

	    }

	    $titulo=utf8_decode("Deposito ".$deposito." Material Consumido desde ".$this->control->ParseDMYtoYMD($F_inicio,'/')." hasta ".$this->control->ParseDMYtoYMD($F_fin,'/'));
	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_nro="";

	    $pdf->ln(6);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(15,6,'Fecha',1,0,'C');
        $pdf->Cell(35,6,'Tipo',1,0,'C');
        $pdf->Cell(15,6,'Nro.',1,0,'C');
        $pdf->Cell(15,6,'Codigo',1,0,'C');
        $pdf->Cell(160,6,'Descripcion',1,0,'C');
        $pdf->Cell(20,6,'Unidad',1,0,'C');
        $pdf->Cell(20,6,'Cantidad',1,1,'C');

	    foreach ($dato as $key => $value) {

	     
	        /*$pdf->SetFont('Arial','B',10);
	        $pdf->SetFillColor(230,230,0);
	        $pdf->Cell(280,6,'Deposito: '.$value['deposito'],1,0,'C',true);
	        $tmp_nro=$value['id_deposito'];*/
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(15,6,utf8_decode($value['fecha']).$i,1,0,'C');
	        $pdf->Cell(35,6,utf8_decode($value['tipo']).$i,1,0,'L');
	        $pdf->Cell(15,6,utf8_decode($value['nro']).$i,1,0,'C');
	        $pdf->Cell(15,6,utf8_decode($value['barcode']).$i,1,0,'C');
	        $pdf->Cell(160,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,1,'R');
	      	
	    }
	    $pdf->Output();



	    }
   	}

   	function excel_reposicion(){

   		$login=$this->control->control_login();
		if($login==TRUE){

			$usr=$this->session->userdata('usr');
	

	   		$this->load->library('excel');
	   		$this->excel->setActiveSheetIndex(0);
	   		$this->load->model('stock_model');

		    $data=$this->stock_model->rep_reposicion($usr);
		    $i=0;
		    $hoja[$i][0]='Reporte de Elementos de Reposición Inmediata '.date("d/m/Y");
		    $i=3;
		    $hoja[$i][0]='';
		    
		    $i++;
		    foreach ($data[0] as $v => $d) {
		    	$hoja[$i][$v]=$v;
			}
			reset($data);
			$i++;
		    foreach ($data as $key => $value) {
		    	$hoja[$i]=$value;
		    	$i++;
		    }


		    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

		    $this->excel->getActiveSheet()->fromArray($hoja);
		    $tmp=date("Ymd");
		    $filname="Alerta_".$tmp;
		    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
		    header('Cache-Control: max-age=0');
		    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
		    $objWriter->save('php://output');
		}
   	}

	function excel_minimo(){

   		$login=$this->control->control_login();
		if($login==TRUE){

			$usr=$this->session->userdata('usr');
	

	   		$this->load->library('excel');
	   		$this->excel->setActiveSheetIndex(0);
	   		$this->load->model('stock_model');

		    $data=$this->stock_model->rep_minimo($usr);
		    $i=0;
		    $hoja[$i][0]='Reporte de Elementos Cantidad debajo del Minimo '.date("d/m/Y");
		    $i=3;
		    $hoja[$i][0]='';
		    
		    $i++;
		    foreach ($data[0] as $v => $d) {
		    	$hoja[$i][$v]=$v;
			}
			reset($data);
			$i++;
		    foreach ($data as $key => $value) {
		    	$hoja[$i]=$value;
		    	$i++;
		    }


		    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

		    $this->excel->getActiveSheet()->fromArray($hoja);
		    $tmp=date("Ymd");
		    $filname="Alerta_".$tmp;
		    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
		    header('Cache-Control: max-age=0');
		    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
		    $objWriter->save('php://output');
		}
   	}

   	function reporte_reposicion(){

   		$login=$this->control->control_login();
		if($login==TRUE){

			$usr=$this->session->userdata('usr');
	   		$this->load->model('stock_model');
	   		$dato=$this->stock_model->rep_reposicion($usr);





	   	}

	    $this->load->library('Pdf');
	    global $titulo;
	    global $id_deposito;
	    global $deposito;

	    foreach ($dato as $key => $value) {

	      $id_deposito=$value['id_deposito'];
	      $deposito=$value['deposito'];
	      $id_material=$value['id_material'];
	      $descripcion=$value['descripcion'];
	      $cantidad=$value['cantidad'];
	      $minimo=$value['minimo'];
	      $reposicion=$value['reposicion'];

	    }

	    $titulo=utf8_decode("Material Reposición Inmediata al ".date('d/m/Y'));
	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_nro="";

	    foreach ($dato as $key => $value) {

	      if($tmp_nro==$value['id_deposito']){
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
	        $pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['minimo'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['reposicion'],2),1,0,'R');
	        
	      }else{
	        $pdf->SetFont('Arial','B',10);
	        $pdf->SetFillColor(230,230,0);
	        $pdf->Cell(280,6,'Deposito: '.$value['deposito'],1,0,'C',true);
	        $tmp_nro=$value['id_deposito'];
	        $pdf->ln(6);
	        $pdf->SetFont('Arial','B',8);
	        $pdf->Cell(20,6,'Codigo',1,0,'C');
	        $pdf->Cell(180,6,'Descripcion',1,0,'C');
	        $pdf->Cell(20,6,'Unidad',1,0,'C');
	        $pdf->Cell(20,6,'Cantidad',1,0,'C');
	        $pdf->Cell(20,6,utf8_decode('Mínimo'),1,0,'C');
	        $pdf->Cell(20,6,utf8_decode('Reposición'),1,0,'C');
	        $pdf->ln(6);
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
	        $pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['minimo'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['reposicion'],2),1,0,'R');
	      }
	      $pdf->ln(6);
	    }
	    $pdf->Output();
	}

	function reporte_minimo(){

   		$login=$this->control->control_login();
		if($login==TRUE){

			$usr=$this->session->userdata('usr');
	   		$this->load->model('stock_model');
	   		$dato=$this->stock_model->rep_minimo($usr);





	   	}

	    $this->load->library('Pdf');
	    global $titulo;
	    global $id_deposito;
	    global $deposito;

	    foreach ($dato as $key => $value) {

	      $id_deposito=$value['id_deposito'];
	      $deposito=$value['deposito'];
	      $id_material=$value['id_material'];
	      $descripcion=$value['descripcion'];
	      $cantidad=$value['cantidad'];
	      $minimo=$value['minimo'];
	      $reposicion=$value['reposicion'];

	    }

	    $titulo=utf8_decode("Material por debajo del Mínimo Deseado al ".date('d/m/Y'));
	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_nro="";

	    foreach ($dato as $key => $value) {

	      if($tmp_nro==$value['id_deposito']){
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
	        $pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['minimo'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['reposicion'],2),1,0,'R');
	        
	      }else{
	        $pdf->SetFont('Arial','B',10);
	        $pdf->SetFillColor(230,230,0);
	        $pdf->Cell(280,6,'Deposito: '.$value['deposito'],1,0,'C',true);
	        $tmp_nro=$value['id_deposito'];
	        $pdf->ln(6);
	        $pdf->SetFont('Arial','B',8);
	        $pdf->Cell(20,6,'Codigo',1,0,'C');
	        $pdf->Cell(180,6,'Descripcion',1,0,'C');
	        $pdf->Cell(20,6,'Unidad',1,0,'C');
	        $pdf->Cell(20,6,'Cantidad',1,0,'C');
	        $pdf->Cell(20,6,utf8_decode('Mínimo'),1,0,'C');
	        $pdf->Cell(20,6,utf8_decode('Reposición'),1,0,'C');
	        $pdf->ln(6);
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
	        $pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
	        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
	        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['minimo'],2),1,0,'R');
	        $pdf->Cell(20,6,number_format($value['reposicion'],2),1,0,'R');
	      }
	      $pdf->ln(6);
	    }
	    $pdf->Output();
	}

	function rep_consumo_ctro_cto(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
				$data['deposito']=$this->deposito_model->get_deposito($usr);
				$data['app']='movimientos';
			$this->load->view('head');
			$this->load->view('reportes/consumo_ctro_cto_view',$data);
		}
	}

	function get_consumo_ctro_cto(){

   		//$usr=$this->control->control_login();
	    $id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $centro_costo=$this->input->get('centro_costo');
	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      $data=$this->stock_model->rep_consumo_ctro_cto($id_deposito,$F_inicio, $F_fin, $centro_costo);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}


   	function excel_consumo_ctro_cto(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
   		$centro_costo=$this->input->get('centro_costo');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $data=$this->stock_model->rep_consumo_ctro_cto($id_deposito,$F_inicio, $F_fin, $centro_costo);
	    //-------------------------------------------
	    $i=0;

	    $hoja[$i][0]="Consumo deposito ".$data[0]['deposito']." desde ".$F_inicio." hasta ".$F_fin;
	    $i++;
	    $hoja[$i][0]="";
	    reset($data);
	    $i++;
	    $hoja[$i][0]="";
	    $i++;
	    foreach ($data[0] as $v => $d) {
	    	$hoja[$i][$v]=$v;
		}
		reset($data);
		$i++;
	    foreach ($data as $key => $value) {
	    	$hoja[$i]=$value;
	    	$i++;
	    }
	    //-------------------------------------------
	  
	    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Deposito_".$id_deposito."_".$this->control->ParseDMYtoYMD($F_inicio,'/')."_".$this->control->ParseDMYtoYMD($F_fin,'/');
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}

   	//=====================================

   	function print_consumo_ctro_cto(){

   		$id_deposito = $this->input->get('id_deposito');
   		$centro_costo=$this->input->get('centro_costo');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $this->load->model('stock_model');
	    $dato=$this->stock_model->rep_consumo_ctro_cto($id_deposito,$F_inicio, $F_fin, $centro_costo);
	    $this->load->model('deposito_model');
	    $usr=$this->session->userdata('usr');
		$data=$this->deposito_model->get_deposito($usr);

	    $this->load->library('Pdf');
	    global $titulo;
	    global $deposito;
	    global $centro_costo;
	    global $inicio;
	    global $fin;

/*
	    foreach ($data as $key => $value) {
	
			$deposito=$value['label'];
			
	    }
*/
		for ($i = 0; $i < count($data); $i++) {
			if($data[$i]['value']==$id_deposito){
				$deposito=$data[$i]['label'];
			}
		}
		
		
    	$titulo="Deposito: ".$deposito;
	    $inicio=$F_inicio;
	    $fin=$F_fin;

	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_centro_costo="";
	    $tmp_denominacion="";
	    $pdf->SetFont('Arial','',12);
	    //$pdf->Cell(20,6,'Compra:',0,0,'L');
	    $pdf->Cell(40,6,'Consumos desde el '.$inicio.' hasta el '.$fin,0,0,'L');
	    
	    
	    $pdf->ln(6);
	    $pdf->ln(6);

	    if($dato==null){
	    	$pdf->SetFont('Arial','B',20);
			$pdf->SetFillColor(230,230,0);
	    	$pdf->Cell(280,6,'No existen datos',1,0,'L',1);
	    }else{

	    $total=0;
	    $total_final=0;
	    $flag=false;

	    foreach ($dato as $key => $value) {

			if($tmp_centro_costo==$value['centro_costo']){
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(20,6,utf8_decode($value['barcode']).$i,1,0,'C');
				$pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
				$pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),1,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['subtotal'],2),1,0,'R');
				$total=$total+$value['subtotal'];

			}else{

				
				if($flag==true){
					$pdf->SetFont('Arial','B',10);
					$pdf->SetFillColor(224,224,224);
					$pdf->ln(6);
					$pdf->Cell(80,6,'',0,0,'C');
					$pdf->Cell(120,6,'Total Centro de Costo: '.$tmp_centro_costo." - ".$tmp_denominacion,1,0,'L',1);
					$pdf->Cell(80,6,'$ '.number_format($total,2),1,0,'R',0);
					$total_final=$total_final+$total;
					$pdf->ln(6);
					$total=0;
				}
				$flag=true;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetFillColor(224,224,224);
				$pdf->ln(6);
				$pdf->Cell(280,6,'Centro de Costo: '.$value['centro_costo']." - ".utf8_decode($value['denominacion']),1,0,'L',1);
				$tmp_centro_costo=$value['centro_costo'];
				$tmp_denominacion=utf8_decode($value['denominacion']);
				$pdf->ln(6);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(20,6,'Codigo',1,0,'C');
				$pdf->Cell(180,6,'Descripcion',1,0,'C');
				$pdf->Cell(20,6,'Unidad',1,0,'C');
				$pdf->Cell(20,6,'Cantidad',1,0,'C');
				$pdf->Cell(20,6,'Costo Un.',1,0,'C');
				$pdf->Cell(20,6,'Subtotal',1,0,'C');
				$pdf->ln(6);
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(20,6,utf8_decode($value['barcode']).$i,1,0,'C');
				$pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
				$pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R'); 
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),1,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['subtotal'],2),1,0,'R');
				$total=$total+$value['subtotal'];
			}
			$pdf->ln(6);
	  	}
	  	$pdf->SetFont('Arial','B',10);
		$pdf->SetFillColor(224,224,224);
		$pdf->ln(6);
		$pdf->Cell(80,6,'',0,0,'C');
		$pdf->Cell(120,6,'Total Centro de Costo: '.$tmp_centro_costo." - ".$tmp_denominacion,1,0,'L',1);
		$pdf->Cell(80,6,'$ '.number_format($total,2),1,0,'R',0);
		$total_final=$total_final+$total;

		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(0,51,102);
		$pdf->SetTextColor(255,255,255);
		$pdf->ln(12);
		
		$pdf->Cell(200,8,'Total Periodo: '.$inicio." - ".$fin,1,0,'L',1);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(80,8,'$ '.number_format($total_final,2),1,0,'R',0);

	    }
	    $pdf->Output();
	}

	function rep_consumo_totalizado(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
				$data['deposito']=$this->deposito_model->get_deposito($usr);
				$data['app']='movimientos';
			$this->load->view('head');
			$this->load->view('reportes/consumo_totalizado_view',$data);
		}
	}

	function get_consumo_totalizado(){

   		//$usr=$this->control->control_login();
	    $id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    if($id_deposito!=""){
	      $this->load->model('stock_model');
	      $data=$this->stock_model->rep_consumo_totalizado($id_deposito,$F_inicio, $F_fin);
	      $this->output
	          ->set_status_header(200)
	          ->set_content_type('application/json', 'utf-8')
	          ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	          ->_display();
	        exit;
	    }
   	}

   	function excel_consumo_totalizado(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('stock_model');
   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $data=$this->stock_model->rep_consumo_totalizado($id_deposito,$F_inicio, $F_fin);
	    //-------------------------------------------
	    $i=0;

	    $hoja[$i][0]="Consumo deposito ".strtoupper($data[0]['deposito'])." desde ".$F_inicio." hasta ".$F_fin;
	    $i++;
	    $hoja[$i][0]="";
	    reset($data);
	    $i++;
	    $hoja[$i][0]="";
	    $i++;
	    foreach ($data[0] as $v => $d) {
	    	$hoja[$i][$v]=$v;
		}
		reset($data);
		$i++;
	    foreach ($data as $key => $value) {
	    	$hoja[$i]=$value;
	    	$i++;
	    }
	    //-------------------------------------------
	  
	    //$this->excel->getActiveSheet()->setCellValue('Movimientos');

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Deposito_".$id_deposito."_".$this->control->ParseDMYtoYMD($F_inicio,'/')."_".$this->control->ParseDMYtoYMD($F_fin,'/');
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}
   	//=====================================

   	function print_consumo_totalizado(){

   		$id_deposito = $this->input->get('id_deposito');
	    $F_inicio = $this->input->get('FechaInicio');
	    $F_fin = $this->input->get('FechaFin');
	    $this->load->model('stock_model');
	    $dato=$this->stock_model->rep_consumo_totalizado($id_deposito,$F_inicio, $F_fin);
	    $this->load->model('deposito_model');
	    $usr=$this->session->userdata('usr');
		$data=$this->deposito_model->get_deposito($usr);
		$Total=0;

	    $this->load->library('Pdf');
	    global $titulo;
	    global $deposito;
	    global $inicio;
	    global $fin;

/*
	    foreach ($data as $key => $value) {
	
			$deposito=$value['label'];
			
	    }
*/
		for ($i = 0; $i < count($data); $i++) {
			if($data[$i]['value']==$id_deposito){
				$deposito=$data[$i]['label'];
			}
		}
		
		
    	$titulo="Deposito: ".$deposito;
	    $inicio=$F_inicio;
	    $fin=$F_fin;

	    $pdf=new Pdf();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('L');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $tmp_centro_costo="";
	    $pdf->SetFont('Arial','',12);
	    //$pdf->Cell(20,6,'Compra:',0,0,'L');
	    $pdf->Cell(40,6,'Consumos desde el '.$inicio.' hasta el '.$fin,0,0,'L');
	    
	    
	    $pdf->ln(6);
	    $pdf->ln(6);

	    if($dato==null){
	    	$pdf->SetFont('Arial','B',20);
			$pdf->SetFillColor(230,230,0);
	    	$pdf->Cell(280,6,'No existen datos',1,0,'L',1);
	    }else{
	    	$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,6,'Codigo',1,0,'C');
			$pdf->Cell(180,6,'Descripcion',1,0,'C');
			$pdf->Cell(20,6,'Unidad',1,0,'C');
			$pdf->Cell(20,6,'Cantidad',1,0,'C');
			$pdf->Cell(20,6,'Costo Un.',1,0,'C');
			$pdf->Cell(20,6,'Subtotal',1,0,'C');
			$pdf->ln(6);
		    foreach ($dato as $key => $value) {

				$pdf->SetFont('Arial','',8);
				$pdf->Cell(20,6,utf8_decode($value['barcode']).$i,1,0,'C');
				$pdf->Cell(180,6,utf8_decode($value['descripcion']).$i,1,0);
				$pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R'); 
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),1,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['subtotal'],2),1,0,'R');
				$Total+=$value['subtotal'];
				$pdf->ln(6);
		  	}
	    }
	    $pdf->ln(6);
	    $pdf->SetFont('Arial','B',12);
	    $pdf->Cell(220,6,'Consumos desde el '.$inicio.' hasta el '.$fin,0,0,'L');
	    $pdf->SetFont('Arial','B',14);
	    $pdf->Cell(60,6,'$ '.number_format($Total,2),1,0,'R');
	    $pdf->Output();
	}   	

}