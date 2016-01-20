<?php

$id = $_POST['id'];

$email = EmailerCurrent::ROW($id);

if (null == $email) {
	$result = null;
} else {
	$result = array(
		'html'=>$email->getHTML(),
	);
}

echo json_encode($result);

?>