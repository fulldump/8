<?php

$page_id = SystemRoute::ROW($_POST['page_id'])->getReference();
$template = $_POST['template'];

SystemPage::ROW($page_id)->setTemplate($template);

?>