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
    print_consumo_ctro_cto();
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
      Refrescar();
  });

  $( "#reporte" ).change(function(){
      //alert("ups");
      $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
      Refrescar();
  });

  $( "#year_rpte" ).change(function(){
      //alert("ups");
      $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
      Refrescar();
  });


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

  $("#jqGrid").jqGrid({
    url: 'anual_x_mes',//'<?php echo base_url();?>jsonjqgrid.json',
    // we set the changes to be made at client side using predefined word clientArray
    postData:{
          'id_deposito':$("#id_deposito").val(),
          'reporte':$("#reporte").val(),
          'year_rpte':$("#year_rpte").val()
        },
    editurl: 'ppp.php',
    cellEdit : false,
    cellsubmit : 'remote',
    datatype: "json",
    colModel: [
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
        width: 150,
        editable: true
      },
      {
        label : 'Unidad',
        name: 'unidad',
        align: 'center',
        width: 35,
        editable: false
      },
      {
        label: 'Ene',
        name: '1',
        width: 30,
        align: 'right',
//        formatter: 'number',
        editable: true
      },
      {
        label: 'Feb',
        name: '2',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Mar',
        name: '3',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Abr',
        name: '4',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'May',
        name: '5',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Jun',
        name: '6',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Jul',
        name: '7',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Ago',
        name: '8',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Sep',
        name: '9',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Oct',
        name: '10',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Nov',
        name: '11',
        width: 30,
        align: 'right',
        //formatter: 'number',
        editable: true
      },
      {
        label: 'Dic',
        name: '12',
        width: 30,
        align: 'right',
        //formatter: 'number',
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
    width: 1000,
    height: 500,
    rowNum: 20,
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
    $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
    $('#jqGrid').jqGrid('setGridParam',{ 
      url: 'anual_x_mes',
      postData:
        {
          'id_deposito':$("#id_deposito").val(),
          'reporte':$("#reporte").val(),
          'year_rpte':$("#year_rpte").val()
        },
      datatype: 'json' 
    }).trigger("reloadGrid");

  }

  function download_excel(){

    location.href="<?php echo base_url().'index.php/stock/anual_x_mes_excel';?>"+"?id_deposito="+$("#id_deposito").val()+"&reporte="+$("#reporte").val()+"&year_rpte="+$("#year_rpte").val();

  }


  function print_consumo_ctro_cto(){
    alert('No exite reporte para impresion')
    
   // window.open('<?php echo base_url()?>index.php/stock/print_anual_x_mes_excel?id_deposito='+$("#id_deposito").val()+'&year_rpte='+$("#year_rpte").val(),'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');

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
          <a href="<?php echo base_url().'index.php/stock/rep_consumo_ctro_cto'; ?>">
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
      <div>
        <label class="etiqueta">Deposito: </label>
      </div>
      <select name="id_deposito" id="id_deposito" class="ui-widget" style="width:12em;">
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
      <div>
        <label class="etiqueta">Reporte: </label>
      </div>
      <select name="reporte" id="reporte" class="ui-widget" style="width:16em;">
          <option value="CANTIDAD" Selected >Cantidad Despachada</option>
          <option value="COSTO" >Costos actualizados</option>
          <option value="SOLICITADO" >Mat. Solicitado</option>
          <option value="PORCENTAJE"  >% Solicitado/Despachado</option>
          <option value="AVG_STOCK"  >Promedio Stock</option>
          <option value="MAX_STOCK"  >Maximo Stock</option>
          <option value="MIN_STOCK"  >Minimo Stock</option>
      </select>
    </div>
    <div class="celda">
      <div>
        <label class="etiqueta">Año: </label>
      </div>
      <select name="year_rpte" id="year_rpte" class="ui-widget" style="width:12em;">
          <?php 
            for ($i = 0; $i < count($year); $i++) {
              echo "<option value=\"".$year[$i]['year']."\">".$year[$i]['year']."</option>";
            }
          ?>
      </select>
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
