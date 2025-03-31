<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	public function index(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		//$data['usr']=$usr;
			$data['movimiento']=$this->contable_model->get_tipo_movimiento('S',$usr);
			$data['compra']=$this->contable_model->get_tipocompra();
			$data['app']='solicitudes';
			$this->load->view('head');
			$this->load->view('solicitudes/solicitud_view',$data);
		}
	}

	function get_solicitud_web(){
		//$this->load->model('deposito_model');
		$this->load->model('solicitud_model');
		//$this->load->model('sectores_model');
		$usr=$this->session->userdata('usr');
		$deposito=$this->control->dep_auth();
		$sectores=$this->control->sectores_auth();

		/* Reemplazodo por funcion dep_ath() y sectores_auth
		$sector_session=$this->session->userdata('id_sector');
	   	$data=$this->deposito_model->get_deposito($usr);
	   	$deposito="";
	   	foreach ($data as $key) {
	   		if($deposito==''){
	   			$deposito.=$key['value'];
	   		}else{
	   			$deposito.=",".$key['value'];
	   		}
	   	}


	   	//------------------------------------------------------------
	   	unset($data);
	   	$data=$this->sectores_model->sectores_dependientes($this->session->userdata('id_sector'));
	   	//$usr=$this->session->userdata('usr');
	   	$sectores="";
	   	foreach ($data as $key) {
	   		if($sectores==''){
	   			$sectores.=$key['id_sector'];
	   		}else{
	   			$sectores.=",".$key['id_sector'];
	   		}
	   	}

	   	//------------------------------------------------------------
		*/


	   	$dato=$this->solicitud_model->lista_solicitudes_web($deposito,$sectores);
	   	$this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	function solicitudes_web(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		//$data['usr']=$usr;
			$data['movimiento']=$this->contable_model->get_tipo_movimiento('S',$usr);
			$data['compra']=$this->contable_model->get_tipocompra();
			$data['app']='solicitud web';
			$this->load->view('head');
			$this->load->view('solicitudes_web/solicitud_view',$data);
		}
	}

	function solicitud_edit_web(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$this->load->model('solicitud_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		//$data['usr']=$usr;
			$data['movimiento']=$this->contable_model->get_tipo_movimiento('S',$usr);
			$data['compra']=$this->contable_model->get_tipocompra();
			$data['app']='edición solicitud web';
			$this->load->view('head');
			$this->load->view('solicitudes_web/solicitud_edit_view',$data);
		}
	}

	function set_solicitud_edit_web(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
	   		
			/*$this->load->view('head');
			$this->load->view('solicitudes_web/solicitud_edit_view',$data);*/
		}
		$data=array(
			'id_detalle_pedido'=>$this->input->post('id_detalle_pedido'),
			'cantidad'=>$this->input->post('cantidad')
		);
		//$data=$this->material_model->edit_costo_mat($data,$usr);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	}


	function get_disponible(){
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

	function get_cod_disponible(){
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

	function get_barcode_disponible(){
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

	function add_solicitud(){

	    $this->load->model('solicitud_model'); 

	    $data = [
	      'id_deposito'=>$this->input->post('id_deposito'),
	      'id_pedido'=>$this->input->post('id_pedido'),
	      'nro'=>$this->input->post('nro'),
	      'id_zona'=>$this->input->post('id_zona'),
	      'id_personal'=>$this->input->post('id_personal'),
	      'legajo'=>$this->input->post('legajo'),
	      'ot'=>$this->input->post('ot'),
	      'obs'=>$this->input->post('obs'),
	      'sector'=>$this->input->post('sector'),
	      'centro_costo'=>$this->input->post('centro_costo'),
	      'fecha'=>$this->input->post('fecha'),
	      'web'=>$this->input->post('web')
	    ];
	    $grilla=$this->input->post('jgGridData');

	    $usr=$this->session->userdata('usr');
	    $id_usr_sector=$this->session->userdata('id_sector');

	    if($data['id_pedido']==""){
	    	$dato=$this->solicitud_model->add_solicitud($usr,$data,$grilla,$id_usr_sector);
		}else{
			$dato=$this->solicitud_model->update_solicitud($usr,$data,$grilla);
		}

	   $this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	function anular_solicitud(){
		
		$nro=$this->input->post('nro');
		$usr=$this->session->userdata('usr');
		$this->load->model('solicitud_model');
		$dato=$this->solicitud_model->anular_solicitud($nro,$usr);

		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;

	}

	function vale(){
		$nro=$this->input->get('nro');
		$id_deposito=$this->input->get('id_deposito');
		redirect(base_url().'index.php/pdf/vale_salida?nro='.$nro.'&id_deposito='.$id_deposito);
	}

	function lista_solicitudes(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$usr=$this->session->userdata('usr');
	   		$data=$this->deposito_model->get_deposito($usr);
	   		$deposito='';
	   		foreach ($data as $key) {
	   			$deposito.=';'.$key['label'].':'.$key['label'];
		   	}
		   	$data = array('deposito' => $deposito);
			//$this->load->view('head');
			$this->load->view('solicitudes/solicitud_buscar_view',$data);
		}
	}

	//---------------------------------

	function lista_solicitudes_web(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$usr=$this->session->userdata('usr');
	   		$data=$this->deposito_model->get_deposito($usr);
	   		$deposito='';
	   		foreach ($data as $key) {
	   			$deposito.=';'.$key['label'].':'.$key['label'];
		   	}
		   	$data = array('deposito' => $deposito);
			//$this->load->view('head');
			$this->load->view('solicitudes_web/solicitud_buscar_view',$data);
		}
	}

	//---------------------------------

	function get_solicitud(){
		$this->load->model('deposito_model');
		$this->load->model('solicitud_model');
		$usr=$this->session->userdata('usr');
	   	//$data=$this->deposito_model->get_deposito($usr);
	   	$deposito="";
	   	$deposito=$this->control->dep_auth();
	   	/*
	   	foreach ($data as $key) {
	   		if($deposito==''){
	   			$deposito.=$key['value'];
	   		}else{
	   			$deposito.=",".$key['value'];
	   		}
	   	}
	   	*/

	   	$dato=$this->solicitud_model->lista_solicitudes($deposito);
	   	$this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	function get_zona(){
		$this->load->model('solicitud_model');
		
	    if (isset($_GET['term'])){
			$zona = strtolower($_GET['term']);
			//$q=$this->limpia_str(utf8_encode($q));
			$data=array(
				'zona' => $zona,
			);
			$dato=$this->solicitud_model->get_zona($data);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
	    }
	}

	function get_pedido(){

	    $this->load->model('solicitud_model');
	    $nro=$this->input->post('pedido');
	    $tipo_mov=$this->input->post('id_tipo_mov');
	    $data = array(
	    	'nro' => $nro,
	    	'tipo_mov'=>$tipo_mov
	    	);
	    $data=$this->solicitud_model->get_pedido($data);

	    $this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	function get_pedido_web(){

	    $this->load->model('solicitud_model');
	    $nro=$this->input->post('pedido');
	    $tipo_mov=$this->input->post('id_tipo_mov');
	    $data = array(
	    	'nro' => $nro,
	    	'tipo_mov'=>$tipo_mov
	    	);
	    $data=$this->solicitud_model->get_pedido_web($data);

	    $this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	function buscar_solicitud(){

	    $this->load->model('solicitud_model');
	    $nro=$this->input->post('nro');
	    $data=$this->solicitud_model->buscar_solicitud($nro);

	    $this->output
	      ->set_status_header(200)
	      ->set_content_type('application/json', 'utf-8')
	      ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	      ->_display();
	    exit;
	}

	//--------------------

	function buscar_ot(){

		$this->load->model('entradas_model');
		$id_proveedor=$this->input->post('id_proveedor');
		$remito=$this->input->post('remito');
		$data=$this->entradas_model->buscar_remito($id_proveedor,$remito);
	
		$this->output
		  ->set_status_header(200)
		  ->set_content_type('application/json', 'utf-8')
		  ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		  ->_display();
		exit;
	  }

	//---------------------

	function edit_bulto_pedido(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
		}
   		$this->load->model('solicitud_model');
   		$data=array(
   		);
		//$data=$this->solicitud_model->edit_bulto_pedido();
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
   	}

	//--------------------

	function pedido_materiales(){


		$this->load->model('solicitud_model');
		$nros=$this->input->get('nros');
		$dato=$this->solicitud_model->imprimir_solicitudes($nros);
		$largo=count($dato);

		if($dato!="NoData"){

			$this->load->library('Pdf_pedido');

			global $titulo;
			global $id_proveedor;
			global $remito;
			
			
			$sectores=explode(",",$this->control->sectores_auth());
			$sector_habilitado=false;
			

			$titulo="PLANILLA DE PREPARACION DE PEDIDOS";
			
			$nro="";
			$i=0;

			$pdf=new Pdf_pedido();
			$pdf->AliasNbPages();

			//$pdf->AddPage('L');
			//$pdf->SetFont('Arial','B',8);
			//$pdf->Line(145,0,145,210);
			//$pdf->SetFont('Arial','',8);

			foreach ($dato as $key => $value) {

				if($nro!=$value['nro']){
					if($nro!=""){
						$pdf->Ln(5);
						$x=$pdf->GetX();
						$y=$pdf->GetY();
						$pdf->Line($x,$y,200,$y);
						$pdf->Ln(5);
						$pdf->Cell(70,6,'',0,0);
						$pdf->Cell(50,6,'',0,0,'C');
						$pdf->Cell(70,6,'',0,1);
						$pdf->Cell(70,6,'Preparo el pedido:',0,0,'R');
						$pdf->Cell(50,6,'','B',0);
						$pdf->Cell(70,6,'',0,1);
						$pdf->Cell(70,6,'',0,0);
						$pdf->Cell(50,6,utf8_decode('Firma y Aclaración'),0,0,'C');
						$pdf->Cell(70,6,'',0,1);
					}
					$pdf->AddPage('P');
					$pdf->SetFont('Arial','',8);

					$sector_habilitado=false;
					foreach ($sectores as $Sectorkey) {
				   		if($Sectorkey==$value['id_sector']){
				   			$sector_habilitado=true;
				   		}
				   	}
					if($sector_habilitado==false){
						$pdf->SetFont('Arial','B',20);
						$pdf->Text(20, 70, 'Pedido Nro:'.$value['nro'].' no esta habilitado para su sector');
					}else{

					$pdf->Cell(20,6,'FECHA:',0,0,'L');
					$pdf->Cell(20,6,$value['fecha'],1,0,'C');
					$pdf->Cell(80,6,'',0,0,'R');
					$pdf->Cell(40,6,'NOTA DE PEDIDO:',0,0,'R');
					$pdf->SetFont('Arial','B',10);
					$pdf->Cell(30,6,$value['nro'],1,1,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Ln(2);
					$pdf->Cell(20,6,'SECTOR:',0,0,'L');
					$pdf->Cell(70,6,utf8_decode($value['sector']),1,0,'L');
					$pdf->Cell(20,6,'',0,0,'R');
					$pdf->Cell(40,6,'CENTRO DE COSTO:',0,0,'R');
					$pdf->Cell(40,6,$value['centro_costo'],1,1,'C');
					$pdf->Ln(2);
					$pdf->Cell(20,6,'Dependencia:',0,0,'L');
					$pdf->Cell(50,6,utf8_decode($value['Zona']),1,0,'L');
					$pdf->Cell(5,6,'',0,0,'R');
					$pdf->Cell(20,6,'Direccion:',0,0,'R');
					$pdf->Cell(40,6,$value['Direccion'],1,0,'L');
					$pdf->Cell(5,6,'',0,0,'R');
					$pdf->Cell(20,6,'Localidad:',0,0,'R');
					$pdf->Cell(30,6,$value['Localidad'],1,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20,6,'ODT:',0,0,'L');
					$pdf->SetFont('Arial','B',10);
					$pdf->Cell(50,6,utf8_decode($value['odt']),1,0,'L');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(5,6,'',0,0,'R');
					$pdf->Cell(20,6,'Observaciones:',0,0,'R');
					$pdf->Cell(95,6,$value['destino'],1,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20,6,'Legajo:',0,0,'L');
					$pdf->SetFont('Arial','B',10);
					$pdf->Cell(40,6,utf8_decode($value['legajo']),1,0,'L');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(5,6,'',0,0,'R');
					$pdf->Cell(30,6,'Apellido y Nombre:',0,0,'R');
					$pdf->Cell(95,6,$value['apellido_nombre'],1,1,'L');
					$pdf->Ln(2);
					
					//Encabezado lista
					$pdf->Cell(15,6,'item',1,0,'C');
					$pdf->Cell(120,6,'Descripcion',1,0,'C');
					$pdf->Cell(20,6,'Unidad',1,0,'C');
					$pdf->Cell(20,6,'Desp./Cant.',1,0,'C');
					$pdf->Cell(15,6,'Control',1,1,'C');

					//primera linea listado
					$y=$pdf->GetY();
					$x=$pdf->GetX();
					$pdf->Cell(15,6,$value['barcode'],0,0,'C');
					$pdf->Line($x,$y,200,$y);
					$pdf->MultiCell(120,6,utf8_decode($value['descripcion']),0,'L');
					$y1=$pdf->GetY();
					$pdf->SetY($y);
					$pdf->SetX($x+135);
					$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
					$pdf->Cell(20,6,$value['despachado'].' / '.$value['cantidad'],0,0,'R');
					$x=$pdf->GetX();
					$pdf->Rect($x+6,$y+1,3,3);
					$pdf->Cell(10,6,'',0,1,'C');
					$pdf->SetY($y1);
					$nro=$value['nro'];
					
					}
					

				}else{
					$y=$pdf->GetY();
					$x=$pdf->GetX();
					$pdf->Cell(15,6,$value['barcode'],0,0,'C');
					$pdf->Line($x,$y,200,$y);
					$pdf->MultiCell(120,6,utf8_decode($value['descripcion']),0,'L');
					$y1=$pdf->GetY();
					$pdf->SetY($y);
					$pdf->SetX($x+135);
					$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
					$pdf->Cell(20,6,$value['despachado'].' / '.$value['cantidad'],0,0,'R');
					$x=$pdf->GetX();
					$pdf->Rect($x+6,$y+1,3,3);
					$pdf->Cell(10,6,'',0,1,'C');
					$pdf->SetY($y1);
				}
				$i++;
				if($largo==$i){
					$pdf->Ln(5);
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					$pdf->Line($x,$y,200,$y);
					$pdf->Ln(5);
					$pdf->Cell(70,6,'',0,0);
					$pdf->Cell(50,6,'',0,0,'C');
					$pdf->Cell(70,6,'',0,1);
					$pdf->Cell(70,6,'Preparo el pedido:',0,0,'R');
					$pdf->Cell(50,6,'','B',0);
					$pdf->Cell(70,6,'',0,1);
					$pdf->Cell(70,6,'',0,0);
					$pdf->Cell(50,6,utf8_decode('Firma y Aclaración'),0,0,'C');
					$pdf->Cell(70,6,'',0,1);
				}
			}
			$pdf->Output();

		}else{

			$msg="Sin Datos";
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
			
		}
		
		
		
	}

}