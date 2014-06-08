<div id="top">
	<div id="top-right">
		<span id="idioma"></span>


		<button id="salir" onclick="document.getElementById('form-logout').submit();">Salir</button>
		<form id="form-logout" action="" method="post">
			<input type="hidden" name="ACTION" value="LOGOUT">
		</form>
	</div>
	<button id="button-home" onclick="admin_go_home()" title="Página de inicio"></button>
	<button id="button-meta" onclick="admin_meta()" title="Metadatos"></button>
	<button id="button-stats" onclick="admin_estadisticas()" title="Estadísticas de google"></button>
<?php

$id_default_page = Config::GET('DEFAULT_PAGE');

?>
</div>
<div id="preview">
	<iframe id="iframe" src="/?edit" onload="admin_recargar(this.contentWindow.location.href);"></iframe>
</div>