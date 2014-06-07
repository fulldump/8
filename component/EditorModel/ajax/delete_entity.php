<?php

	$entity = $_POST['entity'];
	
	$result = Storm::get($entity)->drop();

	echo json_encode($result);

?>