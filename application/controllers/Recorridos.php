<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recorridos extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	public function index(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('stock_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['recorrido']="Seleccione un deposito";
			$data['app'] ='recorrido';
			$this->load->view('head');
			$this->load->view('recorridos/recorridos_view',$data);
		}else{
			$this->load->view('head');
			redirect(base_url().'index.php/login');
		}
	}

	function lst_recorridos(){
		$this->load->model('stock_model');
		$deposito=$this->input->get('deposito');
		$data=$this->stock_model->rec_a_prog($deposito);
		/*$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
			*/
		//$html=print_r($data);

		$html='<select name="recorrido" id="recorrido" class="ui-widget" style="width:12em;">';
		$html.='<option value="" Selected >----</option>';
				
				for ($i = 0; $i < count($data); $i++) {
				$html.='<option value='.$data[$i]['value'].'>'.$data[$i]['label'].'</option>';
				}
		$html.='<option value=\'0\'>Sin ruta asignada</option>';	
		$html.='</select>';


		$this->output->set_output($html);
		//exit;

	}

	function listado(){

		$this->load->model('stock_model');
		$deposito=$this->input->get('deposito');
		$ruta=$this->input->get('recorrido');
		$recorrido=$this->stock_model->recorrido($deposito,$ruta);
		$data = array('recorrido' => $recorrido );
		$this->load->view('recorridos/lista_recorridos_view',$data);

	}

	function fuera_prog(){

		//$this->load->model('menu_model');
		$usr=$this->session->userdata('usr');
		$app='Recorrido No Programado';
		//$dato=$this->menu_model->load_menu($usr,$app);
		/*if($dato=="N"){
			$data=array(
			'msg' => '<strong>Atencion: Ud. no esta autorizado a realizar altas de Proveedores, comuniquese con su administrados</strong>',
			'clase'=>'ui-state-error ui-corner-all',
			'icono'=>'ui-icon ui-icon-alert',
			'frm'=>'No'
			);
			$this->load->view('msg_view',$data);
		}else{*/
			$this->load->view('recorridos/fuera_prog_view');
		//}
	}


	function print_recorrido(){

		$ruta=$this->input->get('id_entrega');
	    $this->load->model('stock_model');
	    $dato=$this->stock_model->rec_prog($ruta);
	    $this->load->model('deposito_model');
	    $usr=$this->session->userdata('usr');
		$data=$this->deposito_model->get_deposito($usr);
		$Total=0;

	    $this->load->library('Pdf_p');
	    global $titulo;
	    global $deposito;
	    global $inicio;
	    global $fin;
		
		
    	$titulo="Entrega de Pedidos";
	    
	    $pdf=new Pdf_p();
	    $pdf->AliasNbPages();
	    $pdf->AddPage('P');
	    $pdf->SetFont('Arial','B',8);
	    //$pdf->Line(145,0,145,210);
	    $pdf->SetFont('Arial','',8);
	    $i="";
	    $pdf->SetFont('Arial','',12);
	    //$pdf->Cell(20,6,'Compra:',0,0,'L');
	    //$pdf->Cell(40,6,'Consumos desde el '.$inicio.' hasta el '.$fin,0,0,'L');
	    
	    
	    $pdf->ln(6);
	    $pdf->ln(6);

	    if($data==null){
	    	$pdf->SetFont('Arial','B',20);
			$pdf->SetFillColor(180,230,0);
	    	$pdf->Cell(280,6,'No existen datos',1,0,'L',1);
	    }else{

	    	$pdf->SetFont('Arial','B',8);
			//$pdf->Cell(15,6,'Recorrido',1,0,'C');
			$pdf->Cell(50,8,'Fecha:',1,0,'L');
			$pdf->Cell(140,8,'AySA CORREO Y DEPOSITO DE UTILES','LTR',1,'C');
			$pdf->Cell(30,8,'Recorrido Zona',1,0,'C');
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(20,8,$dato[0]['recorrido'],1,0,'C');
			$pdf->Cell(140,8,'','LR',1,'C');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(50,8,'Chofer:',1,0,'L');
			$pdf->Cell(140,8,'','LBR',1,'C');
			//$pdf->ln(8);
			$pdf->Cell(190,8,'LA FIRMA Y ACLARACION ES POR LA RECEPCION DE LOS BULTOS',1,1,'C');


	    	$pdf->SetFont('Arial','B',8);
			//$pdf->Cell(15,6,'Recorrido',1,0,'C');
			$pdf->Cell(50,8,'Destino',1,0,'C');
			$pdf->Cell(50,8,'Solicitante',1,0,'C');
			$pdf->Cell(15,8,'Pedido',1,0,'C');
			$pdf->Cell(15,8,'Bultos',1,0,'C');
			$pdf->Cell(30,8,'Firma',1,0,'C');
			$pdf->Cell(30,8,'Aclaracion',1,0,'C');
			$pdf->ln(8);

			//$pdf->Cell(20,6,'$dato',1,0,'C');
			
		    foreach ($dato as $key => $value) {

				$pdf->SetFont('Arial','',8);
				//$pdf->Cell(15,6,$value['recorrido'],1,0,'C');
				$pdf->Cell(50,8,$value['dependencia'],1,0);
				$pdf->Cell(50,8,$value['destino'],1,0,'L');
				$pdf->Cell(15,8,$value['nro_pedido'],1,0,'C');
				$pdf->Cell(15,8,$value['bultos'],1,0,'C'); 
				$pdf->Cell(30,8,'',1,0,'R');
				$pdf->Cell(30,8,'',1,0,'R');
				$pdf->ln(8);
		  	}
	    }
	    $pdf->ln(6);
	    $pdf->SetFont('Arial','B',12);
	    //$pdf->Cell(220,6,'Consumos desde el '.$inicio.' hasta el '.$fin,0,0,'L');
	    $pdf->SetFont('Arial','B',14);
	    //$pdf->Cell(60,6,'$ '.number_format($Total,2),1,0,'R');
	    $pdf->Output();

	}

	function rutas_adm(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('stock_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['recorrido']="Seleccione un deposito";
			$data['app'] ='rutas';
			$this->load->view('head');
			$this->load->view('recorridos/rutas_adm_view',$data);
		}else{
			$this->load->view('head');
			redirect(base_url().'index.php/login');
		}
	}

	function get_ruta(){
		$this->load->model('rutas_model');
		
	    if (isset($_GET['term'])){
			$ruta = strtolower($_GET['term']);
			$deposito=$this->input->get('id_deposito');
			//$q=$this->limpia_str(utf8_encode($q));
			$data=array(
				'ruta' => $ruta,
				'deposito'=>$deposito 
			);
			$dato=$this->rutas_model->get_ruta($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
	    }
	}

	function get_recorrido(){

		$this->load->model('rutas_model');
		$id_ruta = $this->input->get('id_ruta');
		$dato=$this->rutas_model->get_recorrido($id_ruta);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	    
	}

	function get_dependencia(){
		$this->load->model('rutas_model');
		
	    if (isset($_GET['term'])){
			$dependencia = strtolower($_GET['term']);

			$data=array(
				'dependencia' => $dependencia
			);

			$dato=$this->rutas_model->get_dependencia($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
	    }
	}

	function get_zonas_dependencia(){

		$this->load->model('rutas_model');
		
	    
		$id_dependencia = $this->input->post('id_dependencia');
		/*$data=array(
			'id_dependencia'=>$id_dependencia
		);*/
		$dato=$this->rutas_model->get_zonas_dependencia($id_dependencia);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;

	}

	function edit_recorrido(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$id_recorrido=$this->input->get('id_recorrido');
			$this->load->model('rutas_model');
			if($id_recorrido!=""){
				$data=$this->rutas_model->get_recorrido_id($id_recorrido);
				$dato = array(
					'id_recorrido'=>$data['id_recorrido'],
					'id_ruta'=>$data['id_ruta'],
					'id_zona'=>$data['id_zona'],
					'dependencia'=>$data['dependencia'],
					'orden'=>$data['orden'],
					'Zona'=>$data['Zona'],
					'Direccion'=>$data['Direccion'],
					'Localidad'=>$data['Localidad']
					);
			}else{
				$dato = array(
					'id_recorrido'=>'',
					'id_ruta'=>'',
					'id_zona'=>'',
					'dependencia'=>'',
					'orden'=>'',
					'Zona'=>'',
					'Direccion'=>'',
					'Localidad'=>''
					);
			}
			$this->load->view('recorridos/ruta_dependencia_view',$dato);
			//$this->load->view('recorridos/recorridos_lista_view',$dato);
		}
	}

	function asignar_dependencia(){
		$login=$this->control->control_login();
		$usr=$this->session->userdata('usr');
		if($login==TRUE){
			$id_ruta=$this->input->post('id_ruta');
			$id_dependencia=$this->input->post('id_dependencia');
			$data=array(
				'id_ruta'=>$id_ruta,
				'id_dependencia'=>$id_dependencia,
				'usr'=>$usr
			);
			$this->load->model('rutas_model');
			$dato=$this->rutas_model->asignar_dependencia($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
		}
	}

	function alta_ruta(){

		$login=$this->control->control_login();
		$usr=$this->session->userdata('usr');
		if($login==TRUE){
			$id_deposito=$this->input->post('id_deposito');
			$ruta=$this->input->post('ruta');
			$data=array(
				'id_deposito'=>$id_deposito,
				'ruta'=>$ruta,
				'usr'=>$usr
			);
			$this->load->model('rutas_model');
			$dato=$this->rutas_model->alta_ruta($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
			
		}
		
	}

	function set_entregas(){

		$login=$this->control->control_login();
		$usr=$this->session->userdata('usr');
		if($login==TRUE){
			$data=$this->input->post('entregaData');
			$id_recorrido=$this->input->post('id_recorrido');
			$chofer=$this->input->post('chofer');
			$patente=$this->input->post('patente');
			$DataEntrega=array(
				'id_recorrido'=>$id_recorrido,
				'chofer'=>$chofer,
				'patente'=>$patente
			);
			$this->load->model('rutas_model');
			$dato=$this->rutas_model->set_entregas($DataEntrega,$data,$usr);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
			
		}
	}

	function recorrido_etiquetas(){

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