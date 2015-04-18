<?php

// Tests utils:

function recursive_compare($a, $b) {
	if (gettype($a) == gettype($b)) {
		if (is_array($a)) {
			foreach ($a as $k=>$v) {
				if (!array_key_exists($k, $b) || !recursive_compare($v, $b[$k])) {
					return false;
				}
			}
			foreach ($b as $k=>$v) {
				if (!array_key_exists($k, $a) || !recursive_compare($a[$k], $v)) {
					return false;
				}
			}
			return true;
		} else {
			return $a == $b;
		}
	}
	return false;
}


// Tests:

$tests = array();

$tests['Empty code'] = function() {
	$code = '';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>''
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Comment (one line)'] = function() {
	$code = ' texto uno [[ this is a comment ]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'comment',
			'data'=> ' this is a comment '
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Comment (with tab char)'] = function() {
	$code = ' texto uno [[		this is a comment ]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'comment',
			'data'=> '		this is a comment '
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Comment (multiple line)'] = function() {
	$code = ' texto uno [[
this
is
a
comment
]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'comment',
			'data'=> '
this
is
a
comment
'
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Comment (empty)'] = function() {
	$code = ' texto uno [[]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'comment',
			'data'=> ''
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Comment (unexpected end)'] = function() {
	$code = ' text one [[ unexpected end of file';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' text one '
		),
		array(
			'type'=>'comment',
			'data'=> ' unexpected end of file'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};


$tests['One tag'] = function() {
	$code = ' texto uno [[MI_ITEM]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['One tag (blanks)'] = function() {
	$code = ' texto uno [[MI_ITEM   
   	]] texto dos';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos'
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};


$tests['One tag (unexpected end)'] = function() {
	$code = ' texto uno [[MI_ITEM';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Flags (no end)'] = function() {
	$code = ' texto uno [[MI_ITEM flag]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(
				'flag',
			),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Flags (with space)'] = function() {
	$code = ' texto uno [[MI_ITEM uno dos tres ]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(
				'uno',
				'dos',
				'tres'
				),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Flags (diverse)'] = function() {
	$code = ' texto uno [[MI_ITEM 
	:uno 
	dos 	
	tres"
	"cuatro
	$cinco 	 ]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(),
			'name'=>'MI_ITEM',
			'flags'=>array(
				':uno',
				'dos',
				'tres"',
				'"cuatro',
				'$cinco',
				),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Attributes (equal)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo=valor ]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'valor'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['Attributes (colon)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo:valor ]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'valor'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Attributes (no end)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo=valor]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'valor'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Attributes (spaces)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo    =   valor]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'valor'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};


$tests['Attributes (single quotes)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo = \'Habia una vez\']] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'Habia una vez'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Attributes (double quotes)'] = function() {
	$code = ' texto uno [[MI_ITEM atributo = "Habia una vez" ]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'atributo'=>'Habia una vez'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);
	$result = TreeScript::getParse($code);
	return recursive_compare($reference, $result);
};

$tests['Attributes (escaped chars)'] = function() {
	$code = ' texto uno [[MI_ITEM
		simple = "aa \' aa"
		doble = \'bb " bb\'
		barra = \'cc \\ cc\'
		]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'simple'=>'aa \' aa',
				'doble'=>'bb " bb',
				'barra'=>'cc \\ cc'
			),
			'name'=>'MI_ITEM',
			'flags'=>array(),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);

	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['Attributes (Combined 1)'] = function() {
	$code = ' texto uno [[MI_ITEM
	    flag_1
	    a=b
	    flag_2
	    flag_3
	    c= d
	    flag_4
	    e =f
		]] texto dos ';
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' texto uno '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'a'=>'b',
				'c'=>'d',
				'e'=>'f',
			),
			'name'=>'MI_ITEM',
			'flags'=>array(
				'flag_1',
				'flag_2',
				'flag_3',
				'flag_4',
			),
		),
		array(
			'type'=>'text',
			'data'=>' texto dos '
		),
	);

	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['Attributes (Combined 2)'] = function() {
	$code = <<<FUCK
 1 [[MI_ITEM 
	 	a=''
	 	b="'"
		c="function(\$var='', \$ver='') {
			return \$var.\$ver;
		}"
	]] 2 
FUCK;
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' 1 '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'a'=>'',
				'b'=>'\'',
				'c'=>"function(\$var='', \$ver='') {
			return \$var.\$ver;
		}",
			),
			'name'=>'MI_ITEM',
			'flags'=>array(
			),
		),
		array(
			'type'=>'text',
			'data'=>' 2 '
		),
	);

	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['Attributes (repeated keys)'] = function() {
	$code = <<<FUCK
 1 [[MI_ITEM 
	 	a='uno'
	 	a="dos"
	]] 2 
FUCK;
	$reference = array(
		array(
			'type'=>'text',
			'data'=>' 1 '
		),
		array(
			'type'=>'tag',
			'data'=> array(
				'a'=>'dos',
			),
			'name'=>'MI_ITEM',
			'flags'=>array(
			),
		),
		array(
			'type'=>'text',
			'data'=>' 2 '
		),
	);

	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['Multibyte'] = function() {

	$code = <<<FUCK
ú [[MI_ITEM]] ú 1234567890
FUCK;

	$reference = array (
		0 => array(
			'type' => 'text',
			'data' => 'ú ',
		),
		1 => array(
			'type' => 'tag',
			'data' => array(),
			'name' => 'MI_ITEM',
			'flags' => array(),
		),
		2 => array(
			'type' => 'text',
			'data' => ' ú 1234567890',
		),
	);

	$result = TreeScript::getParse($code); print_r($result);
	return recursive_compare($reference, $result);
};

$tests['[[noparse]]'] = function() {
	echo time();

	$code = <<<FUCK

[[NAME1 attribute:value]]

[[noparse]]

[[NAME1 attribute:value]]

FUCK;

	$reference = array(
		0 => array (
			'type' => 'text',
			'data' => "\n",
		),
		1 => array (
			'type' => 'tag',
			'data' => array (
				'attribute' => 'value',
			),
			'name' => 'NAME1',
			'flags' => array (
			),
		),
		2 => array (
			'type' => 'text',
			'data' => "\n\n",
		),
		3 => array (
			'type' => 'noparse',
			'data' => array (
			),
			'name' => 'noparse',
			'flags' => array (
			),
		),
		4 => array (
			'type' => 'text',
			'data' => "\n\n[[NAME1 attribute:value]]\n",
		),
	);

	$result = TreeScript::getParse($code); var_export($result);

	return recursive_compare($reference, $result);
};

$tests['[[noparse a:b]]'] = function() {
	echo time();

	$code = <<<FUCK

[[NAME1 attribute:value]]

[[noparse a:b]]

[[NAME1 attribute:value]]

FUCK;

	$reference = array(
		0 => array (
			'type' => 'text',
			'data' => "\n",
		),
		1 => array (
			'type' => 'tag',
			'data' => array (
				'attribute' => 'value',
			),
			'name' => 'NAME1',
			'flags' => array (
			),
		),
		2 => array (
			'type' => 'text',
			'data' => "\n\n",
		),
		3 => array (
			'type' => 'noparse',
			'data' => array (
				'a' => 'b',
			),
			'name' => 'noparse',
			'flags' => array (
			),
		),
		4 => array (
			'type' => 'text',
			'data' => "\n\n[[NAME1 attribute:value]]\n",
		),
	);

	$result = TreeScript::getParse($code); var_export($result);

	return recursive_compare($reference, $result);
};

$tests['[[noparse a:b e]]'] = function() {
	echo time();

	$code = <<<FUCK

[[NAME1 attribute:value]]

[[noparse a:b e]]

[[NAME1 attribute:value]]

FUCK;

	$reference = array(
		0 => array (
			'type' => 'text',
			'data' => "\n",
		),
		1 => array (
			'type' => 'tag',
			'data' => array (
				'attribute' => 'value',
			),
			'name' => 'NAME1',
			'flags' => array (
			),
		),
		2 => array (
			'type' => 'text',
			'data' => "\n\n",
		),
		3 => array (
			'type' => 'noparse',
			'data' => array (
				'a' => 'b',
			),
			'name' => 'noparse',
			'flags' => array (
				0 => 'e',
			),
		),
		4 => array (
			'type' => 'text',
			'data' => "\n\n[[NAME1 attribute:value]]\n",
		),
	);

	$result = TreeScript::getParse($code); var_export($result);

	return recursive_compare($reference, $result);
};

Test::addFunctions($tests);