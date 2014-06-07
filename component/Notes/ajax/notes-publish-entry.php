<?php

$id_entry = $_POST['id_entry'];

if (Session::isLoggedIn())
	NotesEntry::ROW($id_entry)->publish();

?>