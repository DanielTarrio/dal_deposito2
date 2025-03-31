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
            var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
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
                      $(this).dialog('destroy');
                      $(this).hide();
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
      $("#loader").hide();
      $("#loader").load("<?php echo base_url();?>index.php/entradas/lista_entradas");
      $("#loader").dialog({
          modal: true,
          height: 500,
          width: 700,
          resizable: true,
          title: "Busqueda de Entradas",
          buttons:[
            {
              text:"Remito",
                icons:{
                  primary:"ui-icon-document"
                },
              click: function(){
                  //alert('id_dep='+$("#frm_deposito").val()+' remito: '+$("#frm_remito").val());
                  ver_remito($("#frm_remito").val(), $("#frm_proveedor").val())
              }
            },
            {
              text:"Entrada",
                icons:{
                  primary:"ui-icon-clipboard"
                },
              click: function(){
                  print_entrada($("#frm_nro").val(),$("#frm_deposito").val());
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
      $("#loader").dialog('open');

    });

    //-----------------------------------------------------------

    $("#imprimir").click(function(){
      
      if($.isNumeric($("#nro").val())){
        print_entrada($("#nro").val(),$("#id_deposito" ).val());
      }else{
        $("#dialogo").removeClass();
        $("#dialogo").removeAttr();
        msg="Debe existir una entrada seleccionada para poder imprimir"
        var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:4 7px 200px 4\"></span>"+msg+"</p>";
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
      }
    });

//-----------------------------------------------------------

    $("#catalogo").click(function(){
      $("#loader").hide();
      $("#loader").load("<?php echo base_url();?>index.php/material/frm_material");
      $("#loader").dialog({
          modal: true,
          height: 325,
          width: 500,
          resizable: true,
          title: "Alta Material",
          buttons:[
            {
              text:"Aceptar",
                icons:{
                  primary:"ui-icon-circle-check"
                },
              click: function(){
                  if($("#frm").val()=="OK"){
                    check_alta_mat();
                    //alta_material();
                  }else{
                    $(this).dialog("close");
                  }
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
      $("#loader").dialog('open');

    });

    $("#alta_proveedor").click(function(){
      $("#loader").hide();
      $("#loader").load("<?php echo base_url();?>index.php/entradas/frm_proveedor");
      $("#loader").dialog({
          modal: true,
          height: 325,
          width: 550,
          resizable: true,
          title: "Alta Proveedor",
          buttons:[
            {
              text:"Aceptar",
                icons:{
                  primary:"ui-icon-circle-check"
                },
              click: function(){
                  if($("#frm").val()=="OK"){
                    //alta_proveedor();
                    check_alta_prov();
                  }else{
                    $(this).dialog("close");
                  }
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
      $("#loader").dialog('open');

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
    $("#fecha").change(function(){
      NormalClass("#fecha");
    });

  	$( "#id_deposito" ).change(function(){
      NormalClass("#id_deposito");
    });

    $( "#id_tipo_mov" ).change(function(){
      NormalClass("#id_tipo_mov");
    });
    $("#id_tipo_compra").change(function(){
      NormalClass("#id_tipo_compra");
    });
    $( "#id_compra" ).change(function(){
      NormalClass("#id_compra");
    });

    $("#centro_costo").autocomplete({
      source: "contable/get_centro_costo", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#centro_costo" ).val( ui.item.value);
        NormalClass("#centro_costo");
        return false;
      }
    });

    $("#proveedor").autocomplete({
      source: "entradas/get_proveedor", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#proveedor" ).val( ui.item.label);
        $( "#id_proveedor" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $( "#id_proveedor" ).val( ui.item.value );
        NormalClass("#proveedor");
        return false;
      }
    });


    $("#material").autocomplete({
      source: "entradas/get_material", // path to the get_birds method
        minLength: 2,
      focus: function( event, ui ) {
        $( "#material" ).val( ui.item.label);
        return false;
      },
      select: function( event, ui ) {
        $( "#id_material" ).val( ui.item.value );
        $( "#material" ).val( ui.item.label );
        $( "#unidad" ).val( ui.item.unidad );
        $( "#barcode" ).val( ui.item.barcode );
        $( "#costo_ult" ).val( ui.item.costo_ult );
        return false;
      }
    });
    $("#remito").focusin(function(){
      if($("#id_proveedor").val()==""){
        alert('Debe seleccionar un proveedor');
        $("#proveedor").focus();
      }
    });
    $("#remito").blur(
      function(){
        if($("#remito").val().length==12){
          if($.isNumeric($("#remito").val())){
            var data = {
              id_proveedor: $("#id_proveedor").val(),
              remito: $("#remito").val()
            }
            $.post('entradas/buscar_remito',
            data,
            function(returnedData){
              console.log(returnedData);
              if(returnedData!="0"){
                var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Ya se registran entradas para ese remito\"</p><br><p><table>";
                msg=msg+"<tr><th>Nro.</th><th>Carga</th></tr>";
                for (var i = 0; i < returnedData.length; i++) {
                  msg=msg+"<tr><td>"+returnedData[i]['nro']+"</td><td>"+returnedData[i]['ult_mod']+"</td></tr>";
                 /* msg=msg+"<span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>";*/
                }
                msg=msg+"</table></p>";
                $("#loader").hide();
                $("#dialogo").removeClass();
                $("#dialogo").removeAttr();
                $("#dialogo").html(msg);
                $("#dialogo").dialog({
                  modal: true,
                  height: 300,
                  width: 400,
                  resizable: true,
                  title: "Entrada de Materiales",
                  buttons:[
                    {
                      text:"Ver",
                      icons:{
                        primary:"ui-icon-document"
                      },
                      click: function(){
                        $(this).dialog("close");
                        ver_remito($("#remito").val(),$("#id_proveedor").val());
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
            });
          }else{
            alert('Debe ser numeros');
            $("#remito").val("");
            $("#id_compra").focus();
          }
        }else{
          if($("#remito").val()!=""){
            var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:4 7px 100px 4;\"></span>\"ERROR# introduzaca los 12 numeros del remito incluyendo el 0"+"\"<br>";
            $("#loader").hide();
            $("#dialogo").removeClass();
            $("#dialogo").removeAttr();
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
                    $("#remito").val("");
                    $("#id_compra").focus();
                  }
                }
              ]
            });
          }
        }
    });
    $("#remito").change(function(){
      NormalClass("#remito");
    });
    
    $("#id_material").blur(
      function(){
        if($("#id_material").val()!=""){
            $.ajax({
               url: 'entradas/get_cod_material',
               data: "term="+$( "#id_material" ).val(),
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['value'] );
                  $( "#material" ).val( data[0]['label'] );
                  $( "#unidad" ).val( data[0]['unidad'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
                  $( "#costo_ult" ).val( data[0]['costo_ult'] );
               },
               type: 'GET'
              });
        }else{
          //alert("nada");
        }
        
      });

    $("#barcode").blur(
      function(){
        if($("#id_material").val()==""|| $("#id_material").val()==0){

          if($('#barcode').val()!=""){
           
            $.ajax({
               url: 'entradas/get_barcode_material',
               data: "term="+$( "#barcode" ).val(),
               dataType: 'json',
               error: function() {
                 alert("Error en la peticion AJAX");
               },
               success: function(data) {
                  $( "#id_material" ).val( data[0]['value'] );
                  $( "#material" ).val( data[0]['label'] );
                  $( "#unidad" ).val( data[0]['unidad'] );
                  $( "#barcode" ).val( data[0]['barcode'] );
                  $( "#costo_ult" ).val( data[0]['costo_ult'] );
               },
               type: 'GET'
              });

          }
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

    //========================== jqGrid ==========================

       var template = "<div style='margin-left:15px;'><div>Codigo<sup>*</sup>:</div><div> {id_material} </div>";
            template += "<div> Material: </div><div>{material} </div>";
            template += "<div> Unidad: </div><div>{unidad} </div>";
            template += "<div> Cantidad: </div><div>{cantidad} </div>";
            template += "<hr style='width:100%;'/>";
            template += "<div> {sData} {cData}  </div></div>";

    
        $(document).ready(function () {
            $("#jqGrid").jqGrid({
                datatype: "local",
                editurl: 'clientArray',
                data: mydata,
                height: 250,
                width: 780,
                colModel: [
                    { 
                      label: 'id_material',
                      name: 'id_material',
                      width: 75,
                      hidden:true,
                      key:false,
                      editable: false
                    },
                    { 
                      label: 'Codigo',
                      name: 'barcode',
                      align:'center',
                      width: 75,
                      key:false,
                      editable: false
                    },
                    {
                      label: 'Material',
                      name: 'material',
                      width: 400,
                      editable: true
                    },
                    {
                      label: 'Unidad',
                      name: 'unidad',
                      width: 80,
                      editable: true
                    },
                    {
                      label: 'Cantidad',
                      name: 'cantidad',
                      width: 100,
                      formatter: 'number',
                      align:'right',
                      editable: true
                    },
                    {
                      label: 'costo_ult',
                      name: 'costo_ult',
                      width: 100,
                      formatter: 'number',
                      align:'right',
                      editable: false,
                      hidden: true
                    }
                ],
                 formatter : {
                   integer : {thousandsSeparator: "", defaultValue: '0'},
                   number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'}
                 },
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                caption: "Material a Ingresar",pager: "#jqGridPager",
                onCellSelect: function(rowid,iCol,cellcontent,e) {
                  i = $("#jqGrid").getGridParam("reccount");
                  //alert(cellcontent+' '+rowid+' total:'+i);
                }
            });
            $('#jqGrid').navGrid('#jqGridPager',
                // the buttons to appear on the toolbar of the grid
                { edit: true, add: false, del: true, search: false, refresh: true, view: true, position: "left", cloneToTop: false },
                // options for the Edit Dialog
                {
                  editCaption: "The Edit Dialog",
                  template: template,
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

    if(check_grid($( "#id_material" ).val())=="ok"){
      if(($( "#id_material" ).val()!="")&&($( "#material" ).val()!="")){
        if($( "#cantidad" ).val()>0){
          var myfirstrow = {id_material:$( "#id_material" ).val(),barcode:$( "#barcode" ).val(),material:$("#material").val(), unidad:$( "#unidad" ).val(), cantidad:$( "#cantidad" ).val(), costo_ult:$( "#costo_ult" ).val()};
          i = $("#jqGrid").getGridParam("reccount");
          i++;
          jQuery("#jqGrid").addRowData(i, myfirstrow);
          Limpiar_campos();
        }else{
          alert('Cantidad debe ser positivo');
          $( "#cantidad" ).val(0);
          $( "#cantidad" ).focus();
        }
      }
    }else{
      Limpiar_campos();
      $( "#barcode" ).focus();
    }
  }

  function check_alta_mat(){

    var msg="";
    if($("#frm_id_clase").val()==""){
      msg ="<br>- Falta seleccionar la clase";
    }
    if($("#frm_id_sub_clase").val()==""){
      msg +="<br>- Falta seleccionar la Sub clase";
    }
    if($("#frm_material").val()==""){
      msg +="<br>- Falta descripcion del material";
    }
    if($("#frm_unidad").val()==""){
      msg +="<br>- Falta seleccionar la unidad";
    }

    if(msg!=""){
      msg ="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:2px 7px 50px 2px;\"></span>"+msg+"<br>&nbsp</p>";
      $("#dialogo").hide();
      $("#dialogo").removeClass();
      $("#dialogo").removeAttr();
      $("#dialogo").html(msg);
      $("#dialogo").dialog({
        modal: true,
        height: 250,
        width: 350,
        resizable: true,
        title: "Faltan Datos Materiales",
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
      alta_material();
    }

  }

    function check_alta_prov(){

    var msg="";
    /*
    if($("#frm_cuit").val()==""){
      msg ="<br>- Falta introducir el CUIT";
    }*/
    if($("#frm_proveedor").val()==""){
      msg +="<br>- Falta razón social del proveedor";
    }/*
    if($("#frm_direccion").val()==""){
      msg +="<br>- Falta la dirección del proveedor";
    }
    if($("#frm_telefono").val()==""){
      msg +="<br>- Falta Telefono del proveedor";
    }
    if($("#frm_fax").val()==""){
      msg +="<br>- Falta Fax del proveedor";
    }
    if($("#frm_email").val()==""){
      msg +="<br>- Falta email del proveedor";
    }*/

    if(msg!=""){
      msg ="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:2px 7px 50px 2px;\"></span>"+msg+"<br>&nbsp</p>";
      $("#dialogo").hide();
      $("#dialogo").removeClass();
      $("#dialogo").removeAttr();
      $("#dialogo").html(msg);
      $("#dialogo").dialog({
        modal: true,
        height: 250,
        width: 350,
        resizable: true,
        title: "Faltan Datos del Proveedor",
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
      alta_proveedor();
    }

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

  function alta_material(){
    $("#loader").hide();
    $("#loader").load("<?php echo base_url();?>index.php/material/alta_material",
      {
        frm_id_clase:$("#frm_id_clase").val(),
        frm_id_sub_clase:$("#frm_id_sub_clase").val(),
        frm_material:$("#frm_material").val(),
        frm_unidad:$("#frm_unidad").val(),
        frm_Codebar:$("#frm_Codebar").val()
      });
    $("#loader").dialog({
        modal: true,
        height: 325,
        width: 500,
        resizable: true,
        title: "Alta Material",
        buttons:[
          {
            text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
            click: function(){
                if($("#frm").val()!="No"){
                  $( "#id_material" ).val($("#new_codigo").val());
                  $(this).dialog("close");
                  $( "#id_material" ).focus();
                }else{
                  $(this).dialog("close");
                }
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
    $("#loader").dialog('open')
  }
//==================================================
  function alta_proveedor(){

    $("#loader").hide();
    $("#loader").load("<?php echo base_url();?>index.php/entradas/alta_proveedor",
    {
      "frm_cuit":$("#frm_cuit").val(),
      "frm_proveedor":$("#frm_proveedor").val(),
      "frm_direccion":$("#frm_direccion").val(),
      "frm_telefono":$("#frm_telefono").val(),
      "frm_fax":$("#frm_fax").val(),
      "frm_email":$("#frm_email").val()
    });
    $("#loader").dialog({
      modal: true,
      height: 325,
      width: 550,
      resizable: true,
      title: "Alta Proveedor",
      buttons:[
        {
          text:"Aceptar",
            icons:{
              primary:"ui-icon-circle-check"
            },
          click: function(){
              if($("#frm").val()!="No"){
                $( "#id_proveedor" ).val($("#new_codigo").val());
                $( "#proveedor" ).val($("#frm_proveedor").val());
                $(this).dialog("close");
                $( "#barcode" ).focus();
              }else{
                $(this).dialog("close");
              }
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
    $("#loader").dialog('open')
  }
//==================================================    
  

  function Limpiar_campos(){
    $( "#id_material" ).val('');
    $( "#material" ).val('');
    $( "#unidad" ).val('');
    $( "#barcode" ).val('');
    $( "#cantidad" ).val('');
    $( "#costo_ult" ).val('');
    $( "#barcode" ).focus();
  }

  function ver_remito(remito, proveedor){
    //alert('remito');
    window.open('<?php echo base_url()?>index.php/entradas/remito?remito='+remito+'&id_proveedor='+proveedor,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');

  }

  function print_entrada(nro,deposito){
    //alert('remito');
    window.open('<?php echo base_url()?>index.php/entradas/print_entrada?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');

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
        customData: "bla bla bla",
        id_deposito: $("#id_deposito").val(),
        id_tipo_mov: $("#id_tipo_mov").val(),
        id_proveedor: $("#id_proveedor").val(),
        id_tipo_compra: $("#id_tipo_compra").val(),
        id_compra: $("#id_compra").val(),
        remito: $("#remito").val(),
        centro_costo: $("#centro_costo").val(),
        fecha:$("#fecha").val()
      }
      $.post('entradas/add_stock',
        data,
        function(returnedData){
          console.log(returnedData);
          //alert("Trajo data");
    //==========================================
          $( "#nro" ).val( returnedData['nro'] );
          $("#id_entrada").val(returnedData['id_entrada']);
          if($( "#nro" ).val()!="ERROR"){
            $("#nro").html($( "#nro" ).val());
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se genero la entrada Nro. "+$( "#nro" ).val()+"\"</p>"
          }else{
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+$( "#nro" ).val()+"\"<br>"+data[0]['msg']+"</p>"
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
            title: "Entrada de Materiales",
            buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                $(this).dialog("close");
                Bloquear();
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
      info('Error','Debe indicar el material a ingresar para poder grabar la entrada','error');
    }
  }

  function Bloquear(){
    $('button').prop('disabled','disabled');
    $('input').prop('disabled',true);
    //$('#grabar').prop('disabled',true);
    //$("select").selectmenu("option","disabled",true);
    $("select").attr('disabled','disabled');
    var td=('#edit_'+'jqGrid');
    //alert(td);
    $(td).hide();
    var td=('#del_'+'jqGrid');
    $(td).hide();
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

  function help_window(){
     window.open('../../tutoriales/deposito/entrada_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
  }

//====================================================

function ValidarForm(){
    msg="";
    if($("#id_deposito").val().length==0||$("#id_deposito").val()==null){
      ErrorClass("#id_deposito");
      msg=msg+"<br>- Debe especificar un <b>Deposito</b><br>";
    }
    if($("#id_proveedor").val()==""){
      ErrorClass("#proveedor");
      msg=msg+"- Debe especificar un <b>Proveedor</b><br>";
    }
    if($("#id_tipo_mov").val()==""){
      ErrorClass("#id_tipo_mov");
      msg=msg+"- Debe especificar el <b>Tipo de Entrada</b><br>";
    }
    if($("#id_compra").val()==""){
      ErrorClass("#id_compra");
      msg=msg+"- Debe especificar un <b>Nro. Identificatorio de Compra</b><br>";
    }
    if($("#id_tipo_compra").val()==""){
      ErrorClass("#id_tipo_compra");
      msg=msg+"- Debe especificar un <b>Tipo de Compra</b><br>";
    }
    if($("#fecha").val()==""){
      ErrorClass("#fecha");
      msg=msg+"- Debe especificar una <b>Fecha</b><br>";
    }
    if($("#remito").val()==""){
      ErrorClass("#remito");
      msg=msg+"- Debe especificar un <b>nro. de remito</b><br>";
    }
    if($("#centro_costo").val()==""){
      ErrorClass("#centro_costo");
      msg=msg+"- Debe especificar un <b>Centro de Costo</b><br>";
    }
    if(msg==""){
      msg="OK";
    }
    return msg;
  }

//====================================================

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

  jQuery.fn.center = function(){
    this.css("position","absolute");
    this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
    this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
  }

});
</script>
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
    .ui-icontool-help { background-position: -96px -128px; }
    /*.ui-icontool-clave { background-position: -128px -96px; }
    .ui-icontool-medida { background-position: -160px -96px; }
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
     /* border: solid 1px red;*/
      resize: none;
    }
    .celda_long{
      float: left;
      min-height: 3em;
      width: 40em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.1em;
      padding-bottom: 0.1em;
     /* border: solid 1px green;*/
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
     /* border: solid 1px yellow;*/
      resize: none;
    }

  </style>


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
  <div>
    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
      <li class="ui-state-default ui-corner-all" title="Ingreso de Material">
        <a href="<?php echo base_url().'index.php/entradas'; ?>">
          <span class="ui-icontool ui-icontool-entrada"></span>
        </a>
      </li>
      <li class="ui-state-default ui-corner-all" title="Buscar">
        <span id="Buscar" class="ui-icontool ui-icontool-buscar"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="Grabar">
        <span id="grabar" class="ui-icontool ui-icontool-grabar2"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="Alta Catalogo">
        <span id="catalogo" class="ui-icontool ui-icontool-catalogo"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="Alta Proveedor">
        <span id="alta_proveedor" class="ui-icontool ui-icontool-proveedor"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="Imprimir">
        <span id="imprimir" class="ui-icontool ui-icontool-imprimir"></span>
      </li>
      <li class="ui-state-default ui-corner-all" title="menu">
        <a href="<?php echo base_url().'index.php/menu'; ?>">
          <span class="ui-icontool ui-icontool-menu"></span>
        </a>
      </li>
      <li class="ui-state-default ui-corner-all" title="help">
            <span id="help" class="ui-icontool ui-icontool-help"></span>
        </li>
    </ul>
  </div>
  <div class="linea">
    <div class="celda">
      <div>
        <label>Entrada Nro. </label>
      </div>
      <div id="nro" class="ui-state-default" style="height: 1.2em;width: 10em; padding: 2px;text-align: center;"></div>
      <input type="hidden" id="id_entrada" name="id_entrada">
      <input type="hidden" name="grilla" id="grilla">
    </div>
    <div class="celda_right">
      <div><label class="etiqueta">Fecha: </label></div>
      <input type="text" id="fecha" name="fecha" value=""  class="ui-widget " style="width:8em;text-align:center;">
    </div>
  </div>
  <div class="linea">
    <div class="celda_long">
      <div><label class="etiqueta">Deposito: </label></div>
      <select name="id_deposito" id="id_deposito" class="ui-widget" style="width:10em;">
          <option value="" Selected >----</option>
          <?php 
            for ($i = 0; $i < count($deposito); $i++) {
              echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
            }
          ?>
      </select>
    </div>
    <div class="celda_long">
      <div><label class="etiqueta">Movimiento: </label></div>
      <select name="id_tipo_mov" id="id_tipo_mov" class="ui-widget " style="width:12em;">
        <option value="" Selected >----</option>
        <?php 
          for ($i = 0; $i < count($movimiento); $i++) {
            echo "<option value=\"".$movimiento[$i]['value']."\">".$movimiento[$i]['label']."</option>";
          }
        ?>
      </select>
    </div>
    
  </div>
  <div class="linea">
    <div class="celda_long">
      <div><label class="etiqueta">Proveedor: </label></div>
      <input type="text" id="proveedor" name="proveedor" placeholder="Proveedor"  class="ui-widget " style="width:25em;">
      <input type="hidden" name="id_proveedor" id="id_proveedor">
    </div>
    <div class="celda_body_long">
      <div><label>Tipo de Compra: </label></div>
      <select name="id_tipo_compra" id="id_tipo_compra" class="ui-widget " style="width:12em;">
        <option value="" Selected >-----</option>
        <?php 
          for ($i = 0; $i < count($compra); $i++) {
            echo "<option value=\"".$compra[$i]['value']."\">".$compra[$i]['label']."</option>";
          }
        ?>
      </select>
    </div>
    <div class="celda">
      <div><label>Id.Compra: </label></div>
      <input type="text" id="id_compra" name="id_compra" placeholder="Id. compra"  class="ui-widget " style="width:8em;text-align:center;">
    </div>
    <div class="celda">
      <div><label class="etiqueta">Remito: </label></div>
      <input type="text" id="remito" name="remito" placeholder="Remito" size="12" maxlength="12" class="ui-widget " style="width:12em;">
    </div>
  </div>
  <div class="linea">
    <div class="celda_long">
      <div><label class="etiqueta">Centro de Costo: </label></div>
      <input type="text" name="centro_costo" id="centro_costo" placeholder="Centro de Costo"  class="ui-widget " style="width:12em;text-align:center;">
    </div>
  </div>
	<div class="linea" >
    <div class="ui-state-default " style="margin: auto; min-height:3em; padding:auto;resize: none;overflow: visible;max-width: 70em">
      <div class="celda_body">
        <label>Barcode: </label><input type="text" id="barcode" class="ui-widget ui-widget-content " style="float:right; width:100%;text-align:center;" placeholder="Barcode">
      </div>
      <div class="celda_body_long">
        <label>Descripción: </label><input id="material" type="text" class="ui-widget ui-widget-content " style="float:right; width:100%;" placeholder="Descripcion material">
        <input id="id_material" type="hidden">
        <input id="costo_ult" type="hidden">
      </div>
      <!--
      <div class="celda_body">
        <label>Código: </label><input id="id_material" type="text" class="ui-widget ui-widget-content " style="float:right; width:100%;text-align:center;" placeholder="Código">
      </div>-->
      <div class="celda_body">
        <label>Unidad: </label><input id="unidad" type="text" class="ui-widget ui-widget-content " style="float:right; width:100%;text-align:center;" placeholder="" readonly>
      </div>
      <div class="celda_body">
        <label>Cantidad: </label><input type="text" id="cantidad" class="ui-widget ui-widget-content " style="float:right; width:100%;text-align:center;" placeholder="0.00"><input type="hidden" id="stock"><input type="hidden" id="equipo">
      </div>
   <!--    <div class="celda_body">
        <label>Ubicacion: </label><input type="text" id="ubicacion" class="ui-widget ui-widget-content ui-corner-all" style="float:right; width:100%;text-align:center;" placeholder="Ubicacion"><input type="hidden" id="id_ubicacion">
      </div> -->
      <div  style="padding: 5px;float: left;">
        <button id="add">add</button>
        <button id="limpiar">limpiar</button>
     <!--    <button id="refresh">Actualizar</button> -->
      </div>
    </div>
  </div>
  <div class="linea">
    <div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
       <div style="margin: auto; width: 78em; height:3em; padding:0.4em;">
        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
      </div>
    </div>
  </div>
  <!--
  <h1>
    <?php

    //print_r($this->session->userdata());
    //print $session->get('app_hash_id');

    ?>
  </h1> -->
  <div id="loader"></div>
  <div id="dialogo"></div>
</body>