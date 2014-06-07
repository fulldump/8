<?php

$url = &ControllerPhp::$url;

if (count($url) == 1) {
	$node = SystemRoute::ROW($url[0]);
	if ($node != null)
		header('Location: '.$node->getPath().'?edit');
}

?>