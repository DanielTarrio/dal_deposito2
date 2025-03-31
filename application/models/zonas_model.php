<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zonas_model extends CI_Model {
  
  public function __construct() {
    parent::__construct();
  }

  function lista_zonas(){


	$Sql="select id_zona,id_dependencia,Zona,Localidad,Direccion,CP,Telefono,Responsable,email,activa from ".BBDD_ODBC_SQLSRV."zonas d;";

	$Sql="select z.id_zona,z.id_dependencia,d.dependencia,z.Zona,z.Localidad,z.Direccion,z.CP,z.Telefono,z.Responsable,z.email,z.activa from ".BBDD_ODBC_SQLSRV."zonas z left join ".BBDD_ODBC_SQLSRV."dependencia d on z.id_dependencia=d.id_dependencia order by z.id_dependencia;";
   
    $query = $this->db->query($Sql);
    //$i=0;
    if($query->num_rows() > 0){
	    foreach ($query->result_array() as $row){
	      //$new_row['label']=$row['descripcion']; //build an array
	    	$new_row['id_zona']=utf8_encode($row['id_zona']);
			$new_row['id_dependencia']=utf8_encode($row['id_dependencia']);
			$new_row['dependencia']=utf8_encode($row['dependencia']);
			$new_row['Zona']=utf8_encode($row['Zona']);
			$new_row['Localidad']=utf8_encode($row['Localidad']);
			$new_row['Direccion']=utf8_encode($row['Direccion']);
			$new_row['CP']=utf8_encode($row['CP']);
			if($row['activa']==1){
				$new_row['activa']="Si";
			}else{
				$new_row['activa']="No";
			}
			$row_set[] = $new_row; //build an array
		/*	$i++;
	    	if($i>31){
	    		break;
	    	}*/
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
					$where=" where m.zona like '%".$v."%'";
				}else{
					$where=$where." and m.zona like '%".$v."%'";
				}
			}
		}
	     
		$where="select * from ".BBDD_ODBC_SQLSRV."zonas m ".$where." and m.activa=1;";

		$query = $this->db->query($where);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		      //$new_row['label']=$row['descripcion']; //build an array
		    	$new_row['value']=utf8_encode($row['id_zona']);
				$new_row['label']=utf8_encode($row['Zona']);
				$new_row['Localidad']=utf8_encode($row['Localidad']);
				$new_row['Direccion']=utf8_encode($row['Direccion']);
				$new_row['activa']=utf8_encode($row['activa']);
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

	function get_cod_dependencia($data){

	  	
	     
		//$where="select * from ".BBDD_ODBC_SQLSRV."zonas m where m.id_zona=".$data.";";

		$Sql="select z.id_zona,z.id_dependencia,d.dependencia,z.Zona,z.Localidad,z.Direccion,z.CP,z.Telefono,z.Responsable,z.email,z.activa from ".BBDD_ODBC_SQLSRV."zonas z left join ".BBDD_ODBC_SQLSRV."dependencia d on z.id_dependencia=d.id_dependencia  where z.id_zona=".$data.";";

		$query = $this->db->query($Sql);

		$i=0;

	  	if($query->num_rows() > 0){
		    foreach ($query->result_array() as $row){
		      //$new_row['label']=$row['descripcion']; //build an array
		    	$new_row['id_zona']=utf8_encode($row['id_zona']);
		    	$new_row['id_dependencia']=utf8_encode($row['id_dependencia']);
		    	$new_row['dependencia']=utf8_encode($row['dependencia']);
				$new_row['Zona']=utf8_encode($row['Zona']);
				$new_row['Localidad']=utf8_encode($row['Localidad']);
				$new_row['Direccion']=utf8_encode($row['Direccion']);
				$new_row['CP']=utf8_encode($row['CP']);
				$new_row['Telefono']=utf8_encode($row['Telefono']);
				$new_row['Responsable']=utf8_encode($row['Responsable']);
				$new_row['email']=utf8_encode($row['email']);
				$new_row['activa']=utf8_encode($row['activa']);
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

	function add_zona($data){

	    $this->db->trans_start();

	    $Zona=$data['Zona'];
	    $Localidad=$data['Localidad'];
	    $Direccion=$data['Direccion'];
	    $CP=$data['CP'];
	    $activa=$data['activa'];
	    $id_dependencia=$data['id_dependencia'];

	    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."zonas (id_dependencia,Zona,Localidad,Direccion,CP,activa)
	     VALUES
	           (".$id_dependencia.",'".$Zona."', '".$Localidad."','".$Direccion."','".$CP."',".$activa.")"; 
	    $query = $this->db->query($Sql);

	    $filas=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');


	    if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $nro="ERROR";
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }

	    $this->db->trans_complete();
	    unset($data);
	    $data['id_zona']=$filas;
	    $data['Zona']=$Zona;
	    $data['Direccion']=$Direccion;
	    $data['Localidad']=$Localidad;
	    $data['CP']=$CP;
	    $data['msg']=$msg;
	    return $data;
	}


	function edit_zona($data){

	    $this->db->trans_start();

	    $id_zona=$data['id_zona'];
	    $Zona=$data['Zona'];
	    $Localidad=$data['Localidad'];
	    $Direccion=$data['Direccion'];
	    $CP=$data['CP'];
	    $activa=$data['activa'];
	    $id_dependencia=$data['id_dependencia'];

	    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."zonas set Zona='".$Zona."', Localidad='".$Localidad."', Direccion='".$Direccion."', CP='".$CP."', activa=".$activa.", id_dependencia=".$id_dependencia." where id_zona=".$id_zona.";"; 
	    $query = $this->db->query($Sql);

	    $filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');


	    if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $nro="ERROR";
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }

	    $this->db->trans_complete();
	    unset($data);
	    $data['id_zona']=$filas;
	    $data['Zona']=$Zona;
	    $data['Direccion']=$Direccion;
	    $data['Localidad']=$Localidad;
	    $data['CP']=$CP;
	    $data['msg']=$msg;
	    return $data;
	}

}