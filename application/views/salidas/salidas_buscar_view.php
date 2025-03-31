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

    <table id="jqGridVale"></table>
    <div id="jqGridValePager"></div>
    <script type="text/javascript"> 
    
        $(document).ready(function () {
            $("#jqGridVale").jqGrid({
                url: 'salidas/get_salidas',
                mtype: "GET",
                datatype: "json",
                page: 1,
                colModel: [
                    {
                        label: "id_salida",
                        name: 'id_salida',
                        width: 30,
                        hidden: true
                    },
                    {
                        label: "id",
                        name: 'id_deposito',
                        width: 30,
                        hidden: true
                    },
                    {
						label: "Deposito",
                        name: 'deposito',
                        width: 60,
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
                        width: 60,
                        align:'center',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
							sopt : ['eq']
                        }
                    },
                    {
                        label : "Nro Pedido",
                        name: 'nro_pedido',
                        width: 60,
                        align:'center',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['eq']
                        }
                    },
                    {
                        label : "ODT",
                        name: 'odt',
                        width: 60,
                        align:'center',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['eq']
                        }
                    },
                    {
                        label : "Zona",
                        name: 'Zona',
                        width: 100,
                        align:'left',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['cn']
                        }
                    },
                    {
                        label : "Sector",
                        name: 'sector',
                        width: 100,
                        align:'left',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['cn']
                        }
                    },
                    {
                        label : "Apellido y Nombre",
                        name: 'apellido_nombre',
                        width: 100,
                        align:'left',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            sopt : ['cn']
                        }
                    }
                ],
				loadonce: true,
				viewrecords: true,
                multiselect:true,
                width: 830,
                height: 270,
                rowNum: 10,
                pager: "#jqGridValePager",
                onCellSelect: function(rowid,iCol,cellcontent,e) {
                  i = $("#jqGridVale").getGridParam("reccount");
                  //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
                  //disponible=parseFloat($("#jqGridVale").jqGrid('getCell',rowid,'nro'))+' '+$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
                  //alert(disponible);
                  nro_pedido=$("#jqGridVale").jqGrid('getCell',rowid,'nro_pedido')
                  deposito=$("#jqGridVale").jqGrid('getCell',rowid,'id_deposito');
                  bulto=$("#jqGridVale").jqGrid('getCell',rowid,'bultos');
                  /*window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');*/
                },
                ondblClickRow:function(rowid,iCol,cellcontent,e) {
                    i = $("#jqGridVale").getGridParam("reccount");
                    nro=$("#jqGridVale").jqGrid('getCell',rowid,'nro')
                    id_salida=$("#jqGridVale").jqGrid('getCell',rowid,'id_salida');
                    window.open('<?php echo base_url()?>index.php/salidas/vale?id_salida='+id_salida,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
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