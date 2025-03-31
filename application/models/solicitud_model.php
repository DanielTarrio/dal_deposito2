<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_model extends CI_Model {

	function get_zona($data){ //get_dependencia

	  	$zona=$data['zona'];
	  	

		//$this->load->library('Control');
		$long_str=strlen($zona);
		$zona=$this->control->limpia_str($zona);
		$a = preg_split("/[\s\%]+/", $zona);

		$where="";

		foreach ($a as $v) {
			if($v!=NULL){
				if($where==""){
					$where=" where m.zona like '%".$v."%'";
				}else{
					$where=$where." and m.zona like '%".$v."%'";
				}
			}
		}

		if($where==""){
			$where=" where m.activa=1";
		}else{
			$where=$where." and m.activa=1";
		}
	     
		$where="select * from ".BBDD_ODBC_SQLSRV."zonas m ".$where.";";

		$query = $this->db->query($where);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		      //$new_row['label']=$row['descripcion']; //build an array
		    	$new_row['value']=utf8_encode($row['id_zona']);
				$new_row['label']=utf8_encode($row['Zona']);
				$new_row['Localidad']=utf8_encode($row['Localidad']);
				$new_row['Direccion']=utf8_encode($row['Direccion']);
				$new_row['Telefono']=utf8_encode($row['Telefono']);
				$new_row['Responsable']=utf8_encode($row['Responsable']);
				$new_row['email']=utf8_encode($row['email']);
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

	function buscar_solicitud($nro){

	    //$Sql="select * from ".BBDD_ODBC_SQLSRV."pedido where nro='".$nro."';";

	    $Sql="select p.id_pedido,p.nro, count(s.id_salida) as salidas from ".BBDD_ODBC_SQLSRV."pedido p left join ".BBDD_ODBC_SQLSRV."salida s on p.nro=s.nro_pedido where p.nro='".$nro."' group by p.id_pedido,p.nro;";


	    $query = $this->db->query($Sql);
	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	      	$new_row['id_pedido']=utf8_encode($row['id_pedido']);
	        $new_row['nro']=utf8_encode($row['nro']);
	        $new_row['salidas']=utf8_encode($row['salidas']);
	        $row_set[] = $new_row;
	      }
	    }else{
	      $row_set=$query->num_rows();
	    }
	    return $row_set;
	}

	function update_solicitud($usr,$data,$grilla){


		$id_deposito=$data['id_deposito'];
		$id_pedido=$data['id_pedido'];
		$nro=$data['nro'];
		$id_zona=$data['id_zona'];
		$id_personal=$data['id_personal'];
		$legajo=$data['legajo'];
		$ot=$data['ot'];
		$obs=utf8_decode($data['obs']);
		$sector=utf8_decode($data['sector']);
		$centro_costo=$data['centro_costo'];
		$fecha=$this->control->ParseDMYtoYMD($data['fecha'],'/');

		$pp=$this->buscar_solicitud($nro);
		if($pp[0]['salidas']==0){

		$this->db->trans_begin();

		$Sql="update ".BBDD_ODBC_SQLSRV."pedido set nro=".$nro.", fecha='".$fecha."', id_zona=".$id_zona.",id_personal=".$id_personal.", retira=".$legajo.", centro_costo=".$centro_costo.", id_deposito=".$id_deposito.",  odt='".$ot."', destino='".$obs."', sector='".$sector."', mod_usuario='".$usr."', ult_mod=getdate() where id_pedido=".$id_pedido.";";

		$query = $this->db->query($Sql);

		$Sql="delete from ".BBDD_ODBC_SQLSRV."detalle_pedido where id_pedido=".$id_pedido.";";

		$query = $this->db->query($Sql);

		//$id_pedido=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		foreach ($grilla as $key => $value) {

			$id_material=$value['id_material'];
			$id_detalle_pedido=$value['id_detalle_pedido'];
			$cantidad=$value['cantidad'];
			$id_stock=$value['id_stock'];
			$costo_ult=$value['costo_ult'];
			$costo_pp=$value['costo_pp'];
			$stock=$value['cant_stock'];
			$solicitado=$value['solicitado'];
			
			//if($id_detalle_pedido!=""){
				

			//}else{
				$Sql="insert into ".BBDD_ODBC_SQLSRV."detalle_pedido (id_pedido, nro, id_deposito, centro_costo, id_material, solicitado, cantidad, stock, costo_ult, costo_pp, mod_usuario, ult_mod) values ('".$id_pedido."','".$nro."','".$id_deposito."','". $centro_costo."', '".$id_material."', ".$solicitado.",".$cantidad.",".$stock.",".$costo_ult.",".$costo_pp.",'".$usr."', getdate());";
				$query = $this->db->query($Sql);

				$Sql="update ".BBDD_ODBC_SQLSRV."stock set pedido=pedido+".$cantidad." where id_stock=".$id_stock.";";

				$query = $this->db->query($Sql);

				$Sql="select id_material, id_deposito, cantidad, pedido from ".BBDD_ODBC_SQLSRV."stock where id_stock=".$id_stock. ";";
				$query = $this->db->query($Sql);
				if($query->num_rows() > 0){
					$row=$query->row();
					$cant_stock=$row->cantidad;
					$pedido_stock=$row->pedido;
					$id_material_stock=$row->id_material;
					$id_deposito_stock=$row->id_deposito;

				}else{
					$nro="ERROR";
					$msg="error# No se encotro el material id stock especificado en el deposito";
					break;
				}
			//}
		}

		if($nro=="ERROR"){
			$this->db->trans_rollback();
		}else{
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


		}else{
			$msg="Otro usuario realizo salidas con el pedido";
		}

		//$this->db->trans_complete();

		unset($data);
		$data['nro']=$nro;
		$data['msg']=$msg;
		$data['x']=$Sql;

		return $data;


	}


	function add_solicitud($usr,$data,$grilla,$id_usr_sector){

		$id_deposito=$data['id_deposito'];
		$nro=$data['nro'];
		$id_zona=$data['id_zona'];
		$id_personal=$data['id_personal'];
		$legajo=$data['legajo'];
		$ot=$data['ot'];
		$obs=utf8_decode($data['obs']);
		$sector=utf8_decode($data['sector']);
		$centro_costo=$data['centro_costo'];
		$fecha=$this->control->ParseDMYtoYMD($data['fecha'],'/');
		$web=$data['web'];

		if($web==1){
			$id_personal=$this->session->userdata('id_personal');
		}

		//$nro=0;

		//$this->db->trans_start();
		$this->db->trans_begin();
/*-----------No utiliza seed para generar nro, se ingresa manualmente--------------*/
		$nro=$this->db->query("select valor from ".BBDD_ODBC_SQLSRV."seed where tabla='pedido' and columna='id_deposito' and id='".$id_deposito."';")->row('valor');

		$nro=ceil($nro)+1;

		$Sql="update ".BBDD_ODBC_SQLSRV."seed set valor=".$nro." where tabla='pedido' and columna='id_deposito' and id=".$id_deposito.";";

		$query = $this->db->query($Sql);
/*----------------------------------------------------------------------------------*/
		$Sql="insert into ".BBDD_ODBC_SQLSRV."pedido (nro, fecha, id_zona,id_personal, retira, centro_costo, id_deposito,  odt, destino, sector, web, id_sector, mod_usuario, ult_mod) values (".$nro.", '".$fecha."',".$id_zona." ,".$id_personal." ,'".$legajo."', '".$centro_costo."', ".$id_deposito.", '".$ot."', '".$obs."', '".$sector."', ".$web.", ".$id_usr_sector.", '".$usr."', getdate());";

		$query = $this->db->query($Sql);

		$id_pedido=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		foreach ($grilla as $key => $value) {

			$id_material=$value['id_material'];
			$cantidad=$value['cantidad'];
			$id_stock=$value['id_stock'];
			$costo_ult=$value['costo_ult'];
			$costo_pp=$value['costo_pp'];
			$stock=$value['cant_stock'];
			$solicitado=$value['solicitado'];
			

			$Sql="insert into ".BBDD_ODBC_SQLSRV."detalle_pedido (id_pedido, nro, id_deposito, centro_costo, id_material, solicitado, cantidad, stock, costo_ult, costo_pp, mod_usuario, ult_mod) values ('".$id_pedido."','".$nro."','".$id_deposito."','". $centro_costo."', '".$id_material."', ".$solicitado.",".$cantidad.",".$stock.",".$costo_ult.",".$costo_pp.",'".$usr."', getdate());";
			$query = $this->db->query($Sql);

			$Sql="update ".BBDD_ODBC_SQLSRV."stock set pedido=pedido+".$cantidad." where id_stock=".$id_stock.";";

			$query = $this->db->query($Sql);

			$Sql="select id_material, id_deposito, cantidad, pedido from ".BBDD_ODBC_SQLSRV."stock where id_stock=".$id_stock. ";";
			$query = $this->db->query($Sql);
			if($query->num_rows() > 0){
				$row=$query->row();
				$cant_stock=$row->cantidad;
				$pedido_stock=$row->pedido;
				$id_material_stock=$row->id_material;
				$id_deposito_stock=$row->id_deposito;

			}else{
				$nro="ERROR";
				$msg="error# No se encotro el material id stock especificado en el deposito";
				break;
			}
		}

		if($nro=="ERROR"){
			$this->db->trans_rollback();
		}else{
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
		$data['msg']=$msg;
		$data['x']=$Sql;

		return $data;
	}

	function anular_solicitud($nro,$usr){

		//$this->db->trans_begin();

		$Sql="update ".BBDD_ODBC_SQLSRV."detalle_pedido set cantidad=0, completo=1, mod_usuario='".$usr."', ult_mod=getdate() where nro=".$nro.";";
		$query = $this->db->query($Sql);

		$filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');
		
		return $filas;
		
	}

	function get_pedido($data){

		$nro=$data['nro'];
		$tipo_mov=$data['tipo_mov'];

		$Sql="select * from ".BBDD_ODBC_SQLSRV."tipo_movimiento where id_tipo_mov=".$tipo_mov." and tipo like '%DEVOLUCION%';";
		$arraySQL[0]=$Sql;

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			$devolucion=true;
		}else{
			$devolucion=false;
		}

		

		//$query = $this->db->query($Sql);



		//$Sql="select * from ".BBDD_ODBC_SQLSRV."pedido where nro='".$nro."';";

		$Sql="select p.id_pedido,p.id_deposito,p.nro,p.id_deposito,p.id_zona,p.sector,z.Zona,z.Localidad,z.Direccion,p.id_personal, x.legajo, x.apellido_nombre,p.odt,p.destino,p.centro_costo,c.denominacion,p.fecha, p.id_sector from ".BBDD_ODBC_SQLSRV."pedido p, ".BBDD_ODBC_SQLSRV."personal x, ".BBDD_ODBC_SQLSRV."zonas z, ".BBDD_ODBC_SQLSRV."centro_costo c where p.id_personal=x.id_personal and p.id_zona=z.id_zona and p.centro_costo=c.centro_costo and nro='".$nro."';";

		$query = $this->db->query($Sql);		

		$arraySQL[1]=$Sql;

		if($query->num_rows() > 0){


			foreach ($query->result_array() as $row){
				$new_row['id_deposito']=$row['id_deposito'];
				$new_row['nro']=$row['nro'];
				$new_row['id_pedido']=$row['id_pedido'];
				$new_row['id_zona']=$row['id_zona'];
				$new_row['sector']=utf8_encode($row['sector']);
				$new_row['zona']=utf8_encode($row['Zona']);
				$new_row['localidad']=utf8_encode($row['Localidad']);
				$new_row['direccion']=utf8_encode($row['Direccion']);
				$new_row['id_personal']=$row['id_personal'];
				$new_row['legajo']=$row['legajo'];
				$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
				$new_row['id_tipo_mov']=$tipo_mov;
				$new_row['devolucion']=$devolucion;
				$new_row['odt']=utf8_encode($row['odt']);
				$new_row['obs']=utf8_encode($row['destino']);
				$new_row['centro_costo']=$row['centro_costo'];
				$new_row['denominacion']=utf8_encode($row['denominacion']);
				$new_row['fecha']=$this->control->ParseDMYtoYMD($row['fecha'],'/');
				$new_row['id_sector']=$row['id_sector'];
			}
		
			if($devolucion==true){
				$Sql="select d.id_detalle_pedido, d.id_material, m.descripcion, m.barcode, m.unidad, d.cantidad as cant_aut, d.cantidad, s.ubicacion, s.id_stock , s.cantidad as cant_stock, d.despachado, d.solicitado, d.costo_ult, d.costo_pp from ".BBDD_ODBC_SQLSRV."detalle_pedido d, ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."stock s where d.id_material=m.id_material and d.id_material=s.id_material and d.id_deposito=s.id_deposito and d.id_pedido=".$new_row['id_pedido'].";";
			}else{
				$Sql="select d.id_detalle_pedido, d.id_material, m.descripcion, m.barcode, m.unidad, d.cantidad as cant_aut, d.cantidad, s.ubicacion, s.id_stock , s.cantidad as cant_stock, d.despachado, d.solicitado, d.costo_ult , d.costo_pp from ".BBDD_ODBC_SQLSRV."detalle_pedido d, ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."stock s where d.id_material=m.id_material and d.id_material=s.id_material and d.id_deposito=s.id_deposito and d.id_pedido=".$new_row['id_pedido']." and completo=0;";
			}

			$query = $this->db->query($Sql);
			$arraySQL[2]=$Sql;

			if($query->num_rows() > 0){
				foreach ($query->result_array() as $row){
					$linea['id_detalle_pedido']=$row['id_detalle_pedido'];
					$linea['barcode']=$row['barcode'];
					$linea['id_material']=$row['id_material'];
					$linea['material']=utf8_encode($row['descripcion']);
					$linea['unidad']=utf8_encode($row['unidad']);
					$linea['solicitado']=utf8_encode($row['solicitado']);
					$linea['cant_aut']=utf8_encode($row['cant_aut']);
					if($devolucion==true){
						$linea['cantidad']=0;
						$linea['cant_stock']=$row['despachado'];
					}else{
						$linea['cantidad']=$row['cantidad']-$row['despachado'];
						$linea['cant_stock']=$row['cant_stock'];
					}
					$linea['ubicacion']=utf8_encode($row['ubicacion']);
					$linea['costo_ult']=$row['costo_ult'];
					$linea['costo_pp']=$row['costo_pp'];
					$linea['linea']=$row['costo_ult']*$row['cantidad'];
					$linea['id_stock']=$row['id_stock'];
					$grilla[] = $linea;
				}
				$new_row['grilla']=$grilla;
			}else{
				unset($new_row);
				$new_row['nro']="ERROR";
				$new_row['msg']="error# No se encotro el material pendiente del pedido especificado";
			}
			$row_set[] = $new_row;

		}else{
			unset($new_row);
			$new_row['nro']="ERROR";
			$new_row['msg']="error# No se encotro el pedido especificado";
			$row_set[] = $new_row;
		}

		//return $arraySQL;
		return $row_set;
		/*if($nro=="ERROR"){
			unset($data);
			$data['nro']=$nro;
			$data['msg']=$msg;
			$data['x']=$Sql;
			return $data;
		}else{
			return $row_set;
		}*/

	}

	function get_pedido_web($data){

		$nro=$data['nro'];
		$tipo_mov=$data['tipo_mov'];


		$Sql="select p.id_pedido,p.id_deposito,p.nro,p.id_deposito,p.id_zona,p.sector,z.Zona,z.Localidad,z.Direccion,p.id_personal, x.legajo, x.apellido_nombre,p.odt,p.destino,p.centro_costo,c.denominacion,p.fecha, p.id_sector from ".BBDD_ODBC_SQLSRV."pedido p, ".BBDD_ODBC_SQLSRV."personal x, ".BBDD_ODBC_SQLSRV."zonas z, ".BBDD_ODBC_SQLSRV."centro_costo c where p.id_personal=x.id_personal and p.id_zona=z.id_zona and p.centro_costo=c.centro_costo and nro='".$nro."';";

		$query = $this->db->query($Sql);

		$sectores=explode(",",$this->control->sectores_auth());
		$sector_habilitado=false;
		

		$arraySQL[1]=$Sql;

		if($query->num_rows() > 0){

			foreach ($query->result_array() as $row){
				$new_row['id_deposito']=$row['id_deposito'];
				$new_row['nro']=$row['nro'];
				$new_row['id_pedido']=$row['id_pedido'];
				$new_row['id_zona']=$row['id_zona'];
				$new_row['sector']=utf8_encode($row['sector']);
				$new_row['zona']=utf8_encode($row['Zona']);
				$new_row['localidad']=utf8_encode($row['Localidad']);
				$new_row['direccion']=utf8_encode($row['Direccion']);
				$new_row['id_personal']=$row['id_personal'];
				$new_row['legajo']=$row['legajo'];
				$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
				$new_row['id_tipo_mov']=$tipo_mov;
				$new_row['odt']=utf8_encode($row['odt']);
				$new_row['obs']=utf8_encode($row['destino']);
				$new_row['centro_costo']=$row['centro_costo'];
				$new_row['denominacion']=utf8_encode($row['denominacion']);
				$new_row['fecha']=$this->control->ParseDMYtoYMD($row['fecha'],'/');
				$new_row['id_sector']=$row['id_sector'];
				foreach ($sectores as $key) {
			   		if($key==$row['id_sector']){
			   			$sector_habilitado=true;
			   		}
			   	}
				$new_row['sector_habilitado']=$sector_habilitado;
			}

			if ($sector_habilitado==false) {

				unset($new_row);
				$new_row['nro']="ERROR";
				$new_row['msg']="error# El sector al que pertenece no esta habilitado para ver el pedido";

			}else{

				$Sql="select d.id_material, sum(d.solicitado) as stk from ".BBDD_ODBC_SQLSRV."detalle_pedido d join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material where d.id_material in (select id_material from ".BBDD_ODBC_SQLSRV."detalle_pedido where id_pedido=".$new_row['id_pedido'].") and d.completo=0 group by d.id_material, m.descripcion;";

				$query = $this->db->query($Sql);


				if($query->num_rows() > 0){
					foreach ($query->result_array() as $row){
						$pedido_mat[$row['id_material']]=$row['stk'];
					}
				}
			
				
				$Sql="select d.id_detalle_pedido, d.id_material, m.descripcion, m.barcode, m.unidad, d.cantidad as cant_aut, d.cantidad, s.ubicacion, s.id_stock , s.cantidad as cant_stock, d.despachado, d.solicitado, d.costo_ult , d.costo_pp from ".BBDD_ODBC_SQLSRV."detalle_pedido d, ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."stock s where d.id_material=m.id_material and d.id_material=s.id_material and d.id_deposito=s.id_deposito and d.id_pedido=".$new_row['id_pedido']." and completo=0;";
				

				$query = $this->db->query($Sql);
				$arraySQL[2]=$Sql;

					if($query->num_rows() > 0){
						foreach ($query->result_array() as $row){
							$linea['id_detalle_pedido']=$row['id_detalle_pedido'];
							$linea['barcode']=$row['barcode'];
							$linea['id_material']=$row['id_material'];
							$linea['material']=utf8_encode($row['descripcion']);
							$linea['unidad']=utf8_encode($row['unidad']);
							$linea['solicitado']=utf8_encode($row['solicitado']);
							$linea['cant_aut']=utf8_encode($row['cant_aut']);
							$linea['cantidad']=$row['cantidad']-$row['despachado'];
							$stk=$row['cant_stock']-($pedido_mat[$row['id_material']]-$row['solicitado']);
							$linea['cant_stock']=$stk;
							$linea['ubicacion']=utf8_encode($row['ubicacion']);
							$linea['costo_ult']=$row['costo_ult'];
							$linea['costo_pp']=$row['costo_pp'];
							$linea['linea']=$row['costo_ult']*$row['cantidad'];
							$linea['id_stock']=$row['id_stock'];
							$grilla[] = $linea;
						}
						$new_row['grilla']=$grilla;
					}else{
						unset($new_row);
						$new_row['nro']="ERROR";
						$new_row['msg']="error# No se encotro el material pendiente del pedido especificado";
					}
				}
				$row_set[] = $new_row;
		}else{
			unset($new_row);
			$new_row['nro']="ERROR";
			$new_row['msg']="error# No se encotro el pedido especificado";
			$row_set[] = $new_row;
		}
		
		//return $arraySQL;
		//return $$pedido_mat;
		return $row_set;
		/*if($nro=="ERROR"){
			unset($data);
			$data['nro']=$nro;
			$data['msg']=$msg;
			$data['x']=$Sql;
			return $data;
		}else{
			return $row_set;
		}*/

	}

	function lista_solicitudes($deposito){

		$Sql="select p.id_deposito, d.deposito, p.nro, p.odt, p.destino,p.fecha, p.centro_costo, c.denominacion, p.sector, p.retira, x.apellido_nombre,z.Zona, z.Direccion, z.Localidad, sum(y.despachado) as despachado,sum(y.cantidad) as cantidad from ".BBDD_ODBC_SQLSRV."pedido p join ".BBDD_ODBC_SQLSRV."deposito d on p.id_deposito=d.id_deposito join ".BBDD_ODBC_SQLSRV."detalle_pedido y on p.id_pedido=y.id_pedido join ".BBDD_ODBC_SQLSRV."personal x on p.id_personal=x.id_personal join ".BBDD_ODBC_SQLSRV."centro_costo c on p.centro_costo=c.centro_costo join ".BBDD_ODBC_SQLSRV."zonas z on p.id_zona=z.id_zona where p.id_deposito in(".$deposito.") and y.completo=0 and p.fecha>=dateadd(Year,-1,getdate()) group by  p.id_deposito, d.deposito, p.nro, p.odt, p.destino, p.fecha, p.centro_costo, c.denominacion, p.sector, p.retira, x.apellido_nombre,z.Zona, z.Direccion, z.Localidad  order by nro desc;";
		
		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['retira']=utf8_encode($row['retira']);
	        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['denominacion']=utf8_encode($row['denominacion']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['Localidad']=utf8_encode($row['Localidad']);
	        	$new_row['cumplimiento']=$row['despachado'].' / '.$row['cantidad'];
	        /*	$new_row['bultos']='';
	        	$new_row['print']='No';*/
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function lista_solicitudes_web($deposito,$sectores){


		$Sql="select p.id_deposito, d.deposito, p.nro, p.odt, p.destino,p.fecha, p.centro_costo, c.denominacion, p.sector, p.retira, x.apellido_nombre,z.Zona, z.Direccion, z.Localidad, sum(y.despachado) as despachado,sum(y.cantidad) as cantidad , p.mod_usuario, w.id_sector from ".BBDD_ODBC_SQLSRV."pedido p join ".BBDD_ODBC_SQLSRV."deposito d on p.id_deposito=d.id_deposito  join ".BBDD_ODBC_SQLSRV."usuarios w on w.usuario=p.mod_usuario join ".BBDD_ODBC_SQLSRV."detalle_pedido y on p.id_pedido=y.id_pedido join ".BBDD_ODBC_SQLSRV."personal x on p.id_personal=x.id_personal join ".BBDD_ODBC_SQLSRV."centro_costo c on p.centro_costo=c.centro_costo join ".BBDD_ODBC_SQLSRV."zonas z on p.id_zona=z.id_zona where p.id_deposito in(".$deposito.") and p.web=1 and p.editado=0 and w.id_sector in(".$sectores.") and y.completo=0 and p.fecha>=dateadd(Year,-1,getdate()) group by  p.id_deposito, d.deposito, p.nro, p.odt, p.destino, p.fecha, p.centro_costo, c.denominacion, p.sector, p.retira, x.apellido_nombre,z.Zona, z.Direccion, z.Localidad, p.mod_usuario, w.id_sector  order by nro desc;";
		
		$query = $this->db->query($Sql);

		if($query->num_rows() > 0){
			
	        foreach ($query->result_array() as $row){
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['retira']=utf8_encode($row['retira']);
	        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['denominacion']=utf8_encode($row['denominacion']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['Localidad']=utf8_encode($row['Localidad']);

	        	$new_row['cumplimiento']=$row['despachado'].' / '.$row['cantidad'];
	        /*	$new_row['bultos']='';
	        	$new_row['print']='No';*/
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}
	}

	function imprimir_solicitudes($nros){

		$Sql="SELECT p.id_pedido, p.fecha, id_detalle_pedido, p.nro,p.odt, p.destino, p.id_sector,p.id_deposito, x.deposito, p.centro_costo, c.denominacion, z.Zona, z.Direccion, z.Localidad,p.sector, d.id_material, m.barcode, m.descripcion, m.unidad, s.ubicacion, d.solicitado, d.cantidad, d.despachado, d.stock, m.costo_pp, m.costo_ult, d.completo, p.mod_usuario, p.ult_mod, l.legajo, l.apellido_nombre FROM ".BBDD_ODBC_SQLSRV."pedido p join ".BBDD_ODBC_SQLSRV."detalle_pedido d on p.id_pedido=d.id_pedido join ".BBDD_ODBC_SQLSRV."stock s on d.id_deposito=s.id_deposito and d.id_material=s.id_material join ".BBDD_ODBC_SQLSRV."deposito x on p.id_deposito=x.id_deposito join ".BBDD_ODBC_SQLSRV."centro_costo c on p.centro_costo=c.centro_costo join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material join ".BBDD_ODBC_SQLSRV."zonas z on p.id_zona=z.id_zona join ".BBDD_ODBC_SQLSRV."personal l on p.id_personal=l.id_personal where p.nro in(".$nros.") order by p.id_pedido;";
		$query = $this->db->query($Sql);
		if($query->num_rows() > 0){
	        foreach ($query->result_array() as $row){
	        	$new_row['id_deposito']=utf8_encode($row['id_deposito']);
	        	$new_row['deposito']=utf8_encode($row['deposito']);
	        	$new_row['fecha']=utf8_encode($this->control->ParseDMYtoYMD($row['fecha'],'/'));
	        	$new_row['nro']=utf8_encode($row['nro']);
	        	$new_row['odt']=utf8_encode($row['odt']);
	        	$new_row['destino']=utf8_encode($row['destino']);
	        	$new_row['id_sector']=utf8_encode($row['id_sector']);
	        	$new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        	$new_row['denominacion']=utf8_encode($row['denominacion']);
	        	$new_row['sector']=utf8_encode($row['sector']);
	        	$new_row['Zona']=utf8_encode($row['Zona']);
	        	$new_row['Direccion']=utf8_encode($row['Direccion']);
	        	$new_row['Localidad']=utf8_encode($row['Localidad']);
	        	$new_row['id_material']=utf8_encode($row['id_material']);
	        	$new_row['barcode']=utf8_encode($row['barcode']);
	        	$new_row['descripcion']=utf8_encode($row['descripcion']);
	        	$new_row['unidad']=utf8_encode($row['unidad']);
	        	$new_row['ubicacion']=utf8_encode($row['ubicacion']);
	        	$new_row['cantidad']=utf8_encode($row['cantidad']);
	        	$new_row['despachado']=utf8_encode($row['despachado']);
	        	$new_row['legajo']=utf8_encode($row['legajo']);
	        	$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
				$row_set[] = $new_row; //build an array
	        }
        	return $row_set; //format the array into json data
      	}else{
      		return 'NoData';
      	}
	}

}