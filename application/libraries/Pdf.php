<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF { 

	public function __construct() {
    	parent::__construct();
   	}

   		//Cabecera de página
   	public function index(){
   		global $titulo;

   	}
   	

	function Header(){
		global $titulo;
		
		$this->SetFont('Arial','B',12);
	    //Logo
	    //$this->Image(base_url().'images/logo.png',10,10,10);
	    $this->Image('images/logo.png',10,10,10);//Por problemas en protocolo https
	    //$this->Cell(80,20,'p',1);
	    $this->Cell(280,10,$titulo,1,0,'C');
	    $this->Ln(20);

		
	   
	}

//Pie de página
	function Footer(){

		
		$mod_usuario='pepe';
	    //Posición: a 1,5 cm del final
	    $this->SetY(-25);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Número de página
		$this->Cell(280,10,'Fecha:'.Date("d/m/Y").' - Pag.'.$this->PageNo().'/{nb}',0,0,'C');
	}
}
	


	
