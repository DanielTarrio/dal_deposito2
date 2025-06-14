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
  $("#alta_perfil").click(function(){
    add_perfil();
  });

  /*$( "#id_perfil" ).selectmenu({
    change:function(event, ui){
      //alert("ups");
      Refrescar();
    }
  });*/
  $( "#id_perfil" ).change(function(){
      //alert("ups");
      Refrescar();
  });

  $("#help").click(function(){
      help_window();
  });

/*  $.datepicker.setDefaults({
    showOn: "button",
    buttonImage:"images/calendar.gif",
    buttonImageOnly:true
  });*/
  
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

//======================================================

   $('#tree').jqGrid({
        "width":"450",
        "hoverrows":false,
        "viewrecords":false,
        "gridview":false,
        "url":"permisos?id_perfil="+$('#id_perfil').val(),
        "editurl" : "usuarios",
        "ExpandColumn":"name",
        "height":"350",
        "sortname":"id_aplicacion",
        "scrollrows":true,
        "treeGrid":true,
        "treedatatype":"json",
        "treeGridModel":"nested",
        "loadonce":true,
        "rowNum":1000,
        "treeReader":{
          "left_field":"lft",
          "right_field":"rgt",
          "level_field":"level",
          "leaf_field":"isLeaf",
          "expanded_field":"expanded",
          "loaded":"loaded",
          "icon_field":"icon"
        },
        "treeIcons" : {
          "plus": "ui-icon-circlesmall-plus",
          "minus": "ui-icon-circlesmall-minus",
          "leaf" : "ui-icon-arrow-1-e"
        },
        "datatype":"json",
        "colModel":[
          {
            "name":"id_aplicacion",
            "index":"id_aplicacion",
            "sorttype":"int",
            "key":true,
            "hidden":true,
            "width":50
          },{
            "name":"name",
            "index":"name",
            "sorttype":"string",
            "label":"Aplicacion",
            "editable":true,
            "width":300
          },{
            "name":"acceso",
            "formatter":"checkbox",
            "align":"center",
            "editable":true,
            "hidden":false,
            "width":30
          },{
            "name":"lft",
            "hidden":true
          },{
            "name":"rgt",
            "hidden":true
          },{
            "name":"level",
            "hidden":true
          },{
            "name":"id_permiso",
            "hidden":true
          },{
            "name":"uiicon",
            "hidden":true
          }
        ],
        caption:"Permisos a Aplicaciones",
        "onSelectRow" : function( rowid ) {
          if(rowid) 
          {
            var rdata = $('#tree').jqGrid('getRowData', rowid);
            id=rdata.id_permiso;
            app=rdata.name;
            id_aplicacion=rdata.id_aplicacion;
            //alert(rdata.acceso);
            if(rdata.acceso=='Yes'){
              Desautorizar(app,id_aplicacion);
            }else{
              Autorizar(app,id_aplicacion);
            }
            
            
            //if(rdata.isLeaf === 'true') {
            //  $("#<?php //echo $id; ?>").val(rdata.id_ubicacion);
            //  $("#<?php //echo $label; ?>").val(rdata.ub_path);
            //alert(rdata.id_aplicacion+' '+rdata.id_permiso);
            //alert($(this).jqGrid('getCell', rowid,'name'));

            //}
          } 
        },
          "sortorder":"asc",
          "pager":"#pager"
      });
      jQuery('#tree').jqGrid('navGrid','#pager',
        { edit: false, add: false, del: false, search: false, refresh: true, view: false, position: "left", cloneToTop: false },

        {
          editCaption: "Editar Ubicacion",
          //template: template,
          url: 'usuarios/add_permiso' ,
          mtype: "GET",
          closeAfterEdit: true,
          onClose:function(){Refrescar();},
          reloadAfterSubmit:true,
          errorTextFormat: function (data) {
            return 'Error: ' + data.responseText
          }
        },
        // options for the Add Dialog
        {
          addCaption: "Alta Ubicacion",
          //template: template,
          url: 'usuarios/add_permiso' ,
          mtype: "GET",
          closeAfterAdd: true,
          reloadAfterSubmit:true,
          onClose:function(){Refrescar();},
          reloadAfterSubmit:true,
          errorTextFormat: function (data) {
            return 'Error: ' + data.responseText
          }
        },
        // options for the Delete Dailog
        {
          Caption: "Eliminar Ubicacion",
          url: 'usuarios/del_permiso' ,
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


//======================================================  

  function Refrescar(){
    //alert($('#id_perfil').val());
    $("#tree").jqGrid("clearGridData", true);
    $('#tree').jqGrid('setGridParam',{ 
        "url":"permisos?id_perfil="+$('#id_perfil').val(),
        "editurl" : "usuarios",
        "ExpandColumn":"name",
        "height":"200",
        "sortname":"id_aplicacion",
        "scrollrows":true,
        "treeGrid":true,
        "treedatatype":"json",
        "treeGridModel":"nested",
        "loadonce":true,
        "rowNum":1000,
        "treeReader":{
          "left_field":"lft",
          "right_field":"rgt",
          "level_field":"level",
          "leaf_field":"isLeaf",
          "expanded_field":"expanded",
          "loaded":"loaded",
          "icon_field":"icon"
        },
        "treeIcons" : {
          "plus": "ui-icon-circlesmall-plus",
          "minus": "ui-icon-circlesmall-minus",
          "leaf" : "ui-icon-arrow-1-e"
        },
        "datatype":"json"
    });
    $("#tree").trigger("reloadGrid");
  }


  //=========================================================

  $("#jqGridMov").jqGrid({
      url: "get_permiso_movimiento?id_perfil="+$('#id_perfil').val(),
      mtype: "GET",
      datatype: "json",
      editurl:'clientArray',
      page: 1,
      colModel: [
          {
              label: "Tipo de Movimiento",
              name: 'tipo',
              width: 240,
              key:true,
              hidden: false,
              align:'left',
              editable: false,
              editrules : { required: true}
          },
          {
              label: "mov.",
              name: 'movimiento',
              width: 50,
              editable: true,
              align:'center',
              editrules : { required: true},
              // stype defines the search type control - in this case HTML select (dropdownlist)
              // searchoptions value - name values pairs for the dropdown - they will appear as options
              
              
          },
          {
              label: "id_permiso_mov.",
              name: 'id_permiso_mov',
              width: 50,
              editable: true,
              align:'center',
              formatter:"checkbox"
              // stype defines the search type control - in this case HTML select (dropdownlist)
              // searchoptions value - name values pairs for the dropdown - they will appear as options
              
              
          }                   
      ],
      loadonce: true,
      viewrecords: true,
      width: 340,
      height: 350,
      rowNum: 15,
      caption:"Permisos de Movimientos",
      rownumbers:false,
      rownumWidth:25,
      pager: "#jqGridMovPager",
      onCellSelect: function(rowid,iCol,cellcontent,e) {
        i = $("#jqGridMov").getGridParam("reccount");
        //alert(cellcontent+' '+rowid+' total:'+i+" iCol:"+iCol);
        //disponible=parseFloat($("#jqGridMov").jqGrid('getCell',rowid,'nro'))+' '+$("#jqGridMov").jqGrid('getCell',rowid,'id_deposito');
        //alert(disponible);
       /* nro=$("#jqGridMov").jqGrid('getCell',rowid,'nro')
        deposito=$("#jqGridMov").jqGrid('getCell',rowid,'id_deposito');
        window.open('<?php echo base_url()?>index.php/salidas/vale?nro='+nro+'&id_deposito='+deposito,'Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=300,left=200,top=300');*/
      }
        });
    // activate the toolbar searching
    //$('#jqGridMov').jqGrid('filterToolbar');
    $('#jqGridMov').jqGrid('navGrid',"#jqGridMovPager", {                
            search: false, // show search button on the toolbar
            add: true,
            edit: true,
            del: false,
            refresh: true
        },
        {
      editCaption: "Editar Deposito",
      width:500,
      //template: template,
      url: 'deposito/edit_deposito' ,
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
      addCaption: "Insertar Deposito",
      //template: template,
      url: 'deposito/add_deposito' ,
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

  //=========================================================

  function Autorizar(app,id_aplicacion){
    $("#dialogo").removeClass();
    $("#dialogo").removeAttr();
    var msg="Desea dar acceso a la aplicacion <b>"+app+"</b>";
    var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
      $("#dialogo").html(msg);
      $("#dialogo").dialog({
        modal: true,
        height: 150,
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
              set_autorizar($("#id_perfil").val(),id_aplicacion)
            }
          },{
            text:"Cancelar",
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
    

      //---------------------
  }
  function Desautorizar(app,id_aplicacion){

      var msg="Desea quitar el acceso a la aplicacion <b>"+app+"</b>";
      var msg="<p class=\"ui-state-highlight ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
      $("#dialogo").html(msg);
      $("#dialogo").dialog({
        modal: true,
        height: 150,
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
              set_desAutorizar($("#id_perfil").val(),id_aplicacion);
            }
          },{
            text:"Cancelar",
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
    
    //------------------------------
  }
//---------------------------------------------
  function set_autorizar(id_perfil,id_aplicacion){


    $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
    $("#loader").css({
      width:$(window).width(),
      height:$(window).height()
    });
    $("#loader").center();
    $("#gif-load").center();

    var data = {
      'id_perfil': id_perfil,
      'id_aplicacion': id_aplicacion
    }
    $.post('add_permiso',
      data,
      function(returnedData){
        console.log(returnedData);
        alert("Trajo data "+returnedData);
        var RowPermisos=returnedData;
  //==========================================
        if(RowPermisos!=0){
          var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se otorgo permiso para la aplicacion "+app+"\"</p>"
        }else{
          var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+"\"<br>"+data[0]['msg']+"</p>"
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
              Refrescar();
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
                $(this).dialog('destroy');
                $(this).hide();
              }
            }
          ]
        });
    });

  }

  function set_desAutorizar(id_perfil,id_aplicacion){

    $("#loader").html('<div class="ui-widget-overlay ui-front"><div class="ui-state-highlight ui-corner-all" style="padding:0.4em" id="gif-load"><img src="<?php echo base_url();?>images/loading-gears.gif" style="width:100px;height:100px"/><h3>Cargando...</h3></div></div>');
    $("#loader").css({
      width:$(window).width(),
      height:$(window).height()
    });
    $("#loader").center();
    $("#gif-load").center();

    var data = {
      'id_perfil': id_perfil,
      'id_aplicacion':id_aplicacion
    }
    $.post('del_permiso',
      data,
      function(returnedData){
        console.log(returnedData);
        alert("Trajo data "+returnedData);
        var RowPermisos=returnedData;
  //==========================================
        if(RowPermisos!=0){
          var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Se quitaron los privilegios de "+RowPermisos+" aplicaciones\"</p>"
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
              $(this).dialog('destroy');
              $(this).hide();
              Refrescar();
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
        var msg="Error en la peticion AJAX"
        var msg="<p class=\"ui-state-error ui-corner-all\" style=\"font-size:120%;padding:10px\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 80% 0;\"></span>"+msg+"</p>";
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
                $(this).dialog('destroy');
                $(this).hide();
              }
            }
          ]
        });
    });
  }

  function add_perfil(){

    $("#loader").hide();
    $("#dialogo").removeClass();
    $("#dialogo").removeAttr();
    var msg="<p><span class=\"ui-icon ui-icon-person\" style=\"float:left;margin:0 7px 50px 0;\"></span><label>Perfil: </label><input type=\"text\" id=\"frm_perfil\" name=\"frm_perfil\" placeholder=\"perfil\"  class=\"ui-widget ui-corner-all\" style=\"width:16em;margin:0.4em\"></p>";
    $("#dialogo").html(msg);
    $("#dialogo").dialog({
      modal: true,
      height: 175,
      width: 350,
      resizable: true,
      title: "Alta de Perfil",
      buttons:[
        {
          text:"Aceptar",
            icons:{
              primary:"ui-icon-circle-check"
            },
          click: function(){
            //let perfil=
            $(this).dialog("close");
            set_perfil($("#frm_perfil").val());
          }
        },{
          text:"Cancelar",
            icons:{
              primary:"ui-icon-circle-close"
            },
          click: function(){
            $(this).dialog("close");
            $(this).dialog('destroy');
            $(this).hide();
          }
        }
      ]
    });
  }

  //==============================================
  function set_perfil(perfil){
    alert(perfil);
      
      var data = {
        perfil: perfil
      }
      
      $.post('set_perfil',
        data,
        function(returnedData){
          console.log(returnedData);
          if(returnedData['estatus']!="ERROR"){
            
            var msg="<p><span class=\"ui-icon ui-icon-check\" style=\"float:left;margin:0 7px 50px 0;\"></span>\""+returnedData['msg']+"\"</p>";
          }else{
            var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"ERROR# "+returnedData['msg']+"\"<br></p>";
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
            title: "Alta de Perfil",
            buttons:[
            {
              text:"Aceptar",
              icons:{
                primary:"ui-icon-circle-check"
              },
              click: function(){
                $(this).dialog("close");
                $("#frm").dialog("close");
                Refrescar();
                //jQuery("#jqGridVale").addRowData(i, data);

                //jQuery("#jqGridVale").setRowData(i, data);
                //Bloquear();
              }
            }
            ]
          });
    //==========================================      
        }
        ).fail(function(){
          console.log("Error");
    //==========================================
        /*  $("#loader").hide();
          $("#loader").removeClass();
          $("#loader").removeAttr();*/
          $("#dialogo").removeClass();
          $("#dialogo").removeAttr();
          var msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span>\"Error en la peticion AJAX\"</p>";
          $("#dialogo").html(msg);
          $("#dialogo").dialog({
            modal: true,
            height: 200,
            width: 300,
            resizable: true,
            title: "Alta de Perfil",
            buttons:[
              {
                text:"Aceptar",
                  icons:{
                    primary:"ui-icon-circle-check"
                  },
                click: function(){
                  $(this).dialog("close");
                  $("#frm").dialog("close");
                }
              }
            ]
          });
      });
      
    }
//============================================


  //==============================================

  function help_window(){
     window.open('<?php echo base_url()?>help/entrada_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
  }

  jQuery.fn.center = function(){
    this.css("position","absolute");
    this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
    this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
  }



//---------------------------------------------
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
    .ui-icontool-reporte { background-position: -224px -64px; }

    .ui-icontool-catalogo { background-position: 0px -32px; }
    .ui-icontool-costo { background-position: -32px -32px; }
    .ui-icontool-imprimir { background-position: -64px -32px; }
    .ui-icontool-deposito { background-position: -96px -32px; }
    .ui-icontool-usuario { background-position: -128px -32px; }
    .ui-icontool-perfil { background-position: -64px -96px; }
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
    .ui-icontool-help { background-position: -96px -128px; }
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

  </style>


<body>
  <div class="ui-widget-header" style="padding-left: 1em"><?php echo $app; ?></div>
	<div>
	    <ul id="iconstool" class="ui-widget ui-helper-clearfix ui-state-default" style="margin:0px 0px 3px -40px;">
	    	<li class="ui-state-default ui-corner-all" title="Menu">
		        <a href="<?php echo base_url().'index.php/menu/crear_menu/configuracion'; ?>">
		        	<span class="ui-icontool ui-icontool-menu"></span>
		        </a>
	    	</li>
        <li class="ui-state-default ui-corner-all" title="Alta Peerfil">
            <span id="alta_perfil" class="ui-icontool ui-icontool-perfil"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="help">
              <span id="help" class="ui-icontool ui-icontool-help"></span>
        </li>
	    </ul>
	</div>
	<div class="linea">
    <div class="celda_long">
      <label class="etiqueta" style="font-size: 120%">Perfil: </label>
      <select name="id_perfil" id="id_perfil" class="ui-widget ui-corner-all" style="font-size: 120%">
          
          <?php 
            for ($i = 0; $i < count($perfiles); $i++) {
              echo "<option value=\"".$perfiles[$i]['value']."\">".$perfiles[$i]['label']."</option>";
            }
          ?>
      </select>
    </div>
  </div>
  
  <div class="ui-corner-all" style="margin:3px; padding:10px 3px 10px 3px">
     <div style="margin: auto; width: 40em; height:3em; padding:0.4em; float: left; margin-left:50px">
      <table id="tree"></table>
      <div id="pager"></div>
    </div>
  
     <div style="margin: auto; width: 40em; height:3em; padding:0.4em; float: right;">
      <table id="jqGridMov"></table>
      <div id="jqGridMovPager"></div>
    </div>
  </div>
  
  <div id="loader"></div>
  <div id="dialogo"></div>
</body>
