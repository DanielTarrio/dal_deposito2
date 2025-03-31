<?php require('fpdf.php');?>
<?php require_once('Connections/CONN_STOCK.php'); ?>
<?php require('javascript/fx.php');?>
<?php
$colname_Recordset1 = 191;
if (isset($_GET['nro'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['nro'] : addslashes($_GET['nro']);
}
if (isset($_GET['id_deposito'])) {
  $id_deposito = (get_magic_quotes_gpc()) ? $_GET['id_deposito'] : addslashes($_GET['id_deposito']);
}
mysql_select_db($database_CONN_STOCK, $CONN_STOCK);
$query_Recordset1 = sprintf("SELECT s.id_salida,s.nro, s.id_deposito, d.deposito, s.fecha, s.retira,
							p.apellido_nombre, s.centro_costo,c.denominacion, s.odt, s.destino, s.mod_usuario, t.tipo
							FROM salida s,deposito d, centro_costo c, personal p, tipo_movimiento t
							where s.nro=%s
							and s.id_deposito=%s 
							and s.id_deposito=d.id_deposito
							and s.centro_costo=c.centro_costo
							and s.retira=p.legajo
                and s.id_tipo_mov=t.id_tipo_mov", $colname_Recordset1,$id_deposito);
//echo $query_Recordset1;				
$Recordset1 = mysql_query($query_Recordset1, $CONN_STOCK) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = $row_Recordset1['id_salida'];
/*if (isset($_POST['id_salida'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_POST['id_salida'] : addslashes($_POST['id_salida']);
}*/
mysql_select_db($database_CONN_STOCK, $CONN_STOCK);
$query_Recordset2 = sprintf("SELECT detalle_salida.id_material, material.unidad, detalle_salida.cantidad, material.descripcion 
FROM detalle_salida, material WHERE material.id_material=detalle_salida.id_material AND detalle_salida.id_salida = %s", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $CONN_STOCK) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);


	$id_salida=$row_Recordset1['id_salida'];
	$nro=$row_Recordset1['nro'];
	$id_deposito=$row_Recordset1['id_deposito'];
	$deposito=$row_Recordset1['deposito'];
	$retira=$row_Recordset1['retira'];
	$odt=$row_Recordset1['odt'];
	$destino=$row_Recordset1['destino'];
	$nombre=$row_Recordset1['apellido_nombre'];
	$centro_costo=$row_Recordset1['centro_costo'];
	$denominacion=$row_Recordset1['denominacion'];
	$fecha=ParseDMYtoYMD($row_Recordset1['fecha'],"/");
	/*if ( ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $regs ) ) {
		$fecha="$regs[3]/$regs[2]/$regs[1]";
	}*/
	$despacho=$row_Recordset1['mod_usuario'];
	$tipo_mov=$row_Recordset1['tipo'];

class PDF extends FPDF
{
	
	
//Cabecera de página
function Header()
{
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
	global $PosX;
	global $PosY;
	global $fecha;
	global $tipo_mov;
	
	
	
    //Logo
    $this->Image('images/logo.png',10,10,10);
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
function Footer()
{
global $despacho;
    //Posición: a 1,5 cm del final
    $this->SetY(-25);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(130,10,'Despacho:['.$despacho.'] - Page '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Cell(10,6,'',0,0,'C');
	$this->Cell(130,10,'Despacho:['.$despacho.'] - Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetFont('Times','B',8);
$pdf->Line(145,0,145,210);
$pdf->Image('images/cut2.png',142.5,30,5);
$pdf->Image('images/cut.png',142.5,180,5);

	$pdf->Cell(15,6,'Código',1,0,'C');
	$pdf->Cell(85,6,'Descripción',1,0,'C');
	$pdf->Cell(10,6,'Und',1,0,'C');
	$pdf->Cell(20,6,'Cantidad',1,0,'C');
	//---------
	$pdf->Cell(10,6,'',0,0,'C');
	//---------------
	$pdf->Cell(15,6,'Código',1,0,'C');
	$pdf->Cell(85,6,'Descripción',1,0,'C');
	$pdf->Cell(10,6,'Und',1,0,'C');
	$pdf->Cell(20,6,'Cantidad',1,1,'C');
$pdf->SetFont('Times','',8);
$i="";
do{
	$pdf->Cell(15,6,$row_Recordset2['id_material'].$i,1,0,'R');
	$pdf->Cell(85,6,utf8_decode($row_Recordset2['descripcion']).$i,1,0);
	$pdf->Cell(10,6,utf8_decode($row_Recordset2['unidad']).$i,1,0);
	$pdf->Cell(20,6,number_format($row_Recordset2['cantidad'],2),1,0,'R');
	//---------
	$pdf->Cell(10,6,'',0,0,'C');
	//---------------
	$pdf->Cell(15,6,$row_Recordset2['id_material'].$i,1,0,'R');
	$pdf->Cell(85,6,utf8_decode($row_Recordset2['descripcion']).$i,1,0);
	$pdf->Cell(10,6,utf8_decode($row_Recordset2['unidad']).$i,1,0);
	$pdf->Cell(20,6,number_format($row_Recordset2['cantidad'],2),1,1,'R');
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
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




mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
