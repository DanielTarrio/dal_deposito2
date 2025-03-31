<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contable extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	function get_personal(){

   		$this->load->model('contable_model');
	    if (isset($_GET['term'])){
	      $q = strtolower($_GET['term']);
	      //$q=$this->limpia_str(utf8_encode($q));
	      $data=$this->contable_model->get_personal($q);
	      //$data=array('pepe','toto');
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
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

   	function personal(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
	   		$data=$this->contable_model->lista_centro_costo($usr);
	   		$centro_costo='';
	   		foreach ($data as $key) {
	   			$centro_costo.=';'.$key['centro_costo'].':'.$key['denominacion'];
		   	}
		   	$data = array('centro_costo' => $centro_costo,
		   		'app'=>'personal');
		
	   		$this->load->view('head');
	   		$this->load->view('contable/personal_view',$data);
   		}
   	}

   	function lista_personal(){
   		$this->load->model('contable_model');
		$data=$this->contable_model->lista_personal();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	
   	}

   	function add_persona(){

   		$this->load->model('contable_model');
   		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'legajo'=>$this->input->get('legajo'),
				'apellido_nombre'=>$this->input->get('apellido_nombre'),
				'centro_costo'=>$this->input->get('centro_costo'),
				'activo'=>$activo
				//'session_usr'=>$this->session->userdata('usr')
				);
		$data=$this->contable_model->add_persona($data);
		return $data;
   	}

   	function edit_persona(){
   		
   		$this->load->model('contable_model');
		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'legajo'=>$this->input->get('legajo'),
				'apellido_nombre'=>$this->input->get('apellido_nombre'),
				'centro_costo'=>$this->input->get('centro_costo'),
				'activo'=>$activo
				//'session_usr'=>$this->session->userdata('usr')
				);
		$data=$this->contable_model->edit_persona($data);
		return 'OK:'.$data;
   	}

   	function centro_costo(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
			$data = array('app' =>'centro de costo');
	   		$this->load->view('head');
	   		$this->load->view('contable/centro_costo_view',$data);
   		}
   	}


   	function get_centro_costo(){
		$this->load->model('contable_model');
		if (isset($_GET['term'])){
			$q = strtolower($_GET['term']);
			$data=$this->contable_model->get_centro_costo($q);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
		}
	}

	function get_denominacion_centro_costo(){
		$this->load->model('contable_model');
		if (isset($_GET['term'])){
			$q = strtolower($_GET['term']);
			$data=$this->contable_model->get_denominacion_centro_costo($q);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
		}
	}

	function lista_centro_costo(){
   		$this->load->model('contable_model');
		$data=$this->contable_model->lista_centro_costo();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	
   	}

   	function add_centro_costo(){

   		$this->load->model('contable_model');
   		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'centro_costo'=>$this->input->get('centro_costo'),
				'denominacion'=>$this->input->get('denominacion'),
				'mod_usuario'=>$this->session->userdata('usr'),
				'ult_mod'=>date('Y-m-d')
				);
		$data=$this->contable_model->add_centro_costo($data);
		return $data;
   	}

   	function edit_centro_costo(){
   		
   		$this->load->model('contable_model');
		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'centro_costo'=>$this->input->get('id'),
				'denominacion'=>$this->input->get('denominacion'),
				'mod_usuario'=>$this->session->userdata('usr'),
				'ult_mod'=>date('Y-m-d')
				);
		$data=$this->contable_model->edit_centro_costo($data);
		return 'OK:'.$data;
   	}   	


   	function unidades(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('contable_model');
			$usr=$this->session->userdata('usr');
			$data = array('app' => 'unidades');
	   		$this->load->view('head');
	   		$this->load->view('contable/unidades_view',$data);
   		}
   	}

	function lista_unidades(){
   		$this->load->model('contable_model');
		$data=$this->contable_model->lista_unidades();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	
   	}

   	function add_unidades(){

   		$this->load->model('contable_model');
   		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'unidad'=>$this->input->get('unidad'),
				'descripcion'=>$this->input->get('descripcion'),
				'mod_usuario'=>$this->session->userdata('usr'),
				'ult_mod'=>date('Y-m-d')
				);
		$data=$this->contable_model->add_unidades($data);
		return $data;
   	}

   	function edit_unidades(){
   		
   		$this->load->model('contable_model');
		$activo=$this->input->get('activo');
   		if($activo=='Si'){
   			$activo=true;
   		}else{
   			$activo=false;
   		}

		$data=array(
				'unidad'=>$this->input->get('id'),
				'descripcion'=>$this->input->get('descripcion'),
				'mod_usuario'=>$this->session->userdata('usr'),
				'ult_mod'=>date('Y-m-d')
				);
		$data=$this->contable_model->edit_unidades($data);
		return 'OK:'.$data;
   	}   	

}