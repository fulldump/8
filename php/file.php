<?php

$url = &ControllerPhp::$url;

if (count($url) == 1) {
	$file = File::ROW($url[0]);
	array_shift($url);
	if ($file != null) {
		$path = 'files/'.$file->getId();
		if (file_exists($path)) {
			header("Content-type: ".$file->getMime());
			header('Content-Disposition: attachment; filename="'.$file->getName().'"');
			header("Content-Length: ". filesize($path));
			readfile($path);
		}
	}
}

?>