<?php

$id = $_POST['id'];

if (null !== Router::$root->getById($id) ) {
	Config::set('404_PAGE', $id);
}

?>