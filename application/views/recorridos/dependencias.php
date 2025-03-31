<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/ecmascript" src="<?php echo base_url();?>js/jquery.jqGrid.min.js"></script>
<!-- This is the localization file of the grid controlling messages, labels, etc. -->
<!-- We support more than 40 localizations -->
<script type="text/ecmascript" src="<?php echo base_url();?>js/i18n/grid.locale-en.js"></script>
<!-- A link to a jQuery UI ThemeRoller theme, more than 22 built-in and many more custom -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>jquery-ui.css" />
<!-- The link to the CSS that the grid needs -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/<?php echo EstiloCss;?>ui.jqgrid.css" />

<div style="margin: auto; width: 82em; height:3em; padding:0.4em">
    <table id="jqGridDependencia"></table>
    <div id="jqGridDependenciaPager"></div>
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


        $("#jqGridDependencia").jqGrid({
            url: 'get_dependencia',
            mtype: "GET",
            datatype: "json",
            editurl:'clientArray',
            page: 1,
            colModel: [
                {
                    label: "id_dependencia",
                    name: 'id_dependencia',
                    width: 100,
                    key:true,
                    hidden: true,
                    align:'center',
                    editable: false,
                    /*editrules : { required: true}*/
                }, 
                {
                    label: "Dependencia",
                    name: 'dependencia',
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
            caption:"Dependencias",
            rownumbers:false,
            rownumWidth:25,
            pager: "#jqGridDependenciaPager",
            onCellSelect: function(rowid,iCol,cellcontent,e) {
              i = $("#jqGridDependencia").getGridParam("reccount");
              //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
              //disponible=parseFloat($("#jqGridDependencia").jqGrid('getCell',rowid,'nro'))+' '+$("#jqGridDependencia").jqGrid('getCell',rowid,'id_deposito');
              //alert(disponible);
             /* nro=$("#jqGridDependencia").jqGrid('getCell',rowid,'nro')
              deposito=$("#jqGridDependencia").jqGrid('getCell',rowid,'id_deposito');
              window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');*/
            }
        });
		// activate the toolbar searching
    $('#jqGridDependencia').jqGrid('filterToolbar');
		$('#jqGridDependencia').jqGrid('navGrid',"#jqGridDependenciaPager", {                
            search: false, // show search button on the toolbar
            add: true,
            edit: true,
            del: false,
            refresh: true
        },
        {
			editCaption: "Editar Dependencia",
			width:300,
      height:180,
			template: template,
      url: 'edit_dependencia' ,
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
      addCaption: "Insertar Dependencia",
      width:300,
      height:180,
			//template: template,
      url: 'add_dependencia' ,
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
      
    $('#jqGridDependencia').jqGrid('setGridParam',{ url: 'lista_centro_costo',datatype: 'json' }).trigger("reloadGrid");
    }

    });

