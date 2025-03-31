<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
  $(function() {
  	$("#label").autocomplete({
      source: "buscar_usr", // path to the get_birds method
        minLength: 0,
      focus: function( event, ui ) {
        $(this).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#frm_usuario" ).val( ui.item.value );
        $( "#frm_apellido_nombre" ).val( ui.item.apellido_nombre );
        return false;
      }
    });

  });
</script>
<div class="linea">
	<div class="celda">
	    <label>Usuario: </label>
	    <input type="text" id="label" name="label" placeholder="usuario"  class="ui-widget ui-corner-all" style="width:100%;text-align:center;margin:0.4em;font-size: 120%">
  </div>
</div>
<div class="linea">
	    <input type="hidden" id="frm_apellido_nombre" name="frm_apellido_nombre">
      <input type="hidden" id="frm_usuario" name="frm_apellido_nombre">
</div>