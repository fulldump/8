<?php
	$id='';
	eval ('$id='.var_export($data['id'], true).';');

	$edit = !in_array('noedit', $flags);

	$simpletext = SimpleText::getByName($id);
	if (null === $simpletext) {
		$simpletext = SimpleText::INSERT();
		$simpletext->setName($id);
		$simpletext->setText($data['text']);
	}

	$text = $simpletext->getText();
?>
<?php if (array_key_exists('edit', $_GET) && Session::isLoggedIn() && $edit) { ?>
<div component="SimpleText" edit_id="<?= $id ?>"></div>
<?php } else { echo '<div class="SimpleText">'.$text.'</div>'; } ?>