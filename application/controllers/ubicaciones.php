<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicaciones extends CI_Controller {

	function __construct() {
      parent::__construct();
	}
	
	function index(){
   
      $deposito=$this->input->get('id_deposito');
      $data = array('id_deposito' => $deposito );
      $this->load->view('head');
      $this->load->view('ubicaciones/ubicaciones_view',$data);
	
   }


	function get_ubicaciones(){

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

   function edit_ubicaciones(){

      $this->load->model('ubicacion_model');
      $data=array(
        'ubicacion'=>$this->input->get('name'),
        'id_ubicacion'=>$this->input->get('id')

        //'session_usr'=>$this->session->userdata('usr')
      );
      $add=$this->ubicacion_model->edit_ubicacion($data);
      return print_r($add);
   }

  function add_ubicaciones(){

      $usr=$this->session->userdata('usr');
      $this->load->model('ubicacion_model');
      $data=array(
         'ubicacion'=>$this->input->get('name'),
         'id_ubicacion'=>$this->input->get('parent_id')
      );
      $add=$this->ubicacion_model->add_ubicacion($data,$usr);
      return print_r($add);
  }

   function del_ubicaciones(){
    
    $this->load->model('ubicacion_model');
    $data=array(
      'ubicacion'=>$this->input->get('name'),
      'id_ubicacion'=>$this->input->get('id_ubicacion'),
      'id_deposito'=>$this->input->get('id_deposito')
      //'session_usr'=>$this->session->userdata('usr')
    );
    $add=$this->ubicacion_model->del_ubicacion($data);
    return print_r($add);

   }   

}