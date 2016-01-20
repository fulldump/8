<?php

	$name = $_POST['name'];

	$component = SystemComponent::INSERT($name);

	echo $component->getName();
?>