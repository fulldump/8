<?php



require('class/Main.class.php');
Main::goCli();


$code = <<<HEREDOC

// texto de antes

<?php
\$id = 35;
\$str = "soy una cadena de texto";
\$lista = array(1,2,3);
\$objeto = new stdClass();
\$objeto->nombre = 'fulanito';
\$objeto->apellido = 'menganito';
?>

[[Blog val="soy un valor" persona=\$objeto id=\$id cadena=\$str lista=\$lista flag1 flag2 flag3]]

// texto de después

HEREDOC;

$output = '';

// var_export(TreeScript::getParse($code));


foreach(TreeScript::getParse($code) as $token) {
	$data = $token['data'];
	switch ($token['type']) {
		case 'text':
			$output .= $data;
			break;

		case 'tag':
			$name = $token['name'];
			$flags = $token['flags'];
			$output .= <<<HEREDOC
<?php function ___$name(\$data, \$flag) {
	print_r(\$data);
	print_r(\$flag);
} ?>

HEREDOC;

			$output .= "<?php ___$name(array(";
			foreach ($data as $D=>$d) {
				if ('$' == $d[0]) {
					$output .= "'$D' => $d,";
				} else {
					$output .= "'$D' => \"$d\",";
				}
			}
			$output .= "),";
			$output .= var_export($flags, true);
			$output .= "); ?>";

			break;
	}

}


file_put_contents('output.php', $output);

