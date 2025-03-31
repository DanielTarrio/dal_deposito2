<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposito_model extends CI_Model {
	
  function get_deposito($usr){
    $where="select a.id_deposito, d.deposito
		from ".BBDD_ODBC_SQLSRV."deposito_autorizado a, ".BBDD_ODBC_SQLSRV."deposito d
		where a.id_deposito=d.id_deposito
		and usuario='".$usr."';";
    $query = $this->db->query($where);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['value']=utf8_encode($row['id_deposito']);
        $new_row['label']=utf8_encode(strtoupper($row['deposito']));
        //$new_row['ub_default']=utf8_encode($row['id_ubicacion']);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }
    return "";//." largo: ".$long_str."<br>".$q;
  }

  function lista_deposito(){

    $where="select d.id_deposito, d.deposito
    from ".BBDD_ODBC_SQLSRV."deposito d;";
    $query = $this->db->query($where);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['id_deposito']=utf8_encode($row['id_deposito']);
        $new_row['deposito']=utf8_encode($row['deposito']);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }
    return "";//." largo: ".$long_str."<br>".$q;

  }

  function add_deposito($data){

    $this->db->trans_start();

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."deposito (deposito, mod_usuario, ult_mod) VALUES ('".$data['deposito']."', '".$data['usr']."', getdate());";
    $query = $this->db->query($Sql);

    $id_deposito=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."seed (tabla, columna, id, valor) VALUES ('entrada', 'id_deposito', ".$id_deposito.",0);";
    $query = $this->db->query($Sql);

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."seed (tabla, columna, id, valor) VALUES ('salida', 'id_deposito', ".$id_deposito.",0);";
    $query = $this->db->query($Sql);
    //-----------------Autorizacion Deposito--------------------

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."deposito_autorizado (id_deposito, usuario, mod_usuario, ult_mod) VALUES (".$id_deposito.",'admin','".$data['usr']."',getdate());";
    $query = $this->db->query($Sql);
    if($data['usr']!='admin'){
      $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."deposito_autorizado (id_deposito, usuario, mod_usuario, ult_mod) VALUES (".$id_deposito.",'".$data['usr']."','".$data['usr']."',getdate());";
      $query = $this->db->query($Sql);
    }
    //----------------Generacion de ubicacion deposito-------------

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."ubicaciones (ubicacion, id_deposito,ruta, lft, rgt, nivel, mod_usuario, ult_mod) VALUES ('Deposito',".$id_deposito.",'".$data['deposito']."',1,2,1,'".$data['usr']."',getdate());";
    $query = $this->db->query($Sql);

    $filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');
    //return $filas;

    if ($this->db->trans_status() === FALSE){
      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      $nro="ERROR";
      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
    }else{
      $msg=$filas;  
    }

    $this->db->trans_complete();



  }

  function edit_deposito($data){

    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."deposito SET deposito='".$data['deposito']."', mod_usuario='".$data['usr']."', ult_mod=getdate() where id_deposito=".$data['id_deposito'].";";
    $query = $this->db->query($Sql);

    $filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');
    return $filas;

  }

  function get_dep_autorizado($data){

    $id_deposito=$data['id_deposito'];

    $Sql="select a.id_dep_aut,a.id_deposito, d.deposito, a.usuario, u.apellido_nombre from ".BBDD_ODBC_SQLSRV."deposito_autorizado a inner join ".BBDD_ODBC_SQLSRV."usuarios u on a.usuario=u.usuario inner join ".BBDD_ODBC_SQLSRV."deposito d on a.id_deposito=d.id_deposito where a.id_deposito=".$id_deposito.";";
    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['id_dep_aut']=$row['id_dep_aut'];
        $new_row['id_deposito']=$row['id_deposito'];
        $new_row['deposito']=$row['deposito'];
        $new_row['usuario']=$row['usuario'];
        $new_row['apellido_nombre']=utf8_encode($row['apellido_nombre']);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }
    return "";//." largo: ".$long_str."<br>".$q;

  }
  function add_aut_dep($data){

    $id_deposito=$data['id_deposito'];
    $usuario=$data['usuario'];
    $usr=$data['usr'];

    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."deposito_autorizado (id_deposito,usuario,mod_usuario     ,ult_mod) VALUES (".$id_deposito.",'".$usuario."','".$usr."',getdate());";

    $query = $this->db->query($Sql);

    $id_dep_aut=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');
    $filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');

    $data = array(
        'id' => $id_dep_aut,
        'filas'=> $filas
      );

    return $data;

  }

  function del_usr_exec($data){

    $id_dep_aut=$data['id_dep_aut'];

    $Sql="DELETE FROM ".BBDD_ODBC_SQLSRV."deposito_autorizado where id_dep_aut=".$id_dep_aut." and usuario<>'admin';";

    $query = $this->db->query($Sql);

    $filas=$this->db->affected_rows();

    //$id_dep_aut=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');
    //$filas=$this->db->query('select @@ROWCOUNT as filas_afectadas;')->row('filas_afectadas');

    $data = array(
        //'id' => $id_dep_aut,
        'filas'=> $filas
    );
    return $data;
  }

}