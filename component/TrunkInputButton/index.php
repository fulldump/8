<?php

!array_key_exists('placeholder', $data) && $data['placeholder'] = 'placeholder';

!array_key_exists('id', $data) && $data['id'] = md5(microtime());

!array_key_exists('class', $data) && $data['class'] = '';

!array_key_exists('type', $data) && $data['type'] = 'text';

?>
<div component="TrunkInputButton" id="<?=$data['id']?>" class="<?=$data['class']?>">
	<button component="TrunkButton" id="<?=$data['id']?>-button" tabindex="-1"></button>
	<div class="input-border">
		<input type="<?=$data['type']?>" placeholder="<?=$data['placeholder']?>">
	</div>
</div>