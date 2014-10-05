<?php

$id = $_POST['id'];

Router::$root->getById($id)->remove();

Router::save();

?>