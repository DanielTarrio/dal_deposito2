<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposito extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

   	function index(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			//$usr=$this->session->userdata('usr');
	   		$data=array('app' => 'depositos');
	   		$this->load->view('head');
	   		$this->load->view('depositos/deposito_view',$data);
   		}
   	}

   	function lista_deposito(){

   		$this->load->model('deposito_model');
		$data=$this->deposito_model->lista_deposito();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	

   	}

   	function add_deposito(){

   		$this->load->model('deposito_model');
		$data=array(
				'deposito'=>$this->input->get('deposito'),
				'usr'=>$this->session->userdata('usr')
				);
		$data=$this->deposito_model->add_deposito($data);
		return $data;

   	}

   	function edit_deposito(){

   		$this->load->model('deposito_model');
		$data=array(
				'id_deposito'=>$this->input->get('id'),
				'deposito'=>$this->input->get('deposito'),
				'usr'=>$this->session->userdata('usr')
				);
		$data=$this->deposito_model->edit_deposito($data);
		return $data;

   	}

   	function autor_deposito(){

   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$usr=$this->session->userdata('usr');
	   		$data['deposito']=$this->deposito_model->get_deposito($usr);
	   		$data['app']='autorización deposito';
	   		$this->load->view('head');
	   		$this->load->view('depositos/deposito_aut_view',$data);
   		}

   	}

   	function get_dep_autorizado(){
   		$this->load->model('deposito_model');
		$data=array(
				'id_deposito'=>$this->input->get('id_deposito'),
				'usr'=>$this->session->userdata('usr')
				);
		$data=$this->deposito_model->get_dep_autorizado($data);
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;	
   	}

   	function frm_aut_deposito(){
   		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
	   		$this->load->view('depositos/frm_aut_view');
   		}
   	}

   	function buscar_usr(){
		$this->load->model('usuarios_model');
	    if (isset($_GET['term'])){
	      $q = strtolower($_GET['term']);
	      //$q=$this->limpia_str(utf8_encode($q));
	      $data=$this->usuarios_model->buscar_usr($q);
	      //$data=array('pepe','toto');
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
	    }
	}

	function add_aut_dep(){
		$login=$this->control->control_login();
			if($login==TRUE){
			$this->load->model('deposito_model');
			$data=array(
					'id_deposito'=>$this->input->post('id_deposito'),
					'usuario'=>$this->input->post('usuario'),
					'usr'=>$this->session->userdata('usr')
					);
			$data=$this->deposito_model->add_aut_dep($data);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
			exit;
		}
	}

	function del_usr_exec(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('deposito_model');
			$data=array(
					'id_dep_aut'=>$this->input->post('id_dep_aut'),
					'usr'=>$this->session->userdata('usr')
					);
			$data=$this->deposito_model->del_usr_exec($data);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
			exit;
		}
	}
   	
}