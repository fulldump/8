<?php

	$entity = $_POST['entity'];
	$field = $_POST['attribute'];
	
	Storm::get($entity)->remove($field);

?>