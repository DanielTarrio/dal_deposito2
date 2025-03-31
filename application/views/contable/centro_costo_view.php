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
    </ul>
  </div>   
<div style="margin: auto; width: 82em; height:3em; padding:0.4em">
    <table id="jqGridVale"></table>
    <div id="jqGridValePager"></div>
</div>
</body> 
<script type="text/javascript"> 

    

    var template = "<p><div style='margin-left:15px;'><div>Solo la denominacion es editable</div></div><br>";
        template += "<div> Denominacion: </div><div>{denominacion} </div></p> ";
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


        $("#jqGridVale").jqGrid({
            url: 'lista_centro_costo',
            mtype: "GET",
            datatype: "json",
            editurl:'clientArray',
            page: 1,
            colModel: [
                {
                    label: "Centro Costo",
                    name: 'centro_costo',
                    width: 100,
                    key:true,
                    hidden: false,
                    align:'center',
                    editable: true,
                    editrules : { required: true}
                }, 
                {
                    label: "Denominacion",
                    name: 'denominacion',
                    width: 280,
                    editable: true,
                    editrules : { required: true},
                    // stype defines the search type control - in this case HTML select (dropdownlist)
                    // searchoptions value - name values pairs for the dropdown - they will appear as options
                    searchoptions:{
                    	sopt : ['cn']
                    }
                    
                },                   
            ],
            loadonce: true,
            viewrecords: true,
            width: 450,
            height: 300,
            rowNum: 15,
            caption:"Centro de Costos",
            rownumbers:false,
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
            }
        });
		// activate the toolbar searching
    $('#jqGridVale').jqGrid('filterToolbar');
		$('#jqGridVale').jqGrid('navGrid',"#jqGridValePager", {                
            search: false, // show search button on the toolbar
            add: true,
            edit: true,
            del: false,
            refresh: true
        },
        {
			editCaption: "Editar centro costo",
			width:300,
      height:180,
			template: template,
      url: 'edit_centro_costo' ,
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
      addCaption: "Insertar centro costo",
      width:300,
      height:180,
			//template: template,
      url: 'add_centro_costo' ,
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
	});

    function Refrescar(){
      
    $('#jqGridVale').jqGrid('setGridParam',{ url: 'lista_centro_costo',datatype: 'json' }).trigger("reloadGrid");
    }

    });

</script>
