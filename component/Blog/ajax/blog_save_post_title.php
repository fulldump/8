<?php

$id = $_POST['id'];
$title = $_POST['title'];

BlogPost::ROW($id)->setTitle($title);


?>