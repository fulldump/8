<?php

$id_notes = $_POST['id_notes'];

if (Session::isLoggedIn())
	Notes::ROW($id_notes)->makeEntry();

?>