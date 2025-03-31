<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	function __construct() {
      parent::__construct();
   	}

	public function index()
	{
		//$this->load->helper('form');
		$this->load->model('menu_model');
		//$usr=$this->session->userdata('usr');
		$app='menu';
		$this->crear_menu($app);
		//$this->load->view('head');
		//$this->load->view('menu/menu_view');
	}

	public function crear_menu($app)
	{
		$login=$this->control->control_login();
		if($login==TRUE){
			$this->load->model('menu_model');
			$this->load->model('stock_model');
			$usr=$this->session->userdata('usr');
			$dato=$this->menu_model->load_menu($usr,$app); //1 en id_menu ppal
			$nivel=intval($dato['nivel']);
			$lft=$dato['lft'];
			$rgt=$dato['rgt'];
			$nivel=$nivel+1;
			$menu=$this->menu_model->permisos_menu($usr,$nivel,$lft,$rgt);
			$path_menu=$this->menu_model->path_menu($app);
	   		$alarmas_stock=$this->stock_model->alarmas_stock($usr);
			$data=array(
				'info'=>$dato,
				'menu'=>$menu,
				'path_menu'=>$path_menu,
				'alarmas_stock'=>$alarmas_stock
			);
			//$this->load->view('head');
			$this->load->view('menu/menu_view',$data);
		}else{
			$this->load->view('head');
			redirect(base_url().'index.php/login');
		}
	}

	function config_aplicaciones(){
		$this->load->model('menu_model');
		$data = array('app' => 'aplicaciones');
		$this->load->view('head');
		$this->load->view('menu/menu_config_view',$data);
	}

	function lista_aplicaciones(){
		$this->load->model('menu_model');
		$data=$this->menu_model->lista_aplicaciones();
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit;
	}

	function add_aplicacion(){

		$usr=$this->session->userdata('usr');
		$this->load->model('menu_model');
		$data=array(
			'aplicacion'=>$this->input->get('name'),
			'id_aplicacion'=>$this->input->get('parent_id'),
			'orden_menu'=>$this->input->get('orden_menu'),
			'menu'=>$this->input->get('menu'),
			'imagen'=>$this->input->get('imagen'),
			'path'=>$this->input->get('path')
		);
		$add=$this->menu_model->add_aplicacion($data,$usr);
		//return $data;

	}

	function edit_aplicacion(){
		$this->load->model('menu_model');
		$data=array(
			'aplicacion'=>$this->input->get('name'),
			'id_aplicacion'=>$this->input->get('id'),
			'orden_menu'=>$this->input->get('orden_menu'),
			'menu'=>$this->input->get('menu'),
			'imagen'=>$this->input->get('imagen'),
			'path'=>$this->input->get('path')
		);
		$add=$this->menu_model->edit_aplicacion($data);
		return print_r($add);
		
	}

}
