<?php

$id = $_POST['id'];

if (null !== Router::$root->getById($id) ) {
	Config::set('DEFAULT_PAGE', $id);
}

?>