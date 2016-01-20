<?php

$id_document = $_POST['id_document'];
//$id_part = $_POST['id_part'];
$path = $_FILES['image']['tmp_name'];

$image = Image::INSERT($path);
if (is_null($image)) {
	echo '0';
} else {
	$part = DocumentPart::INSERT();
	$part->setDocument(Document::ROW($id_document));
	$part->setType(DocumentPartType::ROW(3)); // DE TIPO IMAGEN
	$part->setImage($image);
	echo $image->getId();
}


?>