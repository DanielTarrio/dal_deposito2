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
<style type="text/css">
/*	.ui-jqgrid tr.jqgrow td  { border: 0px none;} */
</style> 
<input type="hidden" id="ub_id" name="ub_id" value="<?php echo $id_deposito ?>">
<?php //$id_deposito=10; ?>
<table id="tree"></table>
<div id="pager"></div>
<script type="text/javascript"> 
    
  $(function() {

    $('#tree').jqGrid({
      width:'280',
      hoverrows:false,
      viewrecords:false,
      gridview:false,
      url:"<?php echo base_url();?>index.php/ubicaciones/get_ubicaciones?id_deposito=<?php echo $id_deposito ?>",
      editurl:'<?php echo base_url();?>index.php/ubicaciones/edit_ubicacion',
      ExpandColumn:"name",
      height:"200",
      sortname:"id_ubicacion",
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
        	name:'id_ubicacion',
        	index:'id_ubicacion',
        	sorttype:'int',
        	key:true,
        	hidden:true,
        	width:50
        },{
        	name:'name',
        	index:'name',
        	sorttype:'string',
        	label:'Name',
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
        	name:'id_deposito',
        	hidden:true
        },{
        	name:'ruta',
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
        editCaption: "Editar Ubicacion",
        //template: template,
        url: '<?php echo base_url();?>index.php/ubicaciones/edit_ubicaciones' ,
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
        addCaption: "Alta Ubicacion",
        //template: template,
        url: '<?php echo base_url();?>index.php/ubicaciones/add_ubicaciones' ,
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
        Caption: "Eliminar Ubicacion",
        url: '<?php echo base_url();?>index.php/ubicaciones/del_ubicaciones' ,
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
   /* $('#tree').jqGrid('setGridParam',{ url:'<?php echo base_url();?>index.php/ubicaciones/get_ubicaciones?id_deposito=<?php echo $id_deposito ?>',datatype: 'json' }).trigger("reloadGrid");
   */
    //$("#tree").trigger("reloadTree") ;

//--------------------------------------
  //alert('reload');
  $("#tree").jqGrid("clearGridData", true);
  $('#tree').jqGrid('setGridParam',{ 
    url: '<?php echo base_url();?>index.php/ubicaciones/get_ubicaciones?id_deposito='+$("#ub_id").val(),
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