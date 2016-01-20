<?php

$search = Database::escape($_POST['search']);

$where = "Name LIKE '%$search%' AND NOT Name = '' ORDER BY Name";

$emails = EmailerCurrent::SELECT($where);

$result = array();
foreach($emails as $email) {
	$result[] = array(
		'id'=>$email->getId(),
		'name'=>$email->getName(),
	);
}

echo json_encode($result);

?>