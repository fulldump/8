<?php

$id_file_instance = $_POST['id'];
$id_file = $_POST['file'];

print_r($_POST);


$file_instance = FileInstance::ROW($id_file_instance);

$file_instance->setFile(File::ROW($id_file));
$file_instance->setType(2);


?>