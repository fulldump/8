<?php

if (Session::isLoggedIn()) {
	SimpleText::getByName($_POST['id'])->setText($_POST['text']);
}

?>