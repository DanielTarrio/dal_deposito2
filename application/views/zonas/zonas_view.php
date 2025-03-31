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

</script>
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
    .ui-icontool-proveedor { background-position: -0px -160px; }

    .ui-icontool-salir { background-position: 0px -96px; }
    .ui-icontool-salir2 { background-position: -32px -96px; }
    .ui-icontool-download { background-position: -64px -96px; }
    .ui-icontool-menu { background-position: 0px -128px; }
    .ui-icontool-zona { background-position: -96px -160px; }
    /*.ui-icontool-medida { background-position: -160px -96px; }
    .ui-icontool-prohibido { background-position: -192px -96px; }
    .ui-icontool-doc { background-position: -224px -96px; }*/

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
      <li class="ui-state-default ui-corner-all" title="Zona Nueva">
          <span class="ui-icontool ui-icontool-zona" id="add_zona"></span>
      </li>
    </ul>
  </div>   
<div style="margin: auto; width: 82em; height:3em; padding:0.4em">
    <table id="jqGridVale"></table>
    <div id="jqGridValePager"></div>
</div>
<div id="loader"></div>
<div id="dialogo"></div>
</body> 
<script type="text/javascript"> 

    

    var template = "<div style='margin-left:15px;'><div>id_zona<sup>*</sup>:</div><div> {id_zona} </div>";
        template += "<div> deposito: </div><div>{deposito} </div>";
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

        $("#add_zona").click(function(){
          add_zona();
        });


        $("#jqGridVale").jqGrid({
            url: 'zonas/lista_zonas',
            mtype: "GET",
            datatype: "json",
            editurl:'clientArray',
            page: 1,
            colModel: [
                {
                    label: "id",
                    name: 'id_zona',
                    width: 30,
                    key:true,
                    hidden: false,
                    align:'center',
                    editable: false,
                    editrules : { required: true}
                },
                {
                    label: "id_dependencia",
                    name: 'id_dependencia',
                    width: 30,
                    hidden: true,
                    align:'center',
                    editable: false,
                    editrules : { required: true}
                },
                {
          label: "Zona",
                    name: 'Zona',
                    width: 150,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                      sopt : ['cn']
                    }
                    
                },
                {
          label: "Direccion",
                    name: 'Direccion',
                    width: 150,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                      sopt : ['cn']
                    }
                    
                },
                {
          label: "dependencia",
                    name: 'dependencia',
                    width: 150,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                      sopt : ['cn']
                    }
                    
                },        
                {
          label: "Localidad",
                    name: 'Localidad',
                    width: 100,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                      sopt : ['cn']
                    }
                    
                },
                {
          label: "CP",
                    name: 'CP',
                    width: 40,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                      sopt : ['cn']
                    }
                    
                },
                {
          label: "activa",
                    name: 'activa',
                    width: 40,
                    align:'center',
                    editable: true,
                    editrules : { required: true},
                    edittype:"select",
                    editoptions:{value: "Si:Si;No:No"},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    stype: "select",
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions: { value: ":[Todos];Si:Si;No:No" }
                }           
            ],
      loadonce: true,
      viewrecords: true,
            width: 750,
            height: 400,
            rowNum: 15,
            caption:"Zonas",
            rownumbers:false,
            rownumWidth:25,
            pager: "#jqGridValePager",
            onCellSelect: function(rowid,iCol,cellcontent,e) {
              i = $("#jqGridVale").getGridParam("reccount");
            },
            ondblClickRow:function(rowid,iCol,cellcontent,e) {
              //alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
              edit_zona(rowid);
            }
        });
    // activate the toolbar searching
    $('#jqGridVale').jqGrid('filterToolbar');
    /*
    $('#jqGridVale').jqGrid('navGrid',"#jqGridValePager", {                
            search: false, // show search button on the toolbar
            add: true,
            edit: true,
            del: false,
            refresh: true
        },
        {
      editCaption: "Editar Zonas",
      width:500,
      //template: template,
      url: 'zonas/edit_zona' ,
      mtype: "GET",
      bSubmit:"Guardar",
      closeAfterEdit:true,
      reloadAfterSubmit:true,
      errorTextFormat: function (data) {
        return 'Error: ' + data.responseText
      },
      onClose: function(){
          Refrescar();
      }
    },
    // options for the Add Dialog
    {
      addCaption: "Insertar Zonas",
      //template: template,
      url: 'zonas/add_zona' ,
      mtype: "GET",
      //closeAfterEdit:true,
      closeAfterAdd:true,
      reloadAfterSubmit:true,
      recreateForm:true,
      errorTextFormat: function (data) {
          return 'Error: ' + data.responseText
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
  });*/

    function edit_zona(rowid){
      
        $("#dialogo").hide();
        $("#dialogo").load("<?php echo base_url();?>index.php/zonas/frm_zona?id_zona="+rowid);
        $("#dialogo").dialog({
          modal: true,
          height: 425,
          width: 750,
          resizable: true,
          title: "Editar Zona: "+rowid,
          buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                if($("#frm").val()=="OK"){
                  if(check_zona()=="OK"){
                    update_zona();
                  }
                }else{
                  $(this).dialog("close");
                }
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
        $("#dialogo").dialog('open');
    }

    function add_zona(){
      
        $("#dialogo").hide();
        $("#dialogo").load("<?php echo base_url();?>index.php/zonas/frm_zona");
        $("#dialogo").dialog({
          modal: true,
          height: 425,
          width: 750,
          resizable: true,
          title: "Nueva Zona",
          buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                if($("#frm").val()=="OK"){
                  if(check_zona()=="OK"){
                    new_zona();
                  }
                }else{
                  $(this).dialog("close");
                }
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
        $("#dialogo").dialog('open');
    }

    function Refrescar(){
      
    $('#jqGridVale').jqGrid('setGridParam',{ url: 'zonas/lista_zonas',datatype: 'json' }).trigger("reloadGrid");
    }

    function check_zona(){
      msg="";
      
      if($("#frm_zona").val().length==0||$("#frm_zona").val()==null){
        ErrorClass("#frm_zona");
        msg=msg+"<br>- Debe especificar un <b>Deposito</b>"; 

      }
      if($("#frm_CP").val().length==0||$("#frm_CP").val()==null){
        ErrorClass("#frm_CP");
        msg=msg+"<br>- Debe especificar un <b>Deposito</b>"; 

      }
      if($("#frm_Localidad").val()==""){
        ErrorClass("#frm_Localidad");
        msg=msg+"<br>- Debe especificar el <b>Tipo de Salida</b>";
      }
      if($("#frm_dirección").val()==""){
        ErrorClass("#frm_dirección");
        //ErrorClass("#denominacion");
        msg=msg+"<br>- Debe especificar un <b>Centro de Costo</b>";
      }

      if(msg==""){
        msg="OK";
      }
      return msg;
    }

  function new_zona(){
    
    if($("#frm_activa").is(':checked')){
      $("#frm_activa").val('1');
    }else{
      $("#frm_activa").val('0');
    }

    var data = {
        frm_zona:$("#frm_zona").val(),
        frm_direccion:$("#frm_direccion").val(),
        frm_Localidad:$("#frm_Localidad").val(),
        frm_CP:$("#frm_CP").val(),
        frm_activa:$("#frm_activa").val(),
        frm_id_dependencia:$("#frm_id_dependencia").val()
      }

    //---------------------------------------------
    $.post('zonas/add_zona',
      data,
      function(returnedData){

        console.log(returnedData);

        var titulo="Alta de Zona";
        if(returnedData['estatus']!="ERROR"){
          var msg="Se dio el alta a la zona " + returnedData['Zona'] +"<br>Direccion: "+returnedData['Direccion'] +"<br>Localidad: "+returnedData['Localidad']+"<br>CP: "+returnedData['CP'];
          var contexto="info";
        }else{
          var msg="Se dio el alta a la zona";
          var contexto="error";
        }
        info(titulo,msg,contexto,500);
        Refrescar();
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

  function update_zona(){

    if($("#frm_activa").is(':checked')){
      $("#frm_activa").val('1');
    }else{
      $("#frm_activa").val('0');
    }

    var data = {
        frm_id_zona:$("#frm_id_zona").val(),
        frm_zona:$("#frm_zona").val(),
        frm_direccion:$("#frm_direccion").val(),
        frm_Localidad:$("#frm_Localidad").val(),
        frm_CP:$("#frm_CP").val(),
        frm_activa:$("#frm_activa").val(),
        frm_id_dependencia:$("#frm_id_dependencia").val()
      }

    //---------------------------------------------
    $.post('zonas/edit_zona',
      data,
      function(returnedData){

        console.log(returnedData);

        var titulo="Editar de Zona";
        if(returnedData['estatus']!="ERROR"){
          var msg="Se guardaron los cambios de la zona " + returnedData['Zona'] +"<br>Direccion: "+returnedData['Direccion'] +"<br>Localidad: "+returnedData['Localidad']+"<br>CP: "+returnedData['CP'];
          var contexto="info";
        }else{
          var msg="Error: no se grabaron los cambios<br>Consulte con el administrador";
          var contexto="error";
        }
        info(titulo,msg,contexto,500);
        Refrescar();
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

  function info(titulo,msg,contexto,dimension){

    if(contexto=="error"){
      var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
    }
    if(contexto=="info"){
      var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-info\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
    }
    if(contexto==""){
      var msg="<p class=\"ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-info\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
    }
    if(dimension<300){
      var dimX=300;
      var dimY=200;
    }else{
      var dimX=dimension;
      var dimY=(dimension*2)/3;
    }
    $("#dialogo").removeClass();
    $("#dialogo").removeAttr();
    $("#dialogo").html(msg);
    $("#dialogo").dialog({
      modal: true,
      height: dimY,
      width: dimX,
      resizable: true,
      title: titulo,
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
  }
  
//====================================================

});

</script>