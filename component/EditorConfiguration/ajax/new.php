<?php


$name = $_POST['name'];

if (0 === Config::create($name)) {
	echo $name;
}

?>