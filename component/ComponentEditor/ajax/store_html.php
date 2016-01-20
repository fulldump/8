<?php

	$name = $_POST['id_component'];
	$code = $_POST['html'];

	SystemComponent::get($name)->setPHP($code);

	echo SystemComponent::get($name)->getPHP();
?>