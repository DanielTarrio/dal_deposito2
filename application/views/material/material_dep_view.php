<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
  $(function() {

    $("#frm_ubicacion").dblclick(function(){
      alert("ubicaciones deposito"+$("#id_deposito").val());
      get_ubicacion();
    });

    function get_ubicacion(){
      $("#ub_tree").hide();
      $("#ub_tree").load("<?php echo base_url();?>index.php/ubicaciones?id_deposito="+$("#id_deposito").val());
      $("#ub_tree").dialog({
        modal: false,
        height: 400,
        width: 400,
        resizable: true,
        title: "Material",
        buttons:[
          {
            text:"Aceptar",
            icons:{
            primary:"ui-icon-circle-check"
          },
            click: function(){
              $(this).dialog("close");
            }
          },
          {
            text:"Cancelar",
            icons:{
            primary:"ui-icon-circle-close"
          },
            click: function(){
              $(this).dialog("close");
              $("#ub_tree").dialog('close');
            }
          }
        ]
      });
      //$("#ub_tree").dialog('open');
    }


  	
  });
</script>
<div class="linea">
<input type="hidden" id="frm" name="frm" value="OK">
<input type="hidden" id="frm_id_material" name="frm_id_material" value="<?php echo $id_material; ?>">
	<div class="celda">
    <label>Clase: </label>
    <input type="text" id="frm_clase" name="clase" placeholder="clase"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $clase; ?>" readonly >
    <input type="hidden" id="frm_id_clase" name="frm_id_clase" value="<?php echo $id_clase; ?>">
  </div>
</div>
<div class="linea">
	<div class="celda">
    <label>Sub Clase: </label>
    <input type="text" id="frm_sub_clase" name="frm_sub_clase" placeholder="sub_clase"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $sub_clase; ?>" readonly ><input type="hidden" id="frm_id_sub_clase" name="frm_id_sub_clase" value="<?php echo $id_sub_clase; ?>">
  </div>
</div>
<div class="linea">
	<div class="celda_long">
    <label>Material: </label>
   	<input type="text" id="frm_material" name="frm_material" placeholder="material"  class="ui-widget ui-corner-all" style="width:32em;margin:0.4em" value="<?php echo $descripcion; ?>" readonly >
  </div> 	
</div>
<div class="linea">
	<div class="celda">
    <label>Codebar: </label>
    <input type="text" id="frm_Codebar" name="frm_Codebar" placeholder="frm_Codebar"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $barcode; ?>" readonly >
  </div>
</div>
<div class="linea">
  <div class="celda">
    <label>Unidad: </label>
    <input type="text" id="frm_unidad" name="frm_unidad" placeholder="unidad"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $unidad; ?>" readonly >
  </div>
  <div class="celda">
    <label>Cantidad: </label>
    <input type="text" id="frm_cantidad" name="frm_cantidad" placeholder="Cantidad"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $cantidad; ?>" readonly>
  </div>
  <div class="celda">
    <label>Minimo: </label>
    <input type="text" id="frm_minimo" name="frm_minimo" placeholder="Minimo"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $minimo; ?>">
  </div>
  <div class="celda">
    <label>Reposicion: </label>
    <input type="text" id="frm_reposicion" name="frm_reposicion" placeholder="Reposicion"  class="ui-widget ui-corner-all" style="width:8em;text-align:center;margin:0.4em" value="<?php echo $reposicion; ?>">
  </div>
</div>
<div class="linea">
  <div class="celda_long">
    <label>Ubicacion: </label>
    <input type="text" id="frm_ubicacion" name="frm_ubicacion" placeholder="Ubicacion"  class="ui-widget ui-corner-all" style="width:32em;margin:0.4em" value="<?php echo $ubicacion; ?>" readonly>
  </div>
</div>