<?php

	$id=0;
	eval ('$id='.$data['id'].';');

?><?php if (array_key_exists('edit', $_GET)  && Session::isLoggedIn() ) { ?>
<div id="TreeDoc<?= $id ?>" doc="<?= $id ?>"></div>
<?php
} else {


	$document = Document::ROW($id);
	if ($document == null) {
		// el documento no existe !!
		// TODO: Lanzar página de error 404 y no cachear!
	} else {
		$result = array();
		$parts = $document->getParts();
		foreach ($parts as $p) {
			$type = $p->getType()->getName();
			switch ($type) {
				case 'TITLE':
					echo '<h2>'.$p->getData().'</h2>';
					break;
				case 'TEXT':
					echo '<div>'.$p->getData().'</div>';
					break;
				case 'IMAGE':
					echo '<div class="part-image"><div class="image-frame"><img style="width:400px;" src="/img/'.$p->getImage()->getId().'" alt="'.$p->getData().'"><div class="text">'.$p->getData().'</div></div></div>';
					break;
			}
		}
	}
} ?>