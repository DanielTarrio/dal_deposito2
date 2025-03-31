<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

	function get_stock($data){

		$id_deposito=$data['deposito'];
	  	$descripcion=$data['descripcion'];


		//$this->load->library('Control');
		$long_str=strlen($descripcion);
		$descripcion=$this->control->limpia_str($descripcion);
		$a = preg_split("/[\s\%]+/", $descripcion);

		$where="";

		foreach ($a as $v) {
			if($v!=NULL){
				if($where==""){
					$where=" where m.descripcion like '%".$v."%'";
				}else{
					$where=$where." and m.descripcion like '%".$v."%'";
				}
			}
		}


		$Sql="select p.id_material, m.descripcion, sum(p.cantidad-p.despachado) as sum from ".BBDD_ODBC_SQLSRV."detalle_pedido p, ".BBDD_ODBC_SQLSRV."material m ".$where." and p.id_material=m.id_material and p.id_deposito=".$id_deposito." and completo=0 group by p.id_material, m.descripcion;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$pedidoArray[$row['id_material']]=$row['sum'];
		    }
		}
	     
		$Sql="select s.id_stock, s.id_material, m.descripcion, m.barcode, m.unidad, s.cantidad, m.costo_ult, m.costo_pp, s.pedido, s.ubicacion from ".BBDD_ODBC_SQLSRV."stock s, ".BBDD_ODBC_SQLSRV."material m ".$where." and m.id_material=s.id_material and s.id_deposito=".$id_deposito.";";

		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		      //$new_row['label']=$row['descripcion']; //build an array
		    	$new_row['value']=utf8_encode($row['id_material']);
				$new_row['label']=utf8_encode($row['descripcion']);
				$new_row['id_stock']=utf8_encode($row['id_stock']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['barcode']=utf8_encode($row['barcode']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['costo_ult']=utf8_encode($row['costo_ult']);
				$new_row['costo_pp']=utf8_encode($row['costo_pp']);
				$new_row['ubicacion']=utf8_encode($row['ubicacion']);
				if(isset($pedidoArray)){
					if(array_key_exists($row['id_material'], $pedidoArray)){
						$new_row['pedido']=$pedidoArray[$row['id_material']];
					}else{
						$new_row['pedido']=0;
					}	
				}else{
					$new_row['pedido']=0;
				}
				

				$row_set[] = $new_row; //build an array
				$i++;
		    	if($i>31){
		    		break;
		    	}
		    }
		    return $row_set; //format the array into json data
		}
		//return $where;//." largo: ".$long_str."<br>".$q;

	}

	function get_cod_stock($data){

			$id_deposito=$data['id_deposito'];
	  	$id_material=$data['id_material'];


		$Sql="select p.id_material, sum(p.cantidad) as sum from".BBDD_ODBC_SQLSRV."detalle_pedido p where p.id_material=".$id_material." and p.id_deposito=".$id_deposito."  and completo=0 group by p.id_material;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$pedidoArray[$row['id_material']]=$row['sum'];
		    }
		}


		$where="select s.id_stock, s.id_material, m.descripcion, m.unidad, m.barcode, s.cantidad, s.pedido, s.minimo, s.reposicion, s.ubicacion, m.costo_ult, m.costo_pp from ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."stock s where m.id_material=s.id_material and m.id_material = ".$id_material." and s.id_deposito=".$id_deposito.";";

      	$query = $this->db->query($where);

      	if($query->num_rows() > 0){
	        foreach ($query->result_array() as $row){
	        	$new_row['id_material']=utf8_encode($row['id_material']);
				$new_row['descripcion']=utf8_encode($row['descripcion']);
				$new_row['id_stock']=utf8_encode($row['id_stock']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['barcode']=utf8_encode($row['barcode']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['minimo']=utf8_encode($row['minimo']);
				$new_row['reposicion']=utf8_encode($row['reposicion']);
				$new_row['ubicacion']=utf8_encode($row['ubicacion']);
				$new_row['costo_ult']=utf8_encode($row['costo_ult']);
				$new_row['costo_pp']=utf8_encode($row['costo_pp']);
				if(isset($pedidoArray)){
					if(array_key_exists($row['id_material'], $pedidoArray)){
						$new_row['pedido']=$pedidoArray[$row['id_material']];
					}else{
						$new_row['pedido']=0;
					}	
				}else{
					$new_row['pedido']=0;
				}



				$row_set[] = $new_row;
	        }
        return $row_set; //format the array into json data
      }
	}

	function get_barcode_stock($data){

		$id_deposito=$data['id_deposito'];
	  	$barcode=$data['barcode'];

	  	$Sql="select p.id_material, m.barcode, sum(p.cantidad) as sum from ".BBDD_ODBC_SQLSRV."detalle_pedido p, ".BBDD_ODBC_SQLSRV."material m where p.id_material=m.id_material and m.barcode='".$barcode."' and p.id_deposito=".$id_deposito." and completo=0 group by p.id_material, m.barcode;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$pedidoArray[$row['id_material']]=$row['sum'];
		    }
		}

		$where="select s.id_stock, s.id_material, m.descripcion, m.unidad, m.barcode, s.cantidad, s.pedido, s.ubicacion, m.costo_ult, m.costo_pp from ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."stock s where m.id_material=s.id_material and m.barcode = '".$barcode."' and s.id_deposito=".$id_deposito.";";

      	$query = $this->db->query($where);

      	if($query->num_rows() > 0){
	        foreach ($query->result_array() as $row){
	        	$new_row['id_material']=utf8_encode($row['id_material']);
				$new_row['descripcion']=utf8_encode($row['descripcion']);
				$new_row['id_stock']=utf8_encode($row['id_stock']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['barcode']=utf8_encode($row['barcode']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['ubicacion']=utf8_encode($row['ubicacion']);
				$new_row['costo_ult']=utf8_encode($row['costo_ult']);
				$new_row['costo_pp']=utf8_encode($row['costo_pp']);
				if(isset($pedidoArray)){
					if(array_key_exists($row['id_material'], $pedidoArray)){
						$new_row['pedido']=$pedidoArray[$row['id_material']];
					}else{ 
						$new_row['pedido']=0;
					}	
				}else{
					$new_row['pedido']=0;
				}


				$row_set[] = $new_row;
	        }
        return $row_set; //format the array into json data
      }
	}

	function add_entrada($usr,$data,$grilla){

		$id_deposito=$data['id_deposito'];
		$id_tipo_mov=$data['id_tipo_mov'];
		$id_proveedor=$data['id_proveedor'];
		$id_tipo_compra=$data['id_tipo_compra'];
		$id_compra=$data['id_compra'];
		$remito=$data['remito'];
		$centro_costo=$data['centro_costo'];
		$fecha=$this->control->ParseDMYtoYMD($data['fecha'],'/');

		$nro=0;

		//$this->db->db_debug=FALSE;

		$this->db->trans_start();

		/*
		$Sql="select valor from ".BBDD_ODBC_SQLSRV."seed where tabla='entrada' and columna='id_deposito' and id='".$id_deposito."';";

		$query = $this->db->query($Sql);
		foreach ($query->result_array() as $row){
		$nro=utf8_encode($row['valor']);
		}
		*/

		$nro=$this->db->query("select valor from ".BBDD_ODBC_SQLSRV."seed where tabla='entrada' and columna='id_deposito' and id='".$id_deposito."';")->row('valor');


		$nro=ceil($nro)+1;

		// $Sql=sprintf("ppupdate %sseed set valor=%s where tabla='entrada' and columna='id_deposito' and id=%s",BBDD_ODBC_SQLSRV,$nro,$id_deposito);


		$Sql="update ".BBDD_ODBC_SQLSRV."seed set valor=".$nro." where tabla='entrada' and columna='id_deposito' and id=".$id_deposito.";";

		$query = $this->db->query($Sql);

		$Sql="insert into ".BBDD_ODBC_SQLSRV."entrada (id_tipo_mov, nro, fecha, remito, centro_costo, id_proveedor, id_tipo_compra, id_compra, id_deposito, mod_usuario, ult_mod) values ('".$id_tipo_mov."','".$nro."','".$fecha."','".$remito."','".$centro_costo."','".$id_proveedor."','".$id_tipo_compra."','".$id_compra."','".$id_deposito."','".$usr."',getdate());";

		$query = $this->db->query($Sql);

		//$id_entrada=$this->db->insert_id();


		//===============No funciona insert_id() con ODBC====================
		$id_entrada=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		// $id_entrada=$this->db->query('select SCOPE_IDENTITY() as insert_id;')->row('insert_id');

		//$id_entrada = $query->row('insert_id');
		/*
		Database driver (sqlsrv_driver.php)
		function insert_id()
		{

		return $this->query('select @@IDENTITY as insert_id')->row('insert_id');
		}
		*/
		foreach ($grilla as $key => $value) {

			$id_material=$value['id_material'];
			$cantidad=$value['cantidad'];
			$costo_ult=$value['costo_ult'];

			$Sql="insert into ".BBDD_ODBC_SQLSRV."detalle_entrada (id_entrada, nro, id_deposito, centro_costo, id_material, cantidad, costo_ult, mod_usuario, ult_mod) values ('".$id_entrada."','".$nro."','".$id_deposito."','". $centro_costo."','".$id_material."','".$cantidad."','".$costo_ult."','".$usr."', getdate());";
			$query = $this->db->query($Sql);

			//=======COSTO PPP==================

			$Sql="select sum(cantidad) as sCant, m.costo_pp from ".BBDD_ODBC_SQLSRV."stock s, ".BBDD_ODBC_SQLSRV."material m where s.id_material=m.id_material and s.id_material=".$id_material." group by m.costo_pp;";
			$query = $this->db->query($Sql);

			/* Se inicializa las variables sCant y costo_pp si nunca tuvo movimiento da error la consula es NULL*/

			$sCant=0;
			$costo_pp=0;

			foreach ($query->result_array() as $row){
				$sCant=$row['sCant'];
				$costo_pp=$row['costo_pp'];
			}

			$costo_pp=(($sCant*$costo_pp)+($cantidad*$costo_ult))/($sCant+$cantidad);

			$Sql="update ".BBDD_ODBC_SQLSRV."material set costo_pp=".$costo_pp.", mod_usuario='".$usr."', ult_mod=getdate() where id_material=".$id_material.";";
			$query = $this->db->query($Sql);

			//==================================

			$Sql="select id_material from ".BBDD_ODBC_SQLSRV."stock where id_deposito=".$id_deposito." and id_material=".$id_material.";";
			$query = $this->db->query($Sql);


			if($query->num_rows() > 0){
				$Sql="update ".BBDD_ODBC_SQLSRV."stock set cantidad=cantidad+".$cantidad.", mod_usuario='".$usr."', ult_mod=getdate() where id_deposito=".$id_deposito." and id_material=".$id_material.";";
				$query = $this->db->query($Sql);
			}else{
				$Sql="insert into ".BBDD_ODBC_SQLSRV."stock(id_material, id_deposito, cantidad, minimo, reposicion, ubicacion, mod_usuario, ult_mod) values(".$id_material.",".$id_deposito.",".$cantidad.",0,0,NULL,'".$usr."', getdate());";
				$query = $this->db->query($Sql);
			}
		}

		$Sql="insert into ".BBDD_ODBC_SQLSRV."movimientos(id_entrada, id_detalle_entrada, nro, id_deposito, centro_costo, id_material, cantidad, mod_usuario, ult_mod, id_tipo_mov) select e.id_entrada, d.id_detalle_entrada, e.nro, e.id_deposito, e.centro_costo, d.id_material, d.cantidad, e.mod_usuario, getdate(), e.id_tipo_mov from ".BBDD_ODBC_SQLSRV."entrada e, ".BBDD_ODBC_SQLSRV."detalle_entrada d where e.id_entrada=d.id_entrada and e.id_deposito=".$id_deposito." and e.id_entrada=".$id_entrada.";";



		$query = $this->db->query($Sql);

		if ($this->db->trans_status() === FALSE){
			log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
			$nro="ERROR";
			$msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
		}else{
			$msg="ok";  
		}

		$this->db->trans_complete();

		unset($data);
		$data['nro']=$nro;
		$data['id_entrada']=$id_entrada;
		$data['msg']=$msg;
		//$data['x']=$Sql;

		return $data;
	}


	function add_salida($usr,$data,$grilla){

		$id_deposito=$data['id_deposito'];
		$id_tipo_mov=$data['id_tipo_mov'];
		$factor=$data['factor'];
		$id_personal=$data['id_personal'];
		$legajo=$data['legajo'];
		$nro_pedido=$data['pedido'];
		$id_pedido=$data['id_pedido'];
		$completo=$data['completo'];
		if($completo=='true'){ //verifica si se da por completo con la salida sin cumplir com lo solicitado
			$completo=1;
		}else{
			$completo=0;
		}
		if($factor<0){	//en caso de devolucion, se considera la entrega completa
			$completo=1;
		}

		$ot=$data['ot'];
		$obs=utf8_decode($data['obs']);
		$bultos=$data['bultos'];
		$recorrido=$data['recorrido'];
		$sector=utf8_decode($data['sector']);
		$id_zona=$data['id_zona'];
		if($id_zona==""){
			$id_zona=0;
		}
		$centro_costo=$data['centro_costo'];
		$fecha=$this->control->ParseDMYtoYMD($data['fecha'],'/');

		$nro=0;

		//$this->db->trans_start();
		$this->db->trans_begin();

		$nro=$this->db->query("select valor from ".BBDD_ODBC_SQLSRV."seed where tabla='salida' and columna='id_deposito' and id='".$id_deposito."';")->row('valor');

		$nro=ceil($nro)+1;

		$Sql="update ".BBDD_ODBC_SQLSRV."seed set valor=".$nro." where tabla='salida' and columna='id_deposito' and id=".$id_deposito.";";

		$query = $this->db->query($Sql);

		$Sql="insert into ".BBDD_ODBC_SQLSRV."salida (nro,id_pedido, nro_pedido, fecha, retira, id_personal, centro_costo, id_deposito, id_tipo_mov, odt, sector, id_zona, destino, bultos, recorrido, mod_usuario, ult_mod) values (".$nro.", '".$id_pedido."', '".$nro_pedido."', '".$fecha."', '".$legajo."', '".$id_personal."', '".$centro_costo."', ".$id_deposito.", ".$id_tipo_mov.", '".$ot."', '".$sector."', ".$id_zona.", '".$obs."',".$bultos.",".$recorrido.", '".$usr."', getdate());";

		$query = $this->db->query($Sql);

		$id_salida=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		foreach ($grilla as $key => $value) {

			$id_material=$value['id_material'];
			$id_detalle_pedido=$value['id_detalle_pedido'];
			$barcode=$value['barcode'];
			$cant_aut=$value['cant_aut'];
			$cantidad=$value['cantidad']*$factor;
			$id_stock=$value['id_stock'];
			$costo_ult=$value['costo_ult'];
			$costo_pp=$value['costo_pp'];

			//======================CHECK PEDIDO COMPLETO=============================
			/*
			Revisa que no se despache una cantidad meyor a la solicitada por un pedido, si en una
			salida directo, no realiza ningun chequeo, no existe nro de pedido.
			*/

			if($id_pedido!=""){
				if($factor>0){
					$Sql="select id_material, solicitado, despachado from ".BBDD_ODBC_SQLSRV."detalle_pedido where id_pedido=".$id_pedido." and id_material=".$id_material.";";

					$query = $this->db->query($Sql);
					if($query->num_rows() > 0){
						$row=$query->row();
						$ped_solicitado=$row->solicitado;
						$ped_despachado=$row->despachado;
						if($ped_despachado+$cantidad>$ped_solicitado){
							$nro="ERROR";
							$msg="Se intenta despachar una cantidad Mayor a la solicitada material:".$barcode."<ul><li>cantidad:".$cantidad."</li><li>despachado:".$ped_despachado."</li><li>solicitado:".$ped_solicitado."</li></ul>";
							break;
						}
					}
					
				}
			}
			
			//========================================================================

			$Sql="insert into ".BBDD_ODBC_SQLSRV."detalle_salida (id_salida, id_pedido, id_detalle_pedido, nro, id_deposito, centro_costo, id_material, cantidad,costo_ult,costo_pp, mod_usuario, ult_mod) values ('".$id_salida."','".$id_pedido."','".$id_detalle_pedido."','".$nro."','".$id_deposito."','". $centro_costo."','".$id_material."','".$cantidad."','".$costo_ult."','".$costo_pp."','".$usr."', getdate());"; 

			$query = $this->db->query($Sql);

			//======================Verifica stock disponible=========================
			/*
			Verifica que exista stock, en caso de no existir realiza un Rollback
			*/
			
			$Sql="select id_material, id_deposito, cantidad from ".BBDD_ODBC_SQLSRV."stock where id_stock=".$id_stock. ";";
			$query = $this->db->query($Sql);
			
			if($query->num_rows() > 0){
				$row=$query->row();
				$cant_stock=$row->cantidad;
				$id_material_stock=$row->id_material;
				$id_deposito_stock=$row->id_deposito;

				if($id_deposito_stock==$id_deposito){
					if(($id_material==$id_material_stock)&&($cant_stock>=$cantidad)){

						//===================ACTUALIZA PEDIDOS===================
						if($id_pedido!=''){
					
							$Sql="update ".BBDD_ODBC_SQLSRV."detalle_pedido set despachado= despachado +".$cantidad.", completo=".$completo." where id_pedido=".$id_pedido." and id_material=".$id_material.";";
							$query = $this->db->query($Sql);
							if($cantidad<=$cant_aut){
								$cant_aut=$cantidad;
							}
							$Sql="update ".BBDD_ODBC_SQLSRV."stock set cantidad=cantidad-(".$cantidad."),pedido=pedido-(".$cant_aut."), mod_usuario='".$usr."', ult_mod=getdate() where id_deposito=".$id_deposito." and id_material=".$id_material.";";
							$query = $this->db->query($Sql);

						}else{
							$Sql="update ".BBDD_ODBC_SQLSRV."stock set cantidad=cantidad-(".$cantidad."), mod_usuario='".$usr."', ult_mod=getdate() where id_deposito=".$id_deposito." and id_material=".$id_material.";";
							$query = $this->db->query($Sql);
						}

						//======================================================

					}else{
						if($cant_stock<$cantidad){
							$nro="ERROR";
							$msg="error# Stock material codigo ".$id_material." inferior al despachar en el deposito. Disponible: ".$cant_stock." a despachar ".$cantidad.". Elimine la linea y carguela nuevamente para actulizar el stock disponible.";
						}else{
							$nro="ERROR";
							$msg="error# No se encotro el material codigo ".$id_material." especificado en el deposito";
						}
						break;
					}
				}else{
					$nro="ERROR";
					$msg="error# cambio de deposito despues de seleccionar material";
					break;
				}
			}else{
				$nro="ERROR";
				$msg="error# No se encotro el material id stock especificado en el deposito";
				break;
			}
		}

		if($id_pedido!=''){
			if($completo!=1){
				
				$Sql="update ".BBDD_ODBC_SQLSRV."detalle_pedido set completo=1 where cantidad=despachado and id_pedido=".$id_pedido.";";
/*
				$Sql="update ".BBDD_ODBC_SQLSRV."detalle_pedido set completo=1 where cantidad=solicitado and id_pedido=".$id_pedido.";";
*/
				$query = $this->db->query($Sql);
			}
		}

		if($nro=="ERROR"){
			$this->db->trans_rollback();
		}else{

			$Sql="insert into ".BBDD_ODBC_SQLSRV."movimientos(id_salida, id_detalle_salida, nro, id_deposito, centro_costo, id_material, cantidad, mod_usuario, ult_mod, id_tipo_mov) select e.id_salida, d.id_detalle_salida, e.nro, e.id_deposito, e.centro_costo, d.id_material, d.cantidad, e.mod_usuario, getdate(), e.id_tipo_mov from ".BBDD_ODBC_SQLSRV."salida e, ".BBDD_ODBC_SQLSRV."detalle_salida d where e.id_salida=d.id_salida and e.id_deposito=".$id_deposito." and e.id_salida=".$id_salida.";";


			$query = $this->db->query($Sql);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
				$nro="ERROR";
				$msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
			}else{
				$this->db->trans_commit();
				$msg="ok";  
			}

		}


		

		//$this->db->trans_complete();

		unset($data);
		$data['nro']=$nro;
		$data['id_salida']=$id_salida;
		$data['msg']=$msg;
		$data['x']=$Sql;

		return $data;
	}

	function vale_salida($id_salida){

		
/*
		$Sql="SELECT s.id_salida,s.nro, s.id_deposito, d.deposito, s.fecha, s.retira, p.apellido_nombre, s.centro_costo, c.denominacion, s.odt, s.destino, s.mod_usuario, t.tipo, x.id_material, z.descripcion, z.unidad, x.cantidad, z.barcode FROM ".BBDD_ODBC_SQLSRV."salida s,".BBDD_ODBC_SQLSRV."deposito d, ".BBDD_ODBC_SQLSRV."centro_costo c, ".BBDD_ODBC_SQLSRV."personal p, ".BBDD_ODBC_SQLSRV."tipo_movimiento t, ".BBDD_ODBC_SQLSRV."detalle_salida x, ".BBDD_ODBC_SQLSRV."material z where s.nro=".$nro." and s.id_deposito=".$id_deposito." and s.id_deposito=d.id_deposito and s.centro_costo=c.centro_costo and s.retira=p.legajo and s.id_tipo_mov=t.id_tipo_mov and x.id_salida=s.id_salida and x.id_material=z.id_material;";
*/
		
		$Sql="SELECT s.id_salida,s.nro, s.id_deposito, s.nro_pedido, d.deposito, s.fecha, s.bultos, s.retira, p.apellido_nombre, s.centro_costo,
		 c.denominacion, s.odt, s.destino,s.sector, s.id_zona, s.mod_usuario, t.tipo, x.id_material, z.descripcion, z.unidad, x.cantidad,z.costo_ult,(x.cantidad*z.costo_ult) as linea, z.barcode, l.clase, h.sub_clase, k.Zona, k.localidad, k.Direccion FROM ".BBDD_ODBC_SQLSRV."salida s,".BBDD_ODBC_SQLSRV."deposito d, ".BBDD_ODBC_SQLSRV."centro_costo c, ".BBDD_ODBC_SQLSRV."personal p, ".BBDD_ODBC_SQLSRV."tipo_movimiento t, ".BBDD_ODBC_SQLSRV."detalle_salida x, ".BBDD_ODBC_SQLSRV."material z, ".BBDD_ODBC_SQLSRV."clase l, ".BBDD_ODBC_SQLSRV."sub_clase h, ".BBDD_ODBC_SQLSRV."zonas k where s.id_salida=".$id_salida." and s.id_deposito=d.id_deposito and s.centro_costo=c.centro_costo  and s.id_personal=p.id_personal and s.id_tipo_mov=t.id_tipo_mov and x.id_salida=s.id_salida  and x.id_material=z.id_material and z.id_clase=l.id_clase and z.id_sub_clase=h.id_sub_clase and s.id_zona=k.id_zona order by l.clase, h.sub_clase;";
		
		$query = $this->db->query($Sql);

		$i=0;

		if($query->num_rows() > 0){
			
        foreach ($query->result_array() as $row){
          //$new_row['label']=$row['descripcion']; //build an array
        	$new_row['id_salida']=utf8_encode($row['id_salida']);
        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
        	$new_row['nro']=utf8_encode($row['nro']);
        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
        	$new_row['denominacion']=utf8_encode($row['denominacion']);
        	$new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
        	$new_row['deposito']=utf8_encode($row['deposito']);
        	$new_row['retira']=utf8_encode($row['retira']);
        	$new_row['odt']=utf8_encode($row['odt']);
        	$new_row['sector']=utf8_encode($row['sector']);
        	$new_row['id_zona']=utf8_encode($row['id_zona']);
        	$new_row['Zona']=utf8_encode($row['Zona']);
        	$new_row['localidad']=utf8_encode($row['localidad']);
        	$new_row['Direccion']=utf8_encode($row['Direccion']);
        	$new_row['fecha']=utf8_encode($row['fecha']);
        	$new_row['bultos']=utf8_encode($row['bultos']);
        	$new_row['tipo']=utf8_encode($row['tipo']);
        	$new_row['destino']=utf8_encode($row['destino']);
        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
        	$new_row['id_material']=utf8_encode($row['id_material']);
        	$new_row['clase']=utf8_encode($row['clase']);
        	$new_row['sub_clase']=utf8_encode($row['sub_clase']);
        	$new_row['descripcion']=utf8_encode($row['descripcion']);
			$new_row['unidad']=utf8_encode($row['unidad']);
			$new_row['costo_ult']=utf8_encode($row['costo_ult']);
			$new_row['cantidad']=utf8_encode($row['cantidad']);
			$new_row['linea']=utf8_encode($row['linea']);
			$new_row['barcode']=utf8_encode($row['barcode']); 
			$row_set[] = $new_row; //build an array
			$i++;
			/*
			if($i>31){
				break;
			}*/
        }
        	return $row_set; //format the array into json data
      	}
      	return $where;//." largo: ".$long_str."<br>".$q;
	}

//=====================================================================

	function vale_salidaId($id_salida){

		
		$Sql="SELECT s.id_salida,s.nro, s.id_deposito, s.nro_pedido, d.deposito, s.fecha, s.bultos, s.retira, p.apellido_nombre, s.centro_costo,
		 c.denominacion, s.odt, s.destino,s.sector, s.id_zona, s.mod_usuario, t.tipo, x.id_material, z.descripcion, z.unidad, x.cantidad,z.costo_ult,(x.cantidad*z.costo_ult) as linea, z.barcode, l.clase, h.sub_clase, k.Zona, k.localidad, k.Direccion FROM ".BBDD_ODBC_SQLSRV."salida s,".BBDD_ODBC_SQLSRV."deposito d, ".BBDD_ODBC_SQLSRV."centro_costo c, ".BBDD_ODBC_SQLSRV."personal p, ".BBDD_ODBC_SQLSRV."tipo_movimiento t, ".BBDD_ODBC_SQLSRV."detalle_salida x, ".BBDD_ODBC_SQLSRV."material z, ".BBDD_ODBC_SQLSRV."clase l, ".BBDD_ODBC_SQLSRV."sub_clase h, ".BBDD_ODBC_SQLSRV."zonas k where s.id_salida in (".$id_salida.") and s.id_deposito=d.id_deposito and s.centro_costo=c.centro_costo  and s.id_personal=p.id_personal and s.id_tipo_mov=t.id_tipo_mov and x.id_salida=s.id_salida  and x.id_material=z.id_material and z.id_clase=l.id_clase and z.id_sub_clase=h.id_sub_clase and s.id_zona=k.id_zona order by s.nro, l.clase, h.sub_clase;";
		
		$query = $this->db->query($Sql);

		$i=0;

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_salida']=utf8_encode($row['id_salida']);
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['denominacion']=utf8_encode($row['denominacion']);
	        	$new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['retira']=utf8_encode($row['retira']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['id_zona']=utf8_encode($row['id_zona']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['localidad']=utf8_encode($row['localidad']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['fecha']=utf8_encode($row['fecha']);
	        	$new_row['bultos']=utf8_encode($row['bultos']);
	        	$new_row['tipo']=utf8_encode($row['tipo']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        	$new_row['id_material']=utf8_encode($row['id_material']);
	        	$new_row['clase']=utf8_encode($row['clase']);
	        	$new_row['sub_clase']=utf8_encode($row['sub_clase']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['costo_ult']=utf8_encode($row['costo_ult']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['linea']=utf8_encode($row['linea']);
				$new_row['barcode']=utf8_encode($row['barcode']); 
				$row_set[] = $new_row; //build an array
				$i++;
	        }
        	return $row_set; //format the array into json data
      	}
      	return $where;//." largo: ".$long_str."<br>".$q;
	}

//=====================================================================	

	function rep_movimientos($id_deposito,$F_inicio, $F_fin){

		$F_inicio=$this->control->ParseDMYtoYMD($F_inicio,'/');
		$F_fin=$this->control->ParseDMYtoYMD($F_fin,'/');
		
		$Sql="SELECT m.id_movimiento,t.movimiento, t.tipo,m.id_salida,s.odt,s.nro,s.nro_pedido,s.sector,q.Zona,s.destino,s.fecha as f_salida,m.id_detalle_salida,m.id_deposito,z.deposito,m.id_entrada,e.fecha as f_entrada,e.id_proveedor,p.proveedor,e.id_compra,c.compra,e.remito,m.id_detalle_entrada,m.centro_costo,m.nro,m.id_material,x.barcode,x.descripcion,x.unidad,m.cantidad,m.mod_usuario,m.ult_mod,m.id_tipo_mov,s.id_personal FROM ".BBDD_ODBC_SQLSRV."movimientos m   inner join ".BBDD_ODBC_SQLSRV."material x on m.id_material=x.id_material inner join ".BBDD_ODBC_SQLSRV."deposito z on m.id_deposito=z.id_deposito  inner join ".BBDD_ODBC_SQLSRV."tipo_movimiento t on m.id_tipo_mov=t.id_tipo_mov left join ".BBDD_ODBC_SQLSRV."salida s on m.id_salida=s.id_salida left join ".BBDD_ODBC_SQLSRV."entrada e on m.id_entrada=e.id_entrada left join ".BBDD_ODBC_SQLSRV."proveedores p on e.id_proveedor=p.id_proveedor left join ".BBDD_ODBC_SQLSRV."zonas q on s.id_zona=q.id_zona left join ".BBDD_ODBC_SQLSRV."tipo_compra c on e.id_tipo_compra=c.id_tipo_compra where m.id_deposito=".$id_deposito." and s.fecha between '".$F_inicio."' and '".$F_fin."' or  m.id_deposito=".$id_deposito." and e.fecha between '".$F_inicio."' and '".$F_fin."' order by 1 asc;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_movimiento']=utf8_encode($row['id_movimiento']);
	        	$new_row['movimiento']=utf8_encode($row['movimiento']);
	        	$new_row['tipo']=utf8_encode($row['tipo']);
	        	//$new_row['id_salida']=utf8_encode($row['id_salida']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	if($row['f_entrada']!=NULL){
					$new_row['fecha']=$this->control->ParseDMYtoYMD($row['f_entrada'],'/');
	        	}else{
	        		$new_row['fecha']=$this->control->ParseDMYtoYMD($row['f_salida'],'/');	
	        	}
	        	//$new_row['id_detalle_salida']=utf8_encode($row['id_detalle_salida']);
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	//$new_row['id_entrada']=utf8_encode($row['id_entrada']);
	        	$new_row['id_proveedor']=utf8_encode($row['id_proveedor']);
	        	$new_row['proveedor']=utf8_encode($row['proveedor']);
	        	$new_row['id_compra']=utf8_encode($row['id_compra']);
	        	$new_row['compra']=utf8_encode($row['compra']);
	        	$new_row['remito']=utf8_encode($row['remito']);
	        	//$new_row['id_detalle_entrada']=utf8_encode($row['id_detalle_entrada']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['id_material']=utf8_encode($row['id_material']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
	        	$new_row['ult_mod']=utf8_encode($row['ult_mod']);
	        	//$new_row['id_personal']=$row['id_personal'];
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
      	//	return $Sql;//." largo: ".$long_str."<br>".$q;



	}

	function get_stock_deposito($id_deposito){

			$Sql="select s.id_material, m.descripcion, m.unidad, m.barcode, s.cantidad, m.id_clase, c.clase, m.id_sub_clase, sub_clase, s.minimo, s.reposicion, s.ubicacion, d.deposito from ".BBDD_ODBC_SQLSRV."stock s inner join ".BBDD_ODBC_SQLSRV."deposito d on s.id_deposito=d.id_deposito	inner join ".BBDD_ODBC_SQLSRV."material m on s.id_material=m.id_material	inner join ".BBDD_ODBC_SQLSRV."clase c on m.id_clase=c.id_clase inner join ".BBDD_ODBC_SQLSRV."sub_clase x on m.id_clase=x.id_sub_clase where s.id_deposito=".$id_deposito.";";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_material']=utf8_encode($row['id_material']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['id_clase']=utf8_encode($row['id_clase']);
	        	$new_row['clase']=utf8_encode($row['clase']);
	        	$new_row['id_sub_clase']=utf8_encode($row['id_sub_clase']);
	        	$new_row['sub_clase']=utf8_encode($row['sub_clase']);
	        	$new_row['minimo']=utf8_encode($row['minimo']);
	        	$new_row['reposicion']=utf8_encode($row['reposicion']);
	        	$new_row['ubicacion']=utf8_encode($row['ubicacion']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}

	}

	function get_material_deposito($data){

		$id_deposito=$data['id_deposito'];
		$id_clase=$data['id_clase'];
	    $id_sub_clase=$data['id_sub_clase'];
	    $id_material=$data['id_material'];
	    $descripcion=$data['material'];
	    $barcode=$data['barcode'];

	    $where="";

	    if($id_clase!=''){
	      $where=" where m.id_clase=".$id_clase;
	    }
	    
	    if($id_sub_clase!=''){
	      if($where==""){
	        $where=" where m.id_sub_clase=".$id_sub_clase;
	      }else{
	        $where=$where." and m.id_sub_clase=".$id_sub_clase;
	      }
	    }

	    if($id_material!=''){
	      if($where==""){
	        $where=" where m.id_material=".$id_material;
	      }else{
	        $where=$where." and m.id_material=".$id_material;
	      }
	    }

	    $long_str=strlen($descripcion);
	    $descripcion=$this->control->limpia_str($descripcion);
	    $a = preg_split("/[\s\%]+/", $descripcion);

	    foreach ($a as $v) {
	      if($v!=NULL){
	        if($where==""){
	          $where=" where m.descripcion like '%".$v."%'";
	        }else{
	          $where=$where." and m.descripcion like '%".$v."%'";
	        }
	    }
    }

    if($barcode!=''){
      if($where==""){
        $where=" where m.barcode='".$barcode."'";
      }else{
        $where=$where." and m.barcode='".$barcode."'";
      }
    }
    if($id_deposito!=''){
      if($where==""){
        $where=" where x.id_deposito=".$id_deposito;
      }else{
        $where=$where." and x.id_deposito=".$id_deposito;
      }
    }

    $Sql="select m.id_material, m.id_clase, c.clase, m.id_sub_clase, s.sub_clase, m.descripcion, m.unidad, m.barcode, m.costo_pp,
		m.costo_ult, x.id_deposito, x.cantidad, x.minimo, x.reposicion, x.id_stock, x.ubicacion
		from ".BBDD_ODBC_SQLSRV."material m 
		inner join ".BBDD_ODBC_SQLSRV."clase c on m.id_clase=c.id_clase 
		inner join ".BBDD_ODBC_SQLSRV."sub_clase s on m.id_sub_clase=s.id_sub_clase
		inner join ".BBDD_ODBC_SQLSRV."stock x on m.id_material=x.id_material
		 ".$where.";";

    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['id_material']=utf8_encode($row['id_material']);
        $new_row['id_clase']=utf8_encode($row['id_clase']);
        $new_row['clase']=utf8_encode($row['clase']);
        $new_row['id_sub_clase']=utf8_encode($row['id_sub_clase']);
        $new_row['sub_clase']=utf8_encode($row['sub_clase']);
        $new_row['descripcion']=utf8_encode($row['descripcion']);
        $new_row['unidad']=utf8_encode($row['unidad']);
        $new_row['barcode']=utf8_encode($row['barcode']);
        $new_row['costo_pp']=utf8_encode($row['costo_pp']);
        $new_row['costo_ult']=utf8_encode($row['costo_ult']);
        $new_row['cantidad']=utf8_encode($row['cantidad']);
        $new_row['minimo']=utf8_encode($row['minimo']);
        $new_row['reposicion']=utf8_encode($row['reposicion']);
        $new_row['id_stock']=utf8_encode($row['id_stock']);
        $new_row['ubicacion']=utf8_encode($row['ubicacion']);
        //$new_row['sql']=utf8_encode($Sql);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }

	}

	function alarmas_stock($usr){

		$Sql="select count(*) as alr_minimo from ".BBDD_ODBC_SQLSRV."stock s where s.cantidad<s.minimo and s.cantidad>s.reposicion and s.id_deposito in (select id_deposito from ".BBDD_ODBC_SQLSRV."deposito_autorizado where usuario='".$usr."');";

		$alr_minimo=$this->db->query($Sql)->row('alr_minimo');

   		$Sql="select count(*) as alr_reposicion from ".BBDD_ODBC_SQLSRV."stock s where s.cantidad<s.reposicion and s.id_deposito in (select id_deposito from ".BBDD_ODBC_SQLSRV."deposito_autorizado where usuario='".$usr."');";
   		$alr_reposicion=$this->db->query($Sql)->row('alr_reposicion');

   		$alarmas_stock = array(
   			'alr_minimo' =>$alr_minimo,
   			'alr_reposicion'=>$alr_reposicion
   		);

   		return $alarmas_stock;

	}

	function rep_minimo($usr){

		$Sql="select s.id_deposito, d.deposito,s.id_material,m.descripcion, m.unidad, s.cantidad, s.minimo, s.reposicion from ".BBDD_ODBC_SQLSRV."stock s inner join ".BBDD_ODBC_SQLSRV."deposito d on s.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."material m on s.id_material=m.id_material where s.cantidad<s.minimo and s.cantidad>s.reposicion and s.id_deposito in (select id_deposito from ".BBDD_ODBC_SQLSRV."deposito_autorizado where usuario='".$usr."');";
		$query = $this->db->query($Sql);
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				//$new_row['label']=$row['descripcion']; //build an array
				$new_row['id_deposito']=utf8_encode($row['id_deposito']);
				$new_row['deposito']=utf8_encode($row['deposito']);
				$new_row['id_material']=utf8_encode($row['id_material']);
				$new_row['descripcion']=utf8_encode($row['descripcion']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['minimo']=utf8_encode($row['minimo']);
				$new_row['reposicion']=utf8_encode($row['reposicion']);
				//$new_row['sql']=utf8_encode($Sql);
				$row_set[] = $new_row; //build an array
			}
			return $row_set;
		}
	}

	function rep_reposicion($usr){

		$Sql="select s.id_deposito, d.deposito,s.id_material,m.descripcion, m.unidad, s.cantidad, s.minimo, s.reposicion from ".BBDD_ODBC_SQLSRV."stock s inner join ".BBDD_ODBC_SQLSRV."deposito d on s.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."material m on s.id_material=m.id_material where s.cantidad<s.reposicion and s.id_deposito in (select id_deposito from ".BBDD_ODBC_SQLSRV."deposito_autorizado where usuario='".$usr."');";
		$query = $this->db->query($Sql);
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				//$new_row['label']=$row['descripcion']; //build an array
				$new_row['id_deposito']=utf8_encode($row['id_deposito']);
				$new_row['deposito']=utf8_encode($row['deposito']);
				$new_row['id_material']=utf8_encode($row['id_material']);
				$new_row['descripcion']=utf8_encode($row['descripcion']);
				$new_row['unidad']=utf8_encode($row['unidad']);
				$new_row['cantidad']=utf8_encode($row['cantidad']);
				$new_row['minimo']=utf8_encode($row['minimo']);
				$new_row['reposicion']=utf8_encode($row['reposicion']);
				//$new_row['sql']=utf8_encode($Sql);
				$row_set[] = $new_row; //build an array
			}
			return $row_set;
		}
	}

	function rep_consumo($dato){
	    
		$id_deposito = $dato['id_deposito'];
		$F_inicio = $dato['F_inicio'];
		$F_fin = $dato['F_fin'];
		$id_material = $dato['id_material'];
		$material = $dato['material'];
		$barcode = $dato['barcode'];


		$Sql="select x.deposito, CONVERT (varchar(10), s.fecha, 103) AS fecha, t.tipo, d.nro, d.id_material,m.barcode,m.descripcion,m.unidad,d.cantidad, d.mod_usuario from ".BBDD_ODBC_SQLSRV."detalle_salida d inner join ".BBDD_ODBC_SQLSRV."salida s on d.id_salida=s.id_salida inner join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material inner join ".BBDD_ODBC_SQLSRV."deposito x on d.id_deposito=x.id_deposito inner join ".BBDD_ODBC_SQLSRV."tipo_movimiento t on s.id_tipo_mov=t.id_tipo_mov";

		if($id_deposito!=""){
			$where=" where x.id_deposito=".$id_deposito;
		}
		
		if(($F_inicio!="") and ($F_fin!="")){
			if($where!=""){
				$where.=" and s.fecha between '".$F_inicio."' and '".$F_fin."'";
			}else{
				$where=" where s.fecha between '".$F_inicio."' and '".$F_fin."'";
			}
		}

		if($barcode!=""){
			if($where!=""){
				$where.=" and m.barcode =".$barcode;
			}else{
				$where=" where m.barcode =".$barcode;
			}
		}
		//==========================================

		$long_str=strlen($material);
		$material=$this->control->limpia_str($material);
		$a = preg_split("/[\s\%]+/", $material);

		foreach ($a as $v) {
			if($v!=NULL){
				if($where==""){
					$where=" where m.descripcion like '%".$v."%'";
				}else{
					$where=$where." and m.descripcion like '%".$v."%'";
				}
			}
		}


		//========================================== 
		
		/*if($material!=""){
			if($where!=""){
				$where.=" and m.descripcion like '%".$material."%'";
			}else{
				$where=" where m.descripcion like '%".$material."%'";
			}
		}*/

		$Sql.=$where.";";

		
		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($row['fecha']);
	        	$new_row['tipo']=utf8_encode($row['tipo']);
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['id_material']=utf8_encode($row['id_material']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
	        	//$new_row['sql']=utf8_encode($Sql);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
		
	}

	function lista_salidas($deposito){
		
		//$Sql="select s.id_deposito, d.deposito, s.nro, s.fecha, s.retira, s.nro_pedido, p.apellido_nombre from ".BBDD_ODBC_SQLSRV."salida s inner join ".BBDD_ODBC_SQLSRV."deposito d  on s.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."personal p on s.id_personal=p.id_personal  where s.id_deposito in(".$deposito.") and s.fecha>=dateadd(Year,-1,getdate()) order by nro desc;";

		$Sql="select s.id_salida, s.id_deposito, d.deposito, s.nro, s.fecha, s.retira, s.nro_pedido, s.sector, s.destino, s.bultos, s.recorrido, p.apellido_nombre, z.Zona,s.odt from ".BBDD_ODBC_SQLSRV."salida s inner join ".BBDD_ODBC_SQLSRV."deposito d on s.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."personal p on s.id_personal=p.id_personal left join ".BBDD_ODBC_SQLSRV."zonas z on s.id_zona=z.id_zona where s.id_deposito in(".$deposito.") and s.fecha>=dateadd(Year,-1,getdate()) order by nro desc;";


		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_salida']=utf8_encode($row['id_salida']);
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
	        	$new_row['retira']=utf8_encode($row['retira']);
	        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['bultos']=utf8_encode($row['bultos']);
	        	$new_row['recorrido']=utf8_encode($row['recorrido']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function lista_entradas($deposito){

		$Sql="select e.id_deposito, d.deposito, e.nro, e.id_proveedor, p.proveedor, e.remito, e.fecha from ".BBDD_ODBC_SQLSRV."entrada e inner join ".BBDD_ODBC_SQLSRV."deposito d on e.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."proveedores p on e.id_proveedor=p.id_proveedor where  e.id_deposito in(".$deposito.") and e.fecha>=dateadd(Year,-1,getdate()) order by d.deposito, e.nro desc;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['id_proveedor']=utf8_encode($row['id_proveedor']);
	        	$new_row['proveedor']=utf8_encode($row['proveedor']);
	        	$new_row['remito']=utf8_encode($row['remito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function edit_max_min($data,$usr){

	    $id_stock=$data['id_stock'];
	    $id_deposito=$data['id_deposito'];
	    $id_material=$data['id_material'];
	    $minimo=$data['minimo'];
	    $reposicion=$data['reposicion'];
	    $ubicacion=$data['ubicacion'];
	    
	    $Sql="update ".BBDD_ODBC_SQLSRV."stock set minimo=".$minimo.", reposicion=".$reposicion.", ubicacion='".$ubicacion."', mod_usuario='".$usr."', ult_mod=getdate() where id_stock=".$id_stock.";";
	    $query = $this->db->query($Sql);

	    //$id_material=$this->db->query('select @@ROWCOUNT as rows_affected;')->row('rows_affected');

	}

	//============================

	function rep_consumo_ctro_cto($id_deposito,$F_inicio, $F_fin, $centro_costo){

		$F_inicio=$this->control->ParseDMYtoYMD($F_inicio,'/');
		$F_fin=$this->control->ParseDMYtoYMD($F_fin,'/');

		$where=" where x.id_deposito=".$id_deposito." and s.fecha between '".$F_inicio."' and '".$F_fin."'";

		if($centro_costo!=""){
			$where=" and s.centro_costo='".$centro_costo."' ";
		}
		
		$Sql="select x.deposito, s.centro_costo, c.denominacion, s.id_zona, z.Zona,m.barcode, m.descripcion, m.unidad, sum(d.cantidad) as cantidad, m.costo_ult, sum(d.cantidad)*m.costo_ult as subtotal from ".BBDD_ODBC_SQLSRV."salida s inner join ".BBDD_ODBC_SQLSRV."detalle_salida d on s.id_salida=d.id_salida inner join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material inner join ".BBDD_ODBC_SQLSRV."centro_costo c on s.centro_costo=c.centro_costo inner join ".BBDD_ODBC_SQLSRV."zonas z on s.id_zona=z.id_zona inner join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito ".$where." group by  x.deposito,s.centro_costo, c.denominacion, s.id_zona, z.Zona,m.barcode, m.descripcion, m.unidad, m.costo_ult order by s.centro_costo, c.denominacion,m.barcode asc;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['denominacion']=utf8_encode($row['denominacion']);
	        	$new_row['id_zona']=utf8_encode($row['id_zona']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['costo_ult']=utf8_encode($row['costo_ult']);
	        	$new_row['subtotal']=utf8_encode($row['subtotal']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
      	//	return $Sql;//." largo: ".$long_str."<br>".$q;

	}

	//============================

	function rep_consumo_totalizado($id_deposito,$F_inicio, $F_fin){

		$F_inicio=$this->control->ParseDMYtoYMD($F_inicio,'/');
		$F_fin=$this->control->ParseDMYtoYMD($F_fin,'/');

		$where=" where x.id_deposito=".$id_deposito." and s.fecha between '".$F_inicio."' and '".$F_fin."'";
		
		$Sql="select x.deposito, m.barcode, m.descripcion, m.unidad, sum(d.cantidad) as cantidad, m.costo_ult, sum(d.cantidad)*m.costo_ult as subtotal from ".BBDD_ODBC_SQLSRV."salida s inner join ".BBDD_ODBC_SQLSRV."detalle_salida d on s.id_salida=d.id_salida inner join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material inner join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito ".$where." group by  x.deposito,m.barcode, m.descripcion, m.unidad, m.costo_ult order by m.barcode asc;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['costo_ult']=utf8_encode($row['costo_ult']);
	        	$new_row['subtotal']=utf8_encode($row['subtotal']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
      	//	return $Sql;//." largo: ".$long_str."<br>".$q;

	}

	function consumo_anual($id_deposito,$reporte,$year_rpte){


		if($reporte==""){
			$reporte="MAX_STOCK";
		}
		
		//==============CANTIDAD=======================
		if($reporte=="CANTIDAD"){
			
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, SUM(cantidad) as cantidad from ".BBDD_ODBC_SQLSRV."salida s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito join ".BBDD_ODBC_SQLSRV."detalle_salida d on s.id_salida=d.id_salida join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." group by  x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) order by m.barcode,MONTH(s.fecha);";

		}

		//==================COSTO====================

		if($reporte=="COSTO"){
			
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, SUM(m.costo_ult) as cantidad from ".BBDD_ODBC_SQLSRV."salida s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito join ".BBDD_ODBC_SQLSRV."detalle_salida d on s.id_salida=d.id_salida join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." group by  x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) order by m.barcode,MONTH(s.fecha);";

		}

		//=============PORCENTAJE======================

		if($reporte=="PORCENTAJE"){
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, (SUM(despachado)/NULLIF(SUM(solicitado),0)) as cantidad
					from ".BBDD_ODBC_SQLSRV."pedido s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito
					join ".BBDD_ODBC_SQLSRV."detalle_pedido d on s.id_pedido=d.id_pedido 
					join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material 
					where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." 
					group by x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
					order by m.barcode,MONTH(s.fecha);";

		}

		//===============SOLICITADO====================
		if($reporte=="SOLICITADO"){
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, SUM(solicitado) as cantidad
			from ".BBDD_ODBC_SQLSRV."pedido s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito
			join ".BBDD_ODBC_SQLSRV."detalle_pedido d on s.id_pedido=d.id_pedido 
			join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material 
			where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." 
			group by x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
			order by m.barcode,MONTH(s.fecha);";
		}
		//=============AVG_STOCK==================
		if($reporte=="AVG_STOCK"){
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, AVG(stock) as cantidad
			from ".BBDD_ODBC_SQLSRV."pedido s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito
			join ".BBDD_ODBC_SQLSRV."detalle_pedido d on s.id_pedido=d.id_pedido 
			join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material 
			where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." 
			group by x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
			order by m.barcode,MONTH(s.fecha);";
		}
		//================MIN_STOCK====================
		if($reporte=="MIN_STOCK"){
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, MIN(stock) as cantidad
			from ".BBDD_ODBC_SQLSRV."pedido s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito
			join ".BBDD_ODBC_SQLSRV."detalle_pedido d on s.id_pedido=d.id_pedido 
			join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material 
			where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." 
			group by x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
			order by m.barcode,MONTH(s.fecha);";
		}
		//================MAX_STOCK====================
		if($reporte=="MAX_STOCK"){
			$Sql="select x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, MAX(stock) as cantidad
		from ".BBDD_ODBC_SQLSRV."pedido s join ".BBDD_ODBC_SQLSRV."deposito x on s.id_deposito=x.id_deposito
		join ".BBDD_ODBC_SQLSRV."detalle_pedido d on s.id_pedido=d.id_pedido 
		join ".BBDD_ODBC_SQLSRV."material m on m.id_material=d.id_material 
		where s.id_deposito=".$id_deposito." and year(s.fecha)=".$year_rpte." 
		group by x.deposito, m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
		order by m.barcode,MONTH(s.fecha);";
		}


		
		/*$Sql="select m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, SUM(solicitado) as solicitado,
		 SUM(despachado) as despachado, (SUM(despachado)/SUM(solicitado)) as porcentual, AVG(stock) as stock, MIN(stock) as MinsStk, MAX(stock) as MaxStk
		from dal_utiles.dbo.pedido s 
		join dal_utiles.dbo.detalle_pedido d on s.id_pedido=d.id_pedido 
		join dal_utiles.dbo.material m on m.id_material=d.id_material 
		where year(s.fecha)=2018 
		group by m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
		order by m.barcode,MONTH(s.fecha);";*/

		/*$Sql="select m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) as mes, (SUM(despachado)/SUM(solicitado)) as cantidad
		from dal_utiles.dbo.pedido s 
		join dal_utiles.dbo.detalle_pedido d on s.id_pedido=d.id_pedido 
		join dal_utiles.dbo.material m on m.id_material=d.id_material 
		where year(s.fecha)=2018 
		group by m.id_material, m.barcode, m.descripcion, m.unidad, MONTH(s.fecha) 
		order by m.barcode,MONTH(s.fecha);";*/

		$query = $this->db->query($Sql);
		$tmp="";
		$Meses="";

		if($query->num_rows() > 0){

			foreach ($query->result_array() as $row){

				for ($i=1; $i <= 12; $i++) {
					$Meses[$i]="";
				}

				if($row['id_material']!=$tmp){

					if($tmp!=""){
						$row_set[] = $new_row;
					}
					
					$tmp=$row['id_material'];
					$new_row['deposito']=utf8_encode($row['deposito']);
					$new_row['barcode']=utf8_encode($row['barcode']);
		        	$new_row['descripcion']=utf8_encode($row['descripcion']);
		        	$new_row['unidad']=utf8_encode($row['unidad']);
		        	$cant=$row['cantidad'];

					for ($i=1; $i <= 12; $i++) {

						$new_row[$i]=$Meses[$i];

					}

					if($reporte=="PORCENTAJE"){
						$new_row[$row['mes']]=number_format(utf8_encode($cant*100))."%";
					}else{
						$new_row[$row['mes']]=number_format(utf8_encode($cant));
					}

					


				}else{
					$cant=$row['cantidad'];
		        	
					if($reporte=="PORCENTAJE"){
						$new_row[$row['mes']]=number_format(utf8_encode($cant*100))."%";
					}else{
						$new_row[$row['mes']]=number_format(utf8_encode($cant));
					}

				}


				//$new_row[$row['mes']]=$row['cantidad'];

				
			}

			return $row_set;

		}

	}

	function imprimir_etiquetas($id_salida){

		$Sql="select s.id_salida, s.nro_pedido, s.bultos, s.recorrido, s.destino, s.sector, z.Zona, z.Direccion, z.Localidad from ".BBDD_ODBC_SQLSRV."salida s join ".BBDD_ODBC_SQLSRV."zonas z on s.id_Zona =z.id_zona where s.id_salida in(".$id_salida.");";

		$query = $this->db->query($Sql);
		
		if($query->num_rows() > 0){
	        foreach ($query->result_array() as $row){
	        	$new_row['id_salida']=utf8_encode($row['id_salida']);
	        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
	        	$new_row['bultos']=utf8_encode($row['bultos']);
	        	$new_row['recorrido']=utf8_encode($row['recorrido']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['Localidad']=utf8_encode($row['Localidad']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}else{
      		return 'NoData';
      	}
	}

	function recorrido($deposito,$ruta){

		if($ruta==0){
			$ruta=" is NULL";
		}else{
			$ruta="=".$ruta;
		}

		$Sql="select s.id_salida, s.id_deposito, s.nro, s.fecha, s.nro_pedido, d.id_dependencia, d.dependencia, z.Zona, z.Direccion, z.Localidad,s.sector, s.destino, s.bultos, s.recorrido, s.prog, r.id_ruta from ".BBDD_ODBC_SQLSRV."salida s join ".BBDD_ODBC_SQLSRV."zonas z on z.id_zona=s.id_zona left join ".BBDD_ODBC_SQLSRV."dependencia d on  d.id_dependencia=z.id_dependencia left join ".BBDD_ODBC_SQLSRV."recorridos r on  r.id_dependencia=d.id_dependencia where id_deposito=".$deposito." and prog=0 and id_ruta".$ruta." order by s.recorrido,r.id_dependencia;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_salida']=utf8_encode($row['id_salida']);
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	//$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['nro_pedido']=utf8_encode($row['nro_pedido']);
	        	if($row['id_dependencia']==NULL){
	        		$new_row['id_dependencia']=0;
	        	}else{
	        		$new_row['id_dependencia']=$row['id_dependencia'];
	        	}
	        	//$new_row['id_dependencia']=utf8_encode($row['id_dependencia']);
	        	$new_row['dependencia']=utf8_encode($row['dependencia']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['Localidad']=utf8_encode($row['Localidad']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['bultos']=utf8_encode($row['bultos']);
	        	$new_row['recorrido']=utf8_encode($row['recorrido']);
	        	$new_row['prog']=utf8_encode($row['prog']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function rec_a_prog($deposito){

		//$Sql="select r.recorrido from ".BBDD_ODBC_SQLSRV."salida r where prog is NULL and id_deposito=".$deposito." group by recorrido order by recorrido;";

		$Sql="select r.id_ruta, r.ruta from ".BBDD_ODBC_SQLSRV."rutas r where id_deposito=".$deposito.";";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['value']=utf8_encode($row['id_ruta']);
	        	$new_row['label']=utf8_encode($row['ruta']);
	        	//$new_row['value']=utf8_encode($row['recorrido']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function rec_prog($id_entrega){ //salida para imprimir recorridos

		/*
		$Sql="select s.id_salida, s.id_deposito, d.deposito, s.nro, s.fecha, s.retira, s.nro_pedido, s.sector, s.destino, s.bultos, s.recorrido, s.prog, p.apellido_nombre, z.Zona from ".BBDD_ODBC_SQLSRV."salida s inner join ".BBDD_ODBC_SQLSRV."deposito d on s.id_deposito=d.id_deposito inner join ".BBDD_ODBC_SQLSRV."personal p on s.id_personal=p.id_personal left join ".BBDD_ODBC_SQLSRV."zonas z on s.id_zona=z.id_zona where id_salida in(".$id_salida.");";
*/
		$Sql="select s.id_salida, s.nro, s.fecha, s.retira, s.nro_pedido, s.sector, e.destino, x.dependencia, e.bultos, s.recorrido, s.prog, z.Zona, e.agregado, e.orden, e.observaciones from ".BBDD_ODBC_SQLSRV."detalle_entrega e left join ".BBDD_ODBC_SQLSRV."dependencia x on e.id_dependencia=x.id_dependencia left join ".BBDD_ODBC_SQLSRV."salida s on e.id_salida=s.id_salida left join ".BBDD_ODBC_SQLSRV."zonas z on s.id_zona=z.id_zona where e.id_entrega=".$id_entrega.";";


		$query = $this->db->query($Sql);

		//$orden=preg_split(",", $id_salida);
		//$orden=explode(",", $id_salida);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	if($row['id_salida']==NULL){
	        		$new_row['id_salida']=$row['agregado'];
	        		$new_row['sector']=$row['sector'];
	        		$new_row['nro_pedido']=$row['agregado'];
	        	}else{
	        		$new_row['id_salida']=$row['id_salida'];
	        		$new_row['sector']=$row['observaciones'];
	        		$new_row['nro_pedido']=$row['nro_pedido'];
	        	}
	        	
	        	$new_row['dependencia']=$row['dependencia'];
	        	//$new_row['deposito']=$row['deposito'];
	        	$new_row['fecha']=$this->control->ParseDMYtoYMD($row['fecha'],'/');
	        	$new_row['nro']=$row['nro'];
	        	//$new_row['retira']=$row['retira'];
	        	//$new_row['apellido_nombre']=$row['apellido_nombre'];
	        	$new_row['destino']=$row['destino'];
	        	$new_row['bultos']=$row['bultos'];
	        	$new_row['recorrido']=$row['recorrido'];
	        	$new_row['prog']=$row['prog'];
	        	$new_row['Zona']=$row['Zona'];
				$row_set[] = $new_row; //build an array
	        }
/*
	        for ($i=0; $i < count($orden); $i++) {
	        	$j=array_search($orden[$i], $row_tmp,'id_salida');
	        	foreach ($row_tmp as $clave){
	        		if($clave['id_salida']==$orden[$i]){
						$new_row['id_salida']=$clave['id_salida'];
						$new_row['id_deposito']=$clave['id_deposito'];
						$new_row['deposito']=$clave['deposito'];
						$new_row['fecha']=$this->control->ParseDMYtoYMD($clave['fecha'],'/');
						$new_row['nro']=$clave['nro'];
						$new_row['nro_pedido']=$clave['nro_pedido'];
						$new_row['retira']=$clave['retira'];
						$new_row['apellido_nombre']=$clave['apellido_nombre'];
						$new_row['sector']=$clave['sector'];
						$new_row['destino']=$clave['destino'];
						$new_row['bultos']=$clave['bultos'];
						$new_row['recorrido']=$clave['recorrido'];
						$new_row['prog']=$clave['prog'];
						$new_row['Zona']=$clave['Zona'];
						$row_set[] = $new_row; //build an array
	        		}
		        }
	        }

*/

        	return $row_set; //format the array into json data
      	}

	}

}