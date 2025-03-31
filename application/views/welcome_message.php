<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<script type="text/javascript">
	$(function() {
		jQuery.fn.center = function(){
			    this.css("position","absolute");
			    this.css("top",($(window).height() - this.height())/2+$(window).scrollTop()+"px");
			    this.css("left",($(window).width() - this.width())/2+$(window).scrollLeft()+"px");
		}
		$("#bb").center();

	});
	

</script>

<body>

	<div id="bb">
		<table border="0" width="300">
			<tr>
				<td align="center" colspan="2">
					<img src="<?php echo base_url().'images/logo.png'; ?>">
				</td>
			</tr>
			<tr>
				<td align="center" width="40">
					<img src="<?php echo base_url().'images/dal.png'; ?>">
				</td>
				<td>
					<p style="margin-bottom: 5px;font-size: 1.5em;font-style: bold">Direccion de Apoyo Logistico</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<a href="<?php echo base_url().'index.php/login'; ?>">
						<p style="font-size: 1.2em;font-style: bold">login</p>
					</a>
				</td>
			</tr>
			
		</table>
	</div>

</body>
</html>