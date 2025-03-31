<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model {
  
  public function __construct() {
    parent::__construct();
  }

  function get_material($q){

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
         $where="select m.id_material, m.descripcion, m.unidad, m.costo_ult, m.barcode from ".BBDD_ODBC_SQLSRV."material m".$where.";";

      $query = $this->db->query($where);

      $i=0;

      if($query->num_rows() > 0){
        foreach ($query->result_array() as $row){
          //$new_row['label']=$row['descripcion'];  //build an array
          $new_row['value']=utf8_encode($row['id_material']);
          $new_row['label']=utf8_encode($row['descripcion']);
          $new_row['unidad']=utf8_encode($row['unidad']);
          $new_row['costo_ult']=utf8_encode($row['costo_ult']);
          $new_row['barcode']=utf8_encode($row['barcode']);
          $row_set[] = $new_row; //build an array
          $i++;
          if($i>31){
            break;
          }
        }
        return $row_set; //format the array into json data
      }
      return $where;//." largo: ".$long_str."<br>".$q;
      
    }

    function get_cod_material($q){
    
      $where="select m.id_clase, c.clase, m.id_sub_clase, s.sub_clase, m.id_material, m.descripcion, m.unidad, m.costo_ult, m.barcode from ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."clase c, ".BBDD_ODBC_SQLSRV."sub_clase s where m.id_clase=c.id_clase and m.id_sub_clase=s.id_sub_clase and m.id_material = ".$q;

      $query = $this->db->query($where);

      if($query->num_rows() > 0){
        foreach ($query->result_array() as $row){
          //$new_row['label']=$row['descripcion']; //build an array
          $new_row['id_clase']=utf8_encode($row['id_clase']);
          $new_row['clase']=utf8_encode($row['clase']);
          $new_row['id_sub_clase']=utf8_encode($row['id_sub_clase']);
          $new_row['sub_clase']=utf8_encode($row['sub_clase']);
          $new_row['value']=utf8_encode($row['id_material']);
          $new_row['label']=utf8_encode($row['descripcion']);
          $new_row['unidad']=utf8_encode($row['unidad']);
          $new_row['costo_ult']=utf8_encode($row['costo_ult']);
        //  $new_row['EAN']=utf8_encode($row['EAN']);
          $new_row['barcode']=utf8_encode($row['barcode']);
          $row_set[] = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }
    }
    function get_barcode_material($q){
    
    $where="select m.id_material, m.descripcion, m.unidad, m.costo_ult, m.barcode from ".BBDD_ODBC_SQLSRV."[material] m where m.barcode = '".$q."'";

    $query = $this->db->query($where);

    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['value']=utf8_encode($row['id_material']);
        $new_row['label']=utf8_encode($row['descripcion']);
        $new_row['unidad']=utf8_encode($row['unidad']);
        $new_row['costo_ult']=utf8_encode($row['costo_ult']);
        $new_row['barcode']=utf8_encode($row['barcode']);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }
   // return $where;//." largo: ".$long_str."<br>".$q;
    
  }

  function get_clase($q){
    $long_str=strlen($q);
      $q=$this->control->limpia_str($q);
      $a = preg_split("/[\s\%]+/", $q);

      $where="";

      foreach ($a as $v) {
        if($v!=NULL){
          if($where==""){
            $where=" where clase like '%".$v."%'";
          }else{
            $where=$where." and clase like '%".$v."%'";
          }
        }
      }

     $where="select m.id_clase, m.clase from ".BBDD_ODBC_SQLSRV."clase m".$where.";";

      $query = $this->db->query($where);

      if($query->num_rows() > 0){
        foreach ($query->result_array() as $row){
          //$new_row['label']=$row['descripcion']; //build an array
          $new_row['value']=utf8_encode($row['id_clase']);
          $new_row['label']=utf8_encode($row['clase']);
          $row_set[] = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }
      return "";//." largo: ".$long_str."<br>".$q;
  }

  function get_sub_clase($data){

    $q=$data['sub_clase'];
    $x=$data['id_clase'];


    $long_str=strlen($q);
      $q=$this->control->limpia_str($q);
      $a = preg_split("/[\s\%]+/", $q);

      $where="";

      foreach ($a as $v) {
        if($v!=NULL){
          if($where==""){
            $where=" where sub_clase like '%".$v."%' and id_clase=".$x;
          }else{
            $where=$where." and sub_clase like '%".$v."%' and id_clase=".$x;
          }
        }else{
          $where=" where id_clase=".$x;
        }
      }

     $where="select m.id_sub_clase, m.sub_clase from ".BBDD_ODBC_SQLSRV."sub_clase m".$where.";";

      $query = $this->db->query($where);

      if($query->num_rows() > 0){
        foreach ($query->result_array() as $row){
          $new_row['value']=utf8_encode($row['id_sub_clase']);
          $new_row['label']=utf8_encode($row['sub_clase']);
          $row_set[] = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }
      return "";//." largo: ".$long_str."<br>".$q;
  }

  function get_unidad($q){

      $long_str=strlen($q);
      $q=$this->control->limpia_str($q);
      $a = preg_split("/[\s\%]+/", $q);

      $where="";

      foreach ($a as $v) {
        if($v!=NULL){
          if($where==""){
            $where=" where d.unidad like '%".$v."%'";
          }else{
            $where=$where." and d.unidad like '%".$v."%'";
          }
        }
      }
         $where="select d.unidad, d.descripcion
          from ".BBDD_ODBC_SQLSRV."unidades d".$where.";";

      $query = $this->db->query($where);

      if($query->num_rows() > 0){
        foreach ($query->result_array() as $row){
          //$new_row['label']=$row['descripcion']; //build an array
          $new_row['value']=utf8_encode($row['unidad']);
          $new_row['label']="[".utf8_encode($row['unidad'])."] ".utf8_encode($row['descripcion']);
          $row_set[] = $new_row; //build an array
        }
        return $row_set; //format the array into json data
      }
      return $where;//." largo: ".$long_str."<br>".$q;

  }

  function alta_catalogo($usr,$data){

    $this->db->trans_start();

    $id_clase=$data['id_clase'];
    $id_sub_clase=$data['id_sub_clase'];
    $descripcion=$data['descripcion'];
    $barcode=$data['barcode'];
    $unidad=$data['unidad'];

    $sql="insert into ".BBDD_ODBC_SQLSRV."material (descripcion, barcode, id_clase, id_sub_clase, unidad, costo_pp, costo_ult, mod_usuario, ult_mod) values ('".$descripcion."','".$barcode."',".$id_clase.",".$id_sub_clase.",'".$unidad."', 0, 0, '".$usr."', getdate())";
    $query = $this->db->query($sql);
    $id_material=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

    if ($this->db->trans_status() === FALSE){
      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      $id_material="ERROR";
      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
    }else{
      $msg="ok";  
    }
    $this->db->trans_complete();
    unset($data);
    $data['id_material']=$id_material;
    $data['msg']=$msg;
    return $data;

  }
  
  function editar_catalogo($usr,$data){

    $this->db->trans_start();

    $id_material=$data['id_material'];
    $id_clase=$data['id_clase'];
    $id_sub_clase=$data['id_sub_clase'];
    $descripcion=utf8_decode($data['descripcion']);
    $barcode=$data['barcode'];
    $unidad=$data['unidad'];

    /*$sql="insert into ".BBDD_ODBC_SQLSRV."material (descripcion, barcode, id_clase, id_sub_clase, unidad, costo_pp, costo_ult, mod_usuario, ult_mod) values ('".$descripcion."','".$barcode."',".$id_clase.",".$id_sub_clase.",'".$unidad."', 0, 0, '".$usr."', getdate())";*/

    $sql="update ".BBDD_ODBC_SQLSRV."material set descripcion='".$descripcion."', barcode='".$barcode."', id_clase=".$id_clase.", id_sub_clase=".$id_sub_clase.", unidad='".$unidad."', mod_usuario='".$usr."', ult_mod=getdate() where id_material=".$id_material.";";

    $query = $this->db->query($sql);
    $id_material=$this->db->query('select @@ROWCOUNT as rows_affected;')->row('rows_affected');

    if ($this->db->trans_status() === FALSE){
      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      $id_material="ERROR";
      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
    }else{
      $msg="ok";  
    }
    $this->db->trans_complete();
    unset($data);
    $data['id_material']=$id_material;
    $data['msg']=$msg;
    return $data;

  }


  function get_catalogo($data){
    
    $id_clase=$data['id_clase'];
    $id_sub_clase=$data['id_sub_clase'];
    $id_material=$data['id_material'];
    $descripcion=$data['descripcion'];
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

    $Sql="select m.id_material, m.id_clase, c.clase, m.id_sub_clase, s.sub_clase, m.descripcion, m.unidad, m.barcode, m.costo_pp, m.costo_ult from ".BBDD_ODBC_SQLSRV."material m, ".BBDD_ODBC_SQLSRV."clase c, ".BBDD_ODBC_SQLSRV."sub_clase s where m.id_clase=c.id_clase and m.id_sub_clase=s.id_sub_clase;";

    $Sql="select m.id_material, m.id_clase, c.clase, m.id_sub_clase, s.sub_clase, m.descripcion, m.unidad, m.barcode, m.costo_pp, m.costo_ult from ".BBDD_ODBC_SQLSRV."material m inner join ".BBDD_ODBC_SQLSRV."clase c on m.id_clase=c.id_clase inner join ".BBDD_ODBC_SQLSRV."sub_clase s on m.id_sub_clase=s.id_sub_clase".$where.";";

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
        //$new_row['sql']=utf8_encode($Sql);
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }


  }

  function edit_costo_mat($data,$usr){

    $id_material=$data['id_material'];
    $barcode=$data['barcode'];
    $costo_pp=$data['costo_pp'];
    $costo_ult=$data['costo_ult'];
    //-----------------------------------------
    $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."material_costo (id_material ,costo_ult,mod_usuario,ult_mod) VALUES (".$id_material.",".$costo_ult.",'".$usr."',getdate());";
    $query = $this->db->query($Sql);
    //-----------------------------------------
    $Sql="update ".BBDD_ODBC_SQLSRV."material set barcode='".$barcode."', costo_ult=".$costo_ult.", mod_usuario='".$usr."', ult_mod=getdate() where id_material=".$id_material.";";
    $query = $this->db->query($Sql);

  }

}