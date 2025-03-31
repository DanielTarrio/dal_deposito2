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

		$( "#id_deposito" ).change(function(){
				Refrescar();
		});

		$("#mat_vista","#ub_tree").dialog('close');

		$("#refresh").button({
			icons: {
			primary: "ui-icon-refresh"
			},
			text: false
		});
		$("#refresh").click(function(){
			Refrescar();
		});

		$("#limpiar").button({
			icons: {
			primary: "ui-icon-close"
			},
			text: false
		});
		$("#limpiar").click(function(){
			Limpiar_campos();
		});

		$("#clase").autocomplete({
	      source: "get_clase", // path to the get_birds method
	        minLength: 0,
	      focus: function( event, ui ) {
	        $( "#clase" ).val( ui.item.label);
	        return false;
	      },
	      select: function( event, ui ) {
	        $( "#id_clase" ).val( ui.item.value );
	        $("#id_sub_clase").val('');
	        $("#sub_clase").val('');
	        return false;
	      }
	    });
	    $("#sub_clase").autocomplete({
			source: function(request, response) {
			$.ajax({
	        	url: "get_sub_clase",
	             dataType: "json",
	        	data: {
	          	term : request.term,
	          //id_deposito : $('#id_deposito').val(),
	          	id_clase : $('#id_clase').val()
	        	},
		        success: function(data) {
		          response(data);
		        }
	    	});
	    	},
		    minLength: 0,
				focus: function( event, ui ) {
					$( "#sub_clase" ).val( ui.item.label);
					return false;
				},
				select: function( event, ui ) {
					$( "#id_sub_clase" ).val( ui.item.value );
					return false;
				}
	    });

		/*
		$( "#id_deposito" ).selectmenu({
			change:function(event, ui){
				Refrescar();
			}
		});
		*/
		


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

//-------------------------------------------------------------		

		$("#jqGrid").jqGrid({
			url: '<?php echo base_url();?>index.php/stock/get_stock_deposito',//'<?php echo base_url();?>jsonjqgrid.json',
			// we set the changes to be made at client side using predefined word clientArray
			postData:{
				'id_deposito':$("#id_deposito").val()
			},
			editurl: 'ppp.php',
			cellEdit : false,
			cellsubmit : 'remote',
			datatype: "json",
			colModel: [
				{
					label: 'Clase',
					name: 'clase',
					align: 'center',
					width: 30,
					editable: true // must set editable to true if you want to make the field editable
				},
				{
					label: 'Sub Clase',
					name: 'sub_clase',
					width: 30,
					editable: true
				},
				{
					label: 'Cod.',
					name: 'id_material',
					width: 15,
					align: 'center',
					//formatter: 'integer',
					key: true,
					editable: true
				},
				{
					label: 'Material',
					name: 'descripcion',
					width: 150,
					editable: true // must set editable to true if you want to make the field editable
				},
				{
					label: 'Unidad',
					name: 'unidad',
					width: 20,
					editable: true // must set editable to true if you want to make the field editable
				},
				{
					label: 'Cant.',
					name: 'cantidad',
					align: 'right',
					width: 20,
					editable: true
				},
				{
					label: 'Min.',
					name: 'minimo',
					align: 'right',
					width: 20,
					editable: true
				},
				{
					label: 'Rep.',
					name: 'reposicion',
					align: 'right',
					width: 20,
					editable: true
				},
				{
					label : 'Ubicacion',
					name: 'ubicacion',
					width: 25,
					editable: false
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
			width: 850,
			height: 300,
			rowNum: 10,
			pager: "#jqGridPager",
			//  onCellSelect: function(rowid,iCol,cellcontent,e) {
			//    alert(cellcontent+' '+rowid);
			//    }
			ondblClickRow:function(rowid,iCol,cellcontent,e) {
				//alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
				edit_material_dep(rowid);
				//alert(rowid);
			}
		});
		
		$('#jqGrid').navGrid('#jqGridPager',
		// the buttons to appear on the toolbar of the grid
		{
		edit: false, add: false, del: true, search: false, refresh: true, view: true, position: "left", cloneToTop: false },
		// options for the Edit Dialog
		{
		editCaption: "The Edit Dialog",
		//template: template,
		errorTextFormat: function (data)
		{
		return 'Error: ' + data.responseText
		}
		},
		// options for the Add Dialog
		{
		//template: template,
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

//-------------------------------------------------------------
		
		function Refrescar(){
  
		   // $("#jqGrid").jqGrid("clearGridData", true).trigger("reloadGrid");
		    $('#jqGrid').jqGrid('setGridParam',{ 
		      url: '<?php echo base_url();?>index.php/stock/get_material_deposito',
		      postData:
		        {
		          'id_deposito':$("#id_deposito").val(),
		          'id_clase':$("#id_clase").val(),
		          'id_sub_clase':$("#id_sub_clase").val(),
		          'id_material':$("#id_material").val(),
		          'material':$("#material").val(),
		          'barcode':$("#barcode").val()
		        },
		      datatype: 'json' 
		    }).trigger("reloadGrid");

		}
		function Limpiar_campos(){
			$( "#id_clase" ).val('');
			$( "#id_sub_clase" ).val('');
			$( "#clase" ).val('');
			$( "#sub_clase" ).val('');
			$( "#id_material" ).val('');
			$( "#material" ).val('');
			$( "#barcode" ).val('');
			$( "#id_clase" ).focus();
		}



		function edit_material_dep(rowid){
			$("#mat_vista").hide();
			$("#mat_vista").load("<?php echo base_url();?>index.php/material/max_min?id_material="+rowid+"&id_deposito="+$("#id_deposito").val());
			$("#mat_vista").dialog({
				modal: false,
				height: 400,
				width: 700,
				resizable: true,
				title: "Material",
				buttons:[
					{
						text:"Aceptar",
						icons:{
						primary:"ui-icon-circle-check"
					},
						click: function(){
							if($("#frm").val()=="OK"){
								alta_material();
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
			$("#mat_vista").dialog('open');
		}

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
	<div>
		<ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
			<li class="ui-state-default ui-corner-all" title="Ingreso de Material">
				<a href="<?php echo base_url().'index.php/entradas'; ?>">
					<span class="ui-icontool ui-icontool-entrada"></span>
				</a>
			</li>
			<li class="ui-state-default ui-corner-all" title="Buscar">
				<span id="Buscar" class="ui-icontool ui-icontool-buscar"></span>
			</li>
			<li class="ui-state-default ui-corner-all" title="Grabar">
				<span id="grabar" class="ui-icontool ui-icontool-grabar2"></span>
			</li>
			<li class="ui-state-default ui-corner-all" title="Alta Catalogo">
				<span id="catalogo" class="ui-icontool ui-icontool-catalogo"></span>
			</li>
			<li class="ui-state-default ui-corner-all" title="Download">
				<a href="<?php echo base_url().'index.php/menu/crear_menu/materiales'; ?>">
					<span class="ui-icontool ui-icontool-menu"></span>
				</a>
			</li>
		</ul>
	</div>
	<div class="linea"></div>
	<div class="linea">
		<div class="celda_long">
	      <label class="etiqueta">Deposito: </label>
	    	<select name="id_deposito" id="id_deposito" class="ui-widget ui-widget-content ui-corner-all">
	        	<option value="" Selected >----</option>
		        	<?php 
		        		for ($i = 0; $i < count($deposito); $i++) {
		        		  echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
		            }
		        	?>
	    	</select>
	    </div>
	</div>
	<div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
		<div class="ui-state-default ui-corner-all" style="margin: auto; width: 860px; height:3em; padding:3px">
			<div class="celda_body">
				<label>Clase: </label>
				<input id="clase" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Clase">
				<input type="hidden" id="id_clase" name="id_clase">
			</div>
			<div class="celda_body">
				<label>Sub Clase: </label>
				<input id="sub_clase" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Sub Clase">
				<input type="hidden" id="id_sub_clase" name="id_clase">
			</div>
			<div class="celda_body">
				<label>Código: </label>
				<input id="id_material" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Código">
			</div>
			<div class="celda_body_long">
				<label>Descripción: </label>
				<input id="material" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;" placeholder="Descripcion material">
			</div>
			<div class="celda_body">
				<label>Barcode: </label>
				<input type="text" id="barcode" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Barcode">
			</div>
			<div style="float: right;">
				<button id="refresh">Actualizar</button>
				<button id="limpiar">limpiar</button>
			</div>
		</div>
	</div>
	<div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
		<div style="margin: auto; width: 850px; height:3em; padding:0.4em">
			<table id="jqGrid"></table>
			<div id="jqGridPager"></div>
		</div>
	</div>
	<div id="ub_tree">
	</div>
	<div id="mat_vista">
	</div>
	<div id="loader"></div>
	<div id="dialogo"></div>	
</body>