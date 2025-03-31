<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/ecmascript" src="<?php echo base_url();?>js/jquery.jqGrid.min.js"></script>
<!-- This is the localization file of the grid controlling messages, labels, etc. -->
<!-- We support more than 40 localizations -->
<script type="text/ecmascript" src="<?php echo base_url();?>js/i18n/grid.locale-en.js"></script>
<!-- A link to a jQuery UI ThemeRoller theme, more than 22 built-in and many more custom -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>jquery-ui.css" />
<!-- The link to the CSS that the grid needs -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>ui.jqgrid.css" />
   <style>
    body {
     height: 100%;
     margin: 0;
     padding: 0;
     font-family: Verdana, Georgia, Serif;
     /*background-color: #f5f5b5;*/
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
    .ui-icontool-usuario { background-position: -0px -96px; }
    .ui-icontool-perfil { background-position: -160px -32px; }
    .ui-icontool-bloqueado { background-position: -192px -32px; }
    .ui-icontool-desbloqueado { background-position: -224px -32px; }

    .ui-icontool-buscar2 { background-position: 0px -64px; }
    .ui-icontool-editar { background-position: -32px -64px; }
    .ui-icontool-eliminar { background-position: -64px -64px; }
    .ui-icontool-eliminar2 { background-position: -96px -64px; }
    .ui-icontool-clave { background-position: -128px -64px; }
    .ui-icontool-medida { background-position: -160px -64px; }
    /*.ui-icontool-prohibido { background-position: -192px -64px; }*/
    .ui-icontool-doc { background-position: -224px -64px; }
    .ui-icontool-proveedor { background-position: -0px -160px; }

    /*.ui-icontool-salir { background-position: 0px -96px; }
    .ui-icontool-salir2 { background-position: -32px -96px; }
    .ui-icontool-download { background-position: -64px -96px; }*/
    .ui-icontool-menu { background-position: 0px -128px; }
    /*.ui-icontool-clave { background-position: -128px -96px; }*/
    .ui-icontool-medida { background-position: -160px -96px; }
    .ui-icontool-prohibido { background-position: -192px -96px; }
    /*.ui-icontool-doc { background-position: -224px -96px; }*/

    .conectado{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: -192px -96px;
    }
    .desconectado{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: -160px -96px;
    }
    .inactivo{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: -32px -96px;
    }
    .activo{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: 0px -96px;
    }
    .bloqueado{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: -192px -32px;
    }
    .permitido{
      width: 32px;
      height: 32px;
      margin-left: auto;
      margin-right: auto;
      background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/tools.png");
      background-position: -224px -32px;
    }

    .grid {
      border: solid 1px #e7e7e7;
    }
    .linea {
      width: 98%;
      height: 3.5em;
      margin: 0.1em;
      padding-left: 0.3em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }
    .etiqueta{
      width: 100%;
      margin: 0.4em;
    }
    .celda{
      float: left;
      height: 3em;
      width: 22em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }
    .celda_long{
      float: left;
      height: 3em;
      width: 40em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }
    .celda_right{
      float: right;
      height: 3em;
      width: 20em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.4em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }
    .celda_body{
      float: left;
      height: 1.6em;
      width: 10em;
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }
    .celda_body_long{
      float: left;
      height: 3em;
      width: 30em;
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }

  </style>
   <style type="text/css">

        /* set the size of the datepicker search control for Order Date*/
        #ui-datepicker-div { font-size:11px; }
        
        /* set the size of the autocomplete search control*/
        .ui-menu-item {
            font-size: 11px;
        }

         .ui-autocomplete {
            font-size: 11px;
        }       

    </style>
<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
  <div>
    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
      <li class="ui-state-default ui-corner-all" title="Menu">
        <a href="<?php echo base_url().'index.php/menu/crear_menu/configuracion'; ?>">
          <span class="ui-icontool ui-icontool-menu"></span>
        </a>
      </li>
      <li class="ui-state-default ui-corner-all" title="Menu">
        <span id="new_usr" class="ui-icontool ui-icontool-usuario"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="Menu">
        <a href="<?php echo base_url().'index.php/menu/crear_menu/configuracion'; ?>">
          <span class="ui-icontool ui-icontool-clave"></span>
        </a>
      </li>
    </ul>
  </div>   
<div style="margin: auto; width: 82em; height:3em; padding:0.4em">
    <table id="jqGridVale"></table>
    <div id="jqGridValePager"></div>
</div>
<div id="loader"></div>
<div id="dialogo"></div>
<div id="frm"></div>
</body> 
<script type="text/javascript"> 

    
    var i;
    var template = "<div style='margin-left:15px;'><div>Legajo<sup>*</sup>:</div><div> {legajo} </div>";
        template += "<div> Apellido y Nombre: </div><div>{apellido_nombre} </div>";
        template += "<div> Centro de Costo: </div><div>{centro_costo} </div>";
        template += "<div> Activo: </div><div>{activo} </div>";
        template += "<hr style='width:100%;'/>";
        template += "<div> {sData} {cData}  </div></div>";

    $(document).ready(function () {

         $( "#icons li" ).hover(
            function() {
                $( this ).addClass( "ui-state-hover" );
            },
            function() {
                $( this ).removeClass( "ui-state-hover" );
            }
        );
        $( "#iconstool li" ).hover(
            function() {
                $( this ).addClass( "ui-state-hover" );
            },
            function() {
                $( this ).removeClass( "ui-state-hover" );
            }
        );

        $("#new_usr").click(function(){

            $("#frm").hide();
            $("#frm").load("<?php echo base_url();?>index.php/usuarios/edit_usr");
            $("#frm").dialog({
            modal: true,
            height: 400,
            width: 550,
            resizable: true,
            title: "Alta de usuario",
            buttons:[
              {
                text:"Aplicar",
                icons:{
                  primary:"ui-icon-circle-check"
                },
                click: function(){
                  //if($("#frm").val()=="OK"){
                    aplicar();
                    //$(this).dialog("close");
                  //}else{
                  //  $(this).dialog("close");
                  //}
                }
              },
              {
                text:"Cancelar",
                icons:{
                  primary:"ui-icon-circle-close"
                },
                click: function(){
                  $(this).dialog("close");
                }
              }
            ]
          });
          $("#frm").dialog('open');

        });


        $("#jqGridVale").jqGrid({
            url: 'get_lista_usr',
            mtype: "GET",
            datatype: "json",
            editurl:'clientArray',
            page: 1,
            colModel: [
                {
                    label: "usuario",
                    name: 'usuario',
                    width: 50,
                    key:true,
                    hidden: false,
                    editable: true,
                    editrules : { required: true}
                },
                {
					          label: "Apellido y Nombre",
                    name: 'apellido_nombre',
                    width: 150,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
  
                    
                },
                {
                    label: "Perfil",
                    name: 'perfil',
                    width: 80,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
  
                    
                },
                {
                    label: "Sector",
                    name: 'sector',
                    width: 80,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
  
                    
                },                   
                {
                    label: "Activo",
                    name: 'activo',
                    width: 32,
                    align:'center',
                    editable: true,
                    stype: "select",
                    searchoptions: { value: ":[Todos];<div class=\"activo\"></div>:activo;<div class=\"inactivo\"></div>:inactivo" }
                    
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                },                   
                {
                    label: "Bloqueado",
                    name: 'bloqueado',
                    width: 32,
                    align:'center',
                    editable: true,
                    stype: "select",
                    searchoptions: { value: ":[Todos];<div class=\"bloqueado\"></div>:bloqueado;<div class=\"permitido\"></div>:permitido" }
                    
                },                   
                {
                    label: "con",
                    name: 'conectado',
                    width: 32,
                    align:'center',
                    editable: true,
                    stype: "select",
                    searchoptions: { value: ":[Todos];<div class=\"conectado\"></div>:conectado;<div class=\"desconectado\"></div>:desconectado" }
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                },
                {
                    label: "Tiempo",
                    name: 'tiempo',
                    width: 32,
                    align:'center',
                    editable: true,
                   
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                }

            ],
			      loadonce: true,
			      viewrecords: true,
            width: 900,
            height: 400,
            rowNum: 15,
            caption:"Usuarios",
            rownumbers:true,
            rownumWidth:25,
            pager: "#jqGridValePager",
            onCellSelect: function(rowid,iCol,cellcontent,e) {
              i = $("#jqGridVale").getGridParam("reccount");
              //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
              //disponible=parseFloat($("#jqGridVale").jqGrid('getCell',rowid,'nro'))+' '+$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
              //alert(disponible);
             /* nro=$("#jqGridVale").jqGrid('getCell',rowid,'nro')
              deposito=$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
              window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');*/
            },
            ondblClickRow:function(rowid,iCol,cellcontent,e) {
              //alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
              edit_usr(rowid);

              //alert();
            }
        });
		// activate the toolbar searching
    $('#jqGridVale').jqGrid('filterToolbar');
		$('#jqGridVale').jqGrid('navGrid',"#jqGridValePager", {                
            search: false, // show search button on the toolbar
            add: false ,
            edit: false,
            del: false,
            refresh: true
        },
        {
			editCaption: "Editar Linea",
			width:500,
			template: template,
            url: 'edit_persona' ,
            mtype: "GET",
			bSubmit:"Guardar",
			closeAfterEdit:true,
            reloadAfterSubmit:true,
			errorTextFormat: function (data) {
			  return 'Error: ' + data.responseText
			},
            afterSubmit: function(data){
                return 'oK'+data.responseText;
                Refrescar();
            },
            afterComplete: function(data){
                return 'oK'+data.responseText;
                alert('jjj');
                Refrescar();
            },
            onClose: function(){
                Refrescar();
            }
		},
		// options for the Add Dialog
		{
			template: template,
            url: 'add_persona' ,
            mtype: "GET",
            closeAfterEdit:true,
            reloadAfterSubmit:true,
			errorTextFormat: function (data) {
			    return 'Error: ' + data.responseText
			},
            afterSubmit: function(data){
                return 'oK'+data.responseText
            },
            onClose: function(){
                Refrescar();
            }
		},
		// options for the Delete Dailog
		{
			errorTextFormat: function (data) {
			    return 'Error: ' + data.responseText
		}
	});

    function Refrescar(){
    $('#jqGridVale').jqGrid('setGridParam',{ url: 'get_lista_usr',datatype: 'json' }).trigger("reloadGrid");
    }

    function edit_usr(rowid){
      /*  $("#frm").removeClass();
        $("#frm").removeAttr();*/
        i=rowid;
        $("#frm").hide();
        $("#frm").load("<?php echo base_url();?>index.php/usuarios/edit_usr?usuario="+rowid);
        $("#frm").dialog({
        modal: true,
        height: 400,
        width: 550,
        resizable: true,
        title: "Usuario: "+rowid,
        buttons:[
          {
            text:"Aplicar",
            icons:{
              primary:"ui-icon-circle-check"
            },
            click: function(){
              //if($("#frm").val()=="OK"){
                aplicar();
                $(this).dialog("close");
              //}else{
              //  $(this).dialog("close");
              //}
            }
          },
          {
            text:"Cancelar",
            icons:{
              primary:"ui-icon-circle-close"
            },
            click: function(){
              $(this).dialog("close");
            }
          }
        ]
      });
      $("#frm").dialog('open');
    }

    function aplicar(){
      //alert('aplicar');
      //$("#dialogo").hide();
        $("#dialogo").html("-Esta seguro de aplicar los cambios?");
        $("#dialogo").dialog({
        modal: true,
        height: 150,
        width: 300,
        resizable: true,
        title: "Confirmación de cambios",
        buttons:[
          {
            text:"Aplicar",
            icons:{
              primary:"ui-icon-circle-check"
            },
            click: function(){
              //if($("#frm").val()=="OK"){
                set_user();
                $(this).dialog("close");
              //}else{
              //  $(this).dialog("close");
              //}
            }
          },
          {
            text:"Cancelar",
            icons:{
              primary:"ui-icon-circle-close"
            },
            click: function(){
              $("#frm").dialog("close");
              $(this).dialog("close");
            }
          }
        ]
      });
      $("#dialogo").dialog('open');
    }

    function set_user(){

      //alert($("#accion").val());

      var data = {
        accion: $("#accion").val(),
        usuario: $("#frm_usuario").val(),
        apellido_nombre: $("#frm_apellido_nombre").val(),
        id_sector: $("#frm_id_sector").val(),
        id_perfil: $("#frm_id_perfil").val(),
        id_personal: $("#frm_id_personal").val(),
        bloqueado: $("#frm_bloqueado").val(),
        conectado: $("#frm_conectado").val(),
        activo: $("#frm_activo").val()
      }

      /*
      $("#loader").removeClass();
      $("#loader").removeAttr();
      $("#loader").dialog("close");
      $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
      $("#loader").css({
        width:$(window).width(),
        height:$(window).height()
      });
      $("#loader").center();
      $("#gif-load").center();
      $("#loader").dialog('open');
      */

      
      $.post('add_usr',
        data,
        function(returnedData){
          console.log(returnedData);
          if(returnedData['estatus']!="ERROR"){
            
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+returnedData['msg']+"\"</p>";
          }else{
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+returnedData['msg']+"\"<br></p>";
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
            title: "Entrada de Materiales",
            buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                $(this).dialog("close");
                $("#frm").dialog("close");
                Refrescar();
                //jQuery("#jqGridVale").addRowData(i, data);

                //jQuery("#jqGridVale").setRowData(i, data);
                //Bloquear();
              }
            }
            ]
          });
    //==========================================      
        }
        ).fail(function(){
          console.log("Error");
    //==========================================
        /*  $("#loader").hide();
          $("#loader").removeClass();
          $("#loader").removeAttr();*/
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
                  $("#frm").dialog("close");
                }
              }
            ]
          });
      });
    }

    function Refrescar(){
  
       // $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
        $('#jqGridVale').jqGrid('setGridParam',{ 
          url: 'get_lista_usr',
          datatype: 'json'
        }).trigger("reloadGrid");

    }


    jQuery.fn.center = function(){
      this.css("position","absolute");
      this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
      this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
    }

  });

</script>