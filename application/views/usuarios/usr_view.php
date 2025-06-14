<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
  $(function() {
  	
  		$("div[id^=status]").hover(
			function() {
				$( this ).addClass( "ui-state-hover" );
			},
			function() {
				$( this ).removeClass( "ui-state-hover" );
			}
		);
		$("#bloqueado").click(function(){
			if($("#frm_bloqueado").val()==0){
				$("#frm_bloqueado").val(1);
				$( "#bloqueado" ).removeClass( "permitido" );
				$( "#bloqueado" ).addClass( "bloqueado" );
			}else{
				$("#frm_bloqueado").val(0);
				$( "#bloqueado" ).removeClass( "bloqueado" );
				$( "#bloqueado" ).addClass( "permitido" );
			}
		});

		$("#conectado").click(function(){
			if($("#frm_conectado").val()==1){
				$("#frm_conectado").val(0);
				$( "#conectado" ).removeClass( "conectado" );
				$( "#conectado" ).addClass( "desconectado" );
			}
		});

		$("#activo").click(function(){
			if($("#frm_activo").val()==0){
				$("#frm_activo").val(1);
				$( "#activo" ).removeClass( "inactivo" );
				$( "#activo" ).addClass( "activo" );
			}else{
				$("#frm_activo").val(0);
				$( "#activo" ).removeClass( "activo" );
				$( "#activo" ).addClass( "inactivo" );
			}
		});

  		$("#frm_sector").autocomplete({
			source: "get_sector", // path to the get_birds method
			minLength: 0,
			focus: function( event, ui ) {
			$( "#frm_sector" ).val( ui.item.label);
				return false;
			},
			select: function( event, ui ) {
			$( "#frm_id_sector" ).val( ui.item.value );
				return false;
			}
	    });
	});
</script>
<style type="text/css">
	.status{

	}
</style>
<div class="linea">
	<div class="celda_body">
		<label>Usuario:</label>
	</div>
	<div class="celda">
	<?php
		if($usuario!=""){
			$tmp='readonly';
			$accion='Editar';
		}else{
			$accion='Insertar';
			$tmp='';
		}
	?>
		<input type="text" class="ui-widget ui-corner-all" name="frm_usuario" id="frm_usuario" maxlength="20" value="<?php echo $usuario; ?>" <?php echo $tmp; ?>>
		<input type="hidden" id="accion" value="<?php echo $accion; ?>">
	</div>
</div>
<div class="linea">
	<div class="celda_body">
		<label>Apellido y Nombre:</label>
	</div>
	<div class="celda">
		<input type="text" class="ui-widget ui-corner-all" name="frm_apellido_nombre" id="frm_apellido_nombre" maxlength="50" value="<?php echo $apellido_nombre; ?>">	
	</div>
</div>
<div class="linea">
	<div class="celda_body">
		<label>Perfil:</label>
	</div>
	<div class="celda">
		<select name="id_perfil" id="frm_id_perfil" class="ui-widget ui-corner-all">
          
          <?php 
            for ($i = 0; $i < count($perfiles); $i++) {
            	if($perfiles[$i]['value']==$id_perfil){
            		$selected='selected';
            	}else{
            		$selected='';
            	}
              echo "<option value=\"".$perfiles[$i]['value']."\" ".$selected." >".$perfiles[$i]['label']."</option>";
            }
          ?>
      	</select>
	</div>
</div>
<div class="linea">
	<div class="celda_body">
		<label>Sector:</label>
	</div>
	<div class="celda">
		<input type="text" class="ui-widget ui-corner-all" name="frm_sector" id="frm_sector" value="<?php echo $sector; ?>">
		<input type="hidden" name="frm_id_sector" id="frm_id_sector" value="<?php echo $id_sector; ?>">	
	</div>
</div>
<div class="linea">
	<div class="celda_body" style="margin-top: 8px">
		<label>Conectado:</label>
	</div>
	<div class="celda_body">
		<?php 
			if($conectado==1){
				$tmp='conectado';
				$titulo='desconectar';
			}else{
				$tmp='desconectado';
				$titulo='';
			}
		?>
		<div id="status_conectado" class="ui-state-default ui-corner-all" style="float:left;width: 32px;height: 32px">
			<div id="conectado" class="<?php echo $tmp;?>" title="<?php echo $titulo;?>"></div>
		</div>
	</div>
	<div style="float: left;"><?php echo $tiempo ?></div>
</div>
<div class="linea">
	<div class="celda_body" style="margin-top: 8px">
		<label>Bloqueado:</label>
	</div>
	<div class="celda_body">
		<?php 
			if($bloqueado==1){
				$tmp='bloqueado';
				$titulo='desbloquear';
			}else{
				$tmp='permitido';
				$titulo='bloquear';
			}
		?>
		<div id="status_bloqueado" class="ui-state-default ui-corner-all" style="float:left;width: 32px;height: 32px">
			<div id="bloqueado" class="<?php echo $tmp;?>" title="<?php echo $titulo;?>"></div>
		</div>
	</div>
</div>
<div class="linea">
	<?php 
		if($activo==1){
			$tmp='activo';
			$titulo='inhabilitar';
		}else{
			$tmp='inactivo';
			$titulo='habilitar';
		}
	?>
	<div class="celda_body" style="margin-top: 8px">
		<label>Activo:</label>
	</div>
	<div id="status_activo" class="ui-state-default ui-corner-all" style="float:left;width: 32px;height: 32px">
		<div id="activo" class="<?php echo $tmp;?>" title="<?php echo $titulo;?>" ></div>
	</div>
	<input id="frm_bloqueado" type="hidden" value="<?php echo $bloqueado;?>">
	<input id="frm_conectado" type="hidden" value="<?php echo $conectado;?>">
	<input id="frm_activo" type="hidden" value="<?php echo $activo;?>">
</div>
