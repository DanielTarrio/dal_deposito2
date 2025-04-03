<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sectores_model extends CI_Model {

	function get_sectores(){

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
   // $where=" where m.id_deposito=".$q." order by m.lft";

    $where="select * from ".BBDD_ODBC_SQLSRV."sectores m order by lft;";//.$where.";";

    $query = $this->db->query($where);

   

    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        //$new_row['label']=$row['descripcion']; //build an array
        $new_row['id_sector']=utf8_encode($row['id_sector']);
        $new_row['name']=utf8_encode($row['sector']);
        $new_row['lft']=utf8_encode($row['lft']);
        $new_row['rgt']=utf8_encode($row['rgt']);
        $new_row['level']=utf8_encode($row['nivel']);
        $row_set[] = $new_row; //build an array
      }
      
      return $row_set; //format the array into json data
    }
    return $where;//." largo: ".$long_str."<br>".$q;
   //{"id_lugar":"2","name":"Planta San Martín ","lft":"2","rgt":"7","level":"1","uiicon":""}, 
  }

  
  function add_sectores($data,$usr){

    $tmp=$data['id_sector'];

    $Sql="SELECT lft, nivel FROM ".BBDD_ODBC_SQLSRV." sectores WHERE id_sector =?;";

    $query=$this->db->query($Sql,array($tmp));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft;
          $nivel=$row->nivel;
        }
      }

      $tmp=$data['sector'];

      $this->db->trans_start();

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET rgt = rgt + 2 WHERE rgt > ?;";

      $query=$this->db->query($Sql,array($lft));

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET lft = lft + 2 WHERE lft > ?;";

      $query=$this->db->query($Sql,array($lft));


      $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."sectores(sector, lft, rgt, nivel, ult_mod, mod_usuario) VALUES('".$tmp."', ".$lft." + 1, ".$lft." + 2, ".$nivel." + 1, Now(),'".$usr."');";

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

  function del_sectores($data){

    $tmp=$data['id_sector'];


    $Sql="SELECT lft, rgt FROM ".BBDD_ODBC_SQLSRV."sectores WHERE id_sector =?;";

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

      $Sql="DELETE ".BBDD_ODBC_SQLSRV."sectores WHERE lft BETWEEN ".$lft." AND ".$rgt.";";

      $query=$this->db->query($Sql);
      
      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET rgt = rgt-".$lng." WHERE rgt > ".$rgt.";";

      $query=$this->db->query($Sql);

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET lft = lft-".$lng." WHERE lft > ".$rgt.";";

      $query=$this->db->query($Sql);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE){
               log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      }

  }

  function edit_sectores($data){

    $id_sector=$data['id_sector'];
    $sector=$data['sector'];
    //$id_deposito=$data['id_deposito'];

     $this->db->trans_start();

    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET sector = '".$sector."' WHERE id_sector = ?;";
    $query=$this->db->query($Sql,array($id_sector));

    $this->db->trans_complete();

   


    $Sql="SELECT lft, nivel FROM ".BBDD_ODBC_SQLSRV." sectores WHERE id_sector =?;";

    $query=$this->db->query($Sql,array($id_sector));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft;
          $nivel=$row->nivel;
        }
      }

     $Sql="SELECT parent.sector FROM ".BBDD_ODBC_SQLSRV."sectores AS node, ".BBDD_ODBC_SQLSRV."sectores AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.id_sector = ".$id_sector."  ORDER BY node.lft;";

    $query=$this->db->query($Sql,array($id_sector));
    /*$NewRuta='';

    if($query->num_rows() > 0 ){
      foreach ($query->result() as $row)
      {    
        if($NewRuta==""){
          $NewRuta.=$row->sector;
        }else{
          $NewRuta.='/'.$row->sector;
        }
        
      }
    }


    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."sectores SET ruta = '".$NewRuta."' WHERE id_sector = ?;";
    
    $query=$this->db->query($Sql,array($id_sector));*/



  }

  function sectores_dependientes($id_sector){

    $Sql="SELECT parent.id_sector, parent.sector, parent.lft, parent.rgt FROM ".BBDD_ODBC_SQLSRV."sectores AS node, ".BBDD_ODBC_SQLSRV."sectores AS parent WHERE parent.lft BETWEEN node.lft AND node.rgt AND node.id_sector='".$id_sector."'ORDER BY parent.lft;";
    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['id_sector']=utf8_encode($row['id_sector']);
        $new_row['sector']=utf8_encode(strtoupper($row['sector']));
        $row_set[] = $new_row; //build an array
      }
      return $row_set; //format the array into json data
    }else{
      return "";
    }
    

  }


}