<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript"> 

$(document).ready(function () {
    $("#jqGridVale").jqGrid({
        url: 'solicitudes/get_solicitud',
        editurl: 'clientArray',
        mtype: "GET",
        datatype: "json",
        page: 1,
        colModel: [
            {
                label : 'Nro',
                name: 'nro',
                width: 80,
                key: true,
                align:'center',
                searchoptions: {
                    // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                    // use it to place a third party control to customize the toolbar
                    sopt : ['eq']
                }
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
            width: 100,
            // stype defines the search type control - in this case HTML select (dropdownlist)
            stype: "select",
            // searchoptions value - name values pairs for the dropdown - they will appear as options
            searchoptions: { value: ":[All]<?php echo $deposito ?>" }
            },
            { 
            label: 'fecha',
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
                label : 'odt',
                name: 'odt',
                width: 100,
                align:'center',
                searchoptions: {
                    // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                    // use it to place a third party control to customize the toolbar
                    sopt : ['cn']
                }
            },
            {
                label : 'sector',
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
                label : 'Zona',
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
                label : 'Legajo',
                name: 'retira',
                width: 70,
                align:'left',
                searchoptions: {
                    // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                    // use it to place a third party control to customize the toolbar
                    sopt : ['cn']
                }
            },
            {
                label : 'Apellido y Nombre',
                name: 'apellido_nombre',
                width: 130,
                align:'left',
                searchoptions: {
                    // dataInit is the client-side event that fires upon initializing the toolbar search field for a column 
                    // use it to place a third party control to customize the toolbar
                    sopt : ['cn']
                }
            },
            {
                label : 'Cump.',
                name: 'cumplimiento',
                width: 50,
                align:'center',
                searchoptions: {
                    // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                    // use it to place a third party control to customize the toolbar
                    sopt : ['cn']
                }
            }
        ],
        loadonce: true,
        viewrecords: true,
        multiselect: true,
        width: 900,
        height: 300,
        rowNum: 10,
        pager: "#jqGridValePager",
        ondblClicRow(rowid,iRow,iCol,e){
            alert(rowid);
        }

    });
    
    // activate the toolbar searching
    
    $('#jqGridVale').jqGrid('filterToolbar');
    $('#jqGridVale').jqGrid('navGrid',"#jqGridValePager", {                
    search: false, // show search button on the toolbar
    add: false,
    edit: true,
    del: false,
    refresh: true
    });


    $("#imp_pedido").button({
        label:"Imprimir Pedidos",
        icons: {
            primary: "ui-icon-print" 
        },
        text: true
    });imp_pedido
    $("#imp_pedido").click(function(){
        List_imp_pedido();
    });

    $("#Edt_pedido").button({
        label:"Editar Pedido",
        icons: {
            primary: "ui-icon-pencil" 
        },
        text: true
    });imp_pedido
    $("#Edt_pedido").click(function(){
        var grid = $('#jqGridVale');
        var selectIDs=grid.getGridParam("selarrrow");
        $("#nro").val(selectIDs[0]);
        $("#lista").dialog("close");
    });


    function List_imp_pedido(){
      
        var grid = $('#jqGridVale');
        var rowKey = grid.getGridParam("selrow");
        if(!rowKey){
            alert("No hay lineas seleccionadas");
        }else{
            var selectIDs = ""
            var selectIDs = grid.getGridParam("selarrrow");
            var result = "";
            for(var i = 0; i < selectIDs.length; i++){
                if(i==0){
                    result +=selectIDs[i];
                }else{
                    result +=","+selectIDs[i];
                }
                //result[i]=selectIDs[i];

            }

            window.open('solicitudes/pedido_materiales?nros='+result,'Imprimir','');

        }
    }
});

</script>
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
<input type="hidden" name="q">
<table id="jqGridVale"></table>
<div id="jqGridValePager"></div>
<div>
    <div style="float: right;margin: 5px;">
        <button id="Edt_pedido">Editar Pedido</button>
    </div>
    <div style="float: right;margin: 5px;">
        <button id="imp_pedido">Imprimir Pedidos Seleccionados</button>
    </div>
</div>
