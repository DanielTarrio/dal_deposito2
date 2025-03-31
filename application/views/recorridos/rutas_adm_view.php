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

  var mydata = new Array();

  <?php

    $tmp="";
    $tmp2="";
    for ($i = 0; $i < count($deposito); $i++) {
      if ($i==0){
        $tmp.="'".$deposito[$i]['label']."'";
        $tmp2.="'".$deposito[$i]['value']."'";
      }else{
        $tmp.=",'".$deposito[$i]['label']."'";
        $tmp2.=",'".$deposito[$i]['value']."'";
      }
    }
    echo "var DepLabel=[".$tmp."];";
    echo "var DepValue=[".$tmp2."];";
  ?>

  $("#NewDependencia").click(function(){
    edit_recorrido('');
  });

  $("#NuevaRuta").click(function(){
    NuevaRuta('');
  });

  $("#AltaDep").click(function(){
    AltaDependencia();
  });
  
  
  $( "#id_deposito" ).change(function(){
      //alert("ups");
      $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
  });

  $("#ruta").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "<?php echo base_url();?>index.php/recorridos/get_ruta",
                 dataType: "json",
              data: {
                term : request.term,
                id_deposito : $('#id_deposito').val()
              },
              success: function(data) {
                response(data);
              }
        });
      },
      minLength: 3,
      focus: function( event, ui ) {
        $( "#ruta" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#id_ruta" ).val( ui.item.value );
        Refrescar();
        return false;
      }
    });

//------------------------------------------------


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

//========================== jqGrid ==========================

/*
m.id_movimiento,t.movimiento, t.tipo,m.id_salida,s.odt,s.destino,s.fecha as f_salida,m.id_detalle_salida,m.id_deposito,z.deposito,m.id_entrada,e.fecha as f_entrada,
e.id_proveedor,e.id_compra,c.compra,e.remito,m.id_detalle_entrada,m.centro_costo,m.nro,
m.id_material,x.descripcion,x.unidad,m.cantidad,m.mod_usuario,m.ult_mod,m.id_tipo_mov,s.retira

*/

  //========================== jqGrid ==========================

  var template = "";//"<div style='margin-left:15px;'><div>id_recorrido<sup>*</sup>:</div><div> {id_recorrido} </div>";
  template += "<div> id_ruta: </div><div>{id_ruta}</div>";
  template += "<div> Dependencia: </div><div>{dependencia} </div>";
  //template += "<div> Dependencia: </div><div>{dependencia} </div>";
  template += "<hr style='width:100%;'/>";
  template += "<div> {sData} {cData}  </div></div>";
/*
 s.retira, p.apellido_nombre, x.deposito,s.fecha, t.tipo, d.nro, d.id_material,m.descripcion,m.unidad,d.cantidad, d.mod_usuario 
*/
  $("#jqGrid").jqGrid({
    url: 'get_recorrido',//'<?php echo base_url();?>jsonjqgrid.json',
    // we set the changes to be made at client side using predefined word clientArray
    postData:{
          'id_ruta':$("#id_ruta").val()
        },
    editurl: 'ppp.php',
    cellEdit : false,
    cellsubmit : 'remote',
    datatype: "json",
    colModel: [
      {
        label: 'id_recorrido',
        name: 'id_recorrido',
        width: 15,
        key: true,
        hidden:true,
        editable: false
      },
      {
        label: 'id_ruta',
        name: 'id_ruta',
        width: 10,
        hidden:true,
        editable: true,
        editoptions:{readonly:"readonly"}
      },
      {
        label: 'id_dependencia',
        name: 'id_dependencia',
        align: 'center',
        width: 30,
        hidden:true,
        editable: true // must set editable to true if you want to make the field editable
      },
      {
        label: 'Dependencia',
        name: 'dependencia',
        width: 50,
        editable: true
      }
    ],
    //sortname: 'id_material',
    formatter : {
      integer : {thousandsSeparator: "", defaultValue: '0'},
      number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'}
    },
    //formatter: {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0'},
    //sortorder : 'asc',
    loadonce: true,
    viewrecords: true,
    width: 780,
    height: 300,
    //rowNum: 10,
    caption: "Recorrido",
    pager: "#jqGridPager",
    onCellSelect: function(rowid,iCol,cellcontent,e) {
      //alert(cellcontent+' '+rowid);
    }/*,
    ondblClickRow:function(rowid,iCol,cellcontent,e) {
    alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
    edit_recorrido(rowid);
    //alert(rowid);
    }*/
  });

  $('#jqGrid').navGrid('#jqGridPager',
  // the buttons to appear on the toolbar of the grid
  {
    edit: true, add: true, del: true, search: false, refresh: false, view: false, position: "left", cloneToTop: false },
  // options for the Edit Dialog
  {
    editCaption: "Editar Dependencia",
    template: template,
    errorTextFormat: function (data)
    {
      return 'Error: ' + data.responseText
    }
  },
  // options for the Add Dialog
  {
    template: template,
    errorTextFormat: function (data)
    {
      return 'Error: ' + data.responseText
    }
  },
  // options for the Delete Dailog
  {
    errorTextFormat: function (data)
    {
      return 'Error: ' + data.responseText
    }
  });
  

  /*
  $('#jqGrid').jqGrid('setGridParam', {
  gridComplete: function(){
  //alert('gridComplete');
  }
  loadComplete: function () {
  $("#jqGrid").setGridParam({datatype:'local'});
  }
  onPaging : function(which_button) {
  $("#jqGrid").setGridParam({datatype:'json'});
  }
  });

*/

  function Refrescar(){

    //alert($("#id_deposito").val());
    //$("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
    $('#jqGrid').jqGrid('setGridParam',{ 
      url: 'get_recorrido',
      postData:
        {
          'id_ruta':$("#id_ruta").val()
        },
      datatype: 'json' 
    }).trigger("reloadGrid");

  }

  function download_excel(){
    location.href="<?php echo base_url().'index.php/stock/excel_consumo';?>"+"?id_deposito="+$("#id_deposito").val()+"&FechaInicio="+$("#FechaInicio").val()+"&FechaFin="+$("#FechaFin").val()+"&id_material="+$("#id_material").val()+"&material="+$("#material").val()+"&barcode="+$("#barcode").val();
  }

  function edit_recorrido(rowid){

    var titulo="";
    if(rowid!=""){
      titulo="Editar Dependencia ruta "+$("#ruta").val();
    }else{
      titulo="Alta Dependencia ruta "+$("#ruta").val();
    }
    $("#frm").hide();
    $("#frm").load("<?php echo base_url();?>index.php/recorridos/edit_recorrido?id_recorrido="+rowid);
    $("#frm").dialog({
      modal: true,
      height: 400,
      width: 550,
      resizable: true,
      title: titulo,
      buttons:[
        {
          text:"Aplicar",
          icons:{
          primary:"ui-icon-circle-check"
        },
        click: function(){
          UpdateRecorrido();
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
          }
        }
      ]
    });
    $("#frm").dialog('open');
  }

  function UpdateRecorrido(){
      alert('UpdateRecorrido');
      $.post("asignar_dependencia",{
          ruta:$("#frm_alta_ruta").val(),
          id_deposito:$("#id_deposito").val(),
          id_ruta:$("#id_ruta").val(),
          id_dependencia:$("#frm_id_dependencia").val()
        },
  //========================
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================

          if(returnedData['id_ruta']!="ERROR"){
            var error=false;
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la ruta Nro. <bolder>"+returnedData['id_ruta']+"</bolder>\"</p>"
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

  //========================    

  }

  function NuevaRuta(){

    if($("#id_deposito").val()!=""){
      for(var i=0; i<DepValue.length; i++){
        if(DepValue[i]==$("#id_deposito").val()){
          var TituloTxt="Nueva Ruta para Deposito: "+DepLabel[i];
        }
      }

      var x='<div><div><label>Ruta:</label></div>';
        x+='<div><input type="text" id="frm_alta_ruta" name="ruta" value=""  class="ui-widget" style="width:25em;margin:0.4em"></div>';
        x+='</div>';
      $("#alta_ruta").hide();
      $("#alta_ruta").html(x);
      $("#alta_ruta").dialog({
        modal: true,
        height: 150,
        width: 350,
        resizable: true,
        title: TituloTxt,
        buttons:[
          {
            text:"Aplicar",
            icons:{
            primary:"ui-icon-circle-check"
          },
          click: function(){
            Alta_Ruta();
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
            }
          }
        ]
      });
      $("#alta_ruta").dialog('open');
    }
    }

    function Alta_Ruta(){

      $.post("alta_ruta",{
        ruta:$("#frm_alta_ruta").val(),
        id_deposito:$("#id_deposito").val()
        },
  //========================
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================

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

  //========================

    }

//==================================================================


  function AltaDependencia(){

      var TituloTxt="Nueva Dependencia";
      var x='<div><div><label>Dependencia:</label></div>';
        x+='<div><input type="text" id="frm_alta_dep" name="ruta" value=""  class="ui-widget" style="width:25em;margin:0.4em"></div>';
        x+='</div>';
      $("#alta_ruta").hide();
      $("#alta_ruta").html(x);
      $("#alta_ruta").dialog({
        modal: true,
        height: 150,
        width: 350,
        resizable: true,
        title: TituloTxt,
        buttons:[
          {
            text:"Aplicar",
            icons:{
            primary:"ui-icon-circle-check"
          },
          click: function(){
            Alta_Ruta();
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
            }
          }
        ]
      });
      $("#alta_ruta").dialog('open');
    
    }

    function EnviarNuevaDep(){

      $.post("alta_ruta",{
        ruta:$("#frm_alta_ruta").val(),
        id_deposito:$("#id_deposito").val()
        },
  //========================
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================

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
  
 
//==================================================================    

});
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
    .ui-icontool-zona { background-position: -96px -160px; }
    .ui-icontool-grabar { background-position: -128px 0px; }
    .ui-icontool-grabar2 { background-position: -160px 0px; }
    .ui-icontool-importar { background-position: -192px 0px; }
    .ui-icontool-reporte { background-position: -224px -64px; }

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
    .ui-icontool-proveedor { background-position: -0px -160px; }
    .ui-icontool-medida { background-position: -160px -64px; }
    .ui-icontool-prohibido { background-position: -192px -64px; }
    .ui-icontool-doc { background-position: -224px -64px; }

    .ui-icontool-salir { background-position: 0px -96px; }
    .ui-icontool-salir2 { background-position: -32px -96px; }
    .ui-icontool-download { background-position: -64px -96px; }
    .ui-icontool-menu { background-position: 0px -128px; }
    .ui-icontool-excel { background-position: -32px -160px; }
    /*.ui-icontool-clave { background-position: -128px -96px; }
    .ui-icontool-medida { background-position: -160px -96px; }
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


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
  <div>
      <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
        <li class="ui-state-default ui-corner-all" title="Nuevo">
          <a href="<?php echo base_url().'index.php/recorridos/rutas_adm'; ?>">
            <span id="grabar" class="ui-icontool ui-icontool-reporte"></span>
          </a>
        </li>
        <li class="ui-state-default ui-corner-all" title="Nueva Ruta">
          <span id="NuevaRuta" class="ui-icontool ui-icontool-proveedor"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="Nueva Dependencia">
          <span id="AltaDep" class="ui-icontool ui-icontool-zona"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="Asignar Dependencia">
          <span id="NewDependencia" class="ui-icontool ui-icontool-zona"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="Menu">
            <a href="<?php echo base_url().'index.php/menu/crear_menu/configuracion'; ?>">
              <span class="ui-icontool ui-icontool-menu"></span>
            </a>
        </li>
      </ul>
  </div>
  <div class="linea">
    <div class="celda_long">
      <div>
        <label class="etiqueta">Deposito:</label>
      </div>
      <select name="id_deposito" id="id_deposito" class="ui-widget" style="width:10em;">
          <option value="" Selected >----</option>
          <?php 
            for ($i = 0; $i < count($deposito); $i++) {
              echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
            }
          ?>
      </select>
    </div>
  </div>
  <div class="linea">
    <div class="celda_long">
      <div>
        <label class="etiqueta">Ruta:</label>
      </div>
      <input type="text" id="ruta" name="ruta" value=""  class="ui-widget" style="width:25em;margin:0.4em">
      <input type="hidden" id="id_ruta" name="id_ruta">
    </div>
  </div>
  <div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
     <div style="margin: auto; width: 82em; height:3em; padding:0.4em">
      <table id="jqGrid"></table>
      <div id="jqGridPager"></div>
      <div class="linea">
        <div class="celda_right">
          <button id="buscar">Buscar</button>
        </div>
      </div>
    </div>
  </div>
  <div id="loader"></div>
  <div id="dialogo"></div>
  <div id="frm"></div>
  <div id="alta_ruta"></div>
  <div id="dependencia"></div>
</body>
