<?php

if (Session::isLoggedIn()) {
	Label::getByName($_POST['id'])->setText($_POST['text']);
}

?>