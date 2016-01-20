<?php
	$id='';
	eval ('$id='.var_export($data['id'], true).';');

	$edit = !in_array('noedit', $flags);

	$label = Label::getByName($id);
	if (null === $label) {
		$label = Label::INSERT();
		$label->setName($id);
		$label->setText($data['text']);
	}

	$text = $label->getText();
?>
<?php if (array_key_exists('edit', $_GET) && Session::isLoggedIn() && $edit) { ?>
<span component="Label" edit_id="<?= $id ?>" style="display:inherit;"><?=$text?></span>
<?php } else { echo $text; } ?>