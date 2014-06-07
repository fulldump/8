<?php

	$results = Test::run();


	$stats_global_pass = 0;
	$stats_global_fail = 0;

	$stats_package = array();
	foreach ($results as $package=>$files) {
		$stats_package_pass = 0;
		$stats_package_fail = 0;

		$stats_file = array();
		foreach ($files as $file=>$tests) {
			$stats_file_pass = 0;
			$stats_file_fail = 0;
			
			$stats_test = array();
			foreach ($tests as $name=>$results) {
				if ($results['passed']) {
					$stats_global_pass++;
					$stats_package_pass++;
					$stats_file_pass++;
				} else {
					$stats_global_fail++;
					$stats_package_fail++;
					$stats_file_fail++;
				}
				$stats_test[$name] = array(
					'name'=>$name,
					'passed'=>$results['passed'],
					'output'=>$results['output'],
					'children'=>null,
				);
			}
			$stats_file[$file] = array(
				'name'=>$file,
				'pass'=>$stats_file_pass,
				'fail'=>$stats_file_fail,
				'children'=>$stats_test,
			);
		}
		$stats_package[$package] = array(
			'name'=>$package,
			'pass'=>$stats_package_pass,
			'fail'=>$stats_package_fail,
			'children'=>$stats_file,
		);
	}

	$stats = array(
		'name'=>'All tests',
		'pass'=>$stats_global_pass,
		'fail'=>$stats_global_fail,
		'children'=>$stats_package,
	);

	function print_node(&$node, $deep=0) {
		if (null == $node['children']) {
			print_leaf($node, $deep);
		} else {
			print_branch($node, $deep);
		}
	}

	function print_branch(&$node, $deep=0) {
		$id = md5($node['name']);
		$passed = ($node['fail']==0) ? 'true' : 'false';
		$expanded = ($deep>0) ? 'true' : 'false';
		$expanded_sign = ($deep>0) ? '-' : '+';
?>
		<div class="branch" passed="<?php echo $passed; ?>">
			<div class="branch-info">
				<div class="branch-stats">
					<?php print_ministats($node['pass'], $node['fail']); ?>
				</div>
				<div id="expand<?php echo $id; ?>" class="branch-expand" onclick="expandClick('<?php echo $id; ?>')"><?php echo $expanded_sign; ?></div>
				<div class="branch-name">
					<?php echo $node['name']; ?>
				</div>
			</div>
			<div class="branch-children" id="children<?php echo $id; ?>" expanded="<?php echo $expanded; ?>">
				<?php
					foreach ($node['children'] as $child) {
						print_node($child, $deep-1);
					}
				?>
			</div>
		</div>
<?php
	}

	function print_leaf(&$node, $deep=0) {
		$id = md5(microtime());
		$passed = ($node['passed']==true) ? 'true' : 'false';

		$onclick = ($node['output'] != '') ? ' onclick="showOutput(\''.$id.'\')"' : '';
?>
		<div class="leaf" passed="<?php echo $passed; ?>"<?php echo $onclick; ?>>
			<?php if ($node['output'] != '') { ?>
				<div class="dropper" id="<?php echo $id; ?>"><?php echo $node['output']; ?></div>
			<?php } ?>
			<?php echo $node['name']; ?>
		</div>
<?php
	}

	function print_ministats($pass, $fail) {
		$total = $pass + $fail;
		$style = ($fail==0) ? '' : 'mark';
		echo "<span>Total: $total</span>";
		echo "<span>Pass: $pass</span>";
		echo "<span class='$style'>Fail: $fail</span>";
	}

?>

<div class="left-panel">
	<?php 	print_node($stats, 3); ?>
</div>
<div class="right-panel"><pre id="viewer"></pre></div>

<script type="text/javascript">
	function showOutput(id) {
		var viewer = document.getElementById('viewer');
		var output = document.getElementById(id);
		viewer.innerHTML = output.innerHTML;
	}

	function expandClick(id) {
		var expand = document.getElementById('expand'+id);
		var children = document.getElementById('children'+id);
		if (children.getAttribute('expanded') == 'true') {
			children.setAttribute('expanded', 'false');
			expand.innerHTML = '+';
		} else {
			children.setAttribute('expanded', 'true');
			expand.innerHTML = '-';
			document.location.hash = id;
		}
	}
</script>