<?php

	$entity = $_POST['entity'];
	$id = $_POST['id'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	
	$set = 'set'.$field;
	$get = 'get'.$field;

	$entity::ROW($id)->$set($value);

	echo $entity::ROW($id)->$get($value);

?>