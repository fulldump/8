<?php

	$id = 'table-'.md5(microtime());
	$table = $data['table'];


?>
<div id="<?=$id?>" table="<?=$table?>">

</div>

<script type="text/javascript">

	var container = document.getElementById('<?=$id?>');
	var table_name = container.getAttribute('table');
	
	var table = new Table(table_name, container);
	
</script>