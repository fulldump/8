<?php

$component_name = $_POST['component_name'];
$ajax_name = $_POST['ajax_name'];

echo SystemComponent::get($component_name)->getAjax($ajax_name);

?>