<?php

$id_part = $_POST['id_part'];
$text = $_POST['text'];

$part = DocumentPart::ROW($id_part);
if ($part->getType()->getName() == 'IMAGE') {
	$part->setData(strip_tags($text, ''));
}



?>