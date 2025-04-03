<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contable_model extends CI_Model {

	function get_tipo_movimiento($q,$usr){

		//$Sql="select * FROM ".BBDD_ODBC_SQLSRV."tipo_movimiento m  where m.movimiento='".$q."';";

		$Sql="select p.id_tipo_mov, m.tipo, m.factor from ".BBDD_ODBC_SQLSRV."permisos_movimiento p, ".BBDD_ODBC_SQLSRV."tipo_movimiento m, ".BBDD_ODBC_SQLSRV."usuarios u where p.id_tipo_mov=m.id_tipo_mov and p.id_perfil=u.id_perfil and m.movimiento='".$q."' and usuario='".$usr."' order by m.id_tipo_mov asc;";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){

	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['id_tipo_mov']);
	        $new_row['label']=utf8_encode($row['tipo']);
	        $new_row['factor']=$row['factor'];
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }

	}
	function get_centro_costo($q){

		/*
		$Sql="select c.centro_costo, CONCAT(c.centro_costo,' ->',c.denominacion) as col1
		from ".BBDD_ODBC_SQLSRV."centro_costo c
		where c.centro_costo like '%".$q."%'
		or  c.denominacion like '%".$q."%';";
		*/
		$Sql="select c.centro_costo, c.denominacion
		from ".BBDD_ODBC_SQLSRV."centro_costo c
		where c.centro_costo like '%".$q."%'
		or  c.denominacion like '%".$q."%';";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['centro_costo']);
	        $new_row['label']=utf8_encode($row['centro_costo']." -> ".$row['denominacion']);
	        $new_row['denominacion']=utf8_encode($row['denominacion']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}


	function get_denominacion_centro_costo($q){

		$long_str=strlen($q);
	    $q=$this->control->limpia_str($q);
	    $a = preg_split("/[\s\%]+/", $q);

	    $where="";

	    foreach ($a as $v) {
	      if($v!=NULL){
	        if($where==""){
	          $where=" where c.centro_costo like '%".$v."%' or  c.denominacion like '%".$v."%'";
	        }else{
	          $where=$where." and denominacion like '%".$v."%'";
	        }
	      }
	    }

		$Sql="select c.centro_costo, c.denominacion
		from ".BBDD_ODBC_SQLSRV."centro_costo c ".$where.";";
		

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['denominacion']);
	        $new_row['label']=utf8_encode($row['centro_costo']." -> ".$row['denominacion']);
	        $new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}



	function lista_centro_costo(){

		$Sql="select c.centro_costo, c.denominacion
		from ".BBDD_ODBC_SQLSRV."centro_costo c";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        $new_row['denominacion']=utf8_encode($row['denominacion']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}


	function add_centro_costo($data){

		$campos="";
		foreach ($data as $k => $v) {
			if($campos!=""){
				$campos=$campos.", ".$k;
				$valores=$valores.",'".utf8_decode($v)."'";
			}else{
				$campos=$k;
				$valores="'".utf8_decode($v)."'";
			}
		}


		//$campos=$campos.",modificacion, usuario_mod";
		//$valores=$valores."',Now(),'admin'";

		$where="INSERT INTO ".BBDD_ODBC_SQLSRV."centro_costo (".$campos.") VALUES (".$valores.");";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}


	function edit_centro_costo($data){

		$id="";
		$where="";
		foreach ($data as $k => $v) {
			if($k!='centro_costo'){
				if($where!=""){
					$where=$where.", ".$k."='".utf8_decode($v)."'";
				}else{
					$where=$where." ".$k."='".utf8_decode($v)."'";
				}
			}else{
				$id=$v;
			}
		}


		$where="UPDATE ".BBDD_ODBC_SQLSRV."centro_costo SET".$where." WHERE centro_costo=".$id.";";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}	


	function get_tipocompra(){

		$Sql="select m.id_tipo_compra, m.compra, m.activo FROM ".BBDD_ODBC_SQLSRV."tipo_compra m  where m.activo=1 ;";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){

	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['id_tipo_compra']);
	        $new_row['label']=utf8_encode($row['compra']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }

	}

	function get_YearMovimientos(){

		$Sql="select YEAR(ult_mod) as mov_year from ".BBDD_ODBC_SQLSRV."movimientos group by year(ult_mod);";
   		$query = $this->db->query($Sql);

   		if($query->num_rows() > 0){

	      foreach ($query->result_array() as $row){
	 
	        $new_row['year']=utf8_encode($row['mov_year']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }

   	}


	function get_proveedor($q){

		$long_str=strlen($q);
	    $q=$this->control->limpia_str($q);
	    $a = preg_split("/[\s\%]+/", $q);

	    $where="";

	    foreach ($a as $v) {
	      if($v!=NULL){
	        if($where==""){
	          $where=" where proveedor like '%".$v."%'";
	        }else{
	          $where=$where." and proveedor like '%".$v."%'";
	        }
	      }
	    }

	   $where="select m.id_proveedor, m.proveedor from ".BBDD_ODBC_SQLSRV."[proveedores] m".$where.";";

	    $query = $this->db->query($where);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['id_proveedor']);
	        $new_row['label']=utf8_encode($row['proveedor']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}

	function get_personal($q){

		$long_str=strlen($q);
	    $q=$this->control->limpia_str($q);
	    $a = preg_split("/[\s\%]+/", $q);

	    $where="";

	    foreach ($a as $v) {
	      if($v!=NULL){
	        if($where==""){
	          $where=" where c.legajo like '%".$v."%' or  c.apellido_nombre like '%".$v."%'";
	        }else{
	          $where=$where." and apellido_nombre like '%".$v."%'";
	        }
	      }
	    }

		$Sql="select c.id_personal,c.legajo, c.apellido_nombre
		from ".BBDD_ODBC_SQLSRV."personal c ".$where.";";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        $new_row['value']=utf8_encode($row['legajo']);
	        $new_row['label']=utf8_encode($row['legajo']." ->".$row['apellido_nombre']);
	        $new_row['id_personal']=$row['id_personal'];
	        $new_row['legajo']=utf8_encode($row['legajo']);
	        $new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}

	function lista_personal(){

		$Sql="select c.legajo, c.apellido_nombre, c.centro_costo, c.activo
		from ".BBDD_ODBC_SQLSRV."personal c";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        $new_row['legajo']=utf8_encode($row['legajo']);
	        $new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
	        $new_row['centro_costo']=utf8_encode($row['centro_costo']);
	        if($row['activo']==0){
	        	$activo='No';
	        }else{
	        	$activo='Si';
	        }
	        $new_row['activo']=$activo;
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}
//======================================

	function add_persona($data){

		$campos="";
		foreach ($data as $k => $v) {
			if($campos!=""){
				$campos=$campos.", ".$k;
				$valores=$valores.",'".utf8_decode($v)."'";
			}else{
				$campos=$k;
				$valores="'".utf8_decode($v)."'";
			}
		}


		//$campos=$campos.",modificacion, usuario_mod";
		//$valores=$valores."',Now(),'admin'";

		$where="INSERT INTO ".BBDD_ODBC_SQLSRV."personal (".$campos.") VALUES (".$valores.");";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}


	function edit_persona($data){

		$id="";
		$where="";
		foreach ($data as $k => $v) {
			if($k!='legajo'){
				if($where!=""){
					$where=$where.", ".$k."='".utf8_decode($v)."'";
				}else{
					$where=$where." ".$k."='".utf8_decode($v)."'";
				}
			}else{
				$id=$v;
			}
		}


		$where="UPDATE ".BBDD_ODBC_SQLSRV."personal SET".$where." WHERE legajo='".$id."';";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}


//=====================================
	function alta_proveedor($usr,$data){

	    $this->db->trans_start();

	    $cuit=$data['cuit'];
	    $proveedor=$data['proveedor'];
	    $direccion=$data['direccion'];
	    $telefono=$data['telefono'];
	    $fax=$data['fax'];
	    $email=$data['email'];

	    $sql="insert into ".BBDD_ODBC_SQLSRV."proveedores (cuit, proveedor, direccion, telefono, fax, email, mod_usuario, ult_mod) values ('".$cuit."', '".$proveedor."', '".$direccion."', '".$telefono."', '".$fax."', '".$email."', '".$usr."', Now());";
	    $query = $this->db->query($sql);
	    $id_proveedor=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

	    if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $id_proveedor="ERROR";
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }

	    $this->db->trans_complete();
	    unset($data);
	    $data['id_proveedor']=$id_proveedor;
	    $data['proveedor']=$proveedor;
	    $data['msg']=$msg;
	    return $data;

	}

	function get_sector($q){
		$long_str=strlen($q);
	    $q=$this->control->limpia_str($q);
	    $a = preg_split("/[\s\%]+/", $q);

	    $where="";

	    foreach ($a as $v) {
	      if($v!=NULL){
	        if($where==""){
	          $where=" where sector like '%".$v."%'";
	        }else{
	          $where=$where." and sector like '%".$v."%'";
	        }
	      }
	    }

	   $where="select m.id_sector, m.sector from ".BBDD_ODBC_SQLSRV."[sectores] m".$where.";";

	    $query = $this->db->query($where);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['value']=utf8_encode($row['id_sector']);
	        $new_row['label']=utf8_encode($row['sector']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}




	function lista_unidades(){

		$Sql="select c.unidad, c.descripcion
		from ".BBDD_ODBC_SQLSRV."unidades c";

		 $query = $this->db->query($Sql);

	    if($query->num_rows() > 0){
	      foreach ($query->result_array() as $row){
	        //$new_row['label']=$row['descripcion']; //build an array
	        $new_row['unidad']=utf8_encode($row['unidad']);
	        $new_row['descripcion']=utf8_encode($row['descripcion']);
	        $row_set[] = $new_row; //build an array
	      }
	      return $row_set; //format the array into json data
	    }
	    return "";//." largo: ".$long_str."<br>".$q;
	}


	function add_unidades($data){

		$campos="";
		foreach ($data as $k => $v) {
			if($campos!=""){
				$campos=$campos.", ".$k;
				$valores=$valores.",'".utf8_decode($v)."'";
			}else{
				$campos=$k;
				$valores="'".utf8_decode($v)."'";
			}
		}


		//$campos=$campos.",modificacion, usuario_mod";
		//$valores=$valores."',Now(),'admin'";

		$where="INSERT INTO ".BBDD_ODBC_SQLSRV."unidades (".$campos.") VALUES (".$valores.");";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}


	function edit_unidades($data){

		$id="";
		$where="";
		foreach ($data as $k => $v) {
			if($k!='unidad'){
				if($where!=""){
					$where=$where.", ".$k."='".utf8_decode($v)."'";
				}else{
					$where=$where." ".$k."='".utf8_decode($v)."'";
				}
			}else{
				$id=$v;
			}
		}


		$where="UPDATE ".BBDD_ODBC_SQLSRV."unidades SET".$where." WHERE unidad='".$id."';";

		$query=$this->db->query($where);

		$data=$this->db->affected_rows();
		return $data;

	}	

}