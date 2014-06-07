<?php

if (Session::isLoggedIn())
	Label::ROW($_POST['id'])->setText($_POST['text']);

?>