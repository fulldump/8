<?php

$name = mysql_real_escape_string($_POST['name']);

$new_email = EmailerCurrent::INSERT();
$new_email->setName($name);

$result = array(
	'id'=>$new_email->getId(),
	'name'=>$new_email->getName(),
);

echo json_encode($result);

?>