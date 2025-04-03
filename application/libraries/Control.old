<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control {

	protected $ci;
	
	//creamos una instancia del super objeto de codeigniter
	//en el constructor para poder tenerlo disponible las veces
	//que necesitemos sin repetir la misma línea
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	function control_metodo($usr,$psw)
	{
		$this->ci->load->helper('string');
		$this->ci->load->model('Login_model');

		if($usr!="admin"){

			$usuario = $this->mailboxpowerloginrd($usr,$psw);

			//$usuario=1;
			if($usuario == "0" || $usuario == ''){
				$this->ci->session->sess_destroy();	
				//$this->ci->session->sess_create(); 
				//$this->ci->session->set_flashdata('cerrada','La sessión se ha cerrado correctamente.');	
				$dataS="NO";

			}else{

				$data_usr=$this->ci->Login_model->get_usuario($usr);
				/*$inicio_session=$data_usr[0]['inicio_session']-10800;
				$fin_session=$data_usr['session']-10800;
				$direccion_ip=$data_usr['direccion_ip'];*/

				if(count($data_usr)>0){
					$dataS="Ok";
					$data=array(
						'session_id'=>random_string('alnum',16),
						'usr'=>$usr,
						'clase'=>"Ok",
						'autenticado'=>TRUE
						//'session_usr'=>$this->session->userdata('usr')
					);
					$this->ci->session->set_userdata($data);
					$this->ci->Login_model->set_session($data_usr);
					$this->ci->Login_model->trace_user($usr);

				}else{
					$dataS="BAD";

				}

				/*
				$dataS="Ok";
				$data=array(
					'session_id'=>random_string('alnum',16),
					'usr'=>$usr,
					'clase'=>"Ok",
					'autenticado'=>TRUE
				//'session_usr'=>$this->session->userdata('usr')
				);
				$this->ci->session->set_userdata($data);
				*/

			}	
		}else{
			$data_usr=$this->ci->Login_model->get_admin($usr,$psw);
			//if(array_key_exists('usuario',$data_usr[0])){
			if(count($data_usr)>0){
				$dataS="Ok";
			 	//Se debe verificar el usuario administrador
				$data=array(
					'session_id'=>random_string('alnum',16),
					'usr'=>$usr,
					'clase'=>"Ok",
					'autenticado'=>TRUE
					//'session_usr'=>$this->session->userdata('usr')
				);
			$this->ci->session->set_userdata($data);
			$this->ci->Login_model->set_session($data_usr);
			$this->ci->Login_model->trace_user($usr);
			}else{
				$dataS="BAD";
			}
		}
		return $dataS;
	}

	
	function control_login()
	{
		$this->ci->load->model('Login_model');
		$control=FALSE;
		if($this->ci->session->userdata('autenticado') == TRUE){
			$usr=$this->ci->session->userdata('usr');
			$session_id=$this->ci->session->userdata('session_id');
			$session_data=$this->ci->Login_model->get_usuario($usr);
			if(($session_data['bloqueado']==0)&&($session_data['activo']==1)){
				if(($session_data['conectado']==1)&&($session_data['session']==$session_id)){
					$this->ci->Login_model->trace_user($usr);
					$control=TRUE;	
				}else{
					//$this->ci->login_model->set_session($usr);
					$control=FALSE;
				}
				
			}else{
				$control=FALSE;
			}
		}
		return $control;
		//return $this->ci->session->userdata('autenticado') == TRUE ? TRUE:FALSE;//$this->ci->session->userdata('usr') : "";
	}	
		
/*
		if( $this->ci->session->userdata('autenticado')==TRUE){
			$data = array(
	        'usuario' =>  $this->ci->session->userdata('usr'),
	        'session_time' => time()
			);
			//redirect(base_url().'index.php/menu');

		}else{
			//$StrSQL=sprintf("update usuarios set session_time=UNIX_TIMESTAMP() WHERE usuario = '%s'", $_SESSION['usr']);
			redirect(base_url().'index.php/login');
		}
		//$this->load->model('control_model');
		//$data=$this->control_model->login_user('$usr');
		//return $data;
		
*/
	


//---------------------------------------------------

	function mailboxpowerloginrd($usr, $psw) {

		$cnx = ldap_connect("ldap://".DOMINIO.":389");

		$username = trim($usr);
		$password = trim($psw);

		if ($cnx) {
			ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);
			$bind = @ldap_bind($cnx, $username.'@'.DOMINIO, $password);
			if ($bind) {
				$ret = @ldap_search(
				    $cnx, DN,
				    "(&(objectClass=user)(sAMAccountName=".$username."))",
				    array("sAMAccountName")
				);
				return @ldap_get_entries($cnx, $ret);
			}
		}

		return 0;

	}

//---------------------------------------------------	
	public function logout($usr)
	{
		
		$this->ci->load->model('Login_model');
		$this->ci->Login_model->end_session($usr);
		$data=array('session_id','usr','clase','autenticado');
		
		$this->ci->session->unset_userdata($data);
		$this->ci->session->sess_destroy();

		return 'Finalizada';
		//$this->ci->session->sess_create(); 
		//$this->ci->session->set_flashdata('cerrada','La sessión se ha cerrado correctamente.');
		//redirect(base_url('login','refresh'));			
		
	}

	function limpia_str($p){
	    $largo=strlen($p);
	    $cadena="";
	    $p=preg_split('//', utf8_decode($p), -1, PREG_SPLIT_NO_EMPTY);
	    foreach ($p as $v) {
	      //echo $v."->".ord($v)."<br>";
	      if(ord($v)<127){
	        if(ord($v)!=63){
	          $cadena=$cadena.$v;
	        }else{
	          $cadena=$cadena."_";
	        }
	        
	      }else{
	        $cadena=$cadena."_";
	      }
	    }
	    return utf8_decode($cadena);
  	}

  	function ParseDMYtoYMD($Data,$Separador){
	$vF=preg_split("(([-])|([/])|(\s)|([:]))",$Data);
	@$fecha=$vF[2].$Separador.$vF[1].$Separador.$vF[0];
	if(count($vF)>3){
		if(count($vF)>5){
			$fecha.=" ".$vF[3].":".$vF[4].":".$vF[5];
		}else{
			$fecha.=" ".$vF[3].":".$vF[4].":00";
		}
	}
	if(count($vF)<2){
		$fecha="";
	}
	return $fecha;//."[".count($vF)."]";
}


}

?>
