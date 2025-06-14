<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
  $(function() {
  	
  		
	});
</script>
<style type="text/css">
	.status{

	}
</style>
<?php
    if($admin=="OK"){
?>
<div class="linea">
	<div class="celda">
		<label>Password:</label>
	</div>
	<div class="celda">
		<input type="password" class="ui-widget ui-corner-all" name="frm_psw_admin" id="frm_psw_admin" maxlength="40" value="" >
	</div>
</div>
<div class="linea">
	<div class="celda">
		<label>Confirmacion:</label>
	</div>
	<div class="celda">
        <input type="password" class="ui-widget ui-corner-all" name="frm_psw_admin2" id="frm_psw_admin2" maxlength="40" value="" >
		<input type="hidden" id="accion" value="pws_admin">
	</div>
</div>
<?php }else{ ?>
    <p class="error">Solo el usuario Admin puede cambiar el password</p>
<?php } ?>
