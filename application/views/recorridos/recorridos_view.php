<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/ecmascript" src="<?php echo base_url();?>js/jquery.jqGrid.min.js"></script>
<!-- This is the localization file of the grid controlling messages, labels, etc. -->
<!-- We support more than 40 localizations -->
<script type="text/ecmascript" src="<?php echo base_url();?>js/i18n/grid.locale-en.js"></script>
<!-- A link to a jQuery UI ThemeRoller theme, more than 22 built-in and many more custom -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>jquery-ui.css" />
<!-- The link to the CSS that the grid needs -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>ui.jqgrid.css" />
<script type="text/javascript">

$(function() {

  
  var ordenElementos;
  $("#id_deposito").change(function(){
    $("#recorrido").load("<?php echo base_url();?>index.php/recorridos/lst_recorridos?deposito="+$("#id_deposito").val());
  });

  $("#recorrido").change(function(){
    $("#ruta_ctd").load("<?php echo base_url();?>index.php/recorridos/listado?deposito="+$("#id_deposito").val()+"&recorrido="+$("#recorrido").val());
  });

  $("#NoProg").click(function(){

    dlg_no_prg();

  });

  $("#imprimir").click(function(){
    //alert($("#elementos").val());
    array_const();
    EnviarData();

  });

  function PrintRecorrido(){

    window.open('<?php echo base_url()?>index.php/recorridos/print_recorrido?id_entrega='+$( "#id_entrega" ).val(),'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');

  }

  function EnviarData(){

    //var gridData = jQuery("#jqGrid").getRowData();
    //var postData = JSON.stringify(gridData);
    //var postData = gridData;
    //$('#grilla').val(gridData);
    var nro;
    //console.log(postData);
 
    //alert("JSON serialized jqGrid data:\n" + postData);

    $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
    $("#loader").css({
      width:$(window).width(),
      height:$(window).height()
    });
    $("#loader").center();
    $("#gif-load").center();
    if($("#completo").is(':checked')){
      //alert($("#completo").is(':checked'));
    }

    var entrega={};

    for(var i=0; i<program.length; i++){

      entrega[i]={};
      entrega[i]['program']=program[i];
      entrega[i]['sector']=sector[i];
      entrega[i]['id_dependencia']=id_dependencia[i];
      entrega[i]['destino']=destino[i];
      entrega[i]['bultos']=bultos[i];
      entrega[i]['obs']=obs[i];
      entrega[i]['agr']=agr[i];
      console.log(i+" Prg:"+program[i]+" Dep:"+sector[i]+" Dest:"+destino[i]+" Bultos:"+bultos[i]);
      console.log("entrega :"+entrega[i]['destino']);
    }
    
/*
    var data=[];
    data['id_recorrido']=$("#recorrido").val();
    data['entrega']=JSON.stringify(entrega);
*/    

    var data={
      'entregaData': entrega,
      'id_recorrido': $("#recorrido").val(),
      'chofer': $("#chofer").val(),
      'patente': $("#patente").val(),
    }

    console.log("----------data---------");
    console.log(data); 

    $.post('recorridos/set_entregas',
      data,
      function(returnedData){
        console.log(returnedData['msg']);
        //alert("Trajo data");
  //==========================================
        $( "#id_entrega" ).val( returnedData['id_entrega'] );
        if($( "#id_entrega" ).val()!="ERROR"){
          $("#id_entrega").html($( "#id_entrega" ).val());
          var error=false;
          var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la Salida id_entrega. <bolder>"+$( "#id_entrega" ).val()+"</bolder>\"</p>"
        }else{
          var error=true;
          var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+$( "#id_entrega" ).val()+"\"<br>"+returnedData['msg']+"</p>"
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
          title: "Recorrido",
          buttons:[
          {
            text:"Aceptar",
            icons:{
              primary:"ui-icon-circle-check"
            },
            click: function(){
              $(this).dialog("close");
              if(error==false){
                PrintRecorrido();
                Bloquear();
              }
            }
          }
          ]
        });
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
          title: "Entrada de Materiales",
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

  

  jQuery.fn.center = function(){
    this.css("position","absolute");
    this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
    this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
  }

	

});

</script>



<style>
 /*   body {
     height: 100%;
     margin: 0;
     padding: 0;
     font-family: Verdana, Georgia, Serif;
     /*background-color: #f5f5b5;
    }*/
    #ruta{
    	list-style: none;
    	margin: 10px;
    	padding: 0;
    }

    #ruta li{
    	border: 1px solid #ccc;
    	padding: 2px 2px 2px 2px;
    	margin: 2px 0;
    	height: 32px;
    	background-color: #eee;
    }
    #ruta div{
    	padding: 4 px 4px 4px 58px;
    	margin: 4px 0;
    	margin-left: 20px;
    }

    #ruta li span {
    	position: absolute;
    	
    }
    
    #ruta_ctd{
      width: 90%;
      border: solid 1px green;
      overflow: auto;
    } 


    #iconstool li { 
      margin: 0px;
      position: relative;
      padding: 4px 0;
      cursor: pointer;
      float: left;
      list-style: none;
    }
    #iconstool span.ui-icontool {
      float: left;
      margin: 0 4px;
    }
    .ui-icontool {
      width: 32px;
      height: 32px;
    }
    .ui-icontool {
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
    }
    .ui-icontool-salida { background-position: 0px 0px; }
    .ui-icontool-entrada { background-position: -32px 0px; }
    .ui-icontool-buscar { background-position: -64px 0px; }
    .ui-icontool-configurar { background-position: -96px 0px; }
    .ui-icontool-grabar { background-position: -128px 0px; }
    .ui-icontool-grabar2 { background-position: -160px 0px; }
    .ui-icontool-importar { background-position: -192px 0px; }
    .ui-icontool-exportar { background-position: -224px 0px; }

    .ui-icontool-catalogo { background-position: 0px -32px; }
    .ui-icontool-costo { background-position: -32px -32px; }
    .ui-icontool-imprimir { background-position: -64px -32px; }
    .ui-icontool-deposito { background-position: -96px -32px; }
    .ui-icontool-usuario { background-position: -128px -32px; }
    .ui-icontool-perfil { background-position: -160px -32px; }
    .ui-icontool-bloqueado { background-position: -192px -32px; }
    .ui-icontool-desbloqueado { background-position: -224px -32px; }

    .ui-icontool-buscar2 { background-position: 0px -64px; }
    .ui-icontool-editar { background-position: -32px -64px; }
    .ui-icontool-eliminar { background-position: -64px -64px; }
    .ui-icontool-eliminar2 { background-position: -96px -64px; }
    .ui-icontool-clave { background-position: -128px -64px; }
    .ui-icontool-medida { background-position: -160px -64px; }
    .ui-icontool-prohibido { background-position: -192px -64px; }
    .ui-icontool-doc { background-position: -224px -64px; }

    .ui-icontool-salir { background-position: 0px -96px; }
    .ui-icontool-salir2 { background-position: -32px -96px; }
    .ui-icontool-download { background-position: -64px -96px; }
    .ui-icontool-menu { background-position: 0px -128px; }
    .ui-icontool-help { background-position: -96px -128px; }
    .ui-icontool-suma { background-position: -192px -160px; }
    .ui-icontool-etiqueta { background-position: -224px -160px; }
    .ui-icontool-proveedor { background-position: -0px -160px; }
   /* .ui-icontool-medida { background-position: -160px -96px; }
    .ui-icontool-prohibido { background-position: -192px -96px; }
    .ui-icontool-doc { background-position: -224px -96px; }*/

    .grid {
      border: solid 1px #e7e7e7;
    }
    .linea {
      float:left;
      position: relative;
      overflow: visible;
      width: 100%;
      padding: 1px;
     /* border: solid 1px black;*/
    }
    .etiqueta{
      width: 100%;
      margin: 0.4em;
    }
    .celda{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 14em;
      padding-left: 0.4em;  
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px red;*/
      resize: none;
    }
    .celda_long{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 30em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px green;*/
      resize: none;
    }
    .celda_right{
      float: right;
      min-height: 3em;
      font-size: 1.1em;
      width: 2em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.4em;
      padding-bottom: 0.1em;
      /*border: solid 1px gray;*/
      resize: none;
    }
    .celda_body{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 10em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
      resize: none;
      overflow: visible;
    }
    .celda_body_short{
      float: left;
      min-height: 3em;
      width: 6em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
     font-size: 1.1em;
      resize: none;
      overflow: visible;
    }
    .celda_body_long{
      float: left;
      min-height: 3em;
      width: 30em;
      font-size: 1.1em;
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px yellow;*/
      resize: none;
    }

  </style>


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
	<div>
	    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
	    	<li class="ui-state-default ui-corner-all" title="Imprimir">
	        	<span id="imprimir" class="ui-icontool ui-icontool-imprimir"></span>
	    	</li>
        <li class="ui-state-default ui-corner-all" title="help">
            <span id="help" class="ui-icontool ui-icontool-help"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="No Programado">
            <span id="NoProg" class="ui-icontool ui-icontool-proveedor"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="menu">
		        <a href="<?php echo base_url().'index.php/menu'; ?>">
		        	<span class="ui-icontool ui-icontool-menu"></span>
		        </a>
	    	</li>
        
	    </ul>
	</div>

	<div class="linea">
		<div class="celda">
			<div>
				<label class="etiqueta">Deposito: </label>
			</div>
			<select name="id_deposito" id="id_deposito" class="ui-widget" style="width:12em;">
				<option value="" Selected >----</option>
				<?php 
				for ($i = 0; $i < count($deposito); $i++) {
				echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
				}
				?>
			</select>
		</div>
		<div class="celda">
			<div>
				<label class="etiqueta">Recorrido: </label>
			</div>
			<select name="recorrido" id="recorrido" class="ui-widget" style="width:12em;">
				<option value="" Selected >----</option>
			</select>
		</div>
    <div class="celda">
      <div>
        <label class="etiqueta">Chofer: </label>
      </div>
      <div>
        <input type="text" name="chofer" id="chofer" placeholder="Chofer">
      </div>
    </div>
    <div class="celda">
      <div>
        <label class="etiqueta">Patente: </label>
      </div>
      <div>
        <input type="text" name="patente" id="patente" placeholder="Patente">
      </div>
    </div>
	</div>
  <div class="linea" style="height: 20px; margin: 10px;">
  	<div>
      <div class="ui-state-default" style="height: 20px;width: 500px; margin: 10px; padding: 3px">Recorrido <span id="id_entrega"></span><input type="hidden" name="id_entrega"></div>
      <div id="ruta_ctd" style="padding: 20px;overflow: auto;">
        <div class="linea ui-widget-header" style="margin: 10px;">
          <div class="celda_body_short" style="margin: 10px;">Pedido</div>
          <div class="celda" style="margin: 10px;">Dependencia</div> 
          <div class="celda" style="margin: 10px;">Sector</div>
          <div class="celda" style="margin: 10px;">Direccion</div>
          <div class="celda_body_short" style="margin: 10px;">Localidad</div>
          <div class="celda_body_short" style="margin: 10px;">Solicitante</div>
          <div class="celda_body_short" style="margin: 10px;">Bultos</div>
          <div class="celda_body_short" style="margin: 10px;">Excluir</div>
          <div class="celda_body_short" style="margin: 10px;">Etiqueta</div>
        </div>
        <ul id="ruta">
          <li></li>
        </ul>
      </div>
  	</div>
  </div>
  <input type="hidden" id="elementos">
  <div id="loader"></div>
  <div id="dialogo"></div>
</body>