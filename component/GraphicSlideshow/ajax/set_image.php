<?php



print_r($_POST);

$item_id = $_POST['item_id'];
$image_id = $_POST['image_id'];


GraphicSlideshowItem::ROW($item_id)->setBackground(Image::ROW($image_id));




?>