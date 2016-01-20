<?php

$id_part = $_POST['id_part'];
$text = $_POST['text'];

$part = DocumentPart::ROW($id_part);
if ($part->getType()->getName() == 'TEXT') {
	
	$text = str_replace(
		array('<div>','</div>','<p>',  '</p>',  '<b>',     '</b>',     '<i>', '</i>', '&nbsp;'),
		array('<div>','</div>','<div>','</div>','<strong>','</strong>','<em>','</em>', ' '),
		$text
	);


	// TODO: Pasar de SPAN a strong, em, u
	$part->setData(strip_tags($text,'<a><span><strong><em><u><br><ol><ul><li>'));
}

echo $part->getData();

?>