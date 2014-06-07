<?php

	$name = $_POST['name'];

	$result = array();
	if (Storm::exists($name)) {
		foreach($name::SELECT() as $r) {
			$result[$r->ID()] = $r->row;
		}
	}

	echo json_encode($result);
?>