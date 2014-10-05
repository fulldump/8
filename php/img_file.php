<?php

$id = $this->router->parameters['{id}'];

$image = Image::ROW($id);
if (null == $image) {
	exit();
}

$parts = $this->router->parts;

if (0 == count($parts)) {
	$path = Rack::Path('img', md5($image->getId()));
} elseif (1 == count($parts)) {
	$transformation = $parts[0];
	$hash = md5($transformation);
	$path = Rack::Path('img.cache', $hash);
	if (!file_exists($path)) {
		Rack::Make('img.cache', $hash);
		$prim = Prim::transform($image);
		$prim->setRules($transformation);
		$prim->saveTo($path);
	}
} else {
	exit;
}


header("Expires: ".date("r", time()+9999999));
header("Content-type: ".$image->getMime());
header("Content-Length: ". filesize($path));
readfile($path);
