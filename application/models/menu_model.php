<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	

	function load_menu($usr,$app)
	{
		$sql="select u.usuario, u.apellido_nombre, u.id_sector, x.sector, s.perfil,p.id_aplicacion,a.aplicacion,a.path,a.lft,a.rgt,a.nivel,a.imagen
		from ".BBDD_ODBC_SQLSRV."usuarios u, ".BBDD_ODBC_SQLSRV."aplicaciones a, ".BBDD_ODBC_SQLSRV."permisos p, ".BBDD_ODBC_SQLSRV."perfiles s, ".BBDD_ODBC_SQLSRV."sectores x
		where u.id_perfil=s.id_perfil
		and u.id_perfil=p.id_perfil
		and p.id_aplicacion=a.id_aplicacion
		and u.id_sector=x.id_sector
		and u.usuario='".$usr."'
		and a.aplicacion='".$app."'
		order by a.lft;";

		$query=$this->db->query($sql);

		if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				$data['usuario']= $row->usuario;
				$data['apellido_nombre']= $row->apellido_nombre;
				$data['sector']= $row->sector;
				$data['perfil']= $row->perfil;
				$data['aplicacion']= $row->aplicacion;
				$data['nivel']= $row->nivel;
				$data['path']= $row->path;
				$data['imagen']= $row->imagen;
				$data['lft']= $row->lft;
				$data['rgt']= $row->rgt;
			}
			return $data;
		}else{
			$data="N";
			return $data;
		}
	}

	function path_menu($app)
	{
		$sql="SELECT parent.aplicacion, parent.path, parent.imagen
		FROM ".BBDD_ODBC_SQLSRV."aplicaciones AS node,
			".BBDD_ODBC_SQLSRV."aplicaciones AS parent
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			AND node.aplicacion = '".$app."'
			ORDER BY node.lft;";

		$query=$this->db->query($sql);

		if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				$data[$row->aplicacion]['path']= $row->path;
				$data[$row->aplicacion]['imagen']= $row->imagen;
			}
			return $data;
		}else{
			$data="Error";
			return $data;
		}
	}

	function permisos_menu($usr,$nivel,$lft,$rgt)//fata que este determinado por mennu padre
	{

		$sql="select u.usuario, u.apellido_nombre, s.perfil,p.id_aplicacion,a.aplicacion,a.orden_menu,a.path,a.imagen,a.lft,a.rgt,a.nivel
			from ".BBDD_ODBC_SQLSRV."usuarios u, ".BBDD_ODBC_SQLSRV."aplicaciones a, ".BBDD_ODBC_SQLSRV."permisos p, ".BBDD_ODBC_SQLSRV."perfiles s
			where u.id_perfil=s.id_perfil
			and u.id_perfil=p.id_perfil
			and p.id_aplicacion=a.id_aplicacion
			and u.usuario='".$usr."'
			and a.nivel=".$nivel."
			and a.lft BETWEEN ".$lft." and ".$rgt."
			order by a.orden_menu;";

		$query=$this->db->query($sql);

		if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				$dato['aplicacion']= $row->aplicacion;
				$dato['path']= $row->path;
				$dato['imagen']= $row->imagen;
				$data[]=$dato;
			}
			return $data;
		}else{
			$data="Error";
			return $data;
		}
	}

	function lista_aplicaciones(){

		$sql="SELECT id_aplicacion, aplicacion, path, menu, orden_menu, imagen, lft, rgt, nivel FROM ".BBDD_ODBC_SQLSRV."aplicaciones order by lft";

		$query=$this->db->query($sql);

		if($query->num_rows() > 0 ){
			foreach ($query->result() as $row){
				$new_row['id_aplicacion']= $row->id_aplicacion;
				$new_row['name']= $row->aplicacion;
				$new_row['path']= $row->path;
				$tmp=$row->menu;
				if($tmp==1){
					$tmp="Si";
				}else{
					$tmp="No";
				}
				$new_row['menu']= $tmp;
				$new_row['orden_menu']= $row->orden_menu;
				$new_row['imagen']= $row->imagen;
				$new_row['lft']= $row->lft;
				$new_row['rgt']= $row->rgt;
				$new_row['level']= $row->nivel;
				$row_set[] = $new_row;
			}
			return $row_set;
		}else{
			$row_set="Error";
			return $row_set;
		}
	}

	function add_aplicacion($data,$usr){



    $tmp=$data['id_aplicacion'];

    $Sql="SELECT lft, nivel, path FROM ".BBDD_ODBC_SQLSRV."aplicaciones WHERE id_aplicacion =?;";

    $query=$this->db->query($Sql,array($tmp));
      if($query->num_rows() > 0 ){
        foreach ($query->result() as $row)
        {
          $lft=$row->lft;
          $nivel=$row->nivel;
        }
      }

      $app=$data['aplicacion'];
      $orden=$data['orden_menu'];
      if($data['menu']=="Si"){
      	$menu=1;
      }else{
      	$menu=0;
      }
      $imagen=$data['imagen'];
      $path=$data['path'];


      $this->db->trans_start();

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."aplicaciones SET rgt = rgt + 2 WHERE rgt > ?;";

      $query=$this->db->query($Sql,array($lft));

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."aplicaciones SET lft = lft + 2 WHERE lft > ?;";

      $query=$this->db->query($Sql,array($lft));


      $Sql="INSERT INTO ".BBDD_ODBC_SQLSRV."aplicaciones(aplicacion,menu, orden_menu, imagen, lft, rgt, nivel, path, ult_mod, mod_usuario) VALUES('".$app."', ".$menu.", ".$orden.",'".$imagen."', ".$lft." + 1, ".$lft." + 2, ".$nivel." + 1,'".$path."', getdate(),'".$usr."');";

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

  function del_aplicacion($data){

    $tmp=$data['id_aplicacion'];
  
    $Sql="SELECT lft, rgt FROM ".BBDD_ODBC_SQLSRV." aplicacion WHERE id_aplicacion =?;";

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

      $Sql="DELETE ".BBDD_ODBC_SQLSRV."aplicacion WHERE lft BETWEEN ".$lft." AND ".$rgt.";";

      $query=$this->db->query($Sql);
      
      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."aplicacion SET rgt = rgt-".$lng." WHERE rgt > ".$rgt.";";

      $query=$this->db->query($Sql);

      $Sql="UPDATE ".BBDD_ODBC_SQLSRV."aplicacion SET lft = lft-".$lng." WHERE lft > ".$rgt.";";

      $query=$this->db->query($Sql);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE){
               log_message('error#',$this->db->_error_number()."# ". $this->db->_error_message().' SQL:'.$Sql);
      }

  }

  function edit_aplicacion($data){
    $this->db->trans_start();

    if($data['menu']=="Si"){
    	$menu=1;
    }else{
    	$menu=0;
    }

    $Sql="UPDATE ".BBDD_ODBC_SQLSRV."aplicaciones SET aplicacion = '".$data['aplicacion']."', orden_menu=".$data['orden_menu'].", menu=".$menu.",imagen='".$data['imagen']."', path='".$data['path']."' WHERE id_aplicacion = ?;";
    
    $query=$this->db->query($Sql,array($data['id_aplicacion']));

    $this->db->trans_complete();
  }

}
?>