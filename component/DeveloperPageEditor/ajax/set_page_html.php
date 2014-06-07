<?php

$id_page = SystemRoute::ROW($_POST['id'])->getReference();
$page = SystemPage::ROW($id_page);
$page->setHTML($_POST['code']);

echo $page->getHTML();

?>