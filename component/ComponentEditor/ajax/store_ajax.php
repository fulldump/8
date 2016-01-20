<?php

$component_name = $_POST['component_name'];
$ajax_name = $_POST['ajax_name'];
$ajax_code = $_POST['ajax_code'];

SystemComponent::get($component_name)->setAjax($ajax_name, $ajax_code);

?>