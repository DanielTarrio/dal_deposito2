<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">

	var spinner=$("#frm_bultos").spinner({change: function(event, ui){},min: 0});
	$("#frm_bultos").on("spinchange",function(event, ui){
	  //alert("cambio spiner");
	});

  
//==============================================

	$("#frm_dependencia").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "recorridos/get_dependencia",
				//url: "solicitudes/get_dependencia",
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
			$( "#frm_dependencia" ).val( ui.item.label);
			$( "#frm_id_dependencia" ).val( ui.item.value );
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			return false;
		},
		select: function( event, ui ) {
			$( "#frm_id_dependencia" ).val( ui.item.value );
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			/*$( "#ubicacion" ).val( ui.item.ubicacion );
			$( "#cant_stock" ).val( ui.item.cantidad );
			$( "#id_stock" ).val( ui.item.id_stock );*/
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
	    <div><label>Dependencia: </label></div>
	    <input type="text" id="frm_dependencia" name="frm_dependencia" placeholder="Dependencia"  class="ui-widget" style="width:32em;text-align:left;"><input type="hidden" name="frm_id_dependencia" id="frm_id_dependencia">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Sector: </label></div>
	    <input type="text" id="frm_sector" name="frm_sector" placeholder="Sector"  class="ui-widget" style="width:32em;">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Direccion: </label></div>
	    <input type="text" id="frm_Direccion" name="frm_Direccion" placeholder="Direccion"  class="ui-widget" style="width:32em;">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Localidad: </label></div>
	    <input type="text" id="frm_Localidad" name="frm_Localidad" placeholder="Localidad"  class="ui-widget" style="width:32em;">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
		<div><label>Solicitante: </label></div>
     	<input type="text" id="frm_solicitante" name="frm_solicitante" placeholder="Solicitante"  class="ui-widget" style="width:32em;">
    </div> 	
</div>
<div class="linea">
	<div class="celda_body">
	    <div><label>Bultos: </label></div>
	    <input type="text" id="frm_bultos" name="frm_bultos" placeholder="Bultos"  class="ui-widget" style="width:8em;text-align:center;">
	</div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Observacion: </label></div>
	    <input type="text" id="frm_obs" name="frm_obs" placeholder="Observacion"  class="ui-widget" style="width:32em;">
	</div>
</div>