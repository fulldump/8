<?php

$id_document = $_POST['id_document'];
$id_part = $_POST['id_part'];

$part = DocumentPart::INSERT();
$part->setDocument(Document::ROW($id_document));
$part->setType(DocumentPartType::ROW(2)); // DE TIPO TITULO

echo $part->getId();

?>