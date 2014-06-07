<?php

	$name = $_POST['id_component'];
	$code = $_POST['js'];

	SystemComponent::getComponentByName($name)->setJS($code);

?>