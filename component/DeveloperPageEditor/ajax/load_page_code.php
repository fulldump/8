<?php

$id_page = SystemRoute::ROW($_POST['id'])->getReference();
$page = SystemPage::ROW($id_page);

$result = array(
	'html'=>$page->getHTML(),
	'css'=>$page->getCSS(),
	'js'=>$page->getJS()
);

echo json_encode($result);

?>