<?php

$id=0;
if (array_key_exists('id', $data))
	eval ('$id='.$data['id'].';');
$file = FileInstance::ROW($id);

if ($file != null) {

	$href = '';
	switch($file->getType()) {
		case 1: $href = $file->getUrl(); break;
		case 2:	$href = '/file/'.$file->getFile()->getId(); break;
	}

?>
<a id="hyperlink-<?php echo $file->getId(); ?>" class="hyperlink" href="<?php echo $href; ?>">[[COMPONENT name=Label id=$file->getLabel()->getId()]]</a>
<?php
	if (Lib::editingMode()) { 
		echo '<button class="shadow-button shadow-button-blue" onclick="file_link_click('.$file->getId().')">Link</button>';
	}
?>
<?php
}
?>