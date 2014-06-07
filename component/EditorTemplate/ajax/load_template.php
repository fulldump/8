<?php

$id = $_POST['id_template'];

$template = SystemTemplate::get($id);

$result = array(
	'html'=>$template->getPHP(),
	'css'=>$template->getCSS(),
	'js'=>$template->getJS()
);

echo json_encode($result);

?>