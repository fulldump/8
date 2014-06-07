<?php

class ProfileDrawer {

	private $html_colors = array(
		'NavajoWhite','PaleGoldenRod','PaleGreen',
		'PaleTurquoise', 'lightblue', 'pink', 'Plum', 'PowderBlue'
	);
	private $sections = array();
	private $profile = null;

	public function __construct($profile) {
		$this->profile = $profile;
	}

	public function draw() {
		$this->_draw_rec($this->profile, 0, 400);
	}

	public function getSections() {
		return $this->sections;
	}

	private function _draw_rec($parent, $top, $height, $level=0) {
		if (!isset($this->sections[$parent['name']])) {
			$this->sections[$parent['name']] = array_pop($this->html_colors);
		}

		$color = $this->sections[$parent['name']];
		$left = 10*$level;

		echo '<div style="border: solid white 1px; background: '.$color.'; position: absolute; top:'.$top.'px; left:'.$left.'px; height:'.$height.'px; width: 16px;"
onmouseover="info(\''.$color.'\',\''.$parent['name'].'\',\''.(intval(100000*$parent['time'])/100).'\',\''.$parent['queries'].'\', \'\')"
onmouseout="noInfo()"></div>';

		foreach ($parent['children'] as $child) {
			$child_top = $top + ($height * microtime_diff($parent['microtime_start'], $child['microtime_start']) / $parent['time']);
			$child_height = $height * $child['time'] / $parent['time'];

			$this->_draw_rec($child, $child_top, $child_height, $level+1);
		}
	}

}

function microtime_diff($start, $end) {
	$start = explode(' ', $start);
	$end = explode(' ', $end);
	return ($end[0] + $end[1])-($start[0] + $start[1]);
}
?>

<?php

$id = $_POST['id'];
$json = json_decode(file_get_contents('profiling/'.$id.'.json'), true);
$root_item = $json['data'];

$drawer = new ProfileDrawer($root_item);

?>

<div style="width:200px; font-size:10px; padding:8px; border:solid silver 0px; margin:8px;">
<?php echo $json['url']; ?><br>
<br>
Total time: <?php echo (intval(100000*$root_item['time'])/100) ; ?>ms<br>
Total queries: <?php echo $root_item['queries']; ?>
</div>

<div style="width:100px; height:400px; font-size:10px; position: relative; margin: 16px;">
<?php $drawer->draw(); ?>
</div>

<div style="width:200px; font-size:10px; padding:8px; border:solid silver 0px; margin:8px;">
<?php
$sections = $drawer->getSections();
foreach ($sections as $C=>$c) {
	echo '<div style="display:inline-block; background-color:'.$c.'; width:8px; height:8px; "></div> '.$C.'<br>';
}
?>
</div>

<div id="profile_info" style="width: 200px; font-size:10px; padding:8px; margin: 8px; border:solid silver 1px; margin-top:8px; display:none;"></div>

<div
style="position: absolute;
top: 0;
right: 0;
bottom: 0;
left: 230px;
padding: 8px;
font-family:
monospace;
overflow-y: auto;">
<h1>Memory</h1>
<?php foreach ($json['memory'] as $L=>$l) {
	echo '<div style="border-top: solid silver 1px;">Memory '.$L.' = '.Lib::humanSize($l).'</div>';
} ?>
<h1>Log (<?php echo count($json['log']); ?>)</h1>
<?php foreach ($json['log'] as $l) {
	echo '<div style="border-top: solid silver 1px;">'.htmlentities($l).'</div>';
} ?>
<h1>Queries (<?php echo count($json['queries']); ?>)</h1>
<?php foreach ($json['queries'] as $l) {
	echo '<div style="border-top: solid silver 1px;">'.htmlentities($l).'</div>';
} ?>
<h1>Included files (<?php echo count($json['included_files']); ?>)</h1>
<?php foreach ($json['included_files'] as $l) {
	echo '<div style="border-top: solid silver 1px;">'.htmlentities($l).'</div>';
} ?>
<h1>Resources</h1>
<?php foreach ($json['rusage'] as $L=>$l) {
	echo '<div style="border-top: solid silver 1px;">Resource '.$L.' = '.htmlentities($l).'</div>';
} ?>
<h1>Post params (<?php echo count($json['post']); ?>)</h1>
<?php foreach ($json['post'] as $L=>$l) {
	echo '<div style="border-top: solid silver 1px;">'.$L.' = '.htmlentities($l).'</div>';
} ?>
<h1>Get params (<?php echo count($json['get']); ?>)</h1>
<?php foreach ($json['get'] as $L=>$l) {
	echo '<div style="border-top: solid silver 1px;">'.$L.' = '.htmlentities($l).'</div>';
} ?>
<?php
$external_classes = $json['declared_classes'];
while('Main' != $external_classes[0]) {
	array_shift($external_classes);
}
?>
<h1>Declared classes (<?php echo count($external_classes); ?>)</h1>
<?php foreach ($external_classes as $l) {
		echo '<div style="border-top: solid silver 1px;">'.htmlentities($l).'</div>';
} ?>
</div>