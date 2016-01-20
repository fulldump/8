<?php

!array_key_exists('text', $data) && $data['text'] = 'text';

!array_key_exists('id', $data) && $data['id'] = md5(microtime());

!array_key_exists('class', $data) && $data['class'] = '';

?><button component="TrunkButton" id="<?=$data['id']?>" class="<?=$data['class']?>"><?=$data['text']?></button>