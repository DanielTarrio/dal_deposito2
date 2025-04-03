<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

	public function index()
	{
		$this->load->helper('form');
		$data=array(
				'msg'=>''
			);
		$this->load->view('head');
		$this->load->view('login/login_view',$data);
	}

	function login_usuario()
	{
		//$this->load->library('Control');
		$login=$this->control->control_metodo($this->input->post('usr'),$this->input->post('psw'));
		if($login=="Ok"){
			$this->control->control_login();
		/*	$data=array(
				'usr'=>$this->input->post('usr'),
				'clase'=>$login,
				'autenticado'=>TRUE
				//'session_usr'=>$this->session->userdata('usr')
				);
			$this->session->set_userdata($data);
		*/	//$this->control->control_login($this->input->post('usr'));
			redirect(base_url().'index.php/menu');
			// $this->load->view('\menu\menu_ppal.php',$data);
		}else{
			$data=array(
				'msg'=>'Usuario inexistente o contraseña incorrecta'
			);
			$this->load->helper('form');
			$this->load->view('head');
			$this->load->view('login/login_view',$data);

		}
	}
	function logout_usuario(){

		$usr=$this->session->userdata('usr');
		$data=array(
			'usr'=>$usr,
			'session_status'=>$this->control->logout($usr)
		);
		$this->load->view('head');
		$this->load->view('login/logout_view',$data);
	}
}
