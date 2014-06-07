<?php

if (Session::isLoggedIn()) {

	$id = $_POST['id'];
	$text = $_POST['text'];

	$st = SimpleText::ROW($id);
	if ($st != NULL)
		$st->setText($text);


}

?>