<?php

	$name = $_POST['id_component'];
	$code = $_POST['css'];

	SystemComponent::get($name)->setCSS($code);

?>