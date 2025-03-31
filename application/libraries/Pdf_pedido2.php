<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// se cambio Nombre de archivo pdf_pedido2.php =>  Pdf_pedido2.php

require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf_pedido2 extends FPDF { 

	public function __construct() {
    	parent::__construct();
   	}

   		//Cabecera de página
   	

	function Header(){

		global $id_salida;
		global $nro;
		global $nro_pedido;
		global $id_deposito;
		global $deposito;
		global $retira;
		global $nombre;
		global $centro_costo;
		global $denominacion;
		global $odt;
		global $bultos;
		global $sector;
		global $id_zona;
		global $Zona;
		global $Direccion;
		global $Localidad;
		global $destino;
		global $fecha;
		global $tipo_mov;
		global $mod_usuario;

	    //Logo
	    $this->Image('images/logo.png',10,10,10);
		
	    //Arial bold 15
	    $this->SetFont('Arial','B',12);
	    //Movernos a la derecha
	    $this->Cell(10,10,'',1,0,'C');
		$this->Cell(180,10,'PLANILLA DE PREPARACION DE PEDIDOS',1,1,'C');
		/*$this->Cell(40,10,'Nro. '.$nro,1,1,'L');*/
		$this->Cell(10,6,'',0,1,'C');
		$this->SetFont('Arial','B',10);

		$this->Ln(2);

		$this->Cell(20,6,'Fecha: ',0,0,'L');
		$this->Cell(30,6,$fecha,1,0,'C');
		$this->Cell(110,6,'Nota de Pedido:',0,0,'R');
		$this->Cell(30,6,''.$nro,1,1,'C');
		$this->Ln(1);
		$this->Cell(20,6,'Sector:',0,0);
		$this->Cell(60,6,utf8_decode($sector),1,0,'L');		
		$this->Cell(80,6,'Centro de Costo:',0,0,'R'); 
		$this->Cell(30,6,$centro_costo,1,1,'C');
		$this->Ln(1);
		$this->SetFont('Arial','',8);
		$this->Cell(20,6,'Dependencia:',0,0);
		$this->Cell(30,6,utf8_decode($Zona),1,0,'L');
		$this->Cell(20,6,'Direccion:',0,0,'R');
		$this->Cell(70,6,utf8_decode($Direccion),1,0,'L');
		$this->Cell(20,6,'Localidad:',0,0,'R');
		$this->Cell(30,6,utf8_decode($Localidad),1,1,'L');
		$this->Ln(1);
		$this->Cell(20,6,'Solicitante:',0,0);
		$this->Cell(100,6,$destino,1,1,'L');
		
	    //Título
	    $this->SetFont('Arial','B',10);
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
	    $this->Cell(200,10,'Despacho:['.$mod_usuario.'] - Pag. '.$this->PageNo().'/{nb}',0,0,'C');
		
	}
}