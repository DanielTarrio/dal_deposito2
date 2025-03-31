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

		var del_usr;
		var usr;
		var deposito;

		$( "#id_deposito" ).change(function(){
				deposito=$("#id_deposito option:selected").text();
				Refrescar();
		});

		$('#add_aut').click(function(){
			if($('#id_deposito').val()!=""){
				autorizar();
			}else{
				$("#dialogo").hide();
				var msg="Para autorizar un usuario, primero debe  seleccionar un deposito"
				var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
				$("#dialogo").html(msg);
				$("#dialogo").dialog({
					modal: true,
					height: 200,
					width: 300,
					resizable: true,
					title: "Seleccionar Deposito",
					buttons:[
						{
						text:"Cerrar",
						icons:{
							primary:"ui-icon-circle-close"
						},
						click: function(){
								$(this).dialog("close");
								$(this).dialog('destroy');
	                      		$(this).hide();
							}
						}
					]
				});
				$("#dialogo").dialog('open');
			}
		});

		$("#del_aut").click(function(){
			del_aut_dep();
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

//-------------------------------------------------------------		

		$("#jqGrid").jqGrid({
			//url: '<?php echo base_url();?>index.php/stock/get_stock_deposito',//'<?php echo base_url();?>jsonjqgrid.json',
			// we set the changes to be made at client side using predefined word clientArray
			url:'',
			postData:{
				'id_deposito':$("#id_deposito").val()
			},
			editurl: 'ppp.php',
			cellEdit : false,
			cellsubmit : 'remote',
			datatype: "json",
			colModel: [
				{
					label: 'id_dep_aut',
					name: 'id_dep_aut',
					align: 'center',
					key: true,
					width: 30,
					hidden:true,
					editable: false // must set editable to true if you want to make the field editable
				},
				{
					label: 'usuario',
					name: 'usuario',
					width: 50,
					editable: true // must set editable to true if you want to make the field editable
				},
				{
					label: 'apellido_nombre',
					name: 'apellido_nombre',
					width: 150,
					editable: true // must set editable to true if you want to make the field editable
				}
			],
			//sortname: 'id_material',
			formatter : {
				integer : {thousandsSeparator: "", defaultValue: '0'},
				number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'}
			},
			caption:'Usuarios Autorizados',
			viewrecords: true,
			rownumbers: true,
			rownumWidth:25,
			loadonce: true,
			//onSelectRow: editRow,
			viewrecords: true,
			width: 400,
			height: 350,
			rowNum: 15,
			pager: "#jqGridPager",
			
			onCellSelect: function(rowid,iCol,cellcontent,e) {
				//alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
				del_usr=rowid;
			}
			/*,
			ondblClickRow:function(rowid,iCol,cellcontent,e) {
				//alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
				autorizar();
			}*/
		});
		//$('#jqGrid').jqGrid('filterToolbar');
		/*
		$('#jqGrid').navGrid('#jqGridPager',
		// the buttons to appear on the toolbar of the grid
		{
		edit: false, add: false, del: true, search: false, refresh: true, view: false, position: "left", cloneToTop: false },
		// options for the Edit Dialog
		{
		editCaption: "The Edit Dialog",
		//url:'edit_material',

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
		});*/
		

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

		
		
		var lastSelection;

		function editRow(id){
			if(id && id !== lastSelection){
				var grid=$("#jqGrid");
				grid.jqGrid()
				grid.jqGrid('restoreRow',lastSelection);
				grid.jqGrid('editRow',id,{keys:true, focusField:6,url:'grabar_max_min'});
				lastSelection=id;
			}
		}

		*/
		function autorizar(){

			$("#frm").hide();
			$("#frm").load("frm_aut_deposito");
			$("#frm").dialog({
				modal: true,
				height: 200,
				width: 300,
				resizable: true,
				title: "Autoriza usuario",
				buttons:[
					{
					text:"Autorizar",
					icons:{
						primary:"ui-icon-check"
					},
					click: function(){
						add_aut_dep();
					}
					},
					{
					text:"Cancelar",
					icons:{
						primary:"ui-icon-circle-close"
					},
					click: function(){
							$(this).dialog("close");
							$(this).dialog('destroy');
		                    $(this).hide();
						}
					}
				]
			});
			$("#frm").dialog('open');

		}
		
		
		function Refrescar(){
			//=========================================
			$("#jqGrid").jqGrid("clearGridData", true);
			$('#jqGrid').jqGrid('setGridParam',{ 
		      url: 'get_dep_autorizado',
		      postData:
		        {
		          'id_deposito':$("#id_deposito").val()
		        },
		      datatype: 'json' 
		    }).trigger("reloadGrid");

		}

		function add_aut_dep(){
			
			if(($("#id_deposito").val()!="") && ($("#frm_usuario").val()!="")){
				var data = {
						id_deposito: $("#id_deposito").val(),
						usuario: $("#frm_usuario").val()
					};
				$.post('add_aut_dep',
					data,
					function(returnedData){
					console.log(returnedData);
					msg="Registro "+returnedData['id']+" se autorizó a "+$("#frm_usuario").val()+" "+$('#frm_apellido_nombre').val();
					$("#frm").dialog('close');
					$("#frm").dialog('destroy');
			        $("#frm").hide();
					msg_dlg('informacion','id',msg,150,250);
					Refrescar();

					})
					.fail(function(){
	        		console.log("Error");
	        		$("#frm").dialog('close');
					$("#frm").dialog('destroy');
			        $("#frm").hide();
			        msg="Error";
					msg_dlg('error','ERROR',msg,150,250);
					Refrescar();

	        	});
			}
		}

		function del_aut_dep(){
			usr=$("#jqGrid").getCell(del_usr,2)+' '+$("#jqGrid").getCell(del_usr,3);
			//alert(del_usr+' '+usr);
			msg="Está seguro de revocar los privilegios del deposito <b>"+deposito+"</b> al usuario:<br><b>"+usr+"</b>";
			//$("#jqGrid").jqGrid("getCell", del_usr,1);
			$("#dialogo").hide();
			var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
			$("#dialogo").html(msg);
			$("#dialogo").dialog({
			modal: true,
			height: 200,
			width: 300,
			resizable: true,
			title: "Revocar Autorización Deposito",
			buttons:[
				{
				text:"Aceptar",
				icons:{
					primary:"ui-icon-check"
				},
				click: function(){
						$(this).dialog("close");
						$(this).dialog('destroy');
			      		$(this).hide();
			      		del_usr_exec(del_usr);
					}
				},
				{
				text:"Cerrar",
				icons:{
					primary:"ui-icon-circle-close"
				},
				click: function(){
						$(this).dialog("close");
						$(this).dialog('destroy');
			      		$(this).hide();
					}
				}
			]
			});
			$("#dialogo").dialog('open');
			
		}

		function del_usr_exec(x){

			if(x!=''){

				var data = {
						id_dep_aut: x
					};
				$.post('del_usr_exec',
					data,
					function(returnedData){
					//console.log(returnedData);
					//alert(returnedData['filas']);
					if(returnedData['filas']==1){
						msg="Se revoco los privilegios de <br><b>"+usr+"</b> del deposito:<br><b>"+deposito+"</b>";
					}
					msg_dlg('informacion','Revocar Autorizacion',msg,150,250);
					Refrescar();
					
					})
					.fail(function(){
	        		console.log("Error");
			        msg="Error";
					msg_dlg('error','ERROR',msg,150,250);
					Refrescar();

	        	});
	        }
		}

		function msg_dlg(contexto,titulo,msg,alto,ancho){

			$("#dialogo").hide();
			if(contexto=='error'){
				var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
			}
			if(contexto=='advertencia'){
				var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
			}
			
			if(contexto=='informacion'){
				var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-info\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
			}
			
			$("#dialogo").html(msg);
			$("#dialogo").dialog({
				modal: true,
				height: 200,
				width: 300,
				resizable: true,
				title: "Seleccionar Deposito",
				buttons:[
					{
					text:"Cerrar",
					icons:{
						primary:"ui-icon-circle-close"
					},
					click: function(){
							$(this).dialog("close");
							$(this).dialog('destroy');
                      		$(this).hide();
						}
					}
				]
			});
			$("#dialogo").dialog('open');

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
	<div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
	<div>
		<ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
			<li class="ui-state-default ui-corner-all" title="menu">
				<a href="<?php echo base_url().'index.php/menu/crear_menu/configuracion'; ?>">
					<span class="ui-icontool ui-icontool-menu"></span>
				</a>
			</li>
			<li class="ui-state-default ui-corner-all" title="Autorizar usuario">
  				<span id="add_aut" class="ui-icontool ui-icontool-usuario"></span>
  			</li>
  			<li class="ui-state-default ui-corner-all" title="Revocar autorizacion">
  				<span id="del_aut" class="ui-icontool ui-icontool-eliminar2"></span>
  			</li>
		</ul>
	</div>
	<div class="linea">
		<div class="celda_long">
	      <label class="etiqueta" style="font-size: 120%">Deposito: </label>
	    	<select name="id_deposito" id="id_deposito" class="ui-widget ui-widget-content ui-corner-all" style="font-size: 120%">
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
		<div style="margin: auto; width: 30em; height:3em; padding:0.4em">
			<table id="jqGrid"></table>
			<div id="jqGridPager"></div>
		</div>
	</div>
	<div id="loader"></div>
	<div id="dialogo"></div>
	<div id="frm">
	</div>	
</body>