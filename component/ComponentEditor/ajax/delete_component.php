<?php

	$id = $_POST['id_component'];

	SystemComponent::get($id)->DELETE();

?>