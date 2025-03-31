<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="linea">
<input type="hidden" id="frm" name="frm" value="OK">
	<div class="celda">
    <div>
      <label>Zona: </label>
    </div>
	    <input type="text" id="frm_zona" name="frm_zona" placeholder="zona"  class="ui-widget" style="width:24em;text-align:left;margin:0.4em" value="<?php echo $Zona; ?>" maxlength="50"><input type="hidden" id="frm_id_zona" name="frm_id_zona" value="<?php echo $id_zona; ?>">
  </div>
</div>
<div class="linea">
	<div class="celda">
    <div>
      <label>Dirección: </label>
    </div>
    <div>
      <input type="text" id="frm_direccion" name="frm_Dirección" placeholder="Dirección"  class="ui-widget" style="width:32em;text-align:left;margin:0.4em" value="<?php echo $Direccion; ?>" maxlength="100">
    </div>
  </div>
</div>
<div class="linea">
	<div class="celda_long">
    <div>
      <label>Localidad: </label>
    </div>
    <div>
      <input type="text" id="frm_Localidad" name="frm_Localidad" placeholder="Localidad"  class="ui-widget" style="width:32em;margin:0.4em" value="<?php echo $Localidad; ?>" maxlength="50">
    </div>
  </div> 	
</div>
<div class="linea">
	<div class="celda">
    <div>
      <label>Código Postal: </label>
    </div>
    <div>
      <input type="text" id="frm_CP" name="frm_CP" placeholder="Código Postal"  class="ui-widget" style="width:8em;text-align:center;margin:0.4em" onkeyup="aMays(event,this)" onblur="aMays(event,this)" value="<?php echo $CP; ?>" maxlength=12>
    </div>
  </div>
  <div class="celda">
    <div>
      <label>activa: </label>
    </div>
    <div>
      <?php 
      if($activa==1){
        $activa="checked";
      }else{
        $activa="";
      }
      ?>
      <input type="checkbox" id="frm_activa" name="frm_activa" placeholder="activa"  class="ui-widget" <?php echo $activa; ?>>
    </div>
  </div>
</div>
<div class="linea">
  <div class="celda_long">
    <div>
      <label>Dependencia: </label>
    </div>
    <div>
      <input type="text" id="frm_dependencia" name="frm_dependencia" placeholder="Dependencia"  class="ui-widget" style="width:32em;margin:0.4em" value="<?php echo $dependencia; ?>" maxlength="50"><input type="hidden" id="frm_id_dependencia" name="frm_id_dependencia" value="<?php echo $id_dependencia; ?>">
    </div>
  </div>  
</div>
<script type="text/javascript">

  $("#frm_dependencia").autocomplete({
    source: function(request, response) {
      $.ajax({
        url: "<?php echo base_url();?>index.php/recorridos/get_dependencia",
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
      return false;
    },
    select: function( event, ui ) {
      $( "#frm_dependencia" ).val( ui.item.label);
      $( "#frm_id_dependencia" ).val( ui.item.value );
      return false;
    }
  });
  $("#frm_dependencia").focusin(function(){
      $("#frm_dependencia").val("");
      $( "#frm_id_dependencia" ).val("");
  });

  $("input[name^='frm_']").change(function(){
      NormalClass(this);
  });


  function ErrorClass(x){
    $(x).removeClass();
    $(x).addClass("ui-state-error"); 
  }

  function NormalClass(x){
    $(x).removeClass();
    $(x).addClass("ui-widget");
  }

  function aMays(e, elemento){
    tecla=(document.all)? e.keyCode : e.which;
    elemento.value = elemento.value.toUpperCase();
  }
  

</script>