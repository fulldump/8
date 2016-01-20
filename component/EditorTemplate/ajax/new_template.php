<?php

	$name = $_POST['name'];

	$template = SystemTemplate::INSERT($name);

	echo $template->getName();

?>