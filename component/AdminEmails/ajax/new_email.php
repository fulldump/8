<?php

$new_email = EmailerCurrent::INSERT();
$new_email->setName($_POST['name']);

$result = array(
	'id'=>$new_email->getId(),
	'name'=>$new_email->getName(),
);

echo json_encode($result);

?>