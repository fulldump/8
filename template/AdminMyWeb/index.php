<?php if(Session::isLoggedIn()) { ?>

[[BODY]]

<?php } else { ?>


<form id="form-login" action="" method="post" style="position:absolute; border:solid gray 1px; width:400px; top:50%; left:50%; margin-left:-200px; margin-top:-100px; border-radius:8px; box-shadow:2px 2px 8px rgba(0,0,0,0.4)">
	<?php
		if (isset($_POST['ACTION']) && $_POST['ACTION']=='LOGIN') {
			echo '<div style="color:red; padding:16px; text-align:center;">Contraseña incorrecta, pruebe de nuevo.</div>';
		}
	?>
	<div class="margen">
		<table style="width:100%;">
			<tr>
				<td>Email</td>
				<td><input id="form-login-input-login" name="user" style="width:100%; border:solid gray 1px;"></td>
			</tr>
			<tr>
				<td>Contraseña</td>
				<td><input type="password" name="pass" style="width:100%; border:solid gray 1px;"></td>
			</tr>
		</table>
		<input type="hidden" name="ACTION" value="LOGIN">
		<div style="text-align:right; padding-top:16px;">
		<button class="shadow-button shadow-button-blue">Entrar</button>
		</div>
	</div>
</form>


<?php } ?>