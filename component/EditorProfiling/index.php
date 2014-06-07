<div id="izquierda">
	<div class="simple-list">

<?php
$select = array();

$path = 'profiling/';
$d = dir($path);
while (false !== ($entry = $d->read())) {
	$entry = pathinfo ($entry);
	if ('json' == $entry['extension']) {
		$select[] = $entry['filename'];
	}
}
$d->close();

rsort($select);


foreach ($select as $s) {
	echo '<button onclick="load_profile(\''.$s.'\')">'.$s.'</button>';
}


?>
	</div>

</div>

<div id="derecha">



</div>