<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salidas extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	public function index(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('Deposito_model');
			$this->load->model('Contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->Deposito_model->get_deposito($usr);
			$data['movimiento']=$this->Contable_model->get_tipo_movimiento('S',$usr);
			$data['compra']=$this->Contable_model->get_tipocompra();
			$data['app'] ='salidas';
			$this->load->view('head');
			$this->load->view('salidas/salidas_view',$data);
		}else{
			$this->load->view('head');
			redirect(base_url().'index.php/login');
		}
	}

	function get_stock(){
		$this->load->model('stock_model');
		
	    if (isset($_GET['term'])){
			$descripcion = strtolower($_GET['term']);
			$deposito=$this->input->get('id_deposito');
			//$q=$this->limpia_str(utf8_encode($q));
			$data=array(
				'descripcion' => $descripcion,
				'deposito'=>$deposito 
			);
			$dato=$this->stock_model->get_stock($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
	    }
	}

	function get_cod_stock(){
		$this->load->model('stock_model');
		$id_material = $this->input->get('id_material');
		$id_deposito=$this->input->get('id_deposito');
		$data=array(
			'id_material' => $id_material,
			'id_deposito'=>$id_deposito 
		);
		$dato=$this->stock_model->get_cod_stock($data);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	}

	function get_barcode_stock(){
		$this->load->model('stock_model');
		$barcode = $this->input->get('barcode');
		$id_deposito=$this->input->get('id_deposito');
		$data=array(
			'barcode' => $barcode,
			'id_deposito'=>$id_deposito 
		);
		$dato=$this->stock_model->get_barcode_stock($data);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	}

	function salida_stock(){

		$login=$this->control->control_login();
		if($login==TRUE){
		    $this->load->model('stock_model');

		    $data = [
		      'id_deposito'=>$this->input->post('id_deposito'),
		      'id_tipo_mov'=>$this->input->post('id_tipo_mov'),
		      'factor'=>$this->input->post('factor'),
		      'pedido'=>$this->input->post('pedido'),
		      'id_pedido'=>$this->input->post('id_pedido'),
		      'completo'=>$this->input->post('completo'),
		      'legajo'=>$this->input->post('legajo'),
		      'id_personal'=>$this->input->post('id_personal'),
		      'ot'=>$this->input->post('ot'),
		      'obs'=>$this->input->post('obs'),
		      'bultos'=>$this->input->post('bultos'),
		      'recorrido'=>$this->input->post('recorrido'),
		      'sector'=>$this->input->post('sector'),
		      'id_zona'=>$this->input->post('id_zona'),
		      'centro_costo'=>$this->input->post('centro_costo'),
		      'fecha'=>$this->input->post('fecha')
		    ];
		    $grilla=$this->input->post('jgGridData');

		    $usr=$this->session->userdata('usr');
		    $dato=$this->stock_model->add_salida($usr,$data,$grilla);

		   $this->output
		      ->set_status_header(200)
		      ->set_content_type('application/json', 'utf-8')
		      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		      ->_display();
		    exit;
	    }else{
			$dato = [
		      'nro'=>'ERROR',
		      'msg'=>'No se encontro session activa'
		    ];
			$this->output
		      ->set_status_header(200)
		      ->set_content_type('application/json', 'utf-8')
		      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		      ->_display();
		    exit;
		}
	}

	function vale(){
		//$nro=$this->input->get('nro');
		//$id_deposito=$this->input->get('id_deposito');
		$id_salida=$this->input->get('id_salida');
		//redirect(base_url().'index.php/pdf/vale_salida?nro='.$nro.'&id_deposito='.$id_deposito);
		//redirect(base_url().'index.php/pdf/'.REPORTE_DEFAULT.'?nro='.$nro.'&id_deposito='.$id_deposito);
		redirect(base_url().'index.php/Pdf/'.REPORTE_DEFAULT.'?id_salida='.$id_salida);
	}

	function lista_salidas(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('Deposito_model');
			$usr=$this->session->userdata('usr');
	   		$data=$this->Deposito_model->get_deposito($usr);
	   		$deposito='';
	   		foreach ($data as $key) {
	   			$deposito.=';'.$key['label'].':'.$key['label'];
		   	}
		   	$data = array('deposito' => $deposito);
			//$this->load->view('head');
			$this->load->view('salidas/salidas_buscar_view',$data);
		}
	}

	function get_salidas(){
		$this->load->model('Deposito_model');
		$this->load->model('Stock_model');
		$usr=$this->session->userdata('usr');
	   	$data=$this->Deposito_model->get_deposito($usr);
	   	$deposito="";
	   	foreach ($data as $key) {
	   		if($deposito==''){
	   			$deposito.=$key['value'];
	   		}else{
	   			$deposito.=",".$key['value'];
	   		}
	   	}
	   	$dato=$this->Stock_model->lista_salidas($deposito);
	   	$this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}


	function pedido_etiquetas(){

		global $tmp;

		$this->load->library('Pdf_etiqueta');

		$this->load->model('stock_model');
		$id_salida=$this->input->get('id_salida'); 
		/*$btos=$this->input->get('btos');
		$rdo=$this->input->get('rdo');
		$bultos=preg_split("/[\s,]+/", $btos);
		$ot=preg_split("/[\s,]+/", $nros);
		$rdo=preg_split("/[\s,]+/", $rdo);*/
 
		$titulo="PLANILLA DE PREPARACION DE PEDIDOS";
		$dato=$this->stock_model->imprimir_etiquetas($id_salida);
		if($dato!='NoData'){
			$nro_pedido="";
			$i=0;
			$largo=count($dato);
			$flag=TRUE;
			$PosX=0;
			$PosY=0;
			$p=0;
			
			$pdf=new Pdf_etiqueta();
			$pdf->AliasNbPages();
			//$pdf->AddPage('L');
			//$pdf->SetFont('Arial','B',8);
			//$pdf->Line(145,0,145,210);
			//$pdf->SetFont('Arial','',8);
			
			$pdf->AddPage('P','Letter');
			//SetMargins(float left, float top [, float right])
			$pdf->SetY(25);
			foreach ($dato as $key => $value) {

				if($nro_pedido!=$value['nro_pedido']){
					/*for ($i=0; $i < count($bultos); $i++) { 
						if($ot[$i]==$value['nro_pedido']){
							$tmp=$bultos[$i];
							$tmp2=$rdo[$i];
							break;
						}else{
							$tmp=$bultos[$i];
							$tmp2=$rdo[ $i];
						}
					}*/

					$tmp=$value['bultos'];
					
					$tmp2=$value['recorrido'];

					if($tmp2==0){
						$tmp2="";
					}
					
					for ($j=0; $j <$tmp ; $j++) {
						
						if($p==14){
							$pdf->AddPage('P','Letter');
							//$pdf->SetMargins(30, 60, 20);
							$pdf->SetY(25);
							$p=0;
						}
						$p++;
						if($flag==TRUE){
							//$pdf->SetX(30);
							$PosX=5;
							$PosY=$pdf->GetY();
							$pdf->SetX($PosX);
							$pdf->SetFont('Arial','',10);
							$pdf->Cell(20,6,'PEDIDO:',0,0,'L');
							$pdf->SetFont('Arial','B',16);
							$pdf->SetFillColor(0, 0, 0);
							$pdf->SetTextColor(255, 255, 255);
							$pdf->Cell(55,6,$value['nro_pedido'],1,1,'C',true);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial','',10);
							$pdf->SetX($PosX);
							$pdf->Cell(20,6,'SECTOR:',0,0,'L');
							$pdf->Cell(55,6,utf8_decode($value['sector']),1,1,'L');
							$pdf->SetX($PosX);
							$pdf->Cell(20,6,'Solicitante:',0,0,'L');
							$pdf->SetFont('Arial','B',12);
							$pdf->Cell(55,6,utf8_decode($value['destino']),1,1,'L');
							$pdf->SetX($PosX);
							$pdf->SetFont('Arial','',10);
							$pdf->Cell(20,6,'Bulto:',0,0,'L');
							$pdf->SetFont('Arial','B',12);
							$pdf->Cell(55,6,($j+1).' de '.$tmp,1,1,'L');
							$pdf->SetY($PosY);
							$pdf->Cell(70,24,'',0,0,'L');
							$pdf->SetFont('Arial','B',32);
							$pdf->Cell(20,24,$tmp2,1,1,'C');//celda recorrido
							$flag=FALSE;
						}else{
							$PosX=105;
							$pdf->SetX($PosX);
							$pdf->SetY($PosY);
							$pdf->SetFont('Arial','',10);
							$pdf->Cell($PosX,6,'',0,0,'L');
							$pdf->Cell(20,6,'PEDIDO:',0,0,'L');
							$pdf->SetFont('Arial','B',16);
							$pdf->SetFillColor(0, 0, 0);
							$pdf->SetTextColor(255, 255, 255);
							$pdf->Cell(55,6,$value['nro_pedido'],1,1,'C',true);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial','',10);
							$pdf->Cell($PosX,6,'',0,0,'L');
							$pdf->Cell(20,6,'SECTOR:',0,0,'L');
							$pdf->Cell(55,6,utf8_decode($value['sector']),1,1,'L');
							$pdf->Cell($PosX,6,'',0,0,'L');
							$pdf->Cell(20,6,'Solicitante:',0,0,'L');
							$pdf->SetFont('Arial','B',12);
							$pdf->Cell(55,6,utf8_decode($value['destino']),1,1,'L');
							$pdf->Cell($PosX,6,'',0,0,'L');
							$pdf->SetFont('Arial','',10);
							$pdf->Cell(20,6,'Bulto:',0,0,'L');
							$pdf->SetFont('Arial','B',12);
							$pdf->Cell(55,6,($j+1).' de '.$tmp,1,1,'L');
							$pdf->SetY($PosY);
							$pdf->Cell(($PosX+75),24,'',0,0,'L');
							$pdf->SetFont('Arial','B',32);
							$pdf->Cell(20,24,$tmp2,1,1,'C');//celda recorrido
							//$pdf->Cell(20,24,'y',1,1,'L');
							$pdf->Ln(10);
							$flag=TRUE;
						}
					}
					$nro_pedido=$value['nro_pedido'];
				}
			}

			$pdf->Output();
		}else{
			$nro="";
			$i=0;
			$largo=count($dato);
			$flag=TRUE;
			$PosX=0;
			$PosY=0;
			$p=0;
			
			$pdf=new Pdf_etiqueta();
			$pdf->AliasNbPages();
			$pdf->AddPage('P','Letter');
			//SetMargins(float left, float top [, float right])
			$pdf->SetY(25);
			$pdf->SetFont('Arial','B',18);
			$pdf->Cell(190,20,'Sin Datos',1,1,'C');
			$pdf->Output();
		}
	}


}