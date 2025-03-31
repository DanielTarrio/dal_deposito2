<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rutas_model extends CI_Model {

	function get_ruta($data){

		$id_deposito=$data['deposito'];
	  	$ruta=$data['ruta'];


		//$this->load->library('Control');
		$long_str=strlen($ruta);
		$ruta=$this->control->limpia_str($ruta);
		$a = preg_split("/[\s\%]+/", $ruta);

		$where="";

		foreach ($a as $v) {
			if($v!=NULL){
				if($where==""){
					$where=" where r.ruta like '%".$v."%'";
				}else{
					$where=$where." and r.ruta like '%".$v."%'";
				}
			}
		}


		$Sql="select r.id_ruta, r.id_deposito, r.ruta from ".BBDD_ODBC_SQLSRV."rutas r ".$where." and r.id_deposito=".$id_deposito.";";

		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$new_row['value']=utf8_encode($row['id_ruta']);
				$new_row['label']=utf8_encode($row['ruta']);
				$row_set[]=$new_row;
		    }
		    return $row_set; //format the array into json data
		}

	}

	function get_recorrido($id_ruta){

		//$Sql="select r.id_recorrido, r.id_ruta, r.id_zona, r.id_dependencia, d.dependencia, r.orden, z.Zona, z.Direccion, z.Localidad from ".BBDD_ODBC_SQLSRV."recorridos r, ".BBDD_ODBC_SQLSRV."zonas z, ".BBDD_ODBC_SQLSRV."dependencia d where r.id_dependencia=d.id_dependencia and r.id_zona=z.id_zona and r.id_ruta=".$id_ruta.";";

		$Sql="select r.id_recorrido, r.id_ruta, r.id_dependencia, d.dependencia from ".BBDD_ODBC_SQLSRV."recorridos r, ".BBDD_ODBC_SQLSRV."dependencia d where r.id_dependencia=d.id_dependencia and r.id_ruta=".$id_ruta.";";


		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$new_row['id_recorrido']=utf8_encode($row['id_recorrido']);
		    	$new_row['id_ruta']=utf8_encode($row['id_ruta']);
		    	$new_row['id_dependencia']=utf8_encode($row['id_dependencia']);
		    	$new_row['dependencia']=utf8_encode($row['dependencia']);
				$row_set[]=$new_row;
		    }
		    return $row_set; //format the array into json data
		}

	}

	function get_dependencia($data){

		$dependencia=$data['dependencia'];

		//$this->load->library('Control');
		$long_str=strlen($dependencia);
		$dependencia=$this->control->limpia_str($dependencia);
		$a = preg_split("/[\s\%]+/", $dependencia);

		$where="";

		foreach ($a as $v) {
			if($v!=NULL){
				if($where==""){
					$where=" where r.dependencia like '%".$v."%'";
				}else{
					$where=$where." and r.dependencia like '%".$v."%'";
				}
			}
		}

		$Sql="select  r.id_dependencia, r.dependencia from ".BBDD_ODBC_SQLSRV."dependencia r ".$where.";";

		//$Sql="select r.id_dependencia, r.dependencia from ".BBDD_ODBC_SQLSRV."dependencia r".$where.";";

		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$new_row['value']=utf8_encode($row['id_dependencia']);
		    	$new_row['label']=utf8_encode($row['dependencia']);
				$row_set[]=$new_row;
		    }
		    return $row_set; //format the array into json data
		}

	}

	function get_zonas_dependencia($id_dependencia){

		$Sql="select d.id_dependencia, d.dependencia, z.id_zona, z.Zona, z.Localidad, z.Direccion, z.CP from ".BBDD_ODBC_SQLSRV."dependencia d left join ".BBDD_ODBC_SQLSRV."zonas z on d.id_dependencia=z.id_dependencia where d.id_dependencia=".$id_dependencia.";";
		
		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$new_row['id_dependencia']=utf8_encode($row['id_dependencia']);
		    	$new_row['dependencia']=utf8_encode($row['dependencia']);
		    	$new_row['id_zona']=utf8_encode($row['id_zona']);
		    	$new_row['Zona']=utf8_encode($row['Zona']);
		    	$new_row['Direccion']=utf8_encode($row['Direccion']);
		    	$new_row['Localidad']=utf8_encode($row['Localidad']);
		    	$new_row['CP']=utf8_encode($row['CP']);
				$row_set[]=$new_row;
		    }
		    return $row_set; //format the array into json data
		}

	}

	function get_recorrido_id($id_recorrido){

		$Sql="select r.id_recorrido, r.id_ruta, r.id_zona, r.dependencia, r.orden, z.Zona, z.Direccion, z.Localidad from ".BBDD_ODBC_SQLSRV."recorridos r, ".BBDD_ODBC_SQLSRV."zonas z where  r.id_zona=z.id_zona and r.id_recorrido=".$id_recorrido.";";

		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		    	$new_row['id_recorrido']=utf8_encode($row['id_recorrido']);
		    	$new_row['id_ruta']=utf8_encode($row['id_ruta']);
		    	$new_row['id_zona']=utf8_encode($row['id_zona']);
		    	$new_row['dependencia']=utf8_encode($row['dependencia']);
		    	$new_row['orden']=utf8_encode($row['orden']);
		    	$new_row['Zona']=utf8_encode($row['Zona']);
		    	$new_row['Direccion']=utf8_encode($row['Direccion']);
		    	$new_row['Localidad']=utf8_encode($row['Localidad']);
				//$row_set[]=$new_row;
		    }
		    return $new_row; //format the array into json data
		}

	}

	function alta_ruta($data){

		$id_deposito=$data['id_deposito'];
		$ruta=$data['ruta'];
		$usr=$data['usr'];
		

		$this->db->trans_start();
		
		$Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."rutas (id_deposito,ruta,ult_mod,mod_usuario) VALUES (".$id_deposito.",'".$ruta."',getdate(),'".$usr."')";

		$query = $this->db->query($Sql);

		$id_ruta=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		if ($this->db->trans_status() === FALSE){
			log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
			$id_ruta="ERROR";
			$msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
		}else{
			$msg="ok";  
		}

		$this->db->trans_complete();

		unset($data);
		$data['id_ruta']=$id_ruta;
		$data['ruta']=$ruta;
		$data['msg']=$msg;
		//$data['x']=$Sql;

		return $data;

	}

	function asignar_dependencia($data){

		$id_ruta=$data['id_ruta'];
		$id_dependencia=$data['id_dependencia'];
		$usr=$data['usr'];
		

		$this->db->trans_start();

		$Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."recorridos (id_ruta,id_dependencia,orden) VALUES (".$id_ruta.", ".$id_dependencia." ,NULL);";

		$query = $this->db->query($Sql);

		$id_ruta=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		if ($this->db->trans_status() === FALSE){
			log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
			$id_ruta="ERROR";
			$msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
		}else{
			$msg="ok";  
		}

		$this->db->trans_complete();

		unset($data);
		$data['id_ruta']=$id_ruta;
		$data['ruta']=$ruta;
		$data['msg']=$msg;
		//$data['x']=$Sql;

		return $data;

	}

	function set_entregas($DataEntrega,$data,$usr){

		$id_ruta=$DataEntrega['id_recorrido'];
		$chofer=$DataEntrega['chofer'];
		$patente=$DataEntrega['patente'];

		$this->db->trans_start();

		$usr=$usr;

		$Sql= "INSERT INTO ".BBDD_ODBC_SQLSRV."entregas
           (id_ruta,fecha,chofer,patente,ult_mod,mod_usuario) VALUES
           ('".$id_ruta."',getdate(),'".$chofer."','".$patente."',getdate(),'".$usr."')";


        $query = $this->db->query($Sql);

		$id_entrega=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

		$i=0;

		foreach ($data as $key => $value) {

			if($value['agr']!=''){
				//$sector=$value['sector'];
				//$obs=$value['obs'];
				$id_salida='NULL';
				//$id_dependencia=$value['id_dependencia'];
				//$agr=$value['agr'];
				//$bultos=$value['bultos'];
				//$destino=$value['destino'];
				

			}else{
				//$sector=$value['sector'];
				//$obs=$value['obs'];
				$id_salida=$value['program'];
				//$id_dependencia=$value['id_dependencia'];
				//$agr=$value['agr'];
				//$bultos=$value['bultos'];
				//$destino=$value['destino'];

			}

			$sector=$value['sector'];
			$obs=$value['obs'];
			$id_dependencia=$value['id_dependencia'];
			$agr=$value['agr'];
			$bultos=$value['bultos'];
			$destino=$value['destino'];
			
			$i++;

			$Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."detalle_entrega (id_entrega,id_salida,id_recorrido,id_dependencia,agregado,bultos,destino,sector,orden,observaciones,ult_mod,mod_usuario) VALUES
           (".$id_entrega." ,".$id_salida.",".$id_ruta.",".$id_dependencia.",'".$agr."',".$bultos.",'".$destino."','".$sector."',".$i.",'".$obs."',getdate(),'".$usr."')";
			$query = $this->db->query($Sql);

			$Sql="update ".BBDD_ODBC_SQLSRV."salida set prog=1 where id_salida in(select id_salida from ".BBDD_ODBC_SQLSRV."detalle_entrega where id_entrega=".$id_entrega.");";
			$query = $this->db->query($Sql);


		}

		if ($this->db->trans_status() === FALSE){
			log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
			$id_entrega="ERROR";
			$msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
		}else{
			$msg="ok";  
		}

		$this->db->trans_complete();

		unset($data);
		$data['id_entrega']=$id_entrega;
		$data['id_ruta']=$id_ruta;
		$data['msg']=$msg;
		//$data['x']=$Sql;

		return $data;

	}

	function get_entregas(){

	}

}