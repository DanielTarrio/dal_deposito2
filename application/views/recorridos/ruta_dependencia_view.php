<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">

	/*
	$("#frm_dependencia").focusin(function(){
		$( "#frm_dependencia" ).val("");
		$( "#frm_id_zona" ).val("");
		$( "#frm_Localidad" ).val("");
		$( "#frm_Direccion" ).val("");
	});*/

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
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			return false;
		},
		select: function( event, ui ) {
			$( "#frm_dependencia" ).val( ui.item.label );
			$( "#frm_Localidad" ).val( ui.item.Localidad );
			$( "#frm_Direccion" ).val( ui.item.Direccion );
			get_zonas_dependencia();
			return false;
		}
	});

	function get_zonas_dependencia(){

		$.post("get_zonas_dependencia",{
        	id_dependencia:$("#frm_id_dependencia").val(),
        },
  //========================
        function(returnedData){
          console.log(returnedData);
          $("#frm_zona").empty();
          $.each(returnedData, function (index){
          	//alert("Trajo data "+returnedData[index].Zona+"=>"+[index]);
          	$("#frm_zona").append("<option value="+returnedData[index].id_zona+">"+returnedData[index].Zona+"</option>");
          });
    //==========================================
/*
          if(returnedData['id_ruta']!="ERROR"){
            var error=false;
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la ruta Nro. <bolder>"+returnedData['id_ruta']+": "+returnedData['ruta']+"</bolder>\"</p>"
          }else{
            var error=true;
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+$( "#nro" ).val()+"\"<br>"+returnedData['msg']+"</p>"
          }
          $("#loader").hide();
          $("#dialogo").removeClass();
          $("#dialogo").removeAttr();
          
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
            resizable: true,
            title: "Creacion de Ruta",
            buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                $(this).dialog("close");
                if(error==false){
                  Bloquear();
                }
              }
            }
            ]
          });*/
    //==========================================      
        }
        ).fail(function(){
          console.log("Error");
    //==========================================
          $("#loader").hide();
          $("#dialogo").removeClass();
          $("#dialogo").removeAttr();
          var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Error en la peticion AJAX\"</p>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
            resizable: true,
            title: "ERROR",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                }
              }
            ]
          });
      });

	}

//==============================================



</script>
<div class="linea">
<input type="hidden" id="frm" name="frm" value="OK">
	<div class="celda">
	    <div><label>Dependencia:</label></div>
	    <input type="text" id="frm_dependencia" name="frm_dependencia" placeholder="Dependencia"  class="ui-widget" style="width:32em;text-align:left;" value="<?php echo $dependencia; ?>">
	    <input type="hidden" name="frm_id_dependencia" id="frm_id_dependencia" value="<?php echo $id_recorrido; ?>"> 
	    <input type="hidden" name="frm_id_ruta" id="frm_id_ruta" value="<?php echo $id_ruta; ?>">
    </div>
</div>
<div class="linea">
	<div class="celda_long">
	    <div><label>Zonas:</label></div>
	    <div id="div_zonas">
			<select name="frm_zona" id="frm_zona" class="ui-widget" style="width:32em;" size="6">
				
			</select>
	    </div>
	    
    </div>
</div>
