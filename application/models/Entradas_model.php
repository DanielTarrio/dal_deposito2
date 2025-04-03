<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entradas_model extends CI_Model {

/*	function add_entrada($usr,$data,$grilla){

  	$id_deposito=$data['id_deposito'];
    $id_tipo_mov=$data['id_tipo_mov'];
    $id_proveedor=$data['id_proveedor'];
    $id_tipo_compra=$data['id_tipo_compra'];
    $id_compra=$data['id_compra'];
    $remito=$data['remito'];
    $centro_costo=$data['centro_costo'];
    $fecha=$this->control->ParseDMYtoYMD($data['fecha'],'/');
   
    $nro=0;
    
    //$this->db->db_debug=FALSE;

    $this->db->trans_start();


    $nro=$this->db->query("select valor from ".BBDD_ODBC_SQLSRV."seed where tabla='entrada' and columna='id_deposito' and id='".$id_deposito."';")->row('valor');
    

    $nro=ceil($nro)+1;

   // $Sql=sprintf("ppupdate %sseed set valor=%s where tabla='entrada' and columna='id_deposito' and id=%s",BBDD_ODBC_SQLSRV,$nro,$id_deposito);

    
    $Sql="update ".BBDD_ODBC_SQLSRV."seed set valor=".$nro." where tabla='entrada' and columna='id_deposito' and id=".$id_deposito.";";

    $query = $this->db->query($Sql);

    $Sql="insert into ".BBDD_ODBC_SQLSRV."entrada (id_tipo_mov, nro, fecha, remito, centro_costo, id_proveedor, id_tipo_compra, id_compra, id_deposito, mod_usuario, ult_mod) values ('".$id_tipo_mov."','".$nro."','".$fecha."','".$remito."','".$centro_costo."','".$id_proveedor."','".$id_tipo_compra."','".$id_compra."','".$id_deposito."','".$usr."',Now());";

    $query = $this->db->query($Sql);

    //$id_entrada=$this->db->insert_id();
    

    //===============No funciona insert_id() con ODBC====================
    $id_entrada=$this->db->query('select @@IDENTITY as insert_id;')->row('insert_id');

   // $id_entrada=$this->db->query('select SCOPE_IDENTITY() as insert_id;')->row('insert_id');

    //$id_entrada = $query->row('insert_id');
/*
    Database driver (sqlsrv_driver.php)
    function insert_id()
    {

        return $this->query('select @@IDENTITY as insert_id')->row('insert_id');
    }
*/
 /*   foreach ($grilla as $key => $value) {
      $id_material=$value['id_material'];
      $cantidad=$value['cantidad'];

      $Sql="insert into ".BBDD_ODBC_SQLSRV."detalle_entrada (id_entrada, nro, id_deposito, centro_costo, id_material, cantidad, mod_usuario, ult_mod) values ('".$id_entrada."','".$nro."','".$id_deposito."','". $centro_costo."','".$id_material."','".$cantidad."','$usr', Now());";
      $query = $this->db->query($Sql);
      $Sql="select id_material from ".BBDD_ODBC_SQLSRV."stock where id_deposito=".$id_deposito." and id_material=".$id_material.";";
      $query = $this->db->query($Sql);
      if($query->num_rows() > 0){
        $Sql="update ".BBDD_ODBC_SQLSRV."stock set cantidad=cantidad+".$cantidad.", mod_usuario='".$usr."', ult_mod=Now() where id_deposito=".$id_deposito." and id_material=".$id_material.";";
        $query = $this->db->query($Sql);
      }else{
        $Sql="insert into ".BBDD_ODBC_SQLSRV."stock(id_material, id_deposito, cantidad, minimo, reposicion, ubicacion, mod_usuario, ult_mod) values(".$id_material.",".$id_deposito.",".$cantidad.",0,0,NULL,'".$usr."', Now());";
        $query = $this->db->query($Sql);
      }
    }
    $Sql="insert into ".BBDD_ODBC_SQLSRV."movimientos(id_entrada, id_detalle_entrada, nro, id_deposito, centro_costo, id_material, cantidad, mod_usuario, ult_mod, id_tipo_mov) select e.id_entrada, d.id_detalle_entrada, e.nro, e.id_deposito, e.centro_costo, d.id_material, d.cantidad, e.mod_usuario, Now(), e.id_tipo_mov from ".BBDD_ODBC_SQLSRV."entrada e, ".BBDD_ODBC_SQLSRV."detalle_entrada d where e.id_entrada=d.id_entrada and e.id_deposito=".$id_deposito." and e.id_entrada=".$id_entrada.";";



    $query = $this->db->query($Sql);

    if ($this->db->trans_status() === FALSE){
      log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      $nro="ERROR";
      $msg='error# '.$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql;
    }else{
      $msg="ok";  
    }

    $this->db->trans_complete();

    unset($data);
    $data['nro']=$nro;
    $data['msg']=$msg;
    $data['x']=$Sql;

    return $data;
  }
*/
  
  function buscar_remito($id_proveedor,$remito){

    $Sql="select * from ".BBDD_ODBC_SQLSRV."entrada where id_proveedor=".$id_proveedor." and remito='".$remito."';";
    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['nro']=utf8_encode($row['nro']);
        $new_row['remito']=utf8_encode($row['remito']);
        $new_row['fecha']=utf8_encode($row['fecha']);
        $new_row['ult_mod']=utf8_encode($row['ult_mod']);
        $row_set[] = $new_row;
      }
    }else{
      $row_set=$query->num_rows();
    }
    return $row_set;
  }

  function remito_material($id_proveedor,$remito){

    $Sql="select e.id_entrada, z.tipo,e.nro, x.deposito, t.compra, e.id_compra, e.remito, e.fecha, e.id_proveedor, p.proveedor, d.id_material, m.descripcion, m.unidad, d.cantidad, e.mod_usuario, e.ult_mod  from ".BBDD_ODBC_SQLSRV."entrada e  inner join ".BBDD_ODBC_SQLSRV."proveedores p on e.id_proveedor=p.id_proveedor  inner join ".BBDD_ODBC_SQLSRV."detalle_entrada d on e.id_entrada=d.id_entrada  inner join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material inner join ".BBDD_ODBC_SQLSRV."deposito x on e.id_deposito=x.id_deposito inner join ".BBDD_ODBC_SQLSRV."tipo_compra t on e.id_tipo_compra=t.id_tipo_compra inner join ".BBDD_ODBC_SQLSRV."tipo_movimiento z on e.id_tipo_mov=z.id_tipo_mov where e.id_proveedor=".$id_proveedor." and e.remito='".$remito."';";

    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['id_entrada']=utf8_encode($row['id_entrada']);
        $new_row['tipo']=utf8_encode($row['tipo']);
        $new_row['nro']=utf8_encode($row['nro']);
        $new_row['compra']=utf8_encode($row['compra']);
        $new_row['deposito']=utf8_encode($row['deposito']);
        $new_row['id_compra']=utf8_encode($row['id_compra']);
        $new_row['remito']=utf8_encode($row['remito']);
        $new_row['fecha']=$this->control->ParseDMYtoYMD($row['fecha'],'/');
        $new_row['proveedor']=utf8_encode($row['proveedor']);
        $new_row['id_material']=utf8_encode($row['id_material']);
        $new_row['descripcion']=utf8_encode($row['descripcion']);
        $new_row['unidad']=utf8_encode($row['unidad']);
        $new_row['cantidad']=utf8_encode($row['cantidad']);
        $new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
        $new_row['ult_mod']=$this->control->ParseDMYtoYMD($row['ult_mod'],'/');
        $row_set[] = $new_row;
      }
    }else{
      $row_set=$query->num_rows();
    }
    return $row_set;

  }

  function print_entrada($nro,$id_deposito){

    $Sql="select e.id_entrada, z.tipo,e.nro, x.deposito, t.compra, e.id_compra, e.remito, e.fecha, e.id_proveedor, p.proveedor, d.id_material, m.descripcion, m.unidad, d.cantidad, e.mod_usuario, e.ult_mod  from ".BBDD_ODBC_SQLSRV."entrada e  inner join ".BBDD_ODBC_SQLSRV."proveedores p on e.id_proveedor=p.id_proveedor  inner join ".BBDD_ODBC_SQLSRV."detalle_entrada d on e.id_entrada=d.id_entrada  inner join ".BBDD_ODBC_SQLSRV."material m on d.id_material=m.id_material inner join ".BBDD_ODBC_SQLSRV."deposito x on e.id_deposito=x.id_deposito inner join ".BBDD_ODBC_SQLSRV."tipo_compra t on e.id_tipo_compra=t.id_tipo_compra inner join ".BBDD_ODBC_SQLSRV."tipo_movimiento z on e.id_tipo_mov=z.id_tipo_mov where e.nro=".$nro." and e.id_deposito=".$id_deposito.";";

    $query = $this->db->query($Sql);
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['id_entrada']=utf8_encode($row['id_entrada']);
        $new_row['tipo']=utf8_encode($row['tipo']);
        $new_row['nro']=utf8_encode($row['nro']);
        $new_row['compra']=utf8_encode($row['compra']);
        $new_row['deposito']=utf8_encode($row['deposito']);
        $new_row['id_compra']=utf8_encode($row['id_compra']);
        $new_row['remito']=utf8_encode($row['remito']);
        $new_row['fecha']=$this->control->ParseDMYtoYMD($row['fecha'],'/');
        $new_row['proveedor']=utf8_encode($row['proveedor']);
        $new_row['id_material']=utf8_encode($row['id_material']);
        $new_row['descripcion']=utf8_encode($row['descripcion']);
        $new_row['unidad']=utf8_encode($row['unidad']);
        $new_row['cantidad']=utf8_encode($row['cantidad']);
        $new_row['mod_usuario']=utf8_encode($row['mod_usuario']);
        $new_row['ult_mod']=$this->control->ParseDMYtoYMD($row['ult_mod'],'/');
        $row_set[] = $new_row;
      }
    }else{
      $row_set=$query->num_rows();
    }
    return $row_set;

  }



}