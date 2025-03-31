<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	function get_clase(){
   		$this->load->model('material_model');
	    if (isset($_GET['term'])){
	      $q = strtolower($_GET['term']);
	      $data=$this->material_model->get_clase($q);
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
	    }
   	}

   	function get_sub_clase(){
	    $this->load->model('material_model');
	    if (isset($_GET['term'])){
	      $data['sub_clase']=$this->input->get('term');
	      $data['id_clase']=$this->input->get('id_clase');
	      $data=$this->material_model->get_sub_clase($data);
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
		}
	}

	function get_unidad(){
		$this->load->model('material_model');
	    if (isset($_GET['term'])){
	      $q = strtolower($_GET['term']);
	      $data=$this->material_model->get_unidad($q);
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
	    }
	}

   	function frm_material(){
   		//$this->load->view('head');

   		$this->load->model('menu_model');
		$usr=$this->session->userdata('usr');
		$app='Alta Material';
		$dato=$this->menu_model->load_menu($usr,$app);
		$q=$this->input->get('id_material');

		if($dato=="N"){
			$data=array(
				'msg' => '<strong>Atencion: Ud. no esta autorizado a realizar altas de catalogo, comuniquese con su administrados</strong>',
				'clase'=>'ui-state-error ui-corner-all',
				'icono'=>'ui-icon ui-icon-alert',
				'frm'=>'No'
			 );
			$this->load->view('msg_view',$data);
		}else{
			$this->load->model('material_model');
			
			if($q!=""){
				$data=$this->material_model->get_cod_material($q);
				$data = array(
						'id_clase' => $data[0]['id_clase'],
						'clase' => $data[0]['clase'],
						'id_sub_clase' => $data[0]['id_sub_clase'],
						'sub_clase' => $data[0]['sub_clase'],
						'id_material' => $data[0]['value'],
						'descripcion' => $data[0]['label'],
						'unidad' => $data[0]['unidad'],
						'barcode'=>$data[0]['barcode']
						);
			}else{
				$data = array(
						'id_clase' => '',
						'clase' => '',
						'id_sub_clase' => '',
						'sub_clase' => '',
						'id_material' => '',
						'descripcion' => '',
						'unidad' => '',
						'barcode'=>''
						);
			}
			$this->load->view('material/alta_material_view',$data);
		}
		
   	}

   	function alta_material(){
   		$usr=$this->session->userdata('usr');
		$this->load->model('material_model');
		$data = [
			'id_clase'=>$this->input->post('frm_id_clase'),
			'id_sub_clase'=>$this->input->post('frm_id_sub_clase'),
			'descripcion'=>$this->input->post('frm_material'),
			'barcode'=>$this->input->post('frm_Codebar'),
			'unidad'=>$this->input->post('frm_unidad'),
		];
		$dato=$this->material_model->alta_catalogo($usr,$data);
		if($dato['msg']=="ok"){
			$data=array(
				'msg' => 'Se dio de alta el codigo:<strong>"'.$dato['id_material'].'"</strong><input type="hidden" id="new_codigo" name="new_codigo" value="'.$dato['id_material'].'">',
				'clase'=>'ui-state-highlight ui-corner-all',
				'icono'=>'ui-icon ui-icon-info',
				'frm'=>$dato['id_material']
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

   	function editar_catalogo(){
   		$usr=$this->session->userdata('usr');
		$this->load->model('material_model');
		$data = [
			'id_material'=>$this->input->post('frm_id_material'),
			'id_clase'=>$this->input->post('frm_id_clase'),
			'id_sub_clase'=>$this->input->post('frm_id_sub_clase'),
			'descripcion'=>$this->input->post('frm_material'),
			'barcode'=>$this->input->post('frm_Codebar'),
			'unidad'=>$this->input->post('frm_unidad')
		];
		$dato=$this->material_model->editar_catalogo($usr,$data);
		if($dato['msg']=="ok"){
			$data=array(
				'msg' => 'Se dio de actualizo el codigo:<strong>"'.$dato['id_material'].'"</strong><input type="hidden" id="new_codigo" name="new_codigo" value="'.$dato['id_material'].'">',
				'clase'=>'ui-state-highlight ui-corner-all',
				'icono'=>'ui-icon ui-icon-info',
				'frm'=>$dato['id_material']
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

   	function material(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['app']='materiales';
			$this->load->view('head');
			$this->load->view('material/material_view',$data);
		}

   	}

   	function catalogo_mat(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
			$this->load->model('material_model');
			$q="";
			$data=$this->material_model->get_clase($q);
			$clase='';
	   		foreach ($data as $key) {
	   			$clase.=';'.$key['value'].':'.$key['label'];
		   	}
		   	$data = array('clase' => $clase,
		   				'app'=>'catalogo');
			$this->load->view('head');
			$this->load->view('material/catalogo_view',$data);
		}
   	}

   	function get_catalogo(){
   		$this->load->model('material_model');
   		 
   		$data=array(
	   		'id_clase'=>$this->input->get('id_clase'),
	   		'id_sub_clase'=>$this->input->get('id_sub_clase'),
	   		'id_material'=>$this->input->get('id_material'),
	   		'descripcion'=>$this->input->get('material'),
	   		'barcode'=>$this->input->get('barcode')
   		);
		$data=$this->material_model->get_catalogo($data);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	    
   	}

   	function edit_costo_mat(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
		}
   		$this->load->model('material_model');
   		$data=array(
	   		'id_material'=>$this->input->post('id_material'),
	   		'barcode'=>$this->input->post('barcode'),
	   		'costo_pp'=>$this->input->post('costo_pp'),
	   		'costo_ult'=>$this->input->post('costo_ult')
   		);
		$data=$this->material_model->edit_costo_mat($data,$usr);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
   	}

   	//-----------------------------------------------

   	function max_min(){

   		$this->load->model('menu_model');
		$usr=$this->session->userdata('usr');
		$app='Material';
		$dato=$this->menu_model->load_menu($usr,$app);

		$id_material=$this->input->get('id_material');
		$id_deposito=$this->input->get('id_deposito');

		if($dato=="N"){
			$data=array(
				'msg' => '<strong>Atencion: Ud. no esta autorizado a realizar altas de catalogo, comuniquese con su administrados</strong>',
				'clase'=>'ui-state-error ui-corner-all',
				'icono'=>'ui-icon ui-icon-alert',
				'frm'=>'No'
			 );
			$this->load->view('msg_view',$data);

		}else{

			$this->load->model('material_model');
			$this->load->model('stock_model');
			if($id_material!=""){
				$data=$this->material_model->get_cod_material($id_material);
				$data2 = array(
					'id_deposito' => $id_deposito,
					'id_material'=> $id_material
					);
				$data2=$this->stock_model->get_cod_stock($data2);
				$dato = array(
						'id_clase' => $data[0]['id_clase'],
						'clase' => $data[0]['clase'],
						'id_sub_clase' => $data[0]['id_sub_clase'],
						'sub_clase' => $data[0]['sub_clase'],
						'id_material' => $data[0]['value'],
						'descripcion' => $data[0]['label'],
						'unidad' => $data[0]['unidad'],
						'barcode'=>$data[0]['barcode'],
						//-------------$data2--------------
						'cantidad'=>$data2[0]['cantidad'],
						'minimo'=>$data2[0]['minimo'],
						'reposicion'=>$data2[0]['reposicion'],
						'ubicacion'=>$data2[0]['ubicacion']
						);
			}
			$this->load->view('material/material_dep_view',$dato);
		}
	}

	function excel_catalogo(){

   		$this->load->library('excel');
   		$this->excel->setActiveSheetIndex(0);
   		$this->load->model('material_model');
   		$data=array(
	   		'id_clase'=>'',
	   		'id_sub_clase'=>'',
	   		'id_material'=>'',
	   		'descripcion'=>'',
	   		'barcode'=>''
   		);
		$data=$this->material_model->get_catalogo($data);

	    $i=0;

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

	    $this->excel->getActiveSheet()->fromArray($hoja);
	    $filname="Catalogo";
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filname="'.$filname.'.xlsx"');
	    header('Cache-Control: max-age=0');
	    $objWriter=PHPExcel_IOFactory::createWriter($this->excel,'Excel2007');
	    $objWriter->save('php://output');
   	}

   	function grabar_max_min(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
		}
   		$this->load->model('stock_model');
   		$data=array(
   			'id_stock'=>$this->input->post('id_stock'),
   			'id_deposito'=>$this->input->post('id_deposito'),
	   		'id_material'=>$this->input->post('id_material'),
	   		'minimo'=>$this->input->post('minimo'),
	   		'reposicion'=>$this->input->post('reposicion'),
	   		'ubicacion'=>$this->input->post('ubicacion')
   		);
		$data=$this->stock_model->edit_max_min($data,$usr);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
   	}

   	function ub_deposito(){

   		$deposito=$this->input->get('id_deposito');
   		$data = array('id_deposito' => $deposito );
   		$this->load->view('material/ub_mat_view',$data);

   	}

   	function ub_mat(){

   		$this->load->model('ubicacion_model');
		$deposito=$this->input->get('id_deposito');
		$data=$this->ubicacion_model->get_ubicacion($deposito);
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;

   	}


}