<?php

	$entity = $_POST['entity'];
	$id = $_POST['id'];
	
	eval('echo '.$entity.'::ROW('.$id.')->DELETE();');

?>