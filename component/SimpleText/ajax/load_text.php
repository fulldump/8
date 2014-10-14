<?php

$id = $_POST['id'];

echo SimpleText::getByName($_POST['id'])->getText();

?>