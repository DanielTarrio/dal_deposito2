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

<?php 

  $tmp="";
  $tmp2="";
  for ($i = 0; $i < count($movimiento); $i++) {

    if ($i==0){
      $tmp.=$movimiento[$i]['value'];
      $tmp2.=$movimiento[$i]['factor'];
    }else{
      $tmp.=",".$movimiento[$i]['value'];
      $tmp2.=",".$movimiento[$i]['factor'];
    }
  }
  echo "var m=[".$tmp."];";
  echo "var f=[".$tmp2."];";

?>


var disponible=0;

  $(function() {
    var mydata = new Array();
    $("#grabar").click(function(){
        console.log('input prop:'+$('input').prop('disabled')+' factor:'+$('#factor').val() );
        if(($('input').prop('disabled')!=true) || ($('#factor').val()==-1)){
          console.log('linea 39 id_salida:'+$("#id_salida").val());
          if($("#id_salida").val()==""){
            msg=ValidarForm();
            console.log(msg);
            if(msg=="OK"){
              Enviar_Data();
            }else{
              //======================
              $("#dialogo").removeClass();
              $("#dialogo").removeAttr();
              var msg="<p  class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>"+msg+"</p>";
                $("#dialogo").html(msg);
                $("#dialogo").dialog({
                  modal: true,
                  height: 300,
                  width: 400,
                  resizable: true,
                  title: "Faltan Datos",
                  buttons:[
                    {
                      text:"Aceptar",
                        icons:{
                          primary:"ui-icon-circle-check"
                        },
                      click: function(){
                        $(this).dialog("close");
                      }
                    }
                  ]
                });
              //======================
            }
          }

        }
    });

    $("#id_deposito").change(function(){
      NormalClass(this);
    });

    $("#id_tipo_mov").change(function(){
      
      for(var i=0; i<m.length; i++){
        if(m[i]==$("#id_tipo_mov").val()){
          $("#factor").val(f[i]);
          NormalClass(this);
        }
      }
      //alert("ups..."+$("#factor").val());
    });
//--------------------------------
    $("#Buscar").click(function(){
      $("#dlgBuscar").hide();
      $("#dlgBuscar").load("<?php echo base_url();?>index.php/Salidas/lista_salidas");
      $("#dlgBuscar").dialog({
          modal: true,
          height: 500,
          width: 850,
          resizable: true,
          title: "Busqueda de Vales",
          buttons:[
            {
              text:"Imprimir Salida",
                icons:{
                  primary:"ui-icon-print"
                },
              click: function(){
                  var tmp=$("#jqGridVale").jqGrid('getGridParam','selarrrow');
                  var z=0;
                  //alert(tmp.length);
                  //================================================
                  //var gridValeData = jQuery("#jqGridVale").getRowData();
                  var gridValeData = $("#jqGridVale").jqGrid('getGridParam','data');
                  //console.log(gridData);
                  for(var i=0; i<tmp.length; i++){
                    z=tmp[i]-1;
                    if(i==0){
                      result=gridValeData[z]['id_salida'];
                    }else{
                      result=result+","+gridValeData[z]['id_salida']; 
                    }
                  }
                  //================================================
                  vale(result);
                  //alert(result+" "+bulto);
              }
            },
            {
              text:"Imprimir Etiqueta",
                icons:{
                  primary:"ui-icon-tag"
                },
              click: function(){
                  //alert($("#jqGridVale").jqGrid('getGridParam','selarrrow'));
                  var tmp=$("#jqGridVale").jqGrid('getGridParam','selarrrow');
                  
                  var z=0;
                  //alert(tmp.length);
                  //================================================
                // var gridValeData = jQuery("#jqGridVale").getRowData();
                  var gridValeData = $("#jqGridVale").jqGrid('getGridParam','data');
                  //console.log(gridData);
                  for(var i=0; i<tmp.length; i++){
                    z=tmp[i]-1;
                    //alert(z);
                    if(i==0){
                      result=gridValeData[z]['id_salida'];
                    }else{
                      result=result+","+gridValeData[z]['id_salida'];
                    }
                  }
                  //================================================
                  imp_etiquetas(result);
                  //alert(result+" "+bulto);
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
      $("#dlgBuscar").dialog('open');

    });

    $("#help").click(function(){
      help_window();
    });

    
//================================

    $("#etiqueta").click(function(){
      if($("#pedido").val()!=""){
        imp_etiquetas($("#id_salida").val());
      }
      
    });

  var spinner=$("#bultos").spinner({change: function(event, ui){},min: 0});
  $("#bultos").on("spinchange",function(event, ui){
      //alert("cambio spiner");
  });

  var spinner=$("#recorrido").spinner({change: function(event, ui){},min: 0});
  $("#recorrido").on("spinchange",function(event, ui){
      //alert("cambio spiner");
  });

//--------------------------------    

    $("#imprimir").click(function(){
      if($.isNumeric($("#nro").val())){
        vale($("#id_salida" ).val());
      }else{
//--------------------------------

        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        msg="Debe existir un vale seleccionado para poder imprimir"
        var msg="<p  class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:4 7px 250px 4;\"></span>"+msg+"</p>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 300,
            width: 400,
            resizable: true,
            title: "Faltan Datos",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                }
              }
            ]
          });


//-------------------------------
      }
    });

    //$( "#id_deposito" ).selectmenu();

    //$( "#id_tipo_mov" ).selectmenu();

    $("#centro_costo").autocomplete({
      source: "contable/get_centro_costo", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#centro_costo" ).val( ui.item.value);
        $( "#denominacion" ).val( ui.item.denominacion);
        $("#chk_centro_costo").val("ok");
        return false;
      },
      select: function( event, ui ) {
        $("#ot").focus();
        NormalClass("#centro_costo");
        NormalClass("#denominacion");
        return false;
      }
    });

    $("#centro_costo").focusin(function(){
      $("#centro_costo").val("");
      $("#denominacion").val("");
      $("#chk_centro_costo").val("");
    });

    $("#denominacion").autocomplete({
      source: "contable/get_centro_costo", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#centro_costo" ).val( ui.item.value);
        $( "#denominacion" ).val( ui.item.denominacion);
        $("#chk_centro_costo").val("ok");
        return false;
      },
      select: function( event, ui ) {
        $("#ot").focus();
        NormalClass("#centro_costo");
        NormalClass("#denominacion");
        return false;
      }
    });

    $("#denominacion").focusin(function(){
      $("#centro_costo").val("");
      $("#denominacion").val("");
      $("#chk_centro_costo").val("");
    });

    $("#sector").blur(function(){
      $("#sector").val($("#sector").val().toUpperCase());
    });


    $("#legajo").autocomplete({
      source: "contable/get_personal", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $("#legajo").val(ui.item.legajo);
        $( "#apellido_nombre" ).val( ui.item.apellido_nombre);
        $("#id_personal").val(ui.item.id_personal);
        return false;
      },
      select: function( event, ui ) {
        $( "#ot" ).focus();
        NormalClass("#legajo");
        NormalClass("#apellido_nombre");
        return false;
      }
    });
    $("#legajo").focusin(function(){
      $("#legajo").val("");
      $("#apellido_nombre").val("");
      $("#id_personal").val("");
    });

    $("#apellido_nombre").autocomplete({
      source: "contable/get_personal", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#apellido_nombre" ).val( ui.item.apellido_nombre);
        $("#legajo").val(ui.item.legajo);
        $("#id_personal").val(ui.item.id_personal);
        return false;
      },
      select: function( event, ui ) {
        $( "#ot" ).focus();
        NormalClass("#apellido_nombre");
        NormalClass("#legajo");
        return false;
      }
    });
    $("#apellido_nombre").focusin(function(){
      $("#legajo").val("");
      $("#apellido_nombre").val("");
      $("#id_personal").val("");
    });

//========================================================

    $("#material").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "salidas/get_stock",
                 dataType: "json",
              data: {
                term : request.term,
                id_deposito : $('#id_deposito').val()
              },
              success: function(data) {
                response(data);
              }
        });
      },
      minLength: 3,
      focus: function( event, ui ) {
        $( "#material" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#id_material" ).val( ui.item.value );
        $( "#unidad" ).val( ui.item.unidad );
        $( "#barcode" ).val( ui.item.barcode );
        $( "#ubicacion" ).val( ui.item.ubicacion );
        var disponible= parseFloat(ui.item.cantidad)-parseFloat(ui.item.pedido);
        $( "#cant_stock" ).val(disponible);
        //$( "#cant_stock" ).val( ui.item.cantidad );
        $( "#id_stock" ).val( ui.item.id_stock );
        $( "#costo_ult" ).val( ui.item.costo_ult );
        $( "#costo_pp" ).val( ui.item.costo_pp );
        return false;
      }
    });
//=========================================================

    $("#pedido").blur(
      function(){
        if($("#id_tipo_mov").val()==""){
          info("Faltan Datos","Debe especificar el tipo de movimiento","info");
        }else{
          trae_pedido();
          $( "#ot" ).focus();
        }
    });

//=========================================================
    function trae_pedido(){
      Limpiar_Form();
      var data = {
        pedido : $('#pedido').val(),
        id_tipo_mov: $('#id_tipo_mov').val()
      }
      $.post('solicitudes/get_pedido',
        data,
        function(returnedData){
          console.log(returnedData);
          if(returnedData[0]['nro']=="ERROR"){
            msg=returnedData[0]['msg']+": "+$("#pedido").val();
            info("Error",msg,"error");
            $("#pedido").val("");
          }else{
            $( "#id_deposito" ).val( returnedData[0]['id_deposito'] );
            $( "#centro_costo" ).val( returnedData[0]['centro_costo'] );
            $( "#denominacion" ).val( returnedData[0]['denominacion'] );
            $( "#legajo" ).val( returnedData[0]['legajo'] );
            $( "#id_pedido" ).val( returnedData[0]['id_pedido'] );
            $( "#id_personal" ).val( returnedData[0]['id_personal'] );
            $( "#legajo" ).val( returnedData[0]['legajo'] );
            $( "#apellido_nombre" ).val( returnedData[0]['apellido_nombre'] );
            $( "#ot" ).val( returnedData[0]['odt'] );
            $( "#obs" ).val( returnedData[0]['obs'] );
            $("#id_tipo_mov").val(returnedData[0]['id_tipo_mov']);
            $("#id_zona").val(returnedData[0]['id_zona']);
            $("#sector").val(returnedData[0]['sector']);
            $("#zona").val(returnedData[0]['zona']);
            $("#localidad").val(returnedData[0]['localidad']);
            $("#direccion").val(returnedData[0]['direccion']);
            var grilla=returnedData[0]['grilla'];
            for(var i=0; i<grilla.length; i++){
              jQuery("#jqGrid").addRowData(i+1, grilla[i]);              
            }
            if(returnedData[0]['devolucion']==true){
              Bloquear_devolucion();
            }
            costo_total();
          }
          //info('titulo',msg,'info');
        //$("#loader").hide();
        
      //==========================================      
        }
      ).fail(function(xhr, textStatus,errorThrown){
        console.log(data);
        console.log(textStatus);
        console.log(xhr.responseText);
        var msg=errorThrown;
        info('titulo',msg,'error');
      });
    };
//=========================================================

    function Limpiar_Form(){

      $("#jqGrid").jqGrid("clearGridData", true);
      $( "#id_deposito" ).val('');
      $( "#centro_costo" ).val('');
      $( "#denominacion" ).val('');
      $( "#legajo" ).val('');
      $( "#id_pedido" ).val('');
      $( "#id_personal" ).val('');
      $( "#legajo" ).val('');
      $( "#apellido_nombre" ).val('');
      $( "#ot" ).val('');
      $( "#obs" ).val('');
      //$("#id_tipo_mov").val('');
      $("#id_zona").val('');
      $("#sector").val('');
      $("#zona").val('');
      $("#localidad").val('');
      $("#direccion").val('');

    }


//=========================================================


    $("#id_material").blur(
      function(){
        if(($("#id_material").val()!="")&&($("#id_deposito").val()!="")) {
            $.ajax({
               url: 'salidas/get_cod_stock',
               data: {
                id_material : $('#id_material').val(),
                id_deposito : $('#id_deposito').val()
              },
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['id_material'] );
                  $( "#material" ).val( data[0]['descripcion'] );
                  $( "#unidad" ).val( data[0]['unidad'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
                  $( "#ubicacion" ).val( data[0]['ubicacion'] );
                  var disponible= parseFloat(data[0]['cantidad'])-parseFloat(data[0]['pedido']);
                  $( "#cant_stock" ).val(disponible);
                  //$( "#cant_stock" ).val( data[0]['cantidad'] );
                  $( "#id_stock" ).val( data[0]['id_stock'] );
                  $( "#costo_ult" ).val( data[0]['costo_ult'] );
                  $( "#costo_pp" ).val( data[0]['costo_pp'] );
               },
               type: 'GET'
              });
        }else{
          //alert("nada");
        }
        
      });

    $("#barcode").blur(
      function(){
        if(($("#barcode").val()!="")&&($("#id_deposito").val()!="")) {
            $.ajax({
               url: 'salidas/get_barcode_stock',
               data: {
                barcode : $('#barcode').val(),
                id_deposito : $('#id_deposito').val()
              },
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['id_material'] );
                  $( "#material" ).val( data[0]['descripcion'] );
                  $( "#unidad" ).val( data[0]['unidad'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
                  $( "#ubicacion" ).val( data[0]['ubicacion'] );
                  var disponible= parseFloat(data[0]['cantidad'])-parseFloat(data[0]['pedido']);
                  $( "#cant_stock" ).val(disponible);
                  //$( "#cant_stock" ).val( data[0]['cantidad'] );
                  $( "#id_stock" ).val( data[0]['id_stock'] );
                  $( "#costo_ult" ).val( data[0]['costo_ult'] );
                  $( "#costo_pp" ).val( data[0]['costo_pp'] );
               },
               type: 'GET'
              });
        }else{
          //alert("nada");
        }
      });
    

    $("#add").button({
      icons: {
        primary: "ui-icon-circle-check"
      },
      text: false
    });
    $("#add").click(function(){
        setData();
    });

    $("#limpiar").button({
      icons: {
        primary: "ui-icon-circle-close"
      },
      text: false
    });
    $("#limpiar").click(function(){
        Limpiar_campos();
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

    $("#suma").click(function(){
      costo_total();
    });

    //========================== jqGrid ==========================

       var template = "<div style='margin-left:15px;'>";
            template += "<div> Codigo: </div><div>{barcode} </div>";
            template += "<div> Material: </div><div>{material} </div>";
            template += "<div> Unidad: </div><div>{unidad} </div>";
            template += "<div> Cantidad: </div><div>{cantidad} </div>";
            template += "<div> Disponible: </div><div>{cant_stock} </div>";
            template += "<div> Costo: </div><div>{costo_ult} </div>";
            template += "<div> Linea: </div><div>{linea} </div>";
            template += "<hr style='width:100%;'/>";
            template += "<div> {sData} {cData}  </div></div>";

    
        $(document).ready(function () {
            $("#jqGrid").jqGrid({
                datatype: "local",
                editurl: 'clientArray',
                data: mydata,
                height: 200,
                width: 850,
                colModel: [
                    { 
                      label: 'Codigo',
                      name: 'barcode',
                      width: 75,
                      key:false,
                      align: 'center',
                      editable: true,
                      editoptions:{size:6,readonly:"readonly"}
                    },
                    {
                      label: 'id_material',
                      name: 'id_material',
                      width: 75,
                      key:false,
                      align: 'center',
                      hidden:true,
                      editable: true,
                      editoptions:{size:6,readonly:"readonly"}
                    },
                    {
                      label: 'Material',
                      name: 'material',
                      width: 400,
                      editable: true,
                      editoptions:{size:50,readonly:"readonly"}
                    },
                    {
                      label: 'Unidad',
                      name: 'unidad',
                      align: 'center',
                      width: 80,
                      editable: true,
                      editoptions:{readonly:"readonly"}
                    },
                    {
                      label: 'Solicitado',
                      name: 'solicitado',
                      align: 'right',
                      width: 100,
                      formatter: 'number',
                      editable: true
                    },
                    {
                      label: 'cant_aut',
                      name: 'cant_aut',
                      align: 'right',
                      width: 100,
                      hidden:true,
                      formatter: 'number',
                      editable: false
                    },
                    {
                      label: 'Cantidad',
                      name: 'cantidad',
                      align: 'right',
                      width: 100,
                      formatter: 'number',
                      editable: true,
                      editrules:{
                        custom:true,
                        custom_func:stock_disponible
                      }
                    },
                    {
                      label: 'Disponible',
                      name: 'cant_stock',
                      align: 'right',
                      width: 100,
                      formatter: 'number',
                      editable: true,
                      editoptions:{readonly:"readonly"},
                      hidden: false
                    },
                    {
                      label: 'Costo',
                      name: 'costo_ult',
                      formatter: 'currency',
                      align:'right',
                      width: 100,
                      editable: true,
                      editoptions:{readonly:"readonly"}
                    },
                    {
                      label: 'costo_pp',
                      name: 'costo_pp',
                      formatter: 'currency',
                      align:'right',
                      width: 100,
                      editable: false,
                      hidden: true,
                      editoptions:{readonly:"readonly"}
                    },
                    {
                      label: '$ Linea',
                      name: 'linea',
                      formatter: 'currency',
                      align:'right',
                      width: 100,
                      editable: true,
                      editoptions:{readonly:"readonly"}
                    },
                    {
                      label: 'id_stock',
                      name: 'id_stock',
                      width: 100,
                      editable: false,
                      hidden: true
                    },
                    {
                      label: 'id_detalle_pedido',
                      name: 'id_detalle_pedido',
                      width: 100,
                      editable: false,
                      hidden: true
                    }
                ],
                 formatter : {
                   integer : {thousandsSeparator: "", defaultValue: '0'},
                   number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'}
                 },
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                caption: "Material a Despachar",pager: "#jqGridPager",
                onCellSelect: function(rowid,iCol,cellcontent,e) {
                  i = $("#jqGrid").getGridParam("reccount");
                  //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
                  disponible=parseFloat($("#jqGrid").jqGrid('getCell',rowid,'cant_stock'));
                  //alert(disponible);
                }
            });
            $('#jqGrid').navGrid('#jqGridPager',
                // the buttons to appear on the toolbar of the grid
                { edit: true, add: false, del: true, search: false, refresh: true, view: true, position: "left", cloneToTop: false },
                // options for the Edit Dialog
                {
                  editCaption: "Editar Linea",
                  width:500,
                  template: template,
                  bSubmit:"Guardar",
                  closeAfterEdit:true,

                  errorTextFormat: function (data) {
                      return 'Error: ' + data.responseText
                }
                },
                // options for the Add Dialog
                {
          template: template,
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    }
                },
                // options for the Delete Dailog
                {
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    }
                });
        });


    //========================== jqGrid =========================

  function setData(){

    if(($( "#id_material" ).val()!="")&&($( "#material" ).val()!="")){
//======================================================
      if(parseFloat($( "#cantidad" ).val())>parseFloat($( "#cant_stock" ).val())){
        msg="La cantidad a despachar es mayor a la existente "+" Existente:"+$("#cant_stock" ).val();
        info("Stock no disponible",msg,"info");
        /*$("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 300,
            width: 400,
            resizable: true,
            title: "Material a Despachar mayor al existente",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                }
              }
            ]
          });*/
      }else{
  //=================verificar por negativo======================================
        if(parseFloat($( "#cantidad" ).val())<0){
        msg="Ingreso un valor negativo, esta seguro de ingresar un ajuste de material";
        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 300,
            width: 400,
            resizable: true,
            title: "Ajuste de Material",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                  inserta_linea();
                }
              },{
                text:"Cancelar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                  Limpiar_campos();
                }
              }
            ]
          });
        }else{
          inserta_linea();
        }
  //verificar por negativo
/*
        var myfirstrow = {id_material:$( "#id_material" ).val(),material:$("#material").val(), unidad:$( "#unidad" ).val(), cantidad:$( "#cantidad" ).val(), ubicacion:$( "#ubicacion" ).val(), id_stock:$( "#id_stock" ).val(), cant_stock:$( "#cant_stock" ).val()};
        i = $("#jqGrid").getGridParam("reccount");
        i++;
        jQuery("#jqGrid").addRowData(i, myfirstrow);
        Limpiar_campos();*/
      }
    }
  }

  function inserta_linea(){
    if(check_grid($( "#id_material" ).val())=="ok"){
      var myfirstrow = {id_material:$( "#id_material" ).val(),barcode:$( "#barcode" ).val(),material:$("#material").val(), unidad:$( "#unidad" ).val(), solicitado:$( "#solicitado" ).val(), cantidad:$( "#cantidad" ).val(), costo_ult:$( "#costo_ult" ).val(),costo_pp:$( "#costo_pp" ).val(), id_stock:$( "#id_stock" ).val(), cant_stock:$( "#cant_stock" ).val(),linea:($( "#costo_ult" ).val()*$( "#cantidad" ).val())};
      i = $("#jqGrid").getGridParam("reccount");
      i++;
      jQuery("#jqGrid").addRowData(i, myfirstrow);
      //Limpiar_campos();
      //costo_total();
    }
    Limpiar_campos();
    costo_total();
  
  }

  $("#total").click(function(){costo_total()});

  function costo_total(){
    var gridData = jQuery("#jqGrid").getRowData();
    var costo=0
    for(var i=0; i<gridData.length; i++){
      gridData[i]['linea']=parseFloat(gridData[i]['cantidad'])*parseFloat(gridData[i]['costo_ult']);
      //alert("cantidad:"+gridData[i]['cantidad']+" costo:"+gridData[i]['costo_ult']);
      costo=parseFloat(costo)+parseFloat(gridData[i]['linea']);
    }
    $("#total").val(costo);
    
    $("#jqGrid").jqGrid("clearGridData", true);
    for(var i=0; i<gridData.length; i++){
      jQuery("#jqGrid").addRowData(i+1, gridData[i]);  
    }
    
  }

  function stock_disponible(value, colname){
    
    if(parseFloat(value)<0){
      var negativo = confirm("Esta introduciendo un valor negativo \n Desea continuar?");
      if(negativo==true){
        return [true];
      }else{
        return[false,"Se cancelo la accion"];
      }
    }else{
      if(disponible<parseFloat(value)){
        return [false,"La cantidad a despachar "+value+" supera a la disponible, maximo a despachar "+disponible];
      }else{
        return [true];
      }
    }
  }

  function Limpiar_campos(){
    $( "#id_material" ).val('');
    $( "#material" ).val('');
    $( "#unidad" ).val('');
    $( "#barcode" ).val('');
    $( "#cantidad" ).val('');
    $( "#cant_stock" ).val('');
    $( "#ubicacion" ).val('');
    $( "#id_stock" ).val('');
    $( "#costo_ult" ).val('');
    $( "#costo_pp" ).val('');
    $( "#id_material" ).focus();
  }

  function vale(id_salida){

    //window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
    window.open('<?php echo base_url()?>index.php/Salidas/vale?id_salida='+id_salida,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
  }

  function help_window(){
     //window.open('<?php echo base_url()?>help/salida_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
     window.open('../../tutoriales/deposito/salida_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
  }



//======================================================================
  function Enviar_Data(){
    console.log('Enviar_Data()');
    var gridData = jQuery("#jqGrid").getRowData();
    if(gridData.length>0){
      //var postData = JSON.stringify(gridData);
      var postData = gridData;
      //$('#grilla').val(gridData);
      var nro;
      //console.log(postData);

      //alert("JSON serialized jqGrid data:\n" + postData);

      $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
      $("#loader").css({
        width:$(window).width(),
        height:$(window).height()
      });
      $("#loader").center();
      $("#gif-load").center();
      if($("#completo").is(':checked')){
        //alert($("#completo").is(':checked'));
      }
      var data = {
        jgGridData: postData,
        id_deposito: $("#id_deposito").val(),
        id_tipo_mov: $("#id_tipo_mov").val(),
        factor: $("#factor").val(),
        pedido: $("#pedido").val(),
        id_pedido: $("#id_pedido").val(),
        completo: $("#completo").is(':checked'),
        id_personal:$("#id_personal").val(),
        legajo: $("#legajo").val(),
        apellido_nombre:$("#apellido_nombre").val(),
        ot: $("#ot").val(),
        obs: $("#obs").val(),
        bultos: $("#bultos").val(),
        recorrido: $("#recorrido").val(),
        sector: $("#sector").val(),
        id_zona: $("#id_zona").val(),
        centro_costo: $("#centro_costo").val(),
        fecha:$("#fecha").val()
      }
      
      console.log('------postData------');
      console.log(postData);
      console.log('---------------------');
      console.log('------data------');
      console.log(data);
      console.log('---------------------');

      $.post('Salidas/salida_stock',
        data,
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================
          $( "#nro" ).val( returnedData['nro'] );
          $( "#id_salida" ).val( returnedData['id_salida']);
          if($( "#nro" ).val()!="ERROR"){
            $("#nro").html($( "#nro" ).val());
            var error=false;
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la Salida Nro. <bolder>"+$( "#nro" ).val()+"</bolder>\"</p>"
          }else{
            var error=true;
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+$( "#nro" ).val()+"\"<br>"+returnedData['msg']+"</p>";
            $( "#id_salida" ).val('');
          }
          $("#loader").hide();
          $("#dialogo").removeClass();
          $("#dialogo").removeAttr();
          
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
            resizable: true,
            title: "Salida de Materiales",
            buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                $(this).dialog("close");
                if(error==false){
                  Bloquear();
                }
              }
            }
            ]
          });
    //==========================================      
        }
        ).fail(function(){
          console.log("Error");
    //==========================================
          $("#loader").hide();
          $("#dialogo").removeClass();
          $("#dialogo").removeAttr();
          var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Error en la peticion AJAX\"</p>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
            resizable: true,
            title: "Entrada de Materiales",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                }
              }
            ]
          });
      });
    }else{
      info('Error','Debe solicitar material para poder grabar la salida','error');
    }
  }

  function Bloquear_devolucion(){
    $('input').prop('disabled',true);
    $("select").attr('disabled','disabled');
  }

  function Bloquear(){
    $('button').prop('disabled','disabled');
    $('input').prop('disabled',true);
    $('#obs').prop('disabled',true);
    $("select").attr('disabled','disabled');
    //var spinner=$("#bultos").spinner({change: function(event, ui){},min: 0});
    $("#bultos").spinner({disabled:true});
    $("#recorrido").spinner({disabled:true});
   /* $("#id_tipo_mov").prop('readonly','readonly');
    $("#id_tipo_compra").prop('readOnly',true);*/
    var td=('#edit_'+'jqGrid');
    //alert(td);
    $(td).hide();
    var td=('#del_'+'jqGrid');
    $(td).hide();
  }
//=========================================================
    $("#zona").focusin(function(){
      $( "#zona" ).val('');
      $( "#id_zona" ).val('');
      $( "#localidad" ).val('');
      $( "#direccion" ).val('');
    });


    $("#zona").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "solicitudes/get_zona",
                 dataType: "json",
              data: {
                term : request.term
                //id_deposito : $('#id_deposito').val()
              },
              success: function(data) {
                response(data);
              }
        });
      },
      minLength: 3,
      focus: function( event, ui ) {
        $( "#zona" ).val( ui.item.label);
        $( "#id_zona" ).val( ui.item.value );
        $( "#localidad" ).val( ui.item.Localidad );
        $( "#direccion" ).val( ui.item.Direccion );
        return false;
      },
      select: function( event, ui ) {
        $( "#id_zona" ).val( ui.item.value );
        $( "#localidad" ).val( ui.item.Localidad );
        $( "#direccion" ).val( ui.item.Direccion );
        $("#barcode").focus();
        /*$( "#ubicacion" ).val( ui.item.ubicacion );
        $( "#cant_stock" ).val( ui.item.cantidad );
        $( "#id_stock" ).val( ui.item.id_stock );*/
        return false;
      }
    });

    $("#localidad").autocomplete({
      source: "solicitudes/get_localidad", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#localidad" ).val( ui.item.value);
        return false;
      }
    });

     $("#direccion").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "solicitudes/get_direccion",
                 dataType: "json",
              data: {
                term : request.term
                //id_deposito : $('#id_deposito').val()
              },
              success: function(data) {
                response(data);
              }
        });
      },
      minLength: 3,
      focus: function( event, ui ) {
        $( "#direccion" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#id_zona" ).val( ui.item.value );
        $( "#localidad" ).val( ui.item.Localidad );
        $( "#zona" ).val( ui.item.Zona );
        /*$( "#ubicacion" ).val( ui.item.ubicacion );
        $( "#cant_stock" ).val( ui.item.cantidad );
        $( "#id_stock" ).val( ui.item.id_stock );*/
        return false;
      }
    });

//=========================================================

function info(titulo,msg,contexto){

  if(contexto=="error"){
    var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
  }
  if(contexto=="info"){
    var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-info\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
  }
  if(contexto==""){
    var msg="<p class=\"ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-info\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+msg+"\"</p>";
  }
  
  $("#dialogo").removeClass();
  $("#dialogo").removeAttr();
  $("#dialogo").html(msg);
  $("#dialogo").dialog({
    modal: true,
    height: 200,
    width: 300,
    resizable: true,
    title: titulo,
    buttons:[
    {
      text:"Aceptar",
      icons:{
        primary:"ui-icon-circle-check"
      },
      click: function(){
        $(this).dialog("close");
      }
    }
    ]
  });
}
//====================================================

function ValidarForm(){
    //var campos_requeridos=['id_deposito','id_personal','id_tipo_mov','centro_costo'];
    msg="";
    if($("#id_deposito").val().length==0||$("#id_deposito").val()==null){
      ErrorClass("#id_deposito");
      msg=msg+"<br>- Debe especificar un <b>Deposito</b>"; 

    }
    if(($("#id_pedido").val()=="") && ($("#pedido").val()!="")){
      $("#pedido").val("");
      ErrorClass("#pedido");
      //ErrorClass("#legajo");
      msg=msg+"<br>- Los pedidos se deben cargar desde la pantalla <b>solicitudes</b> ";
    }

    //trae_pedido();
    /*
    if($("#id_personal").val()==""){
      $("#apellido_nombre").val("");
      ErrorClass("#apellido_nombre");
      ErrorClass("#legajo");
      msg=msg+"<br>- Debe especificar una <b>persona</b> que retira los materiales";
    }
    */
    if($("#id_tipo_mov").val()==""){
      ErrorClass("#id_tipo_mov");
      msg=msg+"<br>- Debe especificar el <b>Tipo de Salida</b>";
    }
    if($("#centro_costo").val()==""){
      ErrorClass("#centro_costo");
      ErrorClass("#denominacion");
      msg=msg+"<br>- Debe especificar un <b>Centro de Costo</b>";
    }
    if(msg==""){
      msg="OK";
    }
    return msg;
  }
//====================================================

  function check_grid(codigo){
    var gridData = jQuery("#jqGrid").getRowData();
    //console.log(gridData);
    var msg="";
    var tmp="ok";
    for(var i=0; i<gridData.length; i++){
      if(codigo==gridData[i]['id_material']){
        msg="El material "+gridData[i]['material']+" ya fue ingresado en la grilla por una cantidad de "+gridData[i]['cantidad'];
        tmp="false";
        info("Material Duplicado",msg,"info");
      }
    }
    return tmp;
  }

//====================================================

  function imp_etiquetas(id_salida){
    //window.open('solicitudes/pedido_etiquetas?nros='+result+'&btos='+btos+'&rdo='+rdo,'Imprimir','');
    window.open('<?php echo base_url()?>index.php/Salidas/pedido_etiquetas?id_salida='+id_salida,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
  }


  function autocomplete_element(value, options) {
    // creating input element
    var $ac = $('<input type="text"/>');
    // setting value to the one passed from jqGrid
    $ac.val(value);
    // creating autocomplete
    $ac.autocomplete({source: "entradas/get_material"});
    // returning element back to jqGrid
    return $ac;
  }
     
  function autocomplete_value(elem, op, value) {
    if (op == "set") {
      $(elem).val(value);
    }
    return $(elem).val();
  }

  function ErrorClass(x){
      $(x).removeClass();
      $(x).addClass("ui-state-error"); 
  }

  function NormalClass(x){
    $(x).removeClass();
    $(x).addClass("ui-widget");
  }

  function ToMays(x){
    M=$(x).val();
    M=M.toUpperCase();
    $(x).val(M);
  }

  jQuery.fn.center = function(){
    this.css("position","absolute");
    this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
    this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
  }


});
</script>
<style>
 /*   body {
     height: 100%;
     margin: 0;
     padding: 0;
     font-family: Verdana, Georgia, Serif;
     /*background-color: #f5f5b5;
    }*/


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

    .ui-icontool-salir { background-position: 0px -96px; }
    .ui-icontool-salir2 { background-position: -32px -96px; }
    .ui-icontool-download { background-position: -64px -96px; }
    .ui-icontool-menu { background-position: 0px -128px; }
    .ui-icontool-help { background-position: -96px -128px; }
    .ui-icontool-suma { background-position: -192px -160px; }
    .ui-icontool-etiqueta { background-position: -224px -160px; }
   /* .ui-icontool-medida { background-position: -160px -96px; }
    .ui-icontool-prohibido { background-position: -192px -96px; }
    .ui-icontool-doc { background-position: -224px -96px; }*/

    .grid {
      border: solid 1px #e7e7e7;
    }
    .linea {
      float:left;
      position: relative;
      overflow: visible;
      width: 100%;
      padding: 1px;
     /* border: solid 1px black;*/
    }
    .etiqueta{
      width: 100%;
      margin: 0.4em;
    }
    .celda{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 22em;
      padding-left: 0.4em;  
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px red;*/
      resize: none;
    }
    .celda_long{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 30em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px green;*/
      resize: none;
    }
    .celda_right{
      float: right;
      min-height: 3em;
      font-size: 1.1em;
      width: 20em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.4em;
      padding-bottom: 0.1em;
      /*border: solid 1px gray;*/
      resize: none;
    }
    .celda_body{
      float: left;
      min-height: 3em;
      font-size: 1.1em;
      width: 10em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
      resize: none;
      overflow: visible;
    }
    .celda_body_short{
      float: left;
      min-height: 3em;
      width: 6em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
     font-size: 1.1em;
      resize: none;
      overflow: visible;
    }
    .celda_body_long{
      float: left;
      min-height: 3em;
      width: 30em;
      font-size: 1.1em;
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px yellow;*/
      resize: none;
    }

  </style>


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
	<div>
	    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
	    	<li class="ui-state-default ui-corner-all" title="Salida de Material">
		        <a href="<?php echo base_url().'index.php/salidas'; ?>">
		        	<span class="ui-icontool ui-icontool-salida"></span>
		        </a>
			</li>
			<li class="ui-state-default ui-corner-all" title="Buscar">
				<span id="Buscar" class="ui-icontool ui-icontool-buscar"></span>
			</li>
			<li class="ui-state-default ui-corner-all" title="Grabar">
				<span id="grabar" class="ui-icontool ui-icontool-grabar2"></span>
			</li>
	    	<li class="ui-state-default ui-corner-all" title="Imprimir">
	        	<span id="imprimir" class="ui-icontool ui-icontool-imprimir"></span>
	    	</li>
        <li class="ui-state-default ui-corner-all" title="Generar Etiquetas">
            <span id="etiqueta" class="ui-icontool ui-icontool-etiqueta"></span>
        </li>
	    	<li class="ui-state-default ui-corner-all" title="actualiza suma">
            <span id="suma" class="ui-icontool ui-icontool-suma"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="help">
            <span id="help" class="ui-icontool ui-icontool-help"></span>
        </li>

        <li class="ui-state-default ui-corner-all" title="menu">
		        <a href="<?php echo base_url().'index.php/menu'; ?>">
		        	<span class="ui-icontool ui-icontool-menu"></span>
		        </a>
	    	</li>
        
	    </ul>
	</div>
	<div class="linea">
	    <div class="celda">
	    		<div> Salida Nro.</div><div id="nro" class="ui-state-default" style="height: 1.2em;width: 10em; padding: 2px;text-align: center;"></div><input type="hidden" id="id_salida" name="id_salida">
	      <input type="hidden" name="grilla" id="grilla">
	    </div>
    <div class="celda_right">
    	<div><label class="etiqueta">Fecha: </label></div>
    	<input type="text" id="fecha" name="fecha" value="<?php echo date("d/m/Y"); ?>"  class="ui-widget" style="width:8em;text-align:center;" readonly>
    </div>
	</div>
	<div class="linea">
	    <div class="celda">
        <div>
          <label class="etiqueta">Deposito: </label>
        </div>
	    	<select name="id_deposito" id="id_deposito" class="ui-widget" style="width:12em;">
	        	<option value="" Selected >----</option>
		        	<?php 
		        		for ($i = 0; $i < count($deposito); $i++) {
		        		  echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
		            }
		        	?>
	    	</select>
	    </div>
	    <div class="celda">
        <div>
          <label class="etiqueta">Movimiento: </label>
        </div>
	    	<select name="id_tipo_mov" id="id_tipo_mov" class="ui-widget" style="width:12em;">
		        <option value="" Selected >----</option>
			        <?php 
			        	for ($i = 0; $i < count($movimiento); $i++) {
			            	echo "<option value=\"".$movimiento[$i]['value']."\">".$movimiento[$i]['label']."</option>";
			        	}
			        ?>
	    	</select>
        <input type="hidden" name="factor" id="factor">
	    </div>
      <div class="celda">
        <div>Pedido: </div>
        <input type="text" id="pedido" name="pedido" placeholder="Nro. pedido">
        <input type="hidden" name="id_pedido" id="id_pedido">
      </div>
      <div class="celda" style="width:8em;">
          <div>Completo:</div>
          <div style="width: 3em;margin-left: 3em">
            <input type="checkbox" name="completo" id="completo" class="ui-widget" value="1" >
          </div>
      </div>
	</div>
  <div class="linea">
    <div class="celda_body">
      <div><label class="etiqueta">Centro de Costo: </label></div>
      <input type="text" name="centro_costo" id="centro_costo" placeholder="Centro de Costo"  class="ui-widget" style="width:8em;text-align:center;"><input type="hidden" id="chk_centro_costo" name="chk_centro_costo">
    </div>
    <div class="celda_long">
      <label id="lbldenominacion" class="etiqueta">Denominacion: </label>
      <input type="text" name="denominacion" id="denominacion" placeholder="Denominacion"  class="ui-widget" style="width:27em;text-align:left;">
    </div>
    <div class="celda">
      <label class="etiqueta">Sector: </label><input type="text" name="sector" id="sector" placeholder="sector"  class="ui-widget" style="width:16em;text-align:center;" >
    </div>
  </div>
  <div class="linea">
    <div class="celda_body">
      <label id="lblot" class="etiqueta">Orden de Trabajo: </label>
      <input type="text" name="ot" id="ot" placeholder="Nro de Ot"  class="ui-widget" style="width:8em;text-align:center;" >
    </div>
    <div class="celda_long">
      <div>
        <label id="lblot" class="etiqueta">Observaciones: </label>
      </div>
      <input type="text" name="obs" id="obs" placeholder="obs"  class="ui-widget" style="width:27em;" >
    </div>
    <div class="celda_body" style="visibility: hidden;">
      <div>
        <label id="lblot" class="etiqueta">Bultos: </label>
      </div>
      <input type="text" name="bultos" id="bultos" value="0"  class="ui-widget" style="width:3em; text-align: center;" readonly >
    </div>
    <div class="celda" style="visibility: hidden;">
      <div>
        <label id="lblot" class="etiqueta">Recorrido: </label>
      </div>
      <input type="text" name="recorrido" id="recorrido" value="0"  class="ui-widget" style="width:3em; text-align: center;" readonly >
    </div>
    <div class="celda_body">
      <label class="etiqueta">Retira legajo: </label>
      <input type="text" id="legajo" name="legajo" class="ui-widget" style="width:8em;text-align:center;" placeholder="Legajo"><input type="hidden" id="id_personal" name="id_personal" value="1">
    </div>
    <div class="celda">
      <label class="etiqueta">Retira material: </label><input type="text" name="apellido_nombre" id="apellido_nombre" placeholder="legajo o apellido"  class="ui-widget" style="width:16em;text-align:center;">
    </div>
  </div>
  <div class="linea">
    <div class="celda">
      <label id="lbldependencia" class="etiqueta">Dependencia: </label>
      <input type="text" name="zona" id="zona" placeholder="Dependencia"  class="ui-widget" style="width:19em;text-align:left;"><input type="hidden" id="id_zona" name="id_zona">
    </div>
    <div class="celda">
      <div>
        <label id="lblLocalidad" class="etiqueta">Localidad: </label>
      </div>
      <div>
        <input type="text" name="localidad" id="localidad" placeholder="Localidad"  class="ui-widget" style="width:19em;text-align:left;">
      </div>
    </div>
    <div class="celda_long">
      <div>
        <label id="lblDireccion" class="etiqueta">Direccion: </label>
      </div>
      <div>
        <input type="text" name="direccion" id="direccion" placeholder="Direccion"  class="ui-widget" style="width:32em;text-align:left;">
      </div>
    </div>
  </div>
	<div class="linea" >
    <div class="ui-state-default ui-corner-all" style="margin: auto; min-height:3em; padding:auto;resize: none;overflow: visible;max-width: 82em">
      <div class="celda_body">
        <label>Barcode: </label><input type="text" id="barcode" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Barcode">
      </div>
      <div class="celda_body_long">
        <label>Descripción: </label><input id="material" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;" placeholder="Descripcion material">
      </div>
      <div class="celda_body_short">
        <label>Unidad: </label><input id="unidad" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="" readonly>
      </div>
      <div class="celda_body_short">
        <label>Cantidad: </label><input type="text" id="cantidad" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="00.0">
        <input type="hidden" id="id_stock">
        <input id="id_material" type="hidden">
        <input type="hidden" id="costo_ult" name="costo_ult">
        <input type="hidden" id="costo_pp" name="costo_pp">
        <input type="hidden" id="ubicacion" name="ubicacion">
      </div>
      <div class="celda_body_short">
          <label>Disponible: </label><input id="cant_stock" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Stock" readonly>
      </div>
   <!--    <div class="celda_body">
        <label>Ubicacion: </label><input type="text" id="ubicacion" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Ubicacion"><input type="hidden" id="id_ubicacion">
      </div> -->

      <div style="padding: 5px;float: left;">
        <button id="add">Insertar</button>
        <button id="limpiar">limpiar</button>
     <!--    <button id="refresh">Actualizar</button> -->
      </div>
    </div>
  </div>
  <div class="linea">
  <div class="ui-corner-all" style="padding:3px 3px 3px 3px">
     <div style="margin: auto; width: 78em; height:3em; padding:0.4em;">
      <table id="jqGrid"></table>
      <div id="jqGridPager"></div>
      <div class="celda_body" style="float: right;margin: 3px;"><b>
        <label>Costo Total: $</label><input id="total" type="text" class="ui-widget" style="text-align:right;width: 8em;" placeholder="0.00" readonly></b>
      </div>
    </div>
  </div>
  </div>
  <div id="loader"></div>
  <div id="dlgBuscar"></div>
  <div id="dialogo"></div>
</body>