<div component="TrunkLogin">
	<form action="" method="post">
		<input type="hidden" name="ACTION" value="LOGIN">
		<?php if ('POST' == $_SERVER['REQUEST_METHOD'] && $_POST['ACTION'] == 'LOGIN') { ?>
			<div class="errors">Contraseña incorrecta, pruebe de nuevo.</div>
		<?php }	?>
		<div class="inputs">
			<label><input name="user" type="text" placeholder="Email" required autofocus></label>
			<label><input name="pass" type="password" placeholder="Password" required></label>
		</div>
		<div class="buttons">
		[[COMPONENT name:TrunkButton id=login-button text:Login]]
		</div>
	</form>
</div>
