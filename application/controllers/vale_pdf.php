docker compose<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vale_pdf extends CI_Controller {

	function __construct() {
      parent::__construct();
      $this->load->library('Salidapdf');
   	}


	public function vale_salida(){

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

		$this->load->model('stock_model');
		$this->load->library('Salidapdf');
		$id_deposito=$this->input->get('id_deposito');
		$nro=$this->input->get('nro');
		$dato=$this->stock_model->vale_salida($id_deposito,$nro);

		foreach ($dato as $key => $value) {

			$id_salida=$value['id_salida'];
			$nro=$value['nro'];
			$id_deposito=$value['id_deposito'];
			$deposito=$value['deposito'];
			$retira=$value['retira'];
			$nombre=$value['apellido_nombre'];
			$centro_costo=$value['centro_costo'];
			$denominacion=$value['denominacion'];
			$odt=$value['odt'];
			$destino=$value['destino'];
			$fecha=$value['fecha'];
			$tipo_mov=$value['tipo'];
			$mod_usuario=$value['mod_usuario'];

		}


		$pdf=new Salidapdf();
		$pdf->AliasNbPages();
		$pdf->AddPage('L');
		$pdf->SetFont('Times','B',8);
		$pdf->Line(145,0,145,210);
		//$pdf->Image(base_url().'images/cut2.png',142.5,30,5);
		//$pdf->Image(base_url().'images/cut.png',142.5,180,5);
		$pdf->Image('images/cut2.png',142.5,30,5);//por problemas en protocolo https
		$pdf->Image('images/cut.png',142.5,180,5);

		$pdf->Cell(15,6,utf8_decode('Código'),1,0,'C');
		$pdf->Cell(85,6,utf8_decode('Descripción'),1,0,'C');
		$pdf->Cell(10,6,'Und',1,0,'C');
		$pdf->Cell(20,6,'Cantidad',1,0,'C');
		//---------
		$pdf->Cell(10,6,'',0,0,'C');
		//---------------
		$pdf->Cell(15,6,utf8_decode('Código'),1,0,'C');
		$pdf->Cell(85,6,utf8_decode('Descripción'),1,0,'C');
		$pdf->Cell(10,6,'Und',1,0,'C');
		$pdf->Cell(20,6,'Cantidad',1,1,'C');
		$pdf->SetFont('Times','',8);
		$i="";

		foreach ($dato as $key => $value) {

			$pdf->Cell(15,6,$value['id_material'].$i,1,0,'R');
			$pdf->Cell(85,40,utf8_decode($value['descripcion']).$i,1,'J',0);
			$pdf->Cell(10,6,utf8_decode($value['unidad']).$i,1,0);
			$pdf->Cell(20,6,number_format($value['cantidad'],2),1,0,'R');
			//---------
			$pdf->Cell(10,6,'',0,0,'C');
			//---------------
			$pdf->Cell(15,6,$value['id_material'].$i,1,0,'R');
			$pdf->Cell(85,6,utf8_decode($value['descripcion']).$i,1,0);
			$pdf->Cell(10,6,utf8_decode($value['unidad']).$i,1,0);
			$pdf->Cell(20,6,number_format($value['cantidad'],2),1,1,'R');

		}

		$pdf->Ln(20);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(15,6,'',0,0);
		$pdf->Cell(40,6,'Firma','T',0,'C');
		$pdf->Cell(15,6,'',0,0);
		$pdf->Cell(40,6,'Aclaración','T',0,'C');

		$pdf->Cell(30,6,'',0,0,'C');

		$pdf->Cell(15,6,'',0,0);
		$pdf->Cell(40,6,'Firma','T',0,'C');
		$pdf->Cell(15,6,'',0,0);
		$pdf->Cell(40,6,'Aclaración','T',1,'C');
		//for($i=1;$i<=40;$i++)
		
		$pdf->Output();
	}


}

