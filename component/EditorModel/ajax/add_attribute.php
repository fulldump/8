<?php

	$entity = $_POST['entity'];
	$field = $_POST['attribute'];
	$type = $_POST['type'];
	
	Storm::get($entity)->add($field, $type);

?>