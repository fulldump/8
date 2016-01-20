<?php

$id = $_POST['id'];

$document = Document::ROW($id);
if ($document == null) {
	// el documento no existe !!
	echo json_encode(null);
} else {
	$result = array();
	$parts = $document->getParts();
	foreach ($parts as $p) {
		$part = new stdClass();
		$part->id = $p->getId();
		$part->type = $p->getType()->getName();
		$part->text = $p->getData();
		if ($part->type == 'IMAGE') {
			$part->url = '/img/'.$p->getImage()->getId();
		}
	$result[] = $part;
	}
	echo json_encode($result);
}



?>