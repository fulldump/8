<?php

$q = $_POST['q'];


$files = File::SELECT("SearchIndex LIKE '%".mysql_real_escape_string($q)."%'");

$result = array();

foreach ($files as $f) {
	$result[] = array(
		'id'=>$f->getId(),
		'name'=>$f->getName(),
		'mime'=>$f->getMime(),
		'size'=>$f->getSize(),
		'timestamp'=>$f->getTimestamp(),
		'user'=>$f->getUser()->getName(),
	);
}

echo json_encode($result);

?>