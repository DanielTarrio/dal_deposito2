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

  $("#buscar").button({
    icons:{primary:"ui-icon-search"}
  });
  $("#buscar").click(function(){
    Refrescar();
  });
  $("#download").click(function(){
    download_excel();
  });
  $("#imprimir").click(function(){
    print_consumo();
  });

  /*$( "#id_deposito" ).selectmenu({
    change:function(event, ui){
      //alert("ups");
      $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
    }
  });*/

  $( "#id_deposito" ).change(function(){
      //alert("ups");
      $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
  });
  
  $("#FechaInicio").datepicker({
    dateFormat:'dd/mm/yy',
    dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
    monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    onClose: function(selectDate){
      $("#FechaFin").datepicker("option",{"minDate":selectDate, "maxDate":"today"});
    },
    minDate:"-6M"
    //minDate: new Date(2016, 6, 25)
  });
  $("#FechaFin").datepicker({
    dateFormat:'dd/mm/yy',
    dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
    monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    //minDate: new Date(2016, 6, 25)
  });

  $("#material").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "<?php echo base_url();?>index.php/salidas/get_stock",
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
        $( "#material" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#id_material" ).val( ui.item.value );
        $( "#barcode" ).val( ui.item.barcode );
        return false;
      }
    });
/*
      $("#id_material").blur(
      function(){
        if(($("#id_material").val()!="")&&($("#id_deposito").val()!="")) {
            $.ajax({
               url: '<?php echo base_url();?>index.php/salidas/get_cod_stock',
               data: {
                id_material : $('#id_material').val(),
                id_deposito : $('#id_deposito').val()
              },
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['id_material'] );
                  $( "#material" ).val( data[0]['descripcion'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
               },
               type: 'GET'
              });
        }else{
          //alert("nada");
        }
        
      });
*/
    $("#barcode").blur(
      function(){
        if(($("#barcode").val()!="")&&($("#id_deposito").val()!="")) {
            $.ajax({
               url: '<?php echo base_url();?>index.php/salidas/get_barcode_stock',
               data: {
                barcode : $('#barcode').val(),
                id_deposito : $('#id_deposito').val()
              },
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['id_material'] );
                  $( "#material" ).val( data[0]['descripcion'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
               },
               type: 'GET'
              });
        }else{
          //alert("nada");
        }
      });


    function print_consumo(){
    
    dir='id_deposito='+$("#id_deposito").val();
    dir+='&FechaInicio='+$("#FechaInicio").val();
    dir+='&FechaFin='+$("#FechaFin").val();
    dir+='&legajo='+$("#legajo").val();
    dir+='&id_material='+$("#id_material").val();
    dir+='&material='+$("#material").val();
    dir+='&barcode='+$("#barcode").val();

    window.open('<?php echo base_url()?>index.php/stock/imp_consumo?'+dir,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');

    }



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

  var template = "<div style='margin-left:15px;'><div>Codigo<sup>*</sup>:</div><div> {id_material} </div>";
  template += "<div> Descripcion: </div><div>{descripcion} </div>";
  template += "<div> Unidad: </div><div>{unidad} </div>";
  template += "<div> Postal Code: </div><div>{PostalCode} </div>";
  template += "<div> City:</div><div> {City} </div>";
  template += "<hr style='width:100%;'/>";
  template += "<div> {sData} {cData}  </div></div>";
/*
 s.retira, p.apellido_nombre, x.deposito,s.fecha, t.tipo, d.nro, d.id_material,m.descripcion,m.unidad,d.cantidad, d.mod_usuario 
*/
  $("#jqGrid").jqGrid({
    url: 'get_consumo',//'<?php echo base_url();?>jsonjqgrid.json',
    // we set the changes to be made at client side using predefined word clientArray
    postData:{
          'id_deposito':$("#id_deposito").val(),
          'FechaInicio':$("#FechaInicio").val(),
          'FechaFin':$("#FechaFin").val()
        },
    editurl: 'ppp.php',
    cellEdit : false,
    cellsubmit : 'remote',
    datatype: "json",
    colModel: [
      {
        label: 'fecha',
        name: 'fecha',
        width: 15,
        align: 'center',
        //formatter: 'integer',
        key: true,
        editable: true
      },
      {
        label: 'Tipo',
        name: 'tipo',
        width: 30,
        editable: true // must set editable to true if you want to make the field editable
      },
      {
        label: 'Nro.',
        name: 'nro',
        width: 20,
        align: 'center',
        editable: true
      },
      {
        label: 'Codigo',
        name: 'barcode',
         align: 'center',
        width: 30,
        editable: true // must set editable to true if you want to make the field editable
      },
      {
        label: 'Material.',
        name: 'descripcion',
        width: 100,
        editable: true
      },
      {
        label : 'Unidad',
        name: 'unidad',
        align: 'center',
        width: 25,
        editable: false
      },
      {
        label: 'Cantidad',
        name: 'cantidad',
        width: 30,
        align: 'right',
        formatter: 'number',
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
    rowNum: 10,
    pager: "#jqGridPager",
    //  onCellSelect: function(rowid,iCol,cellcontent,e) {
    //    alert(cellcontent+' '+rowid);
    //    }
  });
/*
  $('#jqGrid').navGrid('#jqGridPager',
  // the buttons to appear on the toolbar of the grid
  {
    edit: false, add: false, del: true, search: false, refresh: true, view: true, position: "left", cloneToTop: false },
  // options for the Edit Dialog
  {
    editCaption: "The Edit Dialog",
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
  */

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
      url: 'get_consumo',
      postData:
        {
          'id_deposito':$("#id_deposito").val(),
          'FechaInicio':$("#FechaInicio").val(),
          'FechaFin':$("#FechaFin").val(),
          'id_material':$("#id_material").val(),
          'material':$("#material").val(),
          'barcode':$("#barcode").val()
        },
      datatype: 'json' 
    }).trigger("reloadGrid");

  }

  function download_excel(){
    location.href="<?php echo base_url().'index.php/stock/excel_consumo';?>"+"?id_deposito="+$("#id_deposito").val()+"&FechaInicio="+$("#FechaInicio").val()+"&FechaFin="+$("#FechaFin").val()+"&id_material="+$("#id_material").val()+"&material="+$("#material").val()+"&barcode="+$("#barcode").val();
  }


    //========================== jqGrid =========================

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
    .ui-icontool-configurar { background-position: -96px 0px; }
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
    .ui-icontool-clave { background-position: -128px -64px; }
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
          <a href="<?php echo base_url().'index.php/stock/rep_consumo'; ?>">
            <span id="grabar" class="ui-icontool ui-icontool-reporte"></span>
          </a>
        </li>
        <li class="ui-state-default ui-corner-all" title="Download Excel">
          <span id="download" class="ui-icontool ui-icontool-excel"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="Imprimir">
            <span id="imprimir" class="ui-icontool ui-icontool-imprimir"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="Menu">
            <a href="<?php echo base_url().'index.php/menu/crear_menu/Reportes'; ?>">
              <span class="ui-icontool ui-icontool-menu"></span>
            </a>
        </li>
      </ul>
  </div>
  <div class="linea">
    <div class="celda_long">
      <label class="etiqueta">Deposito: </label>
      <select name="id_deposito" id="id_deposito" class="ui-widget" style="width:10em;"">
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
    <div class="celda">
      <label class="etiqueta">Codigo: </label>
      <input type="text" id="barcode" name="barcode" value=""  class="ui-widget" style="width:8em;text-align:center;margin:0.4em">
    </div>
    <div class="celda_long">
      <label class="etiqueta">Material: </label>
      <input type="text" id="material" name="material" value=""  class="ui-widget" style="width:25em;margin:0.4em">
    </div>
  </div>
  <div class="linea">
    <div class="celda">
      <label class="etiqueta">Fecha Inicio: </label>
      <input type="text" id="FechaInicio" name="FechaInicio" value=""  class="ui-widget" style="width:8em;text-align:center;margin:0.4em">
    </div>
    <div class="celda">
      <label class="etiqueta">Fecha Fin: </label>
      <input type="text" id="FechaFin" name="FechaFin" value=""  class="ui-widget" style="width:8em;text-align:center;margin:0.4em">
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
</body>
