[[COMPONENT name=Bootstrap]]

<?php if ('POST' == $_SERVER['REQUEST_METHOD']) { ?>

<?php

$fields = "";
foreach ($_POST as $F=>$f) {
	$fields .= "<strong>$F</strong>: $f<br>";
}

$to      = $email_contacto;
$subject = 'Contacto - '.$_SERVER['HTTP_HOST'];
$message = $fields;

$headers = "MIME-Version: 1.0\n" ;
$headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
$headers .= "Reply-To: ".trim($_POST['email'])."\n";
$headers .= "X-Priority: 1 (Higuest)n";
$headers .= "X-MSMail-Priority: High\n";
$headers .= "Importance: High\n";

$emails = explode(',', Config::get('FORM_CONTACT_EMAILS'));
$envio_ok = true;
foreach ($emails as $e) {
  $envio_ok = $envio_ok & mail(trim($e), $subject, $message, $headers);
}

?>

[[COMPONENT name=SimpleText text='Formulario enviado. En breve nos pondremos en contacto con usted.' id=78]]

<?php } else { ?>

<form role="form" method="post" action="">
	<fieldset>
		<div class="form-group">
			<label for="nombre">[[COMPONENT name=Label text=Nombre id=157]]</label>
			<input type="text" class="form-control" id="nombre" name="nombre" placeholder="[[COMPONENT name=Label id=157 noedit]]" required>
		</div>
		<div class="form-group">
			<label for="apellidos">[[COMPONENT name=Label text=Apellidos id=158]]</label>
			<input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="[[COMPONENT name=Label id=158 noedit]]" required>
		</div>
		<div class="form-group">
			<label for="telefono">[[COMPONENT name=Label text=Teléfono id=159]]</label>
			<input type="tel" class="form-control" id="telefono" name="telefono" placeholder="[[COMPONENT name=Label id=159 noedit]]" required>
		</div>
		<div class="form-group">
			<label for="movil">[[COMPONENT name=Label text=Móvil id=160]]</label>
			<input type="tel" class="form-control" id="movil" name="movil" placeholder="[[COMPONENT name=Label id=160 noedit]]" required>
		</div>
		<div class="form-group">
			<label for="nombre">[[COMPONENT name=Label text=Email id=161]]</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="[[COMPONENT name=Label id=161 noedit]]" required>
		</div>
	</fieldset>
	
	<div class="checkbox">
		<label>
			<input type="checkbox" name="user-politica" id="user-politica" required>
			[[COMPONENT name=Label text='He leído y acepto la' id=79]] <a href="/Politica-de-privacidad">[[COMPONENT name=Label text='política de privacidad' id=80]]</a>
		</label>
	</div>
  
	<button type="submit" class="btn btn-default">[[COMPONENT name=Label text=Enviar id=162]]</button>
</form>

<?php } ?>
