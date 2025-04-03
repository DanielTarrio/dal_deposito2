<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicacion_model extends CI_Model {

	function get_ubicacion($q){

/*
    //$this->load->library('Control');
    $long_str=strlen($q);
    $q=$this->control->limpia_str($q);
    $a = preg_split("/[\s\%]+/", $q);
    $where="";

    foreach ($a as $v) {
      if($v!=NULL){
        if($where==""){
          $where=" where descripcion like '%".$v."%'";
        }else{
          $where=$where." and descripcion like '%".$v."%'";
        }
      }
    }
    */
    $where=" where m.id_deposito=".$q." order by m.lft";

       $where="select * from ".BBDD_ODBC_SQLSRV."ubicaciones m".$where.";";

    $query = $this->db->query($where);

   

    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['id_ubicacion']=utf8_encode($row['id_ubicacion']);
        $new_row['name']=utf8_encode($row['ubicacion']);
        $new_row['lft']=utf8_encode($row['lft']);
        $new_row['rgt']=utf8_encode($row['rgt']);
        $new_row['level']=utf8_encode($row['nivel']);
        $new_row['id_deposito']=utf8_encode($row['id_deposito']);
        $new_row['ruta']=utf8_encode($row['ruta']);
        $row_set[] = $new_row; //build an array
      }
      
      return $row_set; //format the array into json data
    }
    return $where;//." largo: ".$long_str."<br>".$q;
   //{"id_lugar":"2","name":"Planta San Martín ","lft":"2","rgt":"7","level":"1","uiicon":""}, 
  }

  function get_ub_stock($q,$m){

    $where=" and s.id_deposito=".$q." and s.id_material=".$m.";";

    $where="select s.id_ubicacion, u.ruta from ".BBDD_ODBC_SQLSRV."stock s, ".BBDD_ODBC_SQLSRV."ubicaciones u where s.id_ubicacion=u.id_ubicacion".$where;

       
    $query = $this->db->query($where);

    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['value']=utf8_encode($row['id_ubicacion']);
        $new_row['label']=utf8_encode($row['ruta']);
        $row_set[] = $new_row; //build an array
      }
      
      return $row_set; //format the array into json data
    }
    return $where;//." largo: ".$long_str."<br>".$q;
   //{"id_lugar":"2","name":"Planta San Martín ","lft":"2","rgt":"7","level":"1","uiicon":""}, 
   }



  function add_ubicacion($data,$usr){

    $tmp=$data['id_ubicacion'];

    $Sql="SELECT lft, nivel, id_deposito, ruta FROM ".BBDD_ODBC_SQLSRV." ubicaciones WHERE id_ubicacion =?;";

    $query=$this->db->query($Sql,array($tmp));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft;
          $nivel=$row->nivel;
          $id_deposito=$row->id_deposito;
          $ruta=$row->ruta;
        }
      }

      $tmp=$data['ubicacion'];

      $this->db->trans_start();

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET rgt = rgt + 2 WHERE rgt > ? and id_deposito=?;";

      $query=$this->db->query($Sql,array($lft,$id_deposito));

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET lft = lft + 2 WHERE lft > ? and id_deposito=?;";

      $query=$this->db->query($Sql,array($lft,$id_deposito));


      $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."ubicaciones(ubicacion, id_deposito, lft, rgt, nivel, ruta, ult_mod, mod_usuario) VALUES('".$tmp."', ".$id_deposito.", ".$lft." + 1, ".$lft." + 2, ".$nivel." + 1,'".$ruta."/".$tmp."', Now(),'".$usr."');";

      $query=$this->db->query($Sql);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE){
        log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      }

      /*
      SELECT @myLeft = lft FROM deposito.dbo.lugares
      WHERE lugar = 'AySA';

      UPDATE deposito.dbo.lugares SET rgt = rgt + 2 WHERE rgt > @myLeft;
      UPDATE deposito.dbo.lugares SET lft = lft + 2 WHERE lft > @myLeft;

      INSERT INTO deposito.dbo.lugares(lugar, lft, rgt) VALUES('Estaciones Elevadoras de Agua', @myLeft + 1, @myLeft + 2);
      */

  }

  function del_ubicacion($data){

    $tmp=$data['id_ubicacion'];
    $id_deposito=$data['id_deposito'];


    $Sql="SELECT lft, rgt FROM ".BBDD_ODBC_SQLSRV." ubicaciones WHERE id_ubicacion =?;";

    $query=$this->db->query($Sql,array($tmp));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft; 
          $rgt=$row->rgt; 
          $lng=$rgt - $lft + 1;
        }
      }


      $this->db->trans_start();

      $Sql="DELETE ".BBDD_ODBC_SQLSRV."ubicaciones WHERE lft BETWEEN ".$lft." AND ".$rgt." and id_deposito=".$id_deposito.";";

      $query=$this->db->query($Sql);
      
      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET rgt = rgt-".$lng." WHERE rgt > ".$rgt." and id_deposito=".$id_deposito.";";

      $query=$this->db->query($Sql);

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET lft = lft-".$lng." WHERE lft > ".$rgt." and id_deposito=".$id_deposito.";";

      $query=$this->db->query($Sql);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE){
               log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      }

  }

  function edit_ubicacion($data){

    $id_ubicacion=$data['id_ubicacion'];
    $ubicacion=$data['ubicacion'];
    //$id_deposito=$data['id_deposito'];

     $this->db->trans_start();

    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET ubicacion = '".$ubicacion."' WHERE id_ubicacion = ?;";
    $query=$this->db->query($Sql,array($id_ubicacion));

    $this->db->trans_complete();

   


    $Sql="SELECT lft, nivel, id_deposito, ruta FROM ".BBDD_ODBC_SQLSRV." ubicaciones WHERE id_ubicacion =?;";

    $query=$this->db->query($Sql,array($id_ubicacion));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft;
          $nivel=$row->nivel;
          $id_deposito=$row->id_deposito;
          $ruta=$row->ruta;
        }
      }

     $Sql="SELECT parent.ubicacion FROM ".BBDD_ODBC_SQLSRV."ubicaciones AS node, ".BBDD_ODBC_SQLSRV."ubicaciones AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.id_ubicacion = ".$id_ubicacion." and parent.id_deposito=".$id_deposito." ORDER BY node.lft;";

    $query=$this->db->query($Sql,array($id_ubicacion));
    $NewRuta='';

    if($query->num_rows() > 0 ){
      foreach ($query->result() as $row)
      {    
        if($NewRuta==""){
          $NewRuta.=$row->ubicacion;
        }else{
          $NewRuta.='/'.$row->ubicacion;
        }
        
      }
    }


    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."ubicaciones SET ruta = '".$NewRuta."' WHERE id_ubicacion = ?;";
    
    $query=$this->db->query($Sql,array($id_ubicacion));



  }


}