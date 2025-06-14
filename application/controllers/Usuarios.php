<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	function perfiles(){

		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('usuarios_model');
			$usr=$this->session->userdata('usr');
	   		$data['perfiles']=$this->usuarios_model->get_perfiles();
	   		$data['app']='perfiles';
			$this->load->view('head');
			$this->load->view('usuarios/perfiles_view',$data);
		}

	}

	function set_perfil(){
		$login=$this->control->control_login();
		$usr=$this->session->userdata('usr');
		if($login==TRUE){
			$data=$this->input->post('perfil');
			$this->load->model('usuarios_model');
			$data=$this->usuarios_model->set_perfil($data,$usr);
			$this->output
		   	->set_status_header(200)
		   	->set_content_type('application/json', 'utf-8')
		   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		   	->_display();
		   	exit;
		}
	}

	function permisos(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('usuarios_model');
			$perfil=$this->input->get('id_perfil');
	   		$data=$this->usuarios_model->get_permisos($perfil);
			$this->output
		   	->set_status_header(200)
		   	->set_content_type('application/json', 'utf-8')
		   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		   	->_display();
		   	exit;
		}
	}

	function get_permiso_movimiento(){
		$this->load->model('usuarios_model');
		$perfil=4; //$this->input->get('id_perfil');
   		$data=$this->usuarios_model->get_permiso_movimiento($perfil);
		$this->output
	   	->set_status_header(200)
	   	->set_content_type('application/json', 'utf-8')
	   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	   	->_display();
	   	exit;
	}

	function add_permiso(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('usuarios_model');
			$id_perfil=$this->input->post('id_perfil');
			$id_aplicacion=$this->input->post('id_aplicacion');
			$usr=$this->session->userdata('usr');
	   		$data=$this->usuarios_model->set_permisos($id_perfil,$id_aplicacion,$usr);
			$this->output
		   	->set_status_header(200)
		   	->set_content_type('application/json', 'utf-8')
		   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		   	->_display();
		   	exit;
		}

	}

	function del_permiso(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('usuarios_model');
			$id_perfil=$this->input->post('id_perfil');
			$id_aplicacion=$this->input->post('id_aplicacion');
			$usr=$this->session->userdata('usr');
	   		$data=$this->usuarios_model->del_permisos($id_perfil,$id_aplicacion);
			$this->output
		   	->set_status_header(200)
		   	->set_content_type('application/json', 'utf-8')
		   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		   	->_display();
		   	exit;
		}

	}

	function lista_usuarios(){

		$login=$this->control->control_login();
		$data = array('app' => 'usuarios');
		
		if($login==TRUE){
			$this->load->view('head');
			$this->load->view('usuarios/usuarios_view',$data);
		}
	}

	function get_lista_usr(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('usuarios_model');
			//$perfil=$this->input->get('id_perfil');
	   		$data=$this->usuarios_model->get_lista_usr();
			$this->output
		   	->set_status_header(200)
		   	->set_content_type('application/json', 'utf-8')
		   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		   	->_display();
		   	exit;
		}
	}

	function psw_admin(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
			if($usr=='admin'){
				$data=array('admin'=>'OK');
				$this->load->view('usuarios/usr_admin',$data);
			}else{
				$data=array('admin'=>'NO');
				$this->load->view('usuarios/usr_admin',$data);
			}
		}

	}

	function set_pws_admin(){
		$login=$this->control->control_login();
		$psw=$this->input->post('pws1');
		if($login==TRUE){
			$usr=$this->session->userdata('usr');
			if($usr=='admin'){
				$this->load->model('usuarios_model');
				$data=$this->usuarios_model->pws_admin($psw);
			}else{
				$data = array(
					'estatus' => 'ERROR',
					'msg'=>'No se pudo actualizar el password'
				);
			}
		}
		$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		        ->_display();
		exit;
	}

	function edit_usr(){
		$login=$this->control->control_login();
		if($login==TRUE){
			$usuario=$this->input->get('usuario');
			$this->load->model('usuarios_model');
			$perfil=$this->usuarios_model->get_perfiles();
			if($usuario!=""){
				$data=$this->usuarios_model->get_usr_status($usuario);
				$dato = array('usuario'=>$data['usuario'],
					'id_sector'=>$data['id_sector'],
					'sector'=>$data['sector'],
					'apellido_nombre'=>$data['apellido_nombre'],
					'id_perfil'=>$data['id_perfil'],
					'perfil'=>$data['perfil'],
					'clave'=>$data['clave'],
					'conectado'=>$data['conectado'],
					'bloqueado'=>$data['bloqueado'],
					'error_login'=>$data['error_login'],
					'direccion_ip'=>$data['direccion_ip'],
					'inicio_session'=>$data['inicio_session'],
					'session_time'=>$data['session_time'],
					'activo'=>$data['activo'],
					'perfiles'=>$perfil,
				'tiempo'=>$data['tiempo'] );
			}else{
				$dato = array('usuario'=>'',
					'id_sector'=>'',
					'sector'=>'',
					'apellido_nombre'=>'',
					'id_perfil'=>'',
					'perfil'=>'',
					'clave'=>'',
					'conectado'=>'0',
					'bloqueado'=>'0',
					'error_login'=>'',
					'direccion_ip'=>'',
					'inicio_session'=>'',
					'session_time'=>'',
					'activo'=>'1',
					'perfiles'=>$perfil,
				'tiempo'=>'');
			}
			$this->load->view('usuarios/usr_view',$dato);
		}

	}

	function get_sector(){
		$this->load->model('contable_model');
	    if (isset($_GET['term'])){
	      $q = strtolower($_GET['term']);
	      $data=$this->contable_model->get_sector($q);
	      $this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
	        ->_display();
	      exit;
	    }
	}

	function add_usr(){
		$login=$this->control->control_login();
		if($login==TRUE){
			
			$data = array( 
				'accion'=>$this->input->post('accion'),
				'usuario'=>$this->input->post('usuario'),
				'apellido_nombre'=>$this->input->post('apellido_nombre'),
				'id_sector'=>$this->input->post('id_sector'),
				'id_perfil'=>$this->input->post('id_perfil'),
				'bloqueado'=>$this->input->post('bloqueado'),
				'conectado'=>$this->input->post('conectado'),
				'activo'=>$this->input->post('activo'),
				'usr'=>$this->session->userdata('usr')
			);
			$this->load->model('usuarios_model');
			$dato=$this->usuarios_model->add_usr($data);
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		        ->_display();
		    exit;

 
		}

	}

}