<div id="frame">
	<div id="header">
		[[COMPONENT name=Label text='Welcome to TreeWeb' id=1]]
	</div>
	<div id="body">
		<nav id="col1">
			<?php menu(SystemRoute::ROW(Config::get('DEFAULT_PAGE')), true, ''); ?>
		</nav>
		<div id="col2">
		[[BODY]]
		</div>
	</div>
	<div id="footer">
		2011&copy; Powered by <a href="http://www.treeweb.es/">TreeWeb</a>
	</div>
</div>



<?php

function menu($node, $show_parent=false, $path='', $l=1) {

	/* Cada llamada imprime el nombre de los hijos */
	
	$path .= '/'.$node->getUrl();

	$children = $node->getChildren();
	if (count($children)) {
		echo '<ul class="menu menu-'.$l.'">';
		if ($show_parent) {
			echo '<li>';
			echo '<a href="'.$path.'">'.$node->getTitle().'</a>';
			echo '</li>';
		}
		foreach ($children as $n) {
			$selected = '';
			if (ControllerPage::$page->getId() == $n->getReference())
				$selected = ' selected';
			if (strpos( $n->getTitle(), 'POR HACER'))
				$selected .= ' sin_hacer';

			echo '<li>';
			echo '<a class="'.$selected.'" href="'.$path.'/'.$n->getUrl().'">'.$n->getTitle().'</a>';
			menu($n, false,  $path, $l+1);
			echo '</li>';
		}
		echo '</ul>';
	}
}

?>