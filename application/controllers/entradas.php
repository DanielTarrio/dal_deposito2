<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entradas extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

  public function index(){
  	$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
      
      $g=$this->control->app_hash();

	   	$data['deposito']=$this->deposito_model->get_deposito($usr);
			$data['movimiento']=$this->contable_model->get_tipo_movimiento('E',$usr);
			$data['compra']=$this->contable_model->get_tipocompra();
      $data['app']='entradas';
      
			$this->load->view('head');
			$this->load->view('entradas/entradas_view',$data);
		}else{
      $this->load->view('head');
      redirect(base_url().'index.php/login');
    }
  }


	function get_proveedor(){
    $this->load->model('contable_model');
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      $data=$this->contable_model->get_proveedor($q);
      $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
        ->_display();
      exit;
    }
  }
  function get_material(){
    $this->load->model('material_model');
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      //$q=$this->limpia_str(utf8_encode($q));
      $data=$this->material_model->get_material($q);
      //$data=array('pepe','toto');
      $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
        ->_display();
      exit;
    }
  }
  function get_cod_material(){
    $this->load->model('material_model');
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      //$q=$this->limpia_str(utf8_encode($q));
      $data=$this->material_model->get_cod_material($q);
      //$data=array('pepe','toto');
      $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
        ->_display();
      exit;
    }
  }
  function get_barcode_material(){
    $this->load->model('material_model');
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      //$q=$this->limpia_str(utf8_encode($q));
      $data=$this->material_model->get_barcode_material($q);
      //$data=array('pepe','toto');
      $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
        ->_display();
      exit;
    }
  }

  function add_stock(){

    //sleep(1);
     $login=$this->control->control_login();
    if($login==TRUE){
      $this->load->model('stock_model');

      $data = [
        'id_deposito'=>$this->input->post('id_deposito'),
        'id_tipo_mov'=>$this->input->post('id_tipo_mov'),
        'id_proveedor'=>$this->input->post('id_proveedor'),
        'id_tipo_compra'=>$this->input->post('id_tipo_compra'),
        'id_compra'=>$this->input->post('id_compra'),
        'remito'=>$this->input->post('remito'),
        'centro_costo'=>$this->input->post('centro_costo'),
        'fecha'=>$this->input->post('fecha')
      ];
      $grilla=$this->input->post('jgGridData');
  /*
      $data['id_deposito']=$this->input->post('id_deposito');
      $data['id_tipo_mov']=$this->input->post('id_tipo_mov');
      $data['id_proveedor']=$this->input->post('id_proveedor');
      $data['id_tipo_compra']=$this->input->post('id_tipo_compra');
      $data['id_compra']=$this->input->post('id_compra');
      $data['remito']=$this->input->post('remito');
      $data['centro_costo']=$this->input->post('centro_costo');*/
     // $jgGridData=$this->input->post('jgGridData');
     // $data['jgGridData']=$jgGridData;
     // $data['jgGridData']=$this->input->post('jgGridData');
     // $data['ppp']='hjk';

      $usr=$this->session->userdata('usr');
      $dato=$this->stock_model->add_entrada($usr,$data,$grilla);


      //redirect(base_url().'index.php/entradas/entrada_save_view');

      //$e=$this->input->post('jgGridData');
      //$e=json_decode($e);
      //$this->load->view('entradas/entrada_save_view',$e);
      
      //$dato="Help...".$this->input->post('centro_costo');
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

/*==========================================================
data: {
          jgGridData: postData,
          customData: "bla bla bla",
          id_deposito: $("#id_deposito").val(),
          id_tipo_mov: $("#id_tipo_mov").val(),
          id_proveedor: $("#id_proveedor").val(),
          id_tipo_compra: $("#id_tipo_compra").val(),
          id_compra: $("#id_compra").val(),
          remito: $("#remito").val(),
          centro_costo: $("#centro_costo").val()
        }
===========================================================*/


  function frm_proveedor(){

    $this->load->model('menu_model');
    $usr=$this->session->userdata('usr');
    $app='Alta Proveedores';
    $dato=$this->menu_model->load_menu($usr,$app);
    if($dato=="N"){
      $data=array(
        'msg' => '<strong>Atencion: Ud. no esta autorizado a realizar altas de Proveedores, comuniquese con su administrados</strong>',
        'clase'=>'ui-state-error ui-corner-all',
        'icono'=>'ui-icon ui-icon-alert',
        'frm'=>'No'
       );
      $this->load->view('msg_view',$data);
    }else{
      $this->load->view('entradas/alta_proveedor_view');
    }
  }

  function alta_proveedor(){
    
    $usr=$this->session->userdata('usr');
    $this->load->model('contable_model');

    $data = [
      'cuit'=>$this->input->post('frm_cuit'),
      'proveedor'=>$this->input->post('frm_proveedor'),
      'direccion'=>$this->input->post('frm_direccion'),
      'telefono'=>$this->input->post('frm_telefono'),
      'fax'=>$this->input->post('frm_fax'),
      'email'=>$this->input->post('frm_email')
    ];

    $dato=$this->contable_model->alta_proveedor($usr,$data);

    if($dato['msg']=="ok"){
      $data=array(
        'msg' => 'Se dio de alta el proveedor:<strong>"'.$dato['proveedor'].'"</strong><input type="hidden" id="new_codigo" name="new_codigo" value="'.$dato['id_proveedor'].'"><input type="hidden" id="frm_proveedor" name="frm_proveedor" value="'.$dato['proveedor'].'">',
        'clase'=>'ui-state-highlight ui-corner-all',
        'icono'=>'ui-icon ui-icon-info',
        'frm'=>$dato['id_proveedor']
       );
      $this->load->view('msg_view',$data);
    }else{
      $data=array(
        'msg' => '<strong>Atencion: </strong>'.$dato['msg'],
        'clase'=>'ui-state-error ui-corner-all',
        'icono'=>'ui-icon ui-icon-alert',
        'frm'=>'No'
       );
      $this->load->view('msg_view',$data);
    }
  }

  function buscar_remito(){

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

  function remito(){
    $remito=$this->input->get('remito');
    $id_proveedor=$this->input->get('id_proveedor');
    $this->load->model('entradas_model');
    $dato=$this->entradas_model->remito_material($id_proveedor,$remito);

    $this->load->library('Pdf');
    global $titulo;
    global $id_proveedor;
    global $remito;

    foreach ($dato as $key => $value) {

      $nro=$value['nro'];
      $remito=$value['remito'];
      $id_compra=$value['id_compra'];
      $fecha=$value['fecha'];
      $proveedor=$value['proveedor'];
      $id_material=$value['id_material'];
      $descripcion=$value['descripcion'];
      $cantidad=$value['cantidad'];
      $mod_usuario=$value['mod_usuario'];
      $ult_mod=$value['ult_mod'];

    }

    $titulo=$proveedor." Remito Nro:".$remito;
    $pdf=new Pdf();
    $pdf->AliasNbPages();
    $pdf->AddPage('L');
    $pdf->SetFont('Arial','B',8);
    //$pdf->Line(145,0,145,210);
    $pdf->SetFont('Arial','',8);
    $i="";
    $tmp_nro="";

    foreach ($dato as $key => $value) {

      if($tmp_nro==$value['nro']){
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
        $pdf->Cell(220,6,utf8_decode($value['descripcion']).$i,1,0);
        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
        
      }else{
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(230,230,0);
        $pdf->Cell(280,6,'Deposito: '.$value['deposito'].' - Entrada Nro: '.$value['nro'].' - OC: '.$value['id_compra'].' - Usuario:'.$value['mod_usuario'].' - Fecha de Carga:'.substr($value['ult_mod'], 0,19),1,0,'C',true);
        $tmp_nro=$value['nro'];
        $pdf->ln(6);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(20,6,'Codigo',1,0,'C');
        $pdf->Cell(220,6,'Descripcion',1,0,'C');
        $pdf->Cell(20,6,'Unidad',1,0,'C');
        $pdf->Cell(20,6,'Cantidad',1,0,'C');
        $pdf->ln(6);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
        $pdf->Cell(220,6,utf8_decode($value['descripcion']).$i,1,0);
        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R'); 
      }
      $pdf->ln(6);
    }
    $pdf->Output();
  }

  function print_entrada(){
    //$id_entrada=$this->input->get('id_entrada');
    $nro=$this->input->get('nro');
    $id_deposito=$this->input->get('id_deposito');
    //$id_deposito=$this->input->get('id_deposito');
    $this->load->model('entradas_model');
    $dato=$this->entradas_model->print_entrada($nro,$id_deposito);

    $this->load->library('Pdf');
    global $titulo;
    global $id_proveedor;
    global $remito;

    foreach ($dato as $key => $value) {

      $id_entrada=$value['id_entrada'];
      $tipo=$value['tipo'];
      $nro=$value['nro'];
      $deposito=$value['deposito'];
      $compra=$value['compra'];
      $remito=$value['remito'];
      $id_compra=$value['id_compra'];
      $fecha=$value['fecha'];
      $proveedor=$value['proveedor'];
      $id_material=$value['id_material'];
      $descripcion=$value['descripcion'];
      $cantidad=$value['cantidad'];
      $mod_usuario=$value['mod_usuario'];
      $ult_mod=$value['ult_mod'];

    }

    $titulo="Deposito:".$deposito;
    $pdf=new Pdf();
    $pdf->AliasNbPages();
    $pdf->AddPage('L');
    $pdf->SetFont('Arial','B',8);
    //$pdf->Line(145,0,145,210);
    $pdf->SetFont('Arial','',8);
    $i="";
    $tmp_nro="";
    $pdf->SetFont('Arial','',12);
    //$pdf->Cell(20,6,'Compra:',0,0,'L');
    $pdf->Cell(40,6,'Tipo de Movimiento',0,0,'L');
    $pdf->Cell(20,6,utf8_decode($value['tipo']),0,0,'L');
    $pdf->Cell(20,6,'',0,0,'L');
    $pdf->Cell(20,6,'Nro',0,0,'L');
    $pdf->Cell(40,6,$nro,0,0,'L');
    $pdf->Cell(140,6,utf8_decode($value['fecha']),0,0,'R');
    $pdf->ln(6);
    $pdf->Cell(40,6,utf8_decode($value['compra']),0,0,'L');
    $pdf->Cell(20,6,utf8_decode($value['id_compra']),0,0,'L');
    $pdf->ln(6);
    $pdf->Cell(40,6,'Proveedor',0,0,'L');
    $pdf->Cell(40,6,utf8_decode($value['proveedor']),0,0,'L');
    $pdf->Cell(40,6,'Remito',0,0,'L');
    $pdf->Cell(20,6,utf8_decode($value['remito']),0,0,'L');
    $pdf->ln(6);
    
    
    
    
    $pdf->ln(6);



    foreach ($dato as $key => $value) {

      if($tmp_nro==$value['nro']){
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
        $pdf->Cell(220,6,utf8_decode($value['descripcion']).$i,1,0);
        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
        
      }else{
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(230,230,0);
        $pdf->Cell(280,6,'Deposito: '.$value['deposito'].' - Entrada Nro: '.$value['nro'].' - OC: '.$value['id_compra'].' - Usuario:'.$value['mod_usuario'].' - Fecha de Carga:'.substr($value['ult_mod'], 0,19),1,0,'C',true);
        $tmp_nro=$value['nro'];
        $pdf->ln(6);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(20,6,'Codigo',1,0,'C');
        $pdf->Cell(220,6,'Descripcion',1,0,'C');
        $pdf->Cell(20,6,'Unidad',1,0,'C');
        $pdf->Cell(20,6,'Cantidad',1,0,'C');
        $pdf->ln(6);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(20,6,utf8_decode($value['id_material']).$i,1,0,'C');
        $pdf->Cell(220,6,utf8_decode($value['descripcion']).$i,1,0);
        $pdf->Cell(20,6,utf8_decode($value['unidad']),1,0,'C');
        $pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R'); 
      }
      $pdf->ln(6);
    }
    $pdf->Output();
  }


  function lista_entradas(){
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
      $this->load->view('entradas/entradas_buscar_view',$data);
    }
  }


  function get_entradas(){
    $this->load->model('deposito_model');
    $this->load->model('stock_model');
    $usr=$this->session->userdata('usr');
      $data=$this->deposito_model->get_deposito($usr);
      $deposito="";
      foreach ($data as $key) {
        if($deposito==''){
          $deposito.=$key['value'];
        }else{
          $deposito.=",".$key['value'];
        }
      }
      $dato=$this->stock_model->lista_entradas($deposito);
      $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
        ->_display();
      exit;
  }

}