<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	function get_usuario($usr){
		
		$Sql="select * from ".BBDD_ODBC_SQLSRV."usuarios where usuario='".$usr."' and bloqueado=0 and activo=1;";
		$query = $this->db->query($Sql);
		if($query->num_rows() > 0){

        foreach ($query->result_array() as $row){
			$new_row['usuario']=utf8_encode($row['usuario']);
			$new_row['id_sector']=utf8_encode($row['id_sector']);
			$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
			$new_row['id_perfil']=utf8_encode($row['id_perfil']);
			$new_row['clave']=utf8_encode($row['clave']);
			$new_row['session']=utf8_encode($row['session']);
			$new_row['conectado']=utf8_encode($row['conectado']);
			$new_row['bloqueado']=utf8_encode($row['bloqueado']);
			$new_row['error_login']=utf8_encode($row['error_login']);
			$new_row['direccion_ip']=utf8_encode($row['direccion_ip']);
			$new_row['inicio_session']=utf8_encode($row['inicio_session']);
			$new_row['session_time']=utf8_encode($row['session_time']);
			$new_row['activo']=utf8_encode($row['activo']);
			$new_row['id_personal']=utf8_encode($row['id_personal']);
          	$row_set = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }


	}

	function get_admin($usr,$psw){
		
		$psw=md5($psw);
		
		$Sql="select * from ".BBDD_ODBC_SQLSRV."usuarios where usuario='".$usr."' and clave='".$psw."' and bloqueado=0 and activo=1;";
		$query = $this->db->query($Sql);
		if($query->num_rows() > 0){

        foreach ($query->result_array() as $row){
			$new_row['usuario']=utf8_encode($row['usuario']);
			$new_row['id_sector']=utf8_encode($row['id_sector']);
			$new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
			$new_row['id_perfil']=utf8_encode($row['id_perfil']);
			$new_row['clave']=utf8_encode($row['clave']);
			$new_row['session']=utf8_encode($row['session']);
			$new_row['conectado']=utf8_encode($row['conectado']);
			$new_row['bloqueado']=utf8_encode($row['bloqueado']);
			$new_row['error_login']=utf8_encode($row['error_login']);
			$new_row['direccion_ip']=utf8_encode($row['direccion_ip']);
			$new_row['inicio_session']=utf8_encode($row['inicio_session']);
			$new_row['session_time']=utf8_encode($row['session_time']);
			$new_row['activo']=utf8_encode($row['activo']);
			$new_row['id_personal']=utf8_encode($row['id_personal']);
          	$row_set = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }


	}


	function set_session($data_usr){

		$usr=$data_usr['usuario'];
		$inicio_session=$data_usr['inicio_session'];
		$direccion_ip=$data_usr['direccion_ip'];
		$fin_session=$data_usr['session_time'];
		
		$fecha=date('Y-m-d H:i:s',$inicio_session);
		$memo_log="usuario:;".$usr.";ip:;".$direccion_ip.";inicio_session=;".$inicio_session.";fin_session:;".$fin_session;

 		$this->db->trans_start();

		$Sql="insert into ".BBDD_ODBC_SQLSRV."error_log(fecha,tipo_log,memo_log) values('".$fecha."','SESSION','".$memo_log."')";

 		$query = $this->db->query($Sql);


		$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set session='".$this->session->userdata('session_id')."' ,inicio_session=".time().", session_time=".time().", error_login=0, conectado=1, direccion_ip='".$_SERVER['REMOTE_ADDR']."' where usuario='".$usr."';";
		//$this->db->trans_start();
		$query = $this->db->query($Sql);

		if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }
	    $this->db->trans_complete();
	    return $msg;

	}


	function trace_user($usr){

		$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set session_time=".time().", conectado=1  where usuario='".$usr."';";
		$this->db->trans_start();
		$query = $this->db->query($Sql);
		
		$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set conectado=0  where (session_time+".TIME_OUT.")<".time().";";

		$query = $this->db->query($Sql);
		
		if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }
	    $this->db->trans_complete();
	    return $msg;
	}

	function end_session($usr){
		$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set session_time=".time().", conectado=0  where usuario='".$usr."';";
		$this->db->trans_start();
		$query = $this->db->query($Sql);

		$Sql="update ".BBDD_ODBC_SQLSRV."usuarios set conectado=0  where (session_time+".TIME_OUT.")>".time().";";

		$query = $this->db->query($Sql);
		
		if ($this->db->trans_status() === FALSE){
	      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
	      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
	    }else{
	      $msg="ok";  
	    }
	    $this->db->trans_complete();
	    return $msg;
	}

}