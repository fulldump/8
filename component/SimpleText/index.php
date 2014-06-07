<?php

	$id=0;
	eval ('$id='.$data['id'].';');

?><?php if (array_key_exists('edit', $_GET)  && Session::isLoggedIn() ) { ?>
<div id="SimpleText<?= $id ?>" doc="<?= $id ?>"></div>
<?php
} else {
	echo '<div class="SimpleText">';
	echo SimpleText::ROW($id)->getText();
	echo '</div>';
} ?>