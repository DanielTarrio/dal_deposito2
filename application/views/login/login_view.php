<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?php echo base_url(); ?>js/bowser.js"></script>

<script>
  $(function() {

  	//alert(bowser.name+' '+bowser.version);
  	//$.ready(function(){
  	
	//});

  	$( "#help" ).button({
	   icon: {icon:"ui-icon-help"}, label: "Ayuda"
	});

	$("input").focusin(function(){
		nav_check();
	});
  	

  	

    $( "#login" ).dialog({
    	draggable: false,
    	resizable: false,
    	buttons: [
		    {
		      text: "Login",
		      icons: {
		        primary: "ui-icon-person"
		      },
		      click: function() {
		        //$( this ).dialog( "close" );
		        $( "#form1" ).submit();
		      }
		 
		      // Uncommenting the following line would hide the text,
		      // resulting in the label being used as a tooltip
		      //showText: false
		    }
	    ]
    });
    
    

    function nav_check(){
    	if(bowser.msie){
	  		if(parseInt(bowser.version)<11){
	  			$("#nav").removeClass();
	        	$("#nav").removeAttr();
	  			var alerta='El navegador '+bowser.name+' version:'+bowser.version+' no es compatible con la aplicacion. Comuniquese con Mesa de Ayuda Int. 1240';
	  			var alerta="<p class=\"ui-state-error ui-corner-all\"><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;\"></span></p><div>"+alerta+"</div>";
	  			$("#nav").html(alerta);
	  			$( "#nav" ).dialog({
		    	draggable: false,
		    	resizable: false,
		    	//autoOpen: false,
		    	modal: true,
		    	buttons: [
				    	{
					      text: "Aceptar",
					      icons: {
					        primary: "ui-icon-alert"
					      },
					      click: function() {
					        $( this ).dialog( "close" );
				      	}
				    	}
				    ]
			    });

	  			$("#nav").dialog("open");

	  			//alert("Debe actualizar su navegador");
  			}
  		}
    }

    $("#help").click(function(){
      help_window();
    });
    function help_window(){
     window.open('../../tutoriales/deposito/menu_hlp.php','Tutorial','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=900,height=700,left=200,top=150');
  	}
  });
  </script>
<body>

	
	<div style="margin: 50px">
		<div style="padding: 0 .7em; margin: .5em;float: right;width: 5em">
			<button id="help">
			</button>
		</div>
		<div>
			<h1>Ingreso a Gestion de Materiales Pañol</h1>
		</div>
		
	</div>
	<div id="login" title="Login">
		<?php
			$attr_txt = array('id' => 'form1');
			echo form_open('/login/login_usuario',$attr_txt);
		?>
	  <div style="width:15em;margin:0.5em;">
	  	<?php
			$attr_txt=array(
				'name'=>'usr',
				'placeholder'=>'usuario',
				'class'=>'ui-widget',
				'style' => 'width:12em;'
				);
			$attr_lbl = array(
					'style' => 'display:inline-block;vertical-align:middle;width:9em;'
				);
			echo form_label('usuario:','usr',$attr_lbl);
			echo form_input($attr_txt);
		?>
	  </div>
	  <div style="width:15em;margin:0.5em;">
	  	<?php
	  		$attr_txt=array(
				'name'=>'psw',
				'placeholder'=>'password',
				'type'=>'password',
				'class'=>'ui-widget',
				'style' => 'width:12em;'
				);
			echo form_label('password:','psw',$attr_lbl);
			echo form_input($attr_txt);
		?>
	  </div>
	  	<?php
			echo form_close();
		?>
	</div>

	<div id="nav"></div>
	<?php  if($msg!=""){ ?>
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; margin: .5em;">
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;">
				</span>
				<strong>Error</strong> 
				<?php echo $msg ?>
			</p>
		</div>
	<?php } ?>
	<div style="width: 100px;position: absolute;bottom: 10px;right: 10px"><h4>Versión: 1.0</h4></div>
</body>