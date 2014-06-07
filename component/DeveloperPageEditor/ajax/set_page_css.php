<?php

$id_page = SystemRoute::ROW($_POST['id'])->getReference();
SystemPage::ROW($id_page)->setCSS($_POST['code']);



?>