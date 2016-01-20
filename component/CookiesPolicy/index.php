<?php
if ('POST' == $_SERVER['REQUEST_METHOD']) {
	$_SESSION['CookiesPolicy'] = 'accepted';
}
?>

<?php if ('accepted' != $_SESSION['CookiesPolicy']) { ?>
<div component="CookiesPolicy">
	[[COMPONENT name=SimpleText text='Le informamos de que utilizamos cookies para realizar medición de la navegación de los usuarios. Si continua leyendo, es que acepta su uso.' id=CookiesPolicy.text]]
	<form method="post" action=""><button>[[COMPONENT name=Label text=Aceptar id=CookiesPolicy.button]]</button></form>
</div>
<?php } ?>