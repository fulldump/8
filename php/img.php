<?php

$url = &ControllerPhp::$url;

if (count($url)) {
	$image = Image::ROW($url[0]);
	array_shift($url);
	if (null == $image) {
		exit();
	}
} else {
	echo '<style type="text/css">
body {
	font-family: sans-serif;
}

.box {
	display: block;
	overflow: hidden;
	border: solid silver 1px;
	width: 128px;
	height: 0px;
	padding-top:128px;
	margin: 8px;
	float: left;
	background-repeat: no-repeat;
	background-position: center center;
	background-size: contain;
	background-color: #F8F8F8;
	box-shadow: 0 0 8px rgba(0,0,0,0.1);
	color: black;
	text-decoration: none;
	text-align: center;
}

.box:hover {
	box-shadow: 0 0 10px rgba(0,0,0,0.6);
	overflow: visible;
	text-shadow: 1px 1px 2px white;
}
</style>';
	foreach (Image::SELECT() as $im) {
		echo '<a href="'.$im->getId().'" class="box" style="background-image:url(\'/img/'.$im->getId().'/w:128;q:50\')">'.$im->getId().': '.$im->getWidth().'x'.$im->getHeight().'</a>';
	}
	exit();
}

if (count($url)) {
	$imgcache_collection = 'img.cache';
	$imgcache_hash = md5(ControllerPhp::$path);
	$path = Rack::Path($imgcache_collection, $imgcache_hash);
	if (!file_exists($path)) {
		Rack::Make($imgcache_collection, $imgcache_hash);
		$prim = Prim::transform($image);
		$prim->setRules($url[0]);
		$prim->saveTo($path);
	}
} else {
	$path = Rack::Path('img', md5($image->getId()));
}

header("Expires: ".date("r", time()+9999999));
header("Content-type: ".$image->getMime());
header("Content-Length: ". filesize($path));
readfile($path);

?>