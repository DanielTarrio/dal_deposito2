<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
    $(function() {


//-------------------------------------------------------------     

        $("#jqGridVale").jqGrid({
            url: 'solicitudes/get_solicitud',//'<?php echo base_url();?>jsonjqgrid.json',
            // we set the changes to be made at client side using predefined word clientArray
            
            editurl: 'clientArray',
            cellEdit : false,
            cellsubmit : 'remote',
            datatype: "json",
            colModel: [
                {
                    label: 'Imprimir',
                    name: 'print',
                    width: 30,
                    align: 'center',
                    editable: true, // must set editable to true if you want to make the field editable
                    edittype:"checkbox",
                    editoptions: {value:"X:-"}
                },
                {
                    label: 'nro',
                    name: 'nro',
                    align: 'center',
                    width: 40,
                    hidden:false,
                    editable: false // must set editable to true if you want to make the field editable
                },
                {
                    label: 'sector',
                    name: 'sector',
                    align: 'left',
                    width: 150,
                    editable: false
                },
                {
                    label: 'solicitante',
                    name: 'destino',
                    width: 100,
                    hidden:false,
                    editable: false
                },
                {
                    label: 'Zona',
                    name: 'Zona',
                    width: 100,
                    editable: false
                },
                {
                    label: 'Direccion',
                    name: 'Direccion',
                    width: 150,
                    editable: false
                },
                {
                    label: 'bultos',
                    name: 'bultos',
                    width: 30,
                    align: 'center',
                    editable: true // must set editable to true if you want to make the field editable
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
            onSelectRow: editRow,
            viewrecords: true,
            width: 850,
            height: 300,
            rowNum: 15,
            //pager: "#jqGridPager",
            onCellSelect: function(rowid,iCol,cellcontent,e) {
                //alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
                //alert();
            },
            ondblClickRow:function(rowid,iCol,cellcontent,e) {
                alert('cellcontent:' +cellcontent+' rowid: '+rowid+' iCol:'+iCol);
                //edit_catalogo(rowid);

                //alert();
            }
        });
        
        var lastSelection;

        function editRow(id){
            if(id && id !== lastSelection){
                var grid=$("#jqGridVale");
                grid.jqGrid()
                grid.jqGrid('restoreRow',lastSelection);
                grid.jqGrid('editRow',id,{keys:true, focusField:8,url:'solicitudes/edit_bulto_pedido'});
                lastSelection=id;
            }
        }
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
        $("#imp_etiqueta").button({
        label:"Imprimir Etiquetas",
        icons: {
            primary: "ui-icon-print" 
        },
            text: true
        });imp_etiqueta
        $("#imp_etiqueta").click(function(){
            List_imp_etiqueta();
        });

        function List_imp_pedido(){

            var gridData = jQuery("#jqGridVale").getRowData();
            //console.log(gridData);
            var result="";
            var btos="";
            var i=0;
            for(var i=0; i<gridData.length; i++){
                console.log(gridData[i]['print']+" "+gridData[i]['nro'])
              if('X'==gridData[i]['print']){
                if(result==""){
                    console.log(i);
                    result=gridData[i]['nro'];
                }else{
                    result+=","+gridData[i]['nro'];
                }
              }
            }
            if(result!=""){
                console.log(result);
                window.open('solicitudes/pedido_materiales?nros='+result,'Imprimir','');
            }
            

        }

        function List_imp_etiqueta(){

            var gridData = jQuery("#jqGridVale").getRowData();
            //console.log(gridData);
            var result="";
            var btos="";
            var i=0;
            for(var i=0; i<(gridData.length); i++){
              if('X'==gridData[i]['print']){
                if(result==""){
                    result=gridData[i]['nro'];
                    btos=gridData[i]['bultos'];
                }else{
                    result+=","+gridData[i]['nro'];
                    btos+=","+gridData[i]['bultos'];
                }

                console.log(result);
              }
            }
            if(result!=""){
            console.log(result);
                console.log(btos);
                window.open('solicitudes/pedido_etiquetas?nros='+result+'&btos='+btos,'Imprimir','');
            }
        }


    });
</script>

    <div style="margin: auto;">
        <table id="jqGridVale"></table>
        <div id="jqGridPager"></div>
    </div>
    <div style="float: right;margin: 5px;">
        <button id="imp_pedido">Imprimir Pedidos Seleccionados</button>
        <button id="imp_etiqueta">Imprimir Etiquetas</button>
    </div>    
