<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
    <input type="hidden" name="frm_deposito" id="frm_deposito">
    <input type="hidden" name="frm_nro" id="frm_nro">
    <input type="hidden" name="frm_remito" id="frm_remito">
    <input type="hidden" name="frm_proveedor" id="frm_proveedor">
    <table id="jqGridVale"></table>
    <div id="jqGridValePager"></div>
    <script type="text/javascript"> 
    
        $(document).ready(function () {
            $("#jqGridVale").jqGrid({
                url: 'entradas/get_entradas',
                mtype: "GET",
                datatype: "json",
                page: 1,
                colModel: [
                    {
                        label: "id",
                        name: 'id_deposito',
                        width: 30,
                        hidden: true
                    },
                    {
						label: "Deposito",
                        name: 'deposito',
                        width: 100,
                        // stype defines the search type control - in this case HTML select (dropdownlist)
                        stype: "select",
                        // searchoptions value - name values pairs for the dropdown - they will appear as options
                        searchoptions: { value: ":[All]<?php echo $deposito ?>" }
                    },
                    { 
						label: "fecha",
                        name: 'fecha',
                        align:'center',
                        width: 80,
						sorttype:'date',
						formatter: 'date', 
                        formatoptions: { srcformat: 'd-m-Y', newformat: 'd/m/Y'},
                        reformatAfterEdit : false,
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'fecha_datePicker',
                                    dateFormat: 'dd/mm/yy',
                                    maxDate: '0',
                                    minDate: '-1y',
                                    showOn: 'focus'
                                });
                            },
                            sopt : ['eq']
                        }
                    },                    
                    {
						label : "Nro",
                        name: 'nro',
                        width: 40,
                        align:'center',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
							sopt : ['eq']
                        }
                    },
                    {
                        label: "id_proveedor",
                        name: 'id_proveedor',
                        width: 30,
                        hidden: true
                    },
                    {
                        label : "Proveedor",
                        name: 'proveedor',
                        width: 100,
                        align:'left',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['bw']
                        }
                    },
                    {
                        label : "remito",
                        name: 'remito',
                        width: 60,
                        align:'center',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['eq']
                        }
                    }
                ],
				loadonce: true,
				viewrecords: true,
                width: 650,
                height: 300,
                rowNum: 10,
                pager: "#jqGridValePager",
                onCellSelect: function(rowid,iCol,cellcontent,e) {
                  i = $("#jqGridVale").getGridParam("reccount");
                  //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
                  //disponible=parseFloat($("#jqGridVale").jqGrid('getCell',rowid,'nro'))+' '+$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
                  //alert(disponible);
                  //nro_search=$("#jqGridVale").jqGrid('getCell',rowid,'nro')
                  //dep_search=$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
                  //remito_search=$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
                  $("#frm_deposito").val($("#jqGridVale").jqGrid('getCell',rowid,'id_deposito'));
                  $("#frm_nro").val($("#jqGridVale").jqGrid('getCell',rowid,'nro'));
                  $("#frm_proveedor").val($("#jqGridVale").jqGrid('getCell',rowid,'id_proveedor'));
                  $("#frm_remito").val($("#jqGridVale").jqGrid('getCell',rowid,'remito'));


                  //window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
                }
            });
			// activate the toolbar searching
            $('#jqGridVale').jqGrid('filterToolbar');
			$('#jqGridVale').jqGrid('navGrid',"#jqGridValePager", {                
                search: false, // show search button on the toolbar
                add: false,
                edit: false,
                del: false,
                refresh: true
            });

        });

    </script>