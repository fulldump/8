<?php

	$name = $_POST['id_component'];

	$component = SystemComponent::getComponentByName($name);

	$result = new stdClass();
	$result->js = $component->getJS();
	$result->css = $component->getCSS();
	$result->html = $component->getPHP();

	echo json_encode($result);




?>