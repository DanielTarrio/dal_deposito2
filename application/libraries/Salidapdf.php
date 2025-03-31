<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/fpdf/fpdf.php";

class Salidapdf extends FPDF { 

	public function __construct() {
    	parent::__construct();
   	}

   		//Cabecera de página
   	

	function Header(){

		global $id_salida;
		global $nro;
		global $id_deposito;
		global $deposito;
		global $retira;
		global $nombre;
		global $centro_costo;
		global $denominacion;
		global $odt;
		global $destino;
		global $fecha;
		global $tipo_mov;
		global $mod_usuario;

	    //Logo
	    //$this->Image(base_url().'images/logo.png',10,10,10);
		//$this->Image(base_url().'images/logo.png',150,10,10);
		$this->Image('images/logo.png',10,10,10);//Por problemas en protocolo https
		$this->Image('images/logo.png',150,10,10);
	    //Arial bold 15
	    $this->SetFont('Arial','B',12);
	    //Movernos a la derecha
	    $this->Cell(30,10,'',1,0,'C');
		$this->Cell(60,10,'Vale de '.$tipo_mov,1,0,'C');
		$this->Cell(40,10,'Nro. '.$nro,1,0,'L');
		
		$this->Cell(10,6,'',0,0,'C');
		
		$this->Cell(30,10,'',1,0,'C');
		$this->Cell(60,10,'Vale de '.$tipo_mov,1,0,'C');
		$this->Cell(40,10,'Nro. '.$nro,1,1,'L');
		
		$this->SetFont('Arial','B',10);
		$this->Ln(2);
		$this->Cell(30,6,'Deposito:',0,0);
		$this->Cell(40,6,$deposito,0,0);
		$this->Cell(60,6,'Fecha: '.$fecha,0,0,'R');
		
		$this->Cell(10,6,'',0,0,'C');
		
		$this->Cell(30,6,'Deposito:',0,0);
		$this->Cell(40,6,$deposito,0,0);
		$this->Cell(60,6,'Fecha: '.$fecha,0,1,'R');
		
		$this->Cell(30,6,'Centro de Costo:',0,0);
		$this->Cell(100,6,$centro_costo.' - '.$denominacion,0,0,'L');
		
		$this->Cell(10,6,'',0,0,'C');
		
		$this->Cell(30,6,'Centro de Costo:',0,0);
		$this->Cell(100,6,$centro_costo.' - '.$denominacion,0,1,'L');
		
		$this->Cell(30,6,'Retira:',0,0);
		$this->Cell(100,6,$retira.' - '.$nombre,0,0,'L');
		
		$this->Cell(10,6,'',0,0,'C');
		
		$this->Cell(30,6,'Retira:',0,0);
		$this->Cell(100,6,$retira.' - '.$nombre,0,1,'L');
		
		$this->Cell(30,6,'Destino:',0,0);
		$this->Cell(100,6,$odt.' - '.$destino,0,0,'L');
		
		$this->Cell(10,6,'',0,0,'C');
		
		$this->Cell(30,6,'Destino:',0,0);
		$this->Cell(100,6,$odt.' - '.$destino,0,1,'L');
		
	    //Título
	    $this->Ln(5);
	}

//Pie de página
	function Footer(){

		global $id_salida;
		global $nro;
		global $id_deposito;
		global $deposito;
		global $retira;
		global $nombre;
		global $centro_costo;
		global $denominacion;
		global $odt;
		global $destino;
		global $fecha;
		global $tipo_mov;
		global $mod_usuario;
		
	    //Posición: a 1,5 cm del final
	    $this->SetY(-25);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Número de página
	    $this->Cell(130,10,'Despacho:['.$mod_usuario.'] - Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Cell(10,6,'',0,0,'C');
		$this->Cell(130,10,'Despacho:['.$mod_usuario.'] - Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
	


	
