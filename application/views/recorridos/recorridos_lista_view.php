<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">

	var spinner=$("#frm_bultos").spinner({change: function(event, ui){},min: 0});
	$("#frm_bultos").on("spinchange",function(event, ui){
	  //alert("cambio spiner");
	});

  
//==============================================
	$("#frm_Zona").focusin(function(){
		$( "#frm_Zona" ).val("");
		$( "#frm_id_zona" ).val("");
		$( "#frm_Localidad" ).val("");
		$( "#frm_Direccion" ).val("");
	});

	$("#frm_Zona").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "<?php echo base_url();?>index.php/solicitudes/get_dependencia",
				dataType: "json",
				data: {
					term : request.term
					//id_deposito : $('#id_deposito').val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		focus: function( event, ui ) {
			$( "#frm_Zona" ).val( ui.item.label);
			$( "#frm_id_zona" ).val( ui.item.value );
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			return false;
		},
		select: function( event, ui ) {
			$( "#frm_id_zona" ).val( ui.item.value );
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			return false;
		}
	});

	$("#frm_Localidad").autocomplete({
		source: "solicitudes/get_localidad", // path to the get_birds method
		minLength: 2,
		focus: function( event, ui ) {
			$( "#frm_Localidad" ).val( ui.item.value);
			return false;
		}
	});

	$("#frm_Direccion").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "solicitudes/get_direccion",
				dataType: "json",
				data: {
					term : request.term
					//id_deposito : $('#id_deposito').val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		focus: function( event, ui ) {
			$( "#frm_Direccion" ).val( ui.item.label);
			return false;
		},
		select: function( event, ui ) {
			/*$( "#id_zona" ).val( ui.item.value );*/
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_dependencia" ).val( ui.item.Zona );
			/*$( "#ubicacion" ).val( ui.item.ubicacion );
			$( "#cant_stock" ).val( ui.item.cantidad );
			$( "#id_stock" ).val( ui.item.id_stock );*/
			return false;
		}
	});

//==============================================



</script>
<div class="linea">
<input type="hidden" id="frm" name="frm" value="OK">
	<div class="celda">
	    <div><label>Dependencia:</label></div>
	    <input type="text" id="frm_dependencia" name="frm_dependencia" placeholder="Dependencia"  class="ui-widget" style="width:32em;text-align:left;" value="<?php echo $dependencia; ?>">
	    <input type="hidden" name="frm_id_recorrido" id="frm_id_recorrido" value="<?php echo $id_recorrido; ?>">
	    <input type="hidden" name="frm_id_zona" id="frm_id_zona" value="<?php echo $id_zona; ?>">
	    <input type="hidden" name="frm_id_ruta" id="frm_id_ruta" value="<?php echo $id_ruta; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Zona:</label></div>
	    <input type="text" id="frm_Zona" name="frm_Zona" placeholder="Zona"  class="ui-widget" style="width:32em;" value="<?php echo $Zona; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Direccion:</label></div>
	    <input type="text" id="frm_Direccion" name="frm_Direccion" placeholder="Direccion"  class="ui-widget" style="width:32em;" value="<?php echo $Direccion; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Localidad:</label></div>
	    <input type="text" id="frm_Localidad" name="frm_Localidad" placeholder="Localidad"  class="ui-widget" style="width:32em;" value="<?php echo $Localidad; ?>">
    </div>
</div>