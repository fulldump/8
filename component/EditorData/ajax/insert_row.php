<?php

	$entity = $_POST['entity'];
	
	// echo $entity::INSERT()->getId();
	eval('echo '.$entity.'::INSERT()->getId();');

?>