<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zonas extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	function index(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('zonas_model');
			$usr=$this->session->userdata('usr');
			$data = array('app' => 'zonas');
			$this->load->view('head');
			$this->load->view('zonas/zonas_view',$data);
		}
   	}

   	function lista_zonas(){

   		$this->load->model('zonas_model');
		$data=$this->zonas_model->lista_zonas();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	

   	}

   	function frm_zona(){

   		
		$usr=$this->session->userdata('usr');
		$q=$this->input->get('id_zona');
		
		$this->load->model('zonas_model');
		
		if($q!=""){
			$data=$this->zonas_model->get_cod_dependencia($q);
			$data = array(
					'id_zona' => $data[0]['id_zona'],
					'id_dependencia' => $data[0]['id_dependencia'],
					'dependencia' => $data[0]['dependencia'],
					'Zona' => $data[0]['Zona'],
					'Direccion' => $data[0]['Direccion'],
					'Localidad' => $data[0]['Localidad'],
					'activa' => $data[0]['activa'],
					'CP' => $data[0]['CP'],
					'Telefono' => $data[0]['Telefono'],
					'email' => $data[0]['email'],
					'Responsable'=>$data[0]['Responsable']
					);
		}else{
			$data = array(
					'id_zona' => '',
					'id_dependencia' => '',
					'dependencia' => '',
					'Zona' => '',
					'Direccion' => '',
					'Localidad' => '',
					'activa' => '',
					'CP' => '',
					'Telefono' => '',
					'email' => '',
					'Responsable'=> ''
					);
		}
		$this->load->view('zonas/frm_zonas_view',$data);
		
		
   	}

   	function add_zona(){

   		$this->load->model('zonas_model');

   		if (strlen($this->input->post('frm_id_dependencia'))>0){
   			$tmp=$this->input->post('frm_id_dependencia');
   		}else{
   			$tmp='NULL';
   		}
		$data=array(
				'Zona'=>$this->input->post('frm_zona'),
				'Direccion'=>$this->input->post('frm_direccion'),
				'Localidad'=>$this->input->post('frm_Localidad'),
				'CP'=>$this->input->post('frm_CP'),
				'activa'=>$this->input->post('frm_activa'),
				'id_dependencia'=>$tmp,
				'usr'=>$this->session->userdata('usr')
				);
		$dato=$this->zonas_model->add_zona($data);
		unset($data);

		if($dato['msg']=="ok"){
			$data=array(
				'estatus' => 'ok',
				'clase'=>'u-state-highlight ui-corner-all',
				'icono'=>'ui-icon ui-icon-info',
				'Zona'=> $dato['Zona'],
				'Direccion'=> $dato['Direccion'],
				'Localidad'=> $dato['Localidad'],
				'CP'=> $dato['CP'],
				'frm'=> $dato['id_zona']
			);
			//$this->load->view('msg_view',$data);
		}else{
			$data=array(
				'estatus' => 'ERROR',
				'clase'=>'ui-state-error ui-corner-all',
				'icono'=>'ui-icon ui-icon-alert',
				'frm'=>'No'
			);
			//$this->load->view('msg_view',$data);
		}
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;

   	}

   	function edit_zona(){
   		$this->load->model('zonas_model');

   		if (strlen($this->input->post('frm_id_dependencia'))>0){
   			$tmp=$this->input->post('frm_id_dependencia');
   		}else{
   			$tmp='NULL';
   		}
		$data=array(
				'id_zona'=>$this->input->post('frm_id_zona'),
				'Zona'=>$this->input->post('frm_zona'),
				'Direccion'=>$this->input->post('frm_direccion'),
				'Localidad'=>$this->input->post('frm_Localidad'),
				'CP'=>$this->input->post('frm_CP'),
				'activa'=>$this->input->post('frm_activa'),
				'id_dependencia'=>$tmp,
				'usr'=>$this->session->userdata('usr')
				);
		$dato=$this->zonas_model->edit_zona($data);
		unset($data);

		if($dato['msg']=="ok"){
			$data=array(
				'estatus' => 'ok',
				'Zona'=> $dato['Zona'],
				'Direccion'=> $dato['Direccion'],
				'Localidad'=> $dato['Localidad'],
				'CP'=> $dato['CP'],
				'frm'=> $dato['id_zona']
			);
			//$this->load->view('msg_view',$data);
		}else{
			$data=array(
				'estatus' => 'ERROR',
				'frm'=>'No'
			);
			//$this->load->view('msg_view',$data);
		}
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
   	}

}