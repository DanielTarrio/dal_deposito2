<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">

</style>
<div class="linea ui-widget-header" style="margin: 10px;">
	<div class="celda_body_short" style="margin: 10px;">Pedido</div>
	<div class="celda" style="margin: 10px;">Dependencia</div> 
	<div class="celda" style="margin: 10px;">Sector</div>
  <div class="celda" style="margin: 10px;">Direccion</div>
  <div class="celda_body_short" style="margin: 10px;">Localidad</div>
	<div class="celda_body_short" style="margin: 10px;">Solicitante</div>
	<div class="celda_body_short" style="margin: 10px;">Bultos</div>
	<div class="celda_body_short" style="margin: 10px;">Excluir</div>
  <div class="celda_body_short" style="margin: 10px;">Etiqueta</div>
</div>
<ul id="ruta">
	<?php 
		/*for ($i = 0; $i < count($deposito); $i++) {
		  echo "<option value=\"".$deposito[$i]['value']."\">".$deposito[$i]['label']."</option>";
    	}*/
    	//print_r($recorrido);

    	for ($i=0; $i < count($recorrido) ; $i++) { 
        $tmp='<li id="'.$recorrido[$i]['id_salida'].'" class="linea ui-state-default">';
        $label="<div class=\"celda_body_short\"><b>".$recorrido[$i]['nro_pedido']."</b></div>";
        $label.="<div id='dep".$recorrido[$i]['id_salida']."' class=\"celda\">".$recorrido[$i]['dependencia']."<input type='hidden' name='id_dep".$recorrido[$i]['id_salida']."' id='id_dep".$recorrido[$i]['id_salida']."' value='".$recorrido[$i]['id_dependencia']."'></div>";
        //$label.="<div id='Zona".$recorrido[$i]['id_salida']."' class=\"celda\">".$recorrido[$i]['Zona']."</div>";
        $label.="<div id='sect".$recorrido[$i]['id_salida']."' class=\"celda\">".$recorrido[$i]['sector']."</div>";
        $label.="<div id='Dir".$recorrido[$i]['id_salida']."' class=\"celda\">".$recorrido[$i]['Direccion']."</div>";
        $label.="<div id='Loc".$recorrido[$i]['id_salida']."' class=\"celda_body_short\">".$recorrido[$i]['Localidad']."</div>";
        $label.="<div id='dest".$recorrido[$i]['id_salida']."' class=\"celda_body_short\">".$recorrido[$i]['destino']."</div>";
        $label.="<div id='blto".$recorrido[$i]['id_salida']."' class=\"celda_body_short\">".$recorrido[$i]['bultos']."<input type='hidden' name='obs".$recorrido[$i]['id_salida']."' id='obs".$recorrido[$i]['id_salida']."' value=''><input type='hidden' name='agr".$recorrido[$i]['id_salida']."' id='agr".$recorrido[$i]['id_salida']."' value=''></div>";
        $tmp.='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$label.'<div class="celda_body_short"><span class="ui-icon ui-icon-arrowthick-1-e" title="Excluir de la programación" id="Ex'.$recorrido[$i]['id_salida'].'" onclick="excluir(\''.$recorrido[$i]['id_salida'].'\')"></span></div>';
        $tmp.='<div class="celda_body_short"><span class="ui-icon ui-icon-tag" title="Imprimir Etiqueta" id="Etq'.$recorrido[$i]['id_salida'].'" onclick="excluir(\''.$recorrido[$i]['id_salida'].'\')"></span></div>';
        $tmp.='</li>';
        echo $tmp;
    	}
	?>
</ul>
<script type="text/javascript">

  var chrCode=64;
  var program =[];
  var sector =[];
  var id_dependencia =[];
  var destino =[];
  var bultos =[];
  var obs=[];
  var agr=[];

  function array_const(){

    $("#elementos").val($("#ruta").sortable("toArray"));
    //alert($("#ruta").sortable("toArray").toString());
    var array_elem = $("#ruta").sortable("toArray");
    program=[];
    /*console.log($("#ruta").sortable("toArray").toString());
    console.log($("#ruta").sortable("toArray"));*/
    
    
    for (var i = array_elem.length - 1; i >= 0; i--) {
      program[i]=array_elem[i];
      sector[i]=$("div[id='sect"+array_elem[i]+"']").text();
      id_dependencia[i]=$("#id_dep"+array_elem[i]).val();
      destino[i]=$("div[id='dest"+array_elem[i]+"']").text();
      bultos[i]=$("div[id='blto"+array_elem[i]+"']").text();
      obs[i]=$("#obs"+array_elem[i]).val();
      agr[i]=$("#agr"+array_elem[i]).val();
    }

    console.log("Lelmentos:"+array_elem.length+" /n Program leght: "+program.length);

  }


  $("#ruta").sortable({
    placeholder:"ui-state-highlight",
    update: function(){
      console.log("Cambio de orden");
      var ordenElementos = $(this).sortable("toArray").toString();
      $("#elementos").val(ordenElementos);
      console.log(ordenElementos);
      /*$.get("cambia_orden.php",{nuevo_orden: ordenElementos},function(respuesta){
        alert(respuesta);
      });*/
    }
  });
  /*
  $("span[id^='Ex']").click(function(){
  /*$("span[id^='Ex']").on("click",function(){    
    alert($(this).attr('id'));
    var element=$(this).attr('id');
    element=element.substr(2);
    //alert(element);
    $("li[id='"+element+"']").remove(); 
  });
  */

  function excluir(id){

    array_const();
    $("li[id='"+id+"']").remove();
    console.log(id);
    
  }


  $("#add-item").click(function(){
    dlg_no_prg();
    /*add_lista();*/
  });

  function dlg_no_prg(){

      chrCode=chrCode+1;

      $("#dialogo").load("<?php echo base_url();?>index.php/recorridos/fuera_prog");
      $("#dialogo").dialog({
          modal: true,
          height: 450,
          width: 550,
          resizable: true,
          title: "No Programado",
          buttons:[
            {
              text:"Aceptar",
                icons:{
                  primary:"ui-icon-circle-check"
                },
              click: function(){
                  if($("#frm").val()=="OK"){
                    //alta_proveedor();
                    add_lista();
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
      $("#dialogo").dialog('open');


  }
  
  function add_lista(){

      var ad_li='<li id='+String.fromCharCode(chrCode)+' class="linea ui-sortable-handle ui-state-highlight" >';
      ad_li+='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
      ad_li+='<div class="celda_body_short"><b>'+String.fromCharCode(chrCode)+'</b></div>';
      ad_li+='<div id="dep'+String.fromCharCode(chrCode)+'" class="celda">'+$("#frm_dependencia").val()+'<input type="hidden" name="id_dep'+String.fromCharCode(chrCode)+'" id="id_dep'+String.fromCharCode(chrCode)+'" value="'+$("#frm_id_dependencia").val()+'"></div>';
      ad_li+='<div id="sect'+String.fromCharCode(chrCode)+'" class="celda">'+$("#frm_sector").val()+'</div>';
      ad_li+='<div id="Dir'+String.fromCharCode(chrCode)+'" class="celda">'+$("#frm_Direccion").val()+'</div>';
      ad_li+='<div id="Loc'+String.fromCharCode(chrCode)+'" class="celda_body_short">'+$("#frm_Localidad").val()+'</div>';
      ad_li+='<div id="dest'+String.fromCharCode(chrCode)+'" class="celda_body_short">'+$("#frm_solicitante").val()+'</div>';
      ad_li+='<div id="blto'+String.fromCharCode(chrCode)+'" class="celda_body_short">'+$("#frm_bultos").val()+'<input type="hidden" name="obs'+String.fromCharCode(chrCode)+'" id="obs'+String.fromCharCode(chrCode)+'" value="'+$("#frm_obs").val()+'"><input type="hidden" name="agr'+String.fromCharCode(chrCode)+'" id="agr'+String.fromCharCode(chrCode)+'" value="'+String.fromCharCode(chrCode)+'"></div>';
      ad_li+='<div class="celda_body_short">';
      ad_li+='<span class="ui-icon ui-icon-arrowthick-1-e" title="Excluir de la programación" id="Ex'+String.fromCharCode(chrCode)+'" onclick="javascript:excluir('+String.fromCharCode(chrCode)+');"></span></div>';
      ad_li+='<div class="celda_body_short"><span class="ui-icon ui-icon-tag" title="Imprimir Etiqueta" id="Etq'+String.fromCharCode(chrCode)+'" onclick="excluir('+String.fromCharCode(chrCode)+')"></span></div>';
      ad_li+='</li>';
      
      
      /*alert(ad_li);*/
      $("#ruta").append(ad_li);
      $("#dialogo").dialog("close");
    
  }


</script>
