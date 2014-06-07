<?php

$id = $_POST['id'];

$email = EmailerCurrent::ROW($id);
if (null != $email) {
	$email->DELETE();
}


?>