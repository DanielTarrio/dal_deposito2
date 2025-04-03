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
var disponible=0;

  $(function() {
    var mydata = new Array();
    $("#grabar").click(function(){
        //alert($('input').prop('disabled'));
        if($('input').prop('disabled')!=true){
          msg=ValidarForm();
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
    });

//--------------------------------
    $("#Buscar").click(function(){
      $("#lista").hide();
      $("#lista").load("<?php echo base_url();?>index.php/solicitudes/lista_solicitudes");
      $("#lista").dialog({
          modal: true,
          height: 550,
          width: 900,
          resizable: true,
          title: "Busqueda de Vales",
          buttons:[
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
      $("#lista").dialog('open');

    });

    $("#help").click(function(){
      help_window();
    });

    $("#fecha").datepicker({
        dateFormat:'dd/mm/yy',
        dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        //minDate: new Date(2016, 6, 25)
    });

    $("#anular").click(function(){

      var msg="¿Esta seguro de anular el pedido nro. "+$("#nro").val()+"?";
      var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>"+msg+"</p>";

      $("#dialogo").removeClass();
      $("#dialogo").removeAttr();
      $("#dialogo").html(msg);
      $("#dialogo").dialog({
      modal: true,
      height: 200,
      width: 300,
      resizable: true,
      title: "Anular pedido "+$("#nro").val(),
      buttons:[
          {
            text:"Anular",
            icons:{
              primary:"ui-icon-check"
            },
            click: function(){
              $(this).dialog("close");
              anular($("#nro" ).val());
            }
          },
          {
            text:"Cancelar",
            icons:{
              primary:"ui-icon-close"
            },
            click: function(){
              $(this).dialog("close");
            }
          }
        ]
      });

    });
//-------------------------------

    $("#ot").blur(function(){
      alert("ddd");
    })

//--------------------------------    

    $("#imprimir").click(function(){
      if($.isNumeric($("#nro").val())){
        vale($("#nro" ).val(),$("#id_deposito" ).val());
      }else{
//--------------------------------

        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        msg="Debe existir un pedido seleccionado para poder imprimir"
        var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";
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

    $("#nro").blur(function(){
      if($("#nro").val()!=""){
        NormalClass("#nro");
        var data={
          nro: $("#nro").val()
        }
        $.post('solicitudes/buscar_solicitud',
          data,
          function(returnedData){
          console.log(returnedData);
          if(returnedData!="0"){
            var msg="El número de pedido <b>"+returnedData[0]['nro']+"</b> ya se encuentra ingresado<br>";
            msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-error ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">"+msg+"</p></p>";


            $("#loader").hide();
            $("#dialogo").removeClass();
            $("#dialogo").removeAttr();
            $("#dialogo").html(msg);
            if(returnedData[0]['salidas']==0){
              $("#dialogo").dialog({
                modal: true,
                height: 200,
                width: 300,
                resizable: true,
                title: "Pedido de Materiales",
                buttons:[

                  {
                    text:"Editar",
                    icons:{
                      primary:"ui-icon-pencil"
                    },
                    click: function(){
                      $(this).dialog("close");
                      trae_pedido($('#nro').val());
                    }
                    },
                  {
                    text:"Ver",
                    icons:{
                      primary:"ui-icon-document"
                    },
                    click: function(){
                      $(this).dialog("close");
                      vale($("#nro").val(),'');
                    }
                    },
                    {
                    text:"Cerrar",
                    icons:{
                      primary:"ui-icon-circle-close"
                    },
                    click: function(){
                      $(this).dialog("close");
                    //ver_remito($("#remito").val(),$("#id_proveedor").val());
                    }
                  }
                ]
                });
            }else{
              $("#dialogo").dialog({
                modal: true,
                height: 200,
                width: 300,
                resizable: true,
                title: "Pedido de Materiales",
                buttons:[

                  {
                    text:"Ver",
                    icons:{
                      primary:"ui-icon-document"
                    },
                    click: function(){
                      $(this).dialog("close");
                      vale($("#nro").val(),'');
                    }
                    },
                    {
                    text:"Cerrar",
                    icons:{
                      primary:"ui-icon-circle-close"
                    },
                    click: function(){
                      $(this).dialog("close");
                    //ver_remito($("#remito").val(),$("#id_proveedor").val());
                    }
                  }
                ]
            });
          }
            ErrorClass("#nro");
          }else{
            NormalClass("#nro");
          }
        });
      }
      if($("#centro_costo").val()!=""){
        $("#sector").focus()
      }else{
        $("#centro_costo").focus()
      }
    });

//=========================================================
    function trae_pedido(var_pedido){
      var data = {
        pedido : var_pedido, //$('#nro').val(),
        id_tipo_mov: "0"   /*$('#id_tipo_mov').val()*/
      }
      
      Limpiar_Form();

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
            $("#sector").focus()
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



    $("#sector").change(function(){
      NormalClass("#sector");
    });
    $("#centro_costo").autocomplete({
      source: "contable/get_centro_costo", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#centro_costo" ).val( ui.item.value);
        $( "#denominacion" ).val( ui.item.denominacion);
        return false;
      },
      select: function( event, ui ) {
        $( "#sector" ).focus();
        NormalClass("#denominacion");
        NormalClass("#centro_costo");
        return false;
      }
    });

    $("#centro_costo").focusin(function(){
      $("#centro_costo").val("");
      $("#denominacion").val("");
    });

    $("#denominacion").autocomplete({
      source: "contable/get_denominacion_centro_costo", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#denominacion" ).val( ui.item.value);
        $( "#centro_costo" ).val( ui.item.centro_costo);
        return false;
      },
      select: function( event, ui ) {
        $( "#sector" ).focus();
        NormalClass("#denominacion");
        NormalClass("#centro_costo");
        return false;
      }
    });
    $("#denominacion").focusin(function(){
      $("#centro_costo").val("");
      $("#denominacion").val("");
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
        $( "#zona" ).focus();
        NormalClass("#legajo");
        NormalClass("#apellido_nombre");
        return false;
      }
    });
    $("#apellido_nombre").focusin(function(){
      $("#apellido_nombre").val("");
      $("#legajo").val("");
      $("#id_personal").val("");
    })

    $("#legajo").autocomplete({
      source: "contable/get_personal", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#legajo" ).val( ui.item.value);
        $("#apellido_nombre").val(ui.item.apellido_nombre);
        $("#id_personal").val(ui.item.id_personal);
        return false;
      },
      select: function( event, ui ) {
        $( "#zona" ).focus();
        NormalClass("#legajo");
        NormalClass("#apellido_nombre");
        return false;
      }
    });
    $("#legajo").focusin(function(){
      $("#apellido_nombre").val("");
      $("#legajo").val("");
      $("#id_personal").val("");
    })

//========================================================

    $("#material").autocomplete({
      source: function(request, response) {
        $.ajax({
              url: "solicitudes/get_disponible",
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
        $( "#cant_pedido" ).val(ui.item.pedido);
        $( "#stock" ).val(ui.item.cantidad);
        var disponible= parseFloat(ui.item.cantidad)-parseFloat(ui.item.pedido);
        $( "#cant_stock" ).val(disponible);
        $( "#id_stock" ).val( ui.item.id_stock );
        $( "#costo_ult" ).val( ui.item.costo_ult );
        $( "#costo_pp" ).val( ui.item.costo_pp );
        return false;
      }
    });

//=========================================================

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
        return false;
      },
      select: function( event, ui ) {
        $( "#id_zona" ).val( ui.item.value );
        $( "#localidad" ).val( ui.item.Localidad );
        $( "#direccion" ).val( ui.item.Direccion );
        NormalClass("#zona");
        NormalClass("#localidad");
        NormalClass("#direccion");
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
        $( "#zon" ).val( ui.item.Zona );
        /*$( "#ubicacion" ).val( ui.item.ubicacion );
        $( "#cant_stock" ).val( ui.item.cantidad );
        $( "#id_stock" ).val( ui.item.id_stock );*/
        return false;
      }
    });

//=========================================================


    $("#id_material").blur(
      function(){
        if(($("#id_material").val()!="")&&($("#id_deposito").val()!="")) {
            $.ajax({
               url: 'solicitudes/get_cod_disponible',
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
                  $( "#cant_pedido" ).val(data[0]['pedido']);
                  $( "#stock" ).val(data[0]['cantidad']);
                  var disponible = parseFloat(data[0]['cantidad'])-parseFloat(data[0]['pedido']);
                  $( "#cant_stock" ).val(disponible);
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
               url: 'solicitudes/get_barcode_disponible',
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
                  $( "#cant_pedido" ).val(data[0]['pedido']);
                  $( "#stock" ).val(data[0]['cantidad']);
                  var disponible = parseFloat(data[0]['cantidad'])-parseFloat(data[0]['pedido']);
                  $( "#cant_stock" ).val(disponible);
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

//------------------------CostoLinea-----------------------
    $("input[name='cantidad']").blur(function(){
      alert('algo detecto');
    })   


    $( "body" ).focusout(function( event ) {
      if(event.target.name==='cantidad'){
        //alert('jkhkj');
        $("#linea").val(event.target.value*$("#costo_ult").val());
      }
    });
/* 
<input type="text" id="cantidad" name="cantidad" rowid="1" role="textbox" class="FormElement ui-widget-content ui-corner-all">
*/
//------------------------CostoLinea-----------------------
    //========================== jqGrid ==========================

       var template = "<div style='margin-left:15px;'><div>Codigo<sup>*</sup>:</div><div> {barcode} </div>";
            template += "<div> Material: </div><div>{material} </div>";
            template += "<div> Unidad: </div><div>{unidad} </div>";
            template += "<div> Solicitado: </div><div>{solicitado} </div>";
            template += "<div> Cantidad: </div><div>{cantidad} </div>";
            template += "<div> Stock: </div><div>{cant_stock} </div>";
            template += "<div> Costo: </div><div>{costo_ult} </div>";
            template += "<div> Costo Linea: </div><div>{linea} </div>";
            template += "<hr style='width:100%;'/>";
            template += "<div> {sData} {cData}  </div></div>";

    
        $(document).ready(function () {
            $("#jqGrid").jqGrid({
                datatype: "local",
                editurl: 'clientArray',
                data: mydata,
                height: 250,
                width: 885,
                colModel: [
                    { 
                      label: 'id_material',
                      name: 'id_material',
                      width: 75,
                      key:true,
                      align: 'center',
                      hidden:true,
                      editable: false,
                      //editoptions:{size:2,readonly:"readonly"}
                    },
                    { 
                      label: 'id_detalle_pedido',
                      name: 'id_detalle_pedido',
                      width: 75,
                      key:true,
                      align: 'center',
                      hidden:true,
                      editable: false,
                      //editoptions:{size:2,readonly:"readonly"}
                    },
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
                      label: 'Stock',
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
                      label: 'CostoPP',
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
                    }
                    
                ],
                 formatter : {
                   integer : {thousandsSeparator: "", defaultValue: '0'},
                   number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'},
                   currency:{decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, prefix:"$ ", suffix:""}
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
        var msg="La cantidad solicitado es mayor al disponible "+" Existente:"+$("#cant_stock" ).val()+" generará pedidos pendientes";
        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        //var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";

        msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-highlight ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">"+msg+"</p></p>";

          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 350,
            resizable: true,
            title: "Material a Despachar mayor al Disponible",
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

      if(parseFloat($( "#cantidad" ).val())>parseFloat($( "#solicitado" ).val())){
        var msg="La cantidad a despachar no puede ser mayor a la solicitada "+$("#solicitado" ).val()+" generará pedidos pendientes";
        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        //var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";

        msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-error ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">"+msg+"</p></p>";

          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 350,
            resizable: true,
            title: "Material a Despachar mayor al solicitado",
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
      }else{
  //=================verificar por negativo======================================
        if(parseFloat($( "#cantidad" ).val())<0){
        msg="Ingreso un valor negativo, esta seguro de ingresar un ajuste de material";
        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        //var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+msg+"</div>";

        var msg="El número de pedido <b>"+returnedData[0]['nro']+"</b> ya se encuentra ingresado<br>";
        msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-error ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">"+msg+"</p></p>";

          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
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
      } //cierre dialogo*/
    }
  }

  function inserta_linea(){

    if(check_grid($( "#id_material" ).val())=="ok"){
      var myfirstrow = {id_material:$( "#id_material" ).val(),barcode:$( "#barcode" ).val(),material:$("#material").val(), unidad:$( "#unidad" ).val(), solicitado:$( "#solicitado" ).val(), cantidad:$( "#cantidad" ).val(), costo_ult:$( "#costo_ult" ).val(),costo_pp:$( "#costo_pp" ).val(), id_stock:$( "#id_stock" ).val(), cant_stock:$( "#cant_stock" ).val(),linea:($( "#costo_ult" ).val()*$( "#cantidad" ).val())};
      i = $("#jqGrid").getGridParam("reccount");
      i++;
      jQuery("#jqGrid").addRowData(i, myfirstrow);
    }
    Limpiar_campos();
    costo_total();
  }

  $("#total").click(function(){costo_total()});

  function costo_total(){
    var gridData = jQuery("#jqGrid").getRowData();
    var costo=0
    for(var i=0; i<gridData.length; i++){
      costo=parseFloat(costo)+parseFloat(gridData[i]['linea']);
    }
    $("#total").val(costo);
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
      if(disponible<=parseFloat(value)){
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
    $( "#solicitado" ).val('');
    $( "#cant_pedido" ).val('');
    $( "#stock" ).val('');
    //$( "#id_material" ).focus();
    $( "#barcode" ).focus();
    
  }

  function vale(nro,deposito){
    //window.open('solicitudes/pedido_materiales?nros='+result,'Imprimir','');
    window.open('solicitudes/pedido_materiales?nros='+nro,'Imprimir','Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');
  }

  function help_window(){
     //window.open('<?php echo base_url()?>help/salida_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
     window.open('../../tutoriales/deposito/salida_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
  }



//======================================================================
  function Enviar_Data(){
    var gridData = jQuery("#jqGrid").getRowData();

    if(gridData.length>0){
      //var postData = JSON.stringify(gridData);
      var postData = gridData;
      //$('#grilla').val(gridData);
      var nro;
      //alert("JSON serialized jqGrid data:\n" + postData);

      $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
      $("#loader").css({
        width:$(window).width(),
        height:$(window).height()
      });
      $("#loader").center();
      $("#gif-load").center();
      var data = {
        jgGridData: postData,
        id_deposito: $("#id_deposito").val(),
        id_pedido: $("#id_pedido").val(),
        nro: $("#nro").val(),
        id_zona: $("#id_zona").val(),
        legajo: $("#legajo").val(),
        id_personal: $("#id_personal").val(),
        ot: $("#ot").val(),
        obs: $("#obs").val(),
        sector: $("#sector").val(),
        centro_costo: $("#centro_costo").val(),
        fecha:$("#fecha").val(),
        id_sector:$("#id_sector").val(),
        web:0
      }
      $.post('solicitudes/add_solicitud',
        data,
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================
          $( "#nro" ).val( returnedData['nro'] );
          if($( "#nro" ).val()!="ERROR"){
            $("#nro").html($( "#nro" ).val());
            var error=false;

            var msg="Se genero el Pedido Nro. <bolder>"+$( "#nro" ).val()+"</bolder>";
            msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-highlight ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">"+msg+"</p></p>";

            //var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la Salida Nro. <bolder>"+$( "#nro" ).val()+"</bolder>\"</p>"
          }else{
            var error=true;
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+$( "#nro" ).val()+"\"<br>"+returnedData['msg']+"</p>";



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
      info('Error','Debe solicitar material para poder grabar el pedido','error');
    }
  }

  function Bloquear(){
    $('button').prop('disabled','disabled');
    $('input').prop('disabled',true);
    $('#obs').prop('disabled',true);
    $("select").attr('disabled','disabled');
   /* $("#id_tipo_mov").prop('readonly','readonly');
    $("#id_tipo_compra").prop('readOnly',true);*/
    var td=('#edit_'+'jqGrid');
    //alert(td);
    $(td).hide();
    var td=('#del_'+'jqGrid');
    $(td).hide();
  }
//===================================================
  function anular(nro){

      var data = {
        nro : $('#nro').val(),
      }

      $.post('solicitudes/anular_solicitud',
        data,
        function(returnedData){
          console.log(returnedData);
          if(returnedData=="ERROR"){
            info('Anular Pedido Nro'+$('#nro').val(),returnedData,'error');
          }else{

                  var msg="Se anularon "+returnedData+" items del pedido nro. "+$("#nro").val();
                  var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>"+msg+"</p>";

                  $("#dialogo").removeClass();
                  $("#dialogo").removeAttr();
                  $("#dialogo").html(msg);
                  $("#dialogo").dialog({
                  modal: true,
                  height: 200,
                  width: 300,
                  resizable: true,
                  title: "Anular pedido "+$("#nro").val(),
                  buttons:[
                      {
                        text:"Anular",
                        icons:{
                          primary:"ui-icon-check"
                        },
                        click: function(){
                          $(this).dialog("close");
                          Bloquear();
                        }
                      }
                    ]
                  });

          }
         
        }
      ).fail(function(xhr, textStatus,errorThrown){
        console.log(data);
        console.log(textStatus);
        console.log(xhr.responseText);
        var msg=errorThrown;
        info('titulo',msg,'error');
      });
    

  }

//====================================================

  function ValidarForm(){
    //var campos_requeridos=['id_deposito','id_personal','id_tipo_mov','centro_costo'];
    msg="";
/*
    if($("#nro").val().length==0||$("#nro").val()==null){
      ErrorClass("#nro");
      msg=msg+"<br>- Debe especificar un <b>Numero de Pedido</b>"; 
    }*/
    if($("#id_deposito").val().length==0||$("#id_deposito").val()==null){
      ErrorClass("#id_deposito");
      msg=msg+"<br>- Debe especificar un <b>Deposito</b>"; 
    }
    if($("#id_zona").val().length==0||$("#id_zona").val()==null){
      ErrorClass("#zona");
      ErrorClass("#localidad");
      ErrorClass("#direccion");
      msg=msg+"<br>- Debe especificar una <b>Dependencia</b>"; 
    }

    if($("#sector").val()==""){
      ErrorClass("#sector");
      msg=msg+"<br>- Debe especificar el <b>Un Sector</b>";
    }
    /*
    if($("#id_personal").val()==""){
      $("#apellido_nombre").val("");
      ErrorClass("#apellido_nombre");
      ErrorClass("#legajo");
      msg=msg+"<br>- Debe especificar una <b>persona</b> que retira los materiales";
    }*/
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

  function ErrorClass(x){
      $(x).removeClass();
      $(x).addClass("ui-state-error"); 
  }

  function NormalClass(x){
    $(x).removeClass();
    $(x).addClass("ui-widget");
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
    .ui-icontool-solicitud { background-position: -160px -160px; }
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
      padding: 3px;
     /* border: solid 1px black;*/
    }
    .etiqueta{
      width: 100%;
      margin: 0.4em;
    }
    .celda{
      float: left;
      min-height: 3em;
      width: 22em;
      padding-left: 0.4em;  
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px red;*/
      font-size: 1.1em;
      resize: none;
    }
    
    .celda_right{
      float: right;
      min-height: 3em;
      width: 20em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.4em;
      padding-bottom: 0.1em;
     /* border: solid 1px gray;*/
      resize: none;
      font-size: 1.2em;
    }
    .celda_body{
      float: left;
      min-height: 3em;
      width: 10em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
     font-size: 1.1em;
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
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px yellow;*/
      font-size: 1.1em;
      resize: none;
    }
    .celda_long{
      float: left;
      min-height: 3em;
      width: 40em;
      padding-left: 0.1em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
      /*border: solid 1px green;*/
      font-size: 1.1em;
      resize: none;
    }

  </style>


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
	<div>
	    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
	    	<li class="ui-state-default ui-corner-all" title="Solicitud de Material">
		        <a href="<?php echo base_url().'index.php/solicitudes'; ?>">
		        	<span class="ui-icontool ui-icontool-solicitud"></span>
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
	    	<li class="ui-state-default ui-corner-all" title="menu">
		        <a href="<?php echo base_url().'index.php/menu'; ?>">
		        	<span class="ui-icontool ui-icontool-menu"></span>
		        </a>
	    	</li>
        <li class="ui-state-default ui-corner-all" title="Anular pedido">
            <span id="anular" class="ui-icontool ui-icontool-eliminar2"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="help">
            <span id="help" class="ui-icontool ui-icontool-help"></span>
        </li>
	    </ul>
	</div>
	<div class="linea">
    <div class="celda">
        <div><label class="etiqueta">Deposito: </label></div>
        <select name="id_deposito" id="id_deposito" class="ui-widget" style="width: 10em">
              <?php 
                for ($i = 0; $i < count($deposito); $i++) {
                  echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
                }
              ?>
        </select>
      </div>
	    <div class="celda">
        <div>Pedido Nro. </div>
	    	<input type="text" id="nro" class="ui-widget" style="text-align: center;"><input type="hidden" id="id_pedido" name="id_pedido">
	      <input type="hidden" name="grilla" id="grilla">
	    </div>
    <div class="celda_right">
    	<label class="etiqueta">Fecha: </label>
    	<input type="text" id="fecha" name="fecha" value="<?php echo date("d/m/Y"); ?>"  class="ui-widget" style="width:8em;text-align:center;margin:0.4em">
    </div>
	</div>
	<div class="linea">
      <div class="celda">
        <div><label class="etiqueta">Centro de Costo: </label></div>
        <input type="text" name="centro_costo" id="centro_costo" placeholder="Centro de Costo"  class="ui-widget" style="width:12em;text-align:center;">
      </div>
      <div class="celda">
        <div><label class="etiqueta">Denominacion Centro de Costo:</label></div>
        <input type="text" name="denominacion" id="denominacion" placeholder="Denominacion Centro de Costo"  class="ui-widget" style="width:18em;text-align:center;">
      </div>
	</div>
  <div class="linea">
    <div class="celda">
      <div><label class="etiqueta">Sector: </label></div>
      <input type="text" name="sector" id="sector" placeholder="Sector"  class="ui-widget" style="width:18em;text-align:left;" maxlength="25">
      <input type="hidden" id="id_sector" name="id_sector" value="1">
    </div>
    <div class="celda">
      <div><label class="etiqueta">ODT: </label></div>
      <input type="text" name="ot" id="ot" placeholder="Orden de Trabajo"  class="ui-widget" style="width:10em;text-align:left;" maxlength="12">
    </div>
    <div class="celda">
      <div><label class="etiqueta">Observacion: </label></div>
      <input type="text" name="obs" id="obs" placeholder="observaciones"  class="ui-widget" style="width:18em;text-align:left;" maxlength="20">
    </div>
    <div class="celda_body">
      <div><label class="etiqueta">Legajo: </label></div>
      <input type="text" id="legajo" name="legajo" style="width:6em;text-align:center;" placeholder="Legajo"><input type="hidden" id="id_personal" name="id_personal" value="1"> 
    </div>
    <div class="celda">
      <div><label class="etiqueta">Apellido y Nombre: </label></div>
      <input type="text" name="apellido_nombre" id="apellido_nombre" placeholder="Solicitante"  class="ui-widget" style="width:19em;text-align:left;">
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
    <div class="ui-state-default ui-corner-all" style="margin-left: auto;margin-right: auto; min-height:4em; padding:auto;overflow: visible;max-width: 96em">
      <div class="linea">
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
          <label>Solicitado: </label><input id="solicitado" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="0.00" >
        </div>
        <div class="celda_body_short">
          <label>Cantidad: </label><input type="text" id="cantidad" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="0.00">
          <input type="hidden" id="id_stock">
          <input type="hidden" id="id_material">
          <input type="hidden" id="ubicacion" name="ubicacion">
          <input type="hidden" id="costo_ult" name="costo_ult">
          <input type="hidden" id="costo_pp" name="costo_pp">
        </div>
        <div class="celda_body_short">
            <label>Pedido: </label><input id="cant_pedido" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="0.00" readonly>
        </div>
        <div class="celda_body_short">
            <label>Stock: </label><input id="stock" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="0.00" readonly>
        </div>
        <div class="celda_body_short">
            <label>Disponible: </label><input id="cant_stock" type="text" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="0.00" readonly>
        </div>
     <!--    <div class="celda_body">
          <label>Ubicacion: </label><input type="text" id="ubicacion" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Ubicacion"><input type="hidden" id="id_ubicacion">
        </div> -->
          <div style="padding: 5px;float: right;">
          <button id="add">Insertar</button>
          <button id="limpiar">limpiar</button>
       <!--    <button id="refresh">Actualizar</button> -->
          </div>
        </div>
    </div>
  </div>
  <div class="linea">
  <div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
     <div style="margin: auto; width: 88em; height:3em; padding:0.4em">
      <table id="jqGrid"></table>
      <div id="jqGridPager"></div>
      <div class="celda_body" style="float: right;margin: 3px;"><b>
        <label>Costo Total: $</label><input id="total" type="text" class="ui-widget" style="text-align:right;width: 8em;" placeholder="0.00" readonly></b>
      </div>
      
    </div>
  </div>
  </div>
  </div>
  <div id="loader"></div>
  <div id="dialogo"></div>
  <div id="lista"></div>
</body>