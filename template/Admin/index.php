<?php if(Session::isLoggedIn()) { ?>



<?php

	if (isset($_POST['ACTION']) && $_POST['ACTION'] == 'REGISTER') {
		Usuario::register($_POST['name'], $_POST['login']);
	}
?>



<div id="app-bar">
	<div id="app-bar-right">
		<?php if (Session::isLoggedIn()){ ?>
			<a href="/profile"><?php echo Session::getUser()->getName(); ?></a>
			<a href="javascript:" onclick="document.getElementById('form-logout').submit();">Salir</a>
			<form id="form-logout" action="" method="post">
				<input type="hidden" name="ACTION" value="LOGOUT">
			</form>
		<?php } else { ?>
			<a href="javascript:" onclick="
				document.getElementById('form-login').style.display='none';
				var a = document.getElementById('form-register');
				if (a.style.display=='block') {
					a.style.display='none';
				} else {
					a.style.display='block';
				}">Registrarme</a>
			<a href="javascript:" onclick="
				document.getElementById('form-register').style.display='none';
				var a = document.getElementById('form-login');
				if (a.style.display=='block'){
					a.style.display='none';
				} else {
					a.style.display='block';
				}">Entrar</a>
<form id="form-login" action="" method="post">
	<div class="margen">
		<table>
			<tr>
				<td>Email</td>
				<td><input id="form-login-input-login" name="user"></td>
			</tr>
			<tr>
				<td>Contraseña</td>
				<td><input type="password" name="pass"></td>
			</tr>
		</table>
		<input type="hidden" name="ACTION" value="LOGIN">
		<div style="text-align:right; padding-top:16px;">
		<button class="shadow-button" title="Escribe tu email y recupera tu contraseña" type="button" onclick="recoverPassword(document.getElementById('form-login-input-login').value);document.getElementById('form-login-input-login').value='';">Recuperar</button>
		<button class="shadow-button">Entrar</button>
		</div>
	</div>
</form>
<form id="form-register" action="" method="post">
	<div class="margen">
		<table>
			<tr>
				<td>Nombre</td>
				<td><input name="name"></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input name="login"></td>
			</tr>
		</table>
		<input type="hidden" name="ACTION" value="REGISTER">
		<div style="text-align:right; padding-top:16px;">
		<button class="shadow-button">Registrame !</button>
		</div>
	</div>
</form>

		<?php } ?>
	</div>
	<div>
		<?php // MENU

		$admin = SystemRoute::ROW(9);
		$children = $admin->getChildren();
		foreach ($children as $c) {
			if ($c->getReference() == ControllerPage::$page->getId()) {
				$selected = ' class="selected" ';
			} else {
				$selected = '';
			}
			echo '<a'.$selected.' href="/adminx/'.$c->getUrl().'" title="'.$c->getDescription().'">'.$c->getTitle().'</a>';
		}

		?>
	</div>
</div>


<div id="content">
[[BODY]]
</div>





<?php } else { ?>


<form action="" method="post" style="position:absolute; border:solid gray 1px; width:400px; top:50%; left:50%; margin-left:-200px; margin-top:-100px; border-radius:8px; box-shadow:2px 2px 8px rgba(0,0,0,0.4)">
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