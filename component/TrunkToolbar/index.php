<div component="TrunkToolbar">
	<div class="toolbar">
		<div class="expand-button link icon" onclick="this.parentNode.parentNode.classList.toggle('expanded');"></div>
		<a href="/Adminx/" class="logo link icon">TreeWeb</a>
		<div class="right">
			<a href="/Profile" class="link icon user"><span class="user-name"><?php echo Session::getUser()->getName(); ?></span></a><a href="#" onclick="document.getElementById('form-logout').submit()" class="logout link icon"></a>
		</div>
		<div class="left">
			<?php // Left menu

			/*
			$admin = SystemRoute::ROW(9);
			$children = $admin->getChildren();
			foreach ($children as $c) {
				if ($c->getReference() == ControllerPage::$page->getId()) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
			?><a href="/adminx/<?=$c->getUrl()?>" class="link<?=$selected?>"><?=$c->getTitle()?></a><?php } 
			*/
			?>
			<a href="/adminx/" class="link">Title</a>
		</div>
	</div>
	<div class="shadow" onclick="this.parentNode.classList.remove('expanded');"></div>
	<form id="form-logout" action="" method="post">
		<input type="hidden" name="ACTION" value="LOGOUT">
	</form>
</div>