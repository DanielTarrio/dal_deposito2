<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sectores extends CI_Controller {

	function __construct() {
      parent::__construct();
	}
	
	function index(){
    
      $data = array('app' => 'Sectores' );
      $this->load->view('head');
      $this->load->view('sectores/sectores_view',$data);
	
   }


	function get_sectores(){

   	$this->load->model('sectores_model');

   	$data=$this->sectores_model->get_sectores();
   	$this->output
   	->set_status_header(200)
   	->set_content_type('application/json', 'utf-8')
   	->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
   	->_display();
   	exit;
	}

   function edit_sectores(){

      $this->load->model('sectores_model');
      $data=array(
        'sector'=>$this->input->get('name'),
        'id_sector'=>$this->input->get('id')

        //'session_usr'=>$this->session->userdata('usr')
      );
      $add=$this->sectores_model->edit_sectores($data);
      return print_r($add);
   }

  function add_sectores(){

      $usr=$this->session->userdata('usr');
      $this->load->model('sectores_model');
      $data=array(
         'sector'=>$this->input->get('name'),
         'id_sector'=>$this->input->get('parent_id')
      );
      $add=$this->sectores_model->add_sectores($data,$usr);
      return print_r($add);
  }

   function del_sectores(){
    
    $this->load->model('sectores_model');
    $data=array(
      'id_sector'=>$this->input->get('id')
    );
    $add=$this->sectores_model->del_sectores($data);
    return print_r($add);

   }   

}