<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/fpdf/fpdf.php";

class Salidapdf_P extends FPDF { 

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
		global $localidad;
		global $Direccion;
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
		$this->Cell(140,10,'PARTE DE TRANSFERENCIA DE MATERIALES '.$tipo_mov,1,0,'C');
		$this->Cell(40,10,'Nro. '.$nro,1,1,'L');
		$this->Cell(10,6,'',0,1,'C');
		$this->SetFont('Arial','B',10);
		$this->Ln(2);
		$this->Cell(30,6,'Deposito:',0,0);
		$this->Cell(50,6,$deposito,0,0);
		$this->Cell(30,6,'Nota de Pedido:',0,0,'R');
		$this->Cell(30,6,$nro_pedido,1,0,'C');
		$this->Cell(50,6,'Fecha: '.$fecha,0,1,'R');
		
		$this->Cell(30,6,'Centro de Costo:',0,0); 
		$this->Cell(100,6,$centro_costo.' - '.utf8_decode($denominacion),0,1,'L');
		
		$this->Cell(30,6,'Dependencia:',0,0);
		$this->Cell(60,6,utf8_decode($Zona),0,0,'L');
		$this->Cell(70,6,utf8_decode($Direccion),0,0,'L');
		$this->Cell(20,6,utf8_decode($localidad),0,1,'L');

		$this->Cell(30,6,'Sector:',0,0);
		$this->Cell(100,6,utf8_decode($sector),0,1,'L');

		$this->Cell(30,6,'ODT:',0,0);
		$this->Cell(30,6,utf8_decode($odt),0,0,'L');
		$this->Cell(5,6,'',0,0);
		$this->Cell(35,6,'Observaciones:',0,0);
		$this->Cell(90,6,utf8_decode($destino),0,1,'L');

		$this->Cell(30,6,'Legajo:',0,0,'L');
		$this->Cell(30,6,utf8_decode($retira),1,0,'L');
		$this->Cell(5,6,'',0,0);
		$this->Cell(35,6,'Apellido y Nombre:',0,0,'L');
		$this->Cell(90,6,utf8_decode($nombre),1,1,'L');
		/*
		$this->Cell(30,6,'Destino:',0,0);
		$this->Cell(100,6,$odt.' - '.$destino,0,1,'L');
		*/
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
	    $this->Cell(200,10,'Despacho:['.$mod_usuario.'] - Pag. '.$this->PageNo().'/{nb}',0,0,'C');
		
	}
}
	


	
