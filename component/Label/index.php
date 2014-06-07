<?php
	$id=0;
	eval ('$id='.$data['id'].';');
?>
<?php if (array_key_exists('edit', $_GET) && Session::isLoggedIn()) { ?>
<span id="Label<?= $id ?>" edit_id="<?= $id ?>" style="display:inherit;"><?=Label::ROW($id)->getText() ?></span>
<?php } else { ?>
<?=Label::ROW($id)->getText() ?>
<?php } ?>
