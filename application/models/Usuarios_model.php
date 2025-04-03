<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
	

	function get_perfiles(){
		
		$Sql="select * from ".BBDD_ODBC_SQLSRV."perfiles;";
		$query = $this->db->query($Sql);

	    if($query->num_rows() > 0){

	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['id_perfil']);
	        $new_row['label']=utf8_encode($row['perfil']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }

	}

	function get_permisos($perfil){


		$Sql="Select * from ".BBDD_ODBC_SQLSRV."aplicaciones a order by lft;";

		$query = $this->db->query($Sql);

		if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				//$app['id_aplicacion']= $row->id_aplicacion;
				$i=$row->id_aplicacion;
				$app[$i]['name']= $row->aplicacion;
				$tmp=$row->menu;
				if($tmp==1){
					$tmp="Si";
				}else{
					$tmp="No";
				}
				$app[$i]['menu']= $tmp;
				$app[$i]['orden_menu']= $row->orden_menu;
				$app[$i]['imagen']= $row->imagen;
				$app[$i]['lft']= $row->lft;
				$app[$i]['rgt']= $row->rgt;
				$app[$i]['level']= $row->nivel;
			}
		}

		$Sql="select x.id_aplicacion, x.id_permiso, p.id_perfil, p.perfil
		from  ".BBDD_ODBC_SQLSRV."perfiles p, ".BBDD_ODBC_SQLSRV."permisos x
		where p.id_perfil=x.id_perfil
		and p.id_perfil=?;";

		$query = $this->db->query($Sql,$perfil);

	    if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				//$new_row['id_aplicacion']= $row->id_aplicacion;
				$i=$row->id_aplicacion;
				$permiso[$i]['id_permiso']= $row->id_permiso;
				$permiso[$i]['id_perfil']= $row->id_perfil;
				$permiso[$i]['perfil']= $row->perfil;
				$permiso[$i]['id_aplicacion']=$row->id_aplicacion;
				
			}
			
		}else{
			$permiso['0']['id_aplicacion']='';
		}

		foreach ($app as $key => $value) {
			$new_row['id_aplicacion']= $key;
			$new_row['name']= $value['name'];
			$new_row['menu']= $value['menu'];
			$new_row['orden_menu']= $value['orden_menu'];
			$new_row['imagen']= $value['imagen'];
			$new_row['lft']= $value['lft'];
			$new_row['rgt']= $value['rgt'];
			$new_row['level']= $value['level'];
			//$new_row['value']=$value;
			foreach ($permiso as $key_perm => $value_perm) {
				if(intval($value_perm['id_aplicacion'])==$key){
					$new_row['id_permiso']=$value_perm['id_permiso'];
					$new_row['acceso']=true;
					break;
				}else{
					$new_row['id_permiso']='';
					$new_row['acceso']=false;
				}
			}
			reset($permiso);

	        $row_set[] = $new_row; //build an array
		}

		
		return $row_set;

	}

	function set_permisos($id_perfil,$id_aplicacion,$usr){

		$Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."permisos (id_perfil, id_aplicacion, id_metodo, mod_usuario, ult_mod) VALUES (".$id_perfil.", ".$id_aplicacion.", NULL, '".$usr."', Now());";
		$query = $this->db->query($Sql);
		$filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');
    	return $filas;
	}

	function del_permisos($id_perfil,$id_aplicacion){

		$Sql="delete from ".BBDD_ODBC_SQLSRV."permisos where id_perfil=".$id_perfil." and id_aplicacion in (SELECT node.id_aplicacion FROM ".BBDD_ODBC_SQLSRV."aplicaciones AS node, ".BBDD_ODBC_SQLSRV."aplicaciones AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.id_aplicacion = ".$id_aplicacion.");";
		$query = $this->db->query($Sql);
		$filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');
    	return $filas;

	}

	function get_permiso_movimiento($id_perfil){

		$Sql="select t.id_tipo_mov, t.tipo, t.movimiento, t.factor, p.id_permiso_mov, p.id_perfil from ".BBDD_ODBC_SQLSRV."tipo_movimiento t left join ".BBDD_ODBC_SQLSRV."permisos_movimiento p on t.id_tipo_mov=p.id_tipo_mov and p.id_perfil=".$id_perfil." ;";

		$query = $this->db->query($Sql);

		foreach ($query->result() as $row){
			
			$new_row['id_tipo_mov']=utf8_encode($row->id_tipo_mov);
			$new_row['tipo']=utf8_encode($row->tipo);
			$new_row['movimiento']=utf8_encode($row->movimiento);
			$new_row['factor']=utf8_encode($row->factor);
			$tmp=utf8_encode($row->id_permiso_mov);
			if($tmp!=NULL){
				$tmp=true;
			}else{
				$tmp=false;
			}
			$new_row['id_permiso_mov']=$tmp;
			$row_set[] = $new_row; 

		}
		return $row_set; 


	}

	function buscar_usr($q){
		$Sql="select c.usuario, CONCAT(c.usuario,' ->',c.apellido_nombre) as col1, apellido_nombre
		from ".BBDD_ODBC_SQLSRV."usuarios c
		where c.usuario like '%".$q."%'
		or  c.apellido_nombre like '%".$q."%';";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        $new_row['value']=utf8_encode($row['usuario']);
	        $new_row['label']=utf8_encode($row['col1']);
	        $new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}

	function get_lista_usr(){

		$Sql="select u.usuario, u.id_sector, s.sector, u.apellido_nombre, u.id_perfil, p.perfil, u.clave, u.session, u.conectado, u.bloqueado, u.error_login, u.direccion_ip, u.inicio_session, u.session_time, u.activo, (u.session_time-u.inicio_session) as tiempo from ".BBDD_ODBC_SQLSRV."usuarios u inner join ".BBDD_ODBC_SQLSRV."sectores s on u.id_sector=s.id_sector inner join ".BBDD_ODBC_SQLSRV."perfiles p on u.id_perfil=p.id_perfil;";

		$query = $this->db->query($Sql);


	    if($query->num_rows() > 0 ){
	    	
			foreach ($query->result_array() as $row) {
				$new_row['usuario']= $row['usuario'];
				$new_row['apellido_nombre']= utf8_encode($row['apellido_nombre']);
				$new_row['id_perfil']= $row['id_perfil'];
				$new_row['perfil']= $row['perfil'];
				$new_row['id_sector']= $row['id_sector'];
				$new_row['sector']= $row['sector'];
				if($row['conectado']==1){
					$new_row['conectado']='<div class="conectado"></div>';
				}else{
					$new_row['conectado']='<div class="desconectado"></div>';
				}
				//$new_row['conectado']= $row['conectado'];
				if($row['bloqueado']==1){
					$new_row['bloqueado']='<div class="bloqueado"></div>';
				}else{
					$new_row['bloqueado']='<div class="permitido"></div>';
				}
				//$new_row['bloqueado']= $row['bloqueado'];
				$new_row['direccion_ip']= $row['direccion_ip'];
				if($row['activo']==1){
					$new_row['activo']='<div class="activo"></div>';
				}else{
					$new_row['activo']='<div class="inactivo"></div>';
				}
				//$new_row['activo']= $row['activo'];
				
				$new_row['inicio_session']= $row['inicio_session'];
				$new_row['session_time']= $row['session_time'];
				$hs=floor(($row['tiempo']/3600));
				$min=$row['tiempo']/60%60;
				$seg=$row['tiempo']%60;
				$tmp=$hs.'hs'.str_pad($min,2,'0',STR_PAD_LEFT).'m'.str_pad($seg, 2,STR_PAD_LEFT).'s';
				$new_row['tiempo']= $tmp;
		        $row_set[] = $new_row; //build an array
			}
			//return $row_set;

		}else{
			$row_set[]="ppe";
			
		}
		//$row_set[]="ppe";
		return $row_set;

	}

	function get_usr_status($usuario){


		$Sql="select u.usuario, u.id_sector, s.sector, u.apellido_nombre, u.id_perfil, p.perfil, u.id_personal, u.clave, u.session, u.conectado, u.bloqueado, u.error_login, u.direccion_ip, u.inicio_session, u.session_time, u.activo, (u.session_time-u.inicio_session) as tiempo from ".BBDD_ODBC_SQLSRV."usuarios u inner join ".BBDD_ODBC_SQLSRV."sectores s on u.id_sector=s.id_sector inner join ".BBDD_ODBC_SQLSRV."perfiles p on u.id_perfil=p.id_perfil where u.usuario='".$usuario."';";

		$query = $this->db->query($Sql);


	    if($query->num_rows() > 0 ){
	    	
			foreach ($query->result_array() as $row) {
				$new_row['usuario']= $row['usuario'];
				$new_row['apellido_nombre']= utf8_encode($row['apellido_nombre']);
				$new_row['id_perfil']= $row['id_perfil'];
				$new_row['perfil']= $row['perfil'];
				$new_row['id_personal']= $row['id_personal'];
				$new_row['id_sector']= $row['id_sector'];
				$new_row['sector']= $row['sector'];
				$new_row['clave']= $row['clave'];
				$new_row['error_login']= $row['error_login'];
				$new_row['conectado']= $row['conectado'];
				$new_row['bloqueado']= $row['bloqueado'];
				$new_row['direccion_ip']= $row['direccion_ip'];
				$new_row['activo']= $row['activo'];
				$new_row['inicio_session']= $row['inicio_session'];
				$new_row['session_time']= $row['session_time'];
				$hs=floor(($row['tiempo']/3600));
				$min=$row['tiempo']/60%60;
				$seg=$row['tiempo']%60;
				$tmp=$hs.'hs'.str_pad($min,2,'0',STR_PAD_LEFT).'m'.str_pad($seg, 2,STR_PAD_LEFT).'s';
				$new_row['tiempo']= $tmp;
		        //$row_set[] = $new_row; //build an array
			}
			//return $row_set;

		}else{
			$new_row="ppe";
			
		}
		//$row_set[]="ppe";
		return $new_row;

	}

	function add_usr($data){


		$accion=$data['accion'];
		$usuario=$data['usuario'];
		$apellido_nombre=$data['apellido_nombre'];
		$id_sector=$data['id_sector'];
		$id_perfil=$data['id_perfil'];
		$id_personal=$data['id_personal'];
		$bloqueado=$data['bloqueado'];
		$conectado=$data['conectado'];
		$activo=$data['activo'];
		$usr=$data['usr'];

		unset($data);

		if($accion=='Insertar'){

			$Sql="insert into ".BBDD_ODBC_SQLSRV."usuarios (usuario,id_sector,apellido_nombre,id_perfil, id_personal,clave,conectado,bloqueado,error_login,direccion_ip,inicio_session,session_time,activo,mod_usuario,ult_mod) values('".$usuario."',".$id_sector.",'".$apellido_nombre."',".$id_perfil.",".$id_personal.",'',0,0,0,'',0,0,1,'".$usr."',Now())";
			$query = $this->db->query($Sql);
			$data=$this->db->query('select @@ROWCOUNT as rows_affected;')->row('rows_affected');
			if($data==0){
				$data = array(
					'estatus' => 'ERROR',
					'msg'=>'No se pudo insertar al nuevo usuario:'.$usuario.' '.$apellido_nombre
				);
			}else{
				$data = array('estatus' => 'OK',
					'msg'=>'Se dio el alta al usuario:'.$usuario.' '.$apellido_nombre
				);
			}
		}else{
			$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set id_sector =".$id_sector.",apellido_nombre ='".$apellido_nombre."',id_perfil =".$id_perfil.",conectado =".$conectado.",bloqueado =".$bloqueado.",error_login =0,activo =".$activo.",mod_usuario ='".$usr."',ult_mod =Now() where usuario='".$usuario."';";
			$query = $this->db->query($Sql);
			$data=$this->db->query('select @@ROWCOUNT as rows_affected;')->row('rows_affected');
			if($data==0){	
				$data = array(
					'estatus' => 'ERROR',
					'msg'=>'No se pudo realizar los cambios en el usuario:'.$usuario.' '.$apellido_nombre 
				);
			}else{
				$data = array('estatus' => 'OK',
					'msg'=>'Se modificó al usuario:'.$usuario.' '.$apellido_nombre
				);
			}
		}

		return $data;

	}


}