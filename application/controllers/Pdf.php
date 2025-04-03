<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {

	function __construct() {
      parent::__construct();
      
   	}

	public function vale_salida(){

		//$this->load->library('Salidapdf');
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
		global $destino;
		global $bultos;
		global $sector;
		global $id_zona;
		global $Zona;
		global $localidad;
		global $Direccion;
		global $fecha;
		global $tipo_mov;
		global $mod_usuario;

		$this->load->model('stock_model');
		$this->load->library('Salidapdf_P');
		$id_deposito=$this->input->get('id_deposito');
		$id_salida=$this->input->get('id_salida');
		$dato=$this->stock_model->vale_salida($id_salida);


		

		foreach ($dato as $key => $value) {

			$id_salida=$value['id_salida'];
			$nro=$value['nro'];
			$nro_pedido=$value['nro_pedido'];
			$id_deposito=$value['id_deposito'];
			$deposito=$value['deposito'];
			$retira=$value['retira'];
			$nombre=$value['apellido_nombre'];
			$centro_costo=$value['centro_costo'];
			$denominacion=$value['denominacion'];
			$odt=$value['odt'];
			$destino=utf8_decode($value['destino']);
			$bultos=$value['bultos'];
			$sector=utf8_decode($value['sector']);
			$id_zona=$value['id_zona'];
			$Zona=$value['Zona'];
			$localidad=$value['localidad'];
			$Direccion=$value['Direccion'];
			$fecha=$value['fecha'];
			$tipo_mov=$value['tipo'];
			$mod_usuario=$value['mod_usuario'];

		}


		$pdf=new Salidapdf_P();
		$pdf->AliasNbPages();
		$pdf->AddPage('P');
		$pdf->SetFont('Times','',8);
		/*
		$pdf->Line(145,0,145,210);
		$pdf->Image('images/cut2.png',142.5,30,5);
		$pdf->Image('images/cut.png',142.5,180,5);
		*/
		$pdf->Cell(15,6,utf8_decode('Código'),1,0,'C');
		$pdf->Cell(95,6,utf8_decode('Descripción'),1,0,'C');
		$pdf->Cell(20,6,'Costo',1,0,'C');
		$pdf->Cell(20,6,'Unidad',1,0,'C');
		$pdf->Cell(20,6,'Cantidad',1,0,'C');
		$pdf->Cell(20,6,utf8_decode('Costo Linea'),1,1,'C');
		$pdf->SetFont('Times','',8);
		$i="";
		$x1=$pdf->GetX();
		$y1=$pdf->GetY();
		$corte="";
		$sub_total=0;
		$total=0;
		foreach ($dato as $key => $value) {
			if($corte!=$value['clase']){

				if($corte!=""){
					$pdf->SetFillColor(230,230,230);
					$pdf->SetFont('Times','B',8);
					$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
					$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
					$sub_total=0;
					$pdf->SetFont('Times','',8);
					$pdf->Ln(2);
				}

				$pdf->Ln(2);
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell(80,8,utf8_decode($value['clase']),1,1,'L',true);
				$corte=$value['clase'];
				$pdf->Ln(2);
			}
			$tmp='X: '.$pdf->GetX().'; Y: '.$pdf->GetY();
			$pdf->Cell(15,6,$value['barcode'],0,0,'C');
			$xl=$pdf->GetX();
			$yl=$pdf->GetY();
			$pdf->MultiCell(95,6,utf8_decode($value['descripcion']),0,'J',0);
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->SetXY(120,($y-6));
			$pdf->Line(10,$y,200,$y);
			$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),0,0,'R');
			$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
			$pdf->Cell(20,6,number_format($value['cantidad'],2),0,0,'R');
			$pdf->Cell(20,6,'$ '.number_format($value['linea'],2),0,1,'R');
			$total+=$value['linea'];
			$sub_total+=$value['linea'];
		}

		$pdf->SetFillColor(230,230,230);
		$pdf->SetFont('Times','B',8);
		$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
		$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
		$sub_total=0;
		$pdf->SetFont('Times','',8);
		$xf=$pdf->GetX();
		$yf=$pdf->GetY();
		$pdf->Ln(2);
		$pdf->SetFillColor(200,220,255);
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(90,8,'TOTAL :','LTB',0,'L',true);
		$pdf->Cell(100,8,'$ '.number_format($total,2),'RTB',1,'R',true);

		/*
		$pdf->Line(10,$y1,10,$yf);
		$pdf->Line(25,$y1,25,$yf);
		$pdf->Line(110,$y1,110,$yf);
		$pdf->Line(120,$y1,120,$yf);
		$pdf->Line(140,$y1,140,$yf);
		*/
		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(190,6,'Observaciones','LTR',1);
		$pdf->Cell(190,15,'','LBR',1);
		//$pdf->Ln(20);
		$pdf->SetFont('Arial','B',6);
		//----------------------
		$pdf->Cell(80,6,'EMITIO',0,0);
		$pdf->Cell(30,6,'',0,0);
		$pdf->Cell(80,6,'RECIBE',0,1);
		//----------------------
		$pdf->Cell(80,10,'','LTR',0);
		$pdf->Cell(30,10,'',0,0);
		$pdf->Cell(80,10,'','LTR',1);
		//----------------------
		$pdf->Cell(40,3,'........................................','L',0,'C');
		$pdf->Cell(40,3,'........................................','R',0,'C');
		$pdf->Cell(30,3,'',0,0);
		$pdf->Cell(40,3,'........................................','L',0,'C');
		$pdf->Cell(40,3,'........................................','R',1,'C');
		
		$pdf->Cell(40,6,'FIRMA','LB',0,'C');
		$pdf->Cell(40,6,'ACLARACION','BR',0,'C');
		$pdf->Cell(30,6,'',0,0);
		$pdf->Cell(40,6,'FIRMA','LB',0,'C');
		$pdf->Cell(40,6,'ACLARACION','BR',1,'C');
		//----------------------
		$pdf->Ln(2);
		$pdf->SetFillColor(200,220,255);
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(190,6,'Enviar Formulario Firmado al Deposito de Utiles DAL',1,0,'C',true);

		$pdf->Output();
	}

	//=======================================================================

	public function vale_salidaId(){
//http://localhost/utiles/index.php/pdf/vale_salidaId?id_salida=2096,2095
		//$this->load->library('Salidapdf');
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
		global $destino;
		global $bultos;
		global $sector;
		global $id_zona;
		global $Zona;
		global $localidad;
		global $Direccion;
		global $fecha;
		global $tipo_mov;
		global $mod_usuario;

		$this->load->model('stock_model');
		$this->load->library('Salidapdf_P');
		$id_salida=$this->input->get('id_salida');
		$dato=$this->stock_model->vale_salidaId($id_salida);
		$SalidaId="";
		$corte="";

		$pdf=new Salidapdf_P();
		$pdf->AliasNbPages();
		foreach ($dato as $key => $value) {
			if($SalidaId!=$value['id_salida']){
				$corte="";
				if($SalidaId!=""){

					$pdf->SetFillColor(230,230,230);
					$pdf->SetFont('Times','B',8);
					$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
					$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
					$sub_total=0;
					$pdf->SetFont('Times','',8);
					$xf=$pdf->GetX();
					$yf=$pdf->GetY();
					$pdf->Ln(2);
					$pdf->SetFillColor(200,220,255);
					$pdf->SetFont('Times','B',10);
					$pdf->Cell(90,8,'TOTAL :','LTB',0,'L',true);
					$pdf->Cell(100,8,'$ '.number_format($total,2),'RTB',1,'R',true);

					$pdf->Ln(5);
					$pdf->SetFont('Arial','B',6);
					$pdf->Cell(190,6,'Observaciones','LTR',1);
					$pdf->Cell(190,15,'','LBR',1);
					//$pdf->Ln(20);
					$pdf->SetFont('Arial','B',6);
					//----------------------
					$pdf->Cell(80,6,'EMITIO',0,0);
					$pdf->Cell(30,6,'',0,0);
					$pdf->Cell(80,6,'RECIBE',0,1);
					//----------------------
					$pdf->Cell(80,10,'','LTR',0);
					$pdf->Cell(30,10,'',0,0);
					$pdf->Cell(80,10,'','LTR',1);
					//----------------------
					$pdf->Cell(40,3,'........................................','L',0,'C');
					$pdf->Cell(40,3,'........................................','R',0,'C');
					$pdf->Cell(30,3,'',0,0);
					$pdf->Cell(40,3,'........................................','L',0,'C');
					$pdf->Cell(40,3,'........................................','R',1,'C');
					
					$pdf->Cell(40,6,'FIRMA','LB',0,'C');
					$pdf->Cell(40,6,'ACLARACION','BR',0,'C');
					$pdf->Cell(30,6,'',0,0);
					$pdf->Cell(40,6,'FIRMA','LB',0,'C');
					$pdf->Cell(40,6,'ACLARACION','BR',1,'C');
					//----------------------
					$pdf->Ln(2);
					$pdf->SetFillColor(200,220,255);
					$pdf->SetFont('Times','B',10);
					$pdf->Cell(190,6,'Enviar Formulario Firmado al Deposito de Utiles DAL',1,0,'C',true);

					if($corte!=""){
						$pdf->SetFillColor(230,230,230);
						$pdf->SetFont('Times','B',8);
						$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
						$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
						$sub_total=0;
						$pdf->SetFont('Times','',8);
						$pdf->Ln(2);
					}
				}
				//encabezado vale
				$id_salida=$value['id_salida'];
				$nro=$value['nro'];
				$nro_pedido=$value['nro_pedido'];
				$id_deposito=$value['id_deposito'];
				$deposito=$value['deposito'];
				$retira=$value['retira'];
				$nombre=$value['apellido_nombre'];
				$centro_costo=$value['centro_costo'];
				$denominacion=$value['denominacion'];
				$odt=$value['odt'];
				$destino=utf8_decode($value['destino']);
				$bultos=$value['bultos'];
				$sector=utf8_decode($value['sector']);
				$id_zona=$value['id_zona'];
				$Zona=$value['Zona'];
				$localidad=$value['localidad'];
				$Direccion=$value['Direccion'];
				$fecha=$value['fecha'];
				$tipo_mov=$value['tipo'];
				$mod_usuario=$value['mod_usuario'];
				$pdf->AddPage('P');
				$pdf->SetFont('Times','',8);
				$pdf->Cell(15,6,utf8_decode('Código'),1,0,'C');
				$pdf->Cell(95,6,utf8_decode('Descripción'),1,0,'C');
				$pdf->Cell(20,6,'Costo',1,0,'C');
				$pdf->Cell(20,6,'Unidad',1,0,'C');
				$pdf->Cell(20,6,'Cantidad',1,0,'C');
				$pdf->Cell(20,6,utf8_decode('Costo Linea'),1,1,'C');
				$pdf->SetFont('Times','',8);
				$i="";
				$x1=$pdf->GetX();
				$y1=$pdf->GetY();
				$sub_total=0;
				$total=0;		
				$SalidaId=$value['id_salida'];	
//========
				if($corte!=$value['clase']){

					if($corte!=""){
						$pdf->SetFillColor(230,230,230);
						$pdf->SetFont('Times','B',8);
						$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
						$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
						$sub_total=0;
						$pdf->SetFont('Times','',8);
						$pdf->Ln(2);
					}

					$pdf->Ln(2);
					$pdf->SetFillColor(200,220,255);
					$pdf->Cell(80,8,utf8_decode($value['clase']),1,1,'L',true);
					$corte=$value['clase'];
					$pdf->Ln(2);
				}
				$tmp='X: '.$pdf->GetX().'; Y: '.$pdf->GetY();
				$pdf->Cell(15,6,$value['barcode'],0,0,'C');
				$xl=$pdf->GetX();
				$yl=$pdf->GetY();
				$pdf->MultiCell(95,6,utf8_decode($value['descripcion']),0,'J',0);
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->SetXY(120,($y-6));
				$pdf->Line(10,$y,200,$y);
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),0,0,'R');
				$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),0,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['linea'],2),0,1,'R');
				$total+=$value['linea'];
				$sub_total+=$value['linea'];
 
			}else{
				if($corte!=$value['clase']){

					if($corte!=""){
						$pdf->SetFillColor(230,230,230);
						$pdf->SetFont('Times','B',8);
						$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
						$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
						$sub_total=0;
						$pdf->SetFont('Times','',8);
						$pdf->Ln(2);
					}

					$pdf->Ln(2);
					$pdf->SetFillColor(200,220,255);
					$pdf->Cell(80,8,utf8_decode($value['clase']),1,1,'L',true);
					$corte=$value['clase'];
					$pdf->Ln(2);
				}
				$tmp='X: '.$pdf->GetX().'; Y: '.$pdf->GetY();
				$pdf->Cell(15,6,$value['barcode'],0,0,'C');
				$xl=$pdf->GetX();
				$yl=$pdf->GetY();
				$pdf->MultiCell(95,6,utf8_decode($value['descripcion']),0,'J',0);
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->SetXY(120,($y-6));
				$pdf->Line(10,$y,200,$y);
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),0,0,'R');
				$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),0,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['linea'],2),0,1,'R');
				$total+=$value['linea'];
				$sub_total+=$value['linea'];
			}

		}
/*
		//$pdf->AddPage('P');
		foreach ($dato as $key => $value) {

				$id_salida=$value['id_salida'];
				$nro=$value['nro'];
				$nro_pedido=$value['nro_pedido'];
				$id_deposito=$value['id_deposito'];
				$deposito=$value['deposito'];
				$retira=$value['retira'];
				$nombre=$value['apellido_nombre'];
				$centro_costo=$value['centro_costo'];
				$denominacion=$value['denominacion'];
				$odt=$value['odt'];
				$destino=utf8_decode($value['destino']);
				$bultos=$value['bultos'];
				$sector=utf8_decode($value['sector']);
				$id_zona=$value['id_zona'];
				$fecha=$value['fecha'];
				$tipo_mov=$value['tipo'];
				$mod_usuario=$value['mod_usuario'];
				$pdf->AddPage('P');
				$pdf->SetFont('Times','',8);
				$pdf->Cell(15,6,utf8_decode('Código'),1,0,'C');
				$pdf->Cell(95,6,utf8_decode('Descripción'),1,0,'C');
				$pdf->Cell(20,6,'Costo',1,0,'C');
				$pdf->Cell(20,6,'Unidad',1,0,'C');
				$pdf->Cell(20,6,'Cantidad',1,0,'C');
				$pdf->Cell(20,6,utf8_decode('Costo Linea'),1,1,'C');
				$pdf->SetFont('Times','',8);
				$i="";
				$x1=$pdf->GetX();
				$y1=$pdf->GetY();
				$corte="";
				$sub_total=0;
				$total=0;				

//------------------------------------------------------

			
			foreach ($dato as $key => $value) {
				if($corte!=$value['clase']){

					if($corte!=""){
						$pdf->SetFillColor(230,230,230);
						$pdf->SetFont('Times','B',8);
						$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
						$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
						$sub_total=0;
						$pdf->SetFont('Times','',8);
						$pdf->Ln(2);
					}

					$pdf->Ln(2);
					$pdf->SetFillColor(200,220,255);
					$pdf->Cell(80,8,utf8_decode($value['clase']),1,1,'L',true);
					$corte=$value['clase'];
					$pdf->Ln(2);
				}
				$tmp='X: '.$pdf->GetX().'; Y: '.$pdf->GetY();
				$pdf->Cell(15,6,$value['barcode'],0,0,'C');
				$xl=$pdf->GetX();
				$yl=$pdf->GetY();
				$pdf->MultiCell(95,6,utf8_decode($value['descripcion']),0,'J',0);
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->SetXY(120,($y-6));
				$pdf->Line(10,$y,200,$y);
				$pdf->Cell(20,6,'$ '.number_format($value['costo_ult'],2),0,0,'R');
				$pdf->Cell(20,6,utf8_decode($value['unidad']),0,0,'C');
				$pdf->Cell(20,6,number_format($value['cantidad'],2),0,0,'R');
				$pdf->Cell(20,6,'$ '.number_format($value['linea'],2),0,1,'R');
				$total+=$value['linea'];
				$sub_total+=$value['linea'];
			
			}

			$pdf->SetFillColor(230,230,230);
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
			$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
			$sub_total=0;
			$pdf->SetFont('Times','',8);
			$xf=$pdf->GetX();
			$yf=$pdf->GetY();
			$pdf->Ln(2);
			$pdf->SetFillColor(200,220,255);
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(90,8,'TOTAL :','LTB',0,'L',true);
			$pdf->Cell(100,8,'$ '.number_format($total,2),'RTB',1,'R',true);

			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',6);
			$pdf->Cell(190,6,'Observaciones','LTR',1);
			$pdf->Cell(190,15,'','LBR',1);
			//$pdf->Ln(20);
			$pdf->SetFont('Arial','B',6);
			//----------------------
			$pdf->Cell(80,6,'EMITIO',0,0);
			$pdf->Cell(30,6,'',0,0);
			$pdf->Cell(80,6,'RECIBE',0,1);
			//----------------------
			$pdf->Cell(80,10,'','LTR',0);
			$pdf->Cell(30,10,'',0,0);
			$pdf->Cell(80,10,'','LTR',1);
			//----------------------
			$pdf->Cell(40,3,'........................................','L',0,'C');
			$pdf->Cell(40,3,'........................................','R',0,'C');
			$pdf->Cell(30,3,'',0,0);
			$pdf->Cell(40,3,'........................................','L',0,'C');
			$pdf->Cell(40,3,'........................................','R',1,'C');
			
			$pdf->Cell(40,6,'FIRMA','LB',0,'C');
			$pdf->Cell(40,6,'ACLARACION','BR',0,'C');
			$pdf->Cell(30,6,'',0,0);
			$pdf->Cell(40,6,'FIRMA','LB',0,'C');
			$pdf->Cell(40,6,'ACLARACION','BR',1,'C');
			//----------------------
			$pdf->Ln(2);
			$pdf->SetFillColor(200,220,255);
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(190,6,'Enviar Formulario Firmado al Deposito de Utiles DAL',1,0,'C',true);
			

//-----------------------------------------------------

		}*/

		$pdf->SetFillColor(230,230,230);
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(150,6,'Sub total '.$corte,1,0,'L');
			$pdf->Cell(40,6,'$ '.number_format($sub_total,2),1,1,'R',true);
			$sub_total=0;
			$pdf->SetFont('Times','',8);
			$xf=$pdf->GetX();
			$yf=$pdf->GetY();
			$pdf->Ln(2);
			$pdf->SetFillColor(200,220,255);
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(90,8,'TOTAL :','LTB',0,'L',true);
			$pdf->Cell(100,8,'$ '.number_format($total,2),'RTB',1,'R',true);

			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',6);
			$pdf->Cell(190,6,'Observaciones','LTR',1);
			$pdf->Cell(190,15,'','LBR',1);
			//$pdf->Ln(20);
			$pdf->SetFont('Arial','B',6);
			//----------------------
			$pdf->Cell(80,6,'EMITIO',0,0);
			$pdf->Cell(30,6,'',0,0);
			$pdf->Cell(80,6,'RECIBE',0,1);
			//----------------------
			$pdf->Cell(80,10,'','LTR',0);
			$pdf->Cell(30,10,'',0,0);
			$pdf->Cell(80,10,'','LTR',1);
			//----------------------
			$pdf->Cell(40,3,'........................................','L',0,'C');
			$pdf->Cell(40,3,'........................................','R',0,'C');
			$pdf->Cell(30,3,'',0,0);
			$pdf->Cell(40,3,'........................................','L',0,'C');
			$pdf->Cell(40,3,'........................................','R',1,'C');
			
			$pdf->Cell(40,6,'FIRMA','LB',0,'C');
			$pdf->Cell(40,6,'ACLARACION','BR',0,'C');
			$pdf->Cell(30,6,'',0,0);
			$pdf->Cell(40,6,'FIRMA','LB',0,'C');
			$pdf->Cell(40,6,'ACLARACION','BR',1,'C');
			//----------------------
			$pdf->Ln(2);
			$pdf->SetFillColor(200,220,255);
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(190,6,'Enviar Formulario Firmado al Deposito de Utiles DAL',1,0,'C',true);

		$pdf->Output();
	}

	//=======================================================================

	public function remito(){

		$this->load->library('Pdf');
		global $titulo;
		global $id_proveedor;
		global $remito;
		
		$this->load->model('entradas_model');
		$this->load->library('Pdf');
		$id_proveedor=$this->input->get('id_proveedor');
		$remito=$this->input->get('remito');
		$dato=$this->entradas_model->remito_material($id_proveedor,$remito);
/*
		foreach ($dato as $key => $value) {

			$nro=$value['nro'];
			$id_proveedor=$value['id_proveedor'];
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
*/

		$pdf=new Pdf();
		$pdf->AliasNbPages();
		$pdf->AddPage('L');
		$pdf->SetFont('Arial','B',8);
		$pdf->Line(145,0,145,210);
		$pdf->SetFont('Arial','',8);
		$i="";
/*
		foreach ($dato as $key => $value) {

			$pdf->Cell(15,6,$value['id_material'].$i,1,0,'R');
			$pdf->Cell(85,6,utf8_decode($value['descripcion']).$i,1,0);
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

		*/
		$pdf->Cell(40,6,'Aclaración','T',1,'C');
		//for($i=1;$i<=40;$i++)
		
		$pdf->Output();
	}

	public function vale_salida_P(){
		//$this->load->library('Salidapdf');
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
		$this->load->library('Salidapdf_P');
		$id_deposito=$this->input->get('id_deposito');
		$id_salida=$this->input->get('id_salida');
		$dato=$this->stock_model->vale_salida($id_salida);

		foreach ($dato as $key => $value) {

			$id_salida=$value['id_salida'];
			$nro=$value['nro'];
			$nro_pedido=$value['nro_pedido'];
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


		$pdf=new Salidapdf_P();
		$pdf->AliasNbPages();
		$pdf->AddPage('P');
		$pdf->SetFont('Times','B',8);
		
		$pdf->Cell(20,6,utf8_decode('Código'),1,0,'C');
		$pdf->Cell(120,6,utf8_decode('Descripción'),1,0,'C');
		$pdf->Cell(20,6,'Und',1,0,'C');
		$pdf->Cell(30,6,'Cantidad',1,1,'C');

		
		$pdf->SetFont('Times','',8);
		$i="";
		$x1=$pdf->GetX();
		$y1=$pdf->GetY();
		foreach ($dato as $key => $value) {
			$tmp='X: '.$pdf->GetX().'; Y: '.$pdf->GetY();
			$pdf->Cell(20,6,$value['id_material'].$i,0,0,'C');
			$xl=$pdf->GetX();
			$yl=$pdf->GetY();
			$pdf->MultiCell(120,6,utf8_decode($value['descripcion']).$i,0,'J',0);
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->SetXY(150,($y-6));
			$pdf->Line(10,$y,200,$y);
			$pdf->Cell(20,6,utf8_decode($value['unidad']).$i,0,0,'C');
			$pdf->Cell(30,6,number_format($value['cantidad'],2),0,1,'R');

		}
		$xf=$pdf->GetX();
		$yf=$pdf->GetY();
		$pdf->Line(10,$y1,10,$yf);
		$pdf->Line(30,$y1,30,$yf);
		$pdf->Line(150,$y1,150,$yf);
		$pdf->Line(170,$y1,170,$yf);
		$pdf->Line(200,$y1,200,$yf);


		$pdf->Ln(20);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(45,6,'',0,0);
		$pdf->Cell(40,6,'Firma Autorizante','T',0,'C');
		$pdf->Cell(20,6,'',0,0);
		$pdf->Cell(40,6,utf8_decode('Firma Autorizado'),'T',0,'C');


		//for($i=1;$i<=40;$i++)
		
		$pdf->Output();
	}




}

