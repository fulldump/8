<div component="TrunkToolbar">
	<div class="toolbar">
		<div class="expand-button link icon" onclick="this.parentNode.parentNode.classList.toggle('expanded');"></div>
		<a href="/Adminx/" class="logo link icon">TreeWeb</a>
		<div class="right">
			<a href="/Profile" class="link icon user"><span class="user-name"><?php echo Session::getUser()->getName(); ?></span></a><a href="#" onclick="document.getElementById('form-logout').submit()" class="logout link icon"></a>
		</div>
		<div class="left">
			<?php // Left menu

			
			$adminx = Router::$root->get('adminx');
			foreach ($adminx->children as $child) {
				if ($child->id == Router::$node->id) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
			?><a href="<?=Router::getNodeUrl($child)?>" class="link<?=$selected?>"><?=$child->getProperty('title');?></a><?php
			}
			?>
		</div>
	</div>
	<div class="shadow" onclick="this.parentNode.classList.remove('expanded');"></div>
	<form id="form-logout" action="" method="post">
		<input type="hidden" name="ACTION" value="LOGOUT">
	</form>
</div>