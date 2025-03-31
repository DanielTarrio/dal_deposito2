<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Gestion de Materiales de Pañol</title>
</head>
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>images/dal.png">
<link href="<?php echo base_url(); ?>css/<?php echo EstiloCss;?>jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/<?php echo EstiloCss;?>estilo.css" rel="stylesheet">
<style>
/*	body{
		font: 70.5% "Verdana", sans-serif;
		margin: 50px;
		background-color: black;
		color: #f5f5f0;
	}
	#icons li {
		margin: 2px;
		position: relative;
		padding: 4px 0;
		cursor: pointer;
		float: left;
		list-style: none;
	}
	#icons span.ui-icon {
		float: left;
		margin: 0 4px;
	}*/
	#iconstool li {
		margin: 2px;
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
	width: 160px;
	height: 160px;
	}
	
	.ui-icontool-salida {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/salida96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-entrada {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/entrada96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-material {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/material96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-llave {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/llave96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-reporte {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/reporte2_96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-setup {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/setup96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-usuario {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/usuario96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-sectores {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/sectores96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-salir {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/salir96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-deposito {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/deposito96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-perfiles {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/perfiles96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-modmaterial {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/mod_material96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-costo {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/costo96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-medida {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/balanza96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-menu {
		background-image: url("<?php echo base_url(); ?>css/images/menu96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-personal {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/grupo96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-lugar {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/lugar96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-solicitud {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/solicitud96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-solicitud_web {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/solicitud_web96px.png");
		background-position:center;
		background-repeat: no-repeat;
	}
	.ui-icontool-recorridos {
		background-image: url("<?php echo base_url(); ?>css/<?php echo EstiloCss;?>images/camion96px.png");
		background-position:center;
		background-repeat: no-repeat;
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
    .celda_left{
      float: left;
      height: 3em;
      width: 20em;
      padding-left: 0.4em;
      padding-top: 0.1em;
      padding-right: 0.4em;
      padding-bottom: 0.1em;
      //border: solid 1px;
    }

</style>
<script src="<?php echo base_url();?>js/jquery.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script type="text/javascript">
	$(function() {
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



		$("div[id^='alr']").hover(
			function() {
				$( this ).addClass( "ui-state-hover" );
			},
			function() {
				$( this ).removeClass( "ui-state-hover" );
			}
		)
		.click(function(){
			reporte_alr($(this).attr('id'));
		});

		function reporte_alr(alr){
			
			if(alr=="alr_reposicion"){
				msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-error ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">Reporte de material de reposicion inmediata</p></p>";
			}else{
				msg="<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left;margin:0 7px 50px 0;margin:5px\"><p class=\"ui-state-highlight ui-corner-all\" style=\"width:250px;height:80px;padding:5px;font-size:120%\">Reporte de material de reposicion inmediata</p></p>";
			}
			
			$("#dialogo").hide();
			$("#dialogo").html(msg);
	        $("#dialogo").dialog({
	          modal: true,
	          height: 200,
	          width: 300,
	          resizable: true,
	          title: "Alertas de Stock",
	          buttons:[
	          {
	            text:"Excel",
	            icons:{
	              primary:"ui-icon-calculator"
	            },
	            click: function(){
	              $(this).dialog("close");
	              	if(alr=="alr_reposicion"){
	              		excel_reposicion();
					}else{
						excel_minimo();
					}
	            }
	          },
	          {
	            text:"Imprimir",
	            icons:{
	              primary:"ui-icon-print"
	            },
	            click: function(){
	            	$(this).dialog("close");
	              	if(alr=="alr_reposicion"){
	              		reporte_reposicion();
					}else{
						reporte_minimo();
					}
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
			$("#dialogo").dialog('open');
			
		}

		function excel_reposicion(){
			location.href="<?php echo base_url().'index.php/stock/excel_reposicion';?>";
		}

		function excel_minimo(){
			location.href="<?php echo base_url().'index.php/stock/excel_minimo';?>";
		}

		function reporte_reposicion(){
			window.open('<?php echo base_url()?>index.php/stock/reporte_reposicion','Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');
		}
		function reporte_minimo(){
			window.open('<?php echo base_url()?>index.php/stock/reporte_minimo','Imprimir','status=no,toolbar=no,resizable=yes,scrollbars=yes,width=535,height=400,left=300,top=200');
		}

		

	});
</script>
<body>
	<div style="margin: 20px;">
		<h1>Dirección de Apoyo Logistico</h1>
	</div>
	<div style="margin: 20px;">
		<h2>Gestión de Materiales de Pañol</h2>
	</div>
	<div class="ui-state-default ui-corner-all"  style="margin: 20px;width:30%">
		<h2 style="margin: 20px 20px 20px 20px">
			<?php echo utf8_encode($info['aplicacion']);?>
		</h2>
	</div>
	<div class="ui-widget ui-widget-content ui-corner-all" style="margin: 50px;">
		<br>
		<h4 style="margin: 5px 10px 10px 10px">
		<?php
			foreach ($path_menu as $key => $value) {
				?>
				<a href="<?php echo base_url().'index.php/'.$value['path'];?>">
				<?php
				echo $key;
				?>
				</a>|
				<?php
			}
		?>
		</h4>
	<ul id="iconstool" class="ui-widget ui-helper-clearfix">
			
	<?php
			foreach($menu as $a){
	?>
			<li class="ui-state-default ui-corner-all" title="<?php echo $a['aplicacion']; ?>">
				<a href="<?php echo base_url().'index.php/'.$a['path']; ?>">
					<span class="ui-icontool ui-icontool-<?php echo $a['imagen']; ?>">
						<?php echo $a['aplicacion'];?>
					</span>
				</a>
			</li>
	<?php
			}
	?>
	</ul>
	</div>
	<div style="margin: 20px; heigth:30px">
		<?php
			foreach ($alarmas_stock as $key => $value) {
				if($value>0){
					if($key=='alr_minimo'){
						$alarma='Minimo';
						$clase="ui-state-highlight ui-corner-all";
						$icono="ui-icon ui-icon-info";
					}else{
						$alarma='Reposicion';
						$clase="ui-state-error ui-corner-all";
						$icono="ui-icon ui-icon-alert";
					}
					
		?>
					<div id="<?php echo $key ?>" class="ui-widget ui-corner-all" style="width:20em;float:left;margin:10px">
						<div class="<?php echo $clase; ?>" style="margin: 10px; padding: 0 .7em;">
						<p>
						<span class="<?php echo $icono; ?>" style="float: left; margin-right: .3em;"></span>
						<strong><?php echo $alarma.": ".$value." items"; ?></strong>
						</p>
						</div>
					</div>

		<?php
				}
			}
		?>
	</div>
	<div style="margin: 20px;float:right;">
		<p>
			<?php echo "usuario: ".$info['usuario']." ".$info['apellido_nombre']; ?>
		</p>
		<p>
			<?php echo "Perfil: ".$info['perfil']. " | Sector: ".$info['sector']; ?>
		</p>
	</div>
	<div id="dialogo">
	</div>
</body>