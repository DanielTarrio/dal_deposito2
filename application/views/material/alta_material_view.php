<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
  $(function() {
  	$("#frm_clase").autocomplete({
      source: "<?php echo base_url();?>index.php/material/get_clase", // path to the get_birds method
        minLength: 0,
      focus: function( event, ui ) {
        $( "#frm_clase" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#frm_id_clase" ).val( ui.item.value );
        $("#frm_id_sub_clase").val('');
        $("#frm_sub_clase").val('');
        return false;
      }
    });
    $("#frm_sub_clase").autocomplete({
		source: function(request, response) {
		$.ajax({
        	url: "<?php echo base_url();?>index.php/material/get_sub_clase",
             dataType: "json",
        	data: {
          	term : request.term,
          //id_deposito : $('#id_deposito').val(),
          	id_clase : $('#frm_id_clase').val()
        	},
	        success: function(data) {
	          response(data);
	        }
    	});
    	},
	    minLength: 0,
			focus: function( event, ui ) {
				$( "#frm_sub_clase" ).val( ui.item.label);
				return false;
			},
			select: function( event, ui ) {
				$( "#frm_id_sub_clase" ).val( ui.item.value );
				return false;
			}
    });
    $("#frm_unidad").autocomplete({
      source: "<?php echo base_url();?>index.php/material/get_unidad", // path to the get_birds method
        minLength: 0,
      focus: function( event, ui ) {
        $( "#frm_unidad" ).val( ui.item.value);
        return false;
      }
    });

  });
</script>
<div class="linea">
<input type="hidden" id="frm" name="frm" value="OK">
<input type="hidden" id="frm_id_material" name="frm_id_material" value="<?php echo $id_material; ?>">
	<div class="celda_body">
	    <div><label>Clase: </label></div>
	    <input type="text" id="frm_clase" name="clase" placeholder="clase"  class="ui-widget" style="width:8em;text-align:center;" value="<?php echo $clase; ?>"><input type="hidden" id="frm_id_clase" name="frm_id_clase" value="<?php echo $id_clase; ?>">
    </div>
	<div class="celda_body">
	    <div><label>Sub Clase: </label></div>
	    <input type="text" id="frm_sub_clase" name="frm_sub_clase" placeholder="sub_clase"  class="ui-widget" style="width:8em;text-align:center;" value="<?php echo $sub_clase; ?>"><input type="hidden" id="frm_id_sub_clase" name="frm_id_sub_clase" value="<?php echo $id_sub_clase; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda">
		<div><label>Material: </label></div>
     	<input type="text" id="frm_material" name="frm_material" placeholder="material"  class="ui-widget" style="width:32em;" value="<?php echo $descripcion; ?>">
    </div> 	
</div>
<div class="linea">
	<div class="celda">
	    <div><label>Codebar: </label></div>
	    <input type="text" id="frm_Codebar" name="frm_Codebar" placeholder="frm_Codebar"  class="ui-widget" style="width:8em;text-align:center;" value="<?php echo $barcode; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda">
	    <div><label>Unidad: </label></div>
	    <input type="text" id="frm_unidad" name="frm_unidad" placeholder="unidad"  class="ui-widget" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $unidad; ?>">
    </div>
</div>