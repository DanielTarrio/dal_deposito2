<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
    .app{
      padding: 2px;
      background-color: black;
      color: white;
    }

  </style> 
</head>
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
<div class="linea"></div>
  <div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
    <div style="margin: auto; width: 82em; height:3em; padding:0.4em">
    <table id="tree"></table>
    <div id="pager"></div>
  </div>
</div>
<script type="text/javascript"> 
    
  $(function() {

    $('#tree').jqGrid({
      width:'280',
      hoverrows:false,
      viewrecords:false,
      gridview:false,
      url:"<?php echo base_url();?>index.php/sectores/get_sectores",
      editurl:'<?php echo base_url();?>index.php/sectores/edit_sector',
      ExpandColumn:"name",
      height:"200",
      sortname:"id_sector",
      scrollrows:true,
      treeGrid:true,
      treedatatype:"json",
      treeGridModel:"nested",
      loadonce:true,
      rowNum:1000,
      treeReader:{
        left_field:"lft",
        right_field:"rgt",
        level_field:"level",
        leaf_field:"isLeaf",
        expanded_field:"expanded",
        loaded:"loaded",
        icon_field:"icon"
      },
      treeIcons: {
  			plus: "ui-icon-circlesmall-plus",
  			minus: "ui-icon-circlesmall-minus",
  			leaf : "ui-icon-arrow-1-e"
  		},
      datatype:"json",
      colModel:[
        {
        	name:'id_sector',
        	index:'id_sector',
        	sorttype:'int',
        	key:true,
        	hidden:true,
        	width:50
        },{
        	name:'name',
        	index:'name',
        	sorttype:'string',
        	label:'Sector',
        	editable:true,
        	width:170
        },{
        	name:'lft',
        	hidden:true
        },{
        	name:'rgt',
        	hidden:true
        },{
        	name:'level',
        	hidden:true
        },{
        	name:"uiicon",
        	hidden:true
        }
      ],
      "onSelectRow" : function( rowid ) {
        if(rowid) 
        {
        	var rdata = $('#tree').jqGrid('getRowData', rowid);
        	//if(rdata.isLeaf === 'true') {
        	//	$("#<?php //echo $id; ?>").val(rdata.id_ubicacion);
          //  $("#<?php //echo $label; ?>").val(rdata.ub_path);
          //alert(rdata.ruta);
        	//}
        } 
      },
        sortorder:"asc",
        pager:"#pager",
        ondblClickRow:function(rowid,iCol,cellcontent,e) {
        //alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
        var rdata = $('#tree').jqGrid('getRowData', rowid);
        $("#frm_ubicacion").val(rdata.ruta);
        $("#ub_tree").dialog('close');
        //alert();
        }
    });
    jQuery('#tree').jqGrid('navGrid','#pager',
      { edit: true, add: true, del: true, search: false, refresh: false, view: false, position: "left", cloneToTop: false },

      {
        editCaption: "Editar Sector",
        //template: template,
        url: '<?php echo base_url();?>index.php/sectores/edit_sectores' ,
        mtype: "GET",
        closeAfterEdit: true,
        onClose:function(){Refrescar2();},
        reloadAfterSubmit:true,
        errorTextFormat: function (data) {
          return 'Error: ' + data.responseText
        }
      },
      // options for the Add Dialog
      {
        addCaption: "Alta Sector",
        //template: template,
        url: '<?php echo base_url();?>index.php/sectores/add_sectores' ,
        mtype: "GET",
        closeAfterAdd: true,
        reloadAfterSubmit:true,
        onClose:function(){Refrescar2();},
        reloadAfterSubmit:true,
        errorTextFormat: function (data) {
          return 'Error: ' + data.responseText
        }
      },
      // options for the Delete Dailog
      {
        Caption: "Eliminar Sector",
        url: '<?php echo base_url();?>index.php/sectores/del_sectores' ,
        mtype: "GET",
        closeAfterAdd: true,
        reloadAfterSubmit:true,
        errorTextFormat: function (data) {
          return 'Error: ' + data.responseText
        }
      }
    );

  // bind keyss
  $("#tree").jqGrid('bindKeys');
  // hide header
  //$('.ui-jqgrid-htable','.ui-jqgrid-hbox').hide();
  //var record = jQuery("#tree").getInd(rowid,true);
  function Refrescar2(){
    //location.reload();
   /* $('#tree').jqGrid('setGridParam',{ url:'<?php echo base_url();?>index.php/sectores/get_sectores?id_deposito=<?php echo $id_deposito ?>',datatype: 'json' }).trigger("reloadGrid");
   */
    //$("#tree").trigger("reloadTree") ;

//--------------------------------------
  //alert('reload');
  $("#tree").jqGrid("clearGridData", true);
  $('#tree').jqGrid('setGridParam',{ 
    url: '<?php echo base_url();?>index.php/sectores/get_sectores',
    /*postData:
      {
        'id_deposito':
      },*/
    datatype: 'json' 
  });
  $("#tree").trigger("reloadGrid") ;
  $("#tree").trigger("reloadTree") ;


//--------------------------------------



  }


  });
</script>
</body>
</html>