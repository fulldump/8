<?php

/**
 * Class: HTML
 * Location: /class/HTML.class.php
 * Description: HTML parser and colorizer
 *
 * author: gerardooscarjt@gmail.com
 * date: 2014/10/26
*/

class HTML {

	public static function parse($code) {
		$codigo = $code;
		$parse = array();
		$state = 0;
		$n = mb_strlen($codigo, 'utf-8');
		$i = 0;
		$word = '';
		$token = array();
		while ($i<$n) {
			$symbol = mb_substr ($codigo, $i, 1, 'utf-8');
			
			//echo $state.'('.$symbol.')<br>';
			
			switch ($state) {
				case 0: // Busco texto
					if ($symbol == '<'){
						$state = 1;
					} else {
						$word .= $symbol;
					}
					break;
				case 1: // Hay espacio detrás de < 
					if ($symbol == ' ') {
						$word .= '< ';
						$state = 0;
					} else {
						$parse[] = array('type'=>'text', 'inner'=>$word);
						$token = array('type'=>'', 'attributes'=>array());
						$word = $symbol;
						$state = 2;
					}
					break;
				case 2: // Es un tag
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t" ) {
						
						if ($word == '?' || strtoupper($word) == '?PHP') {
							$state = 10;
						} else {
							$state = 3;
						}
						
						$token['type'] = $word;
						$word = '';
						$attribute_key = '';
						$attribute_value = '';
						
					} else if ($symbol == '>') {
						$token['type'] = $word;
						$word = '';
						$parse[] = $token;
						$state = 0;
					} else {
						$word .= $symbol;
					}
					break;
				case 3: // Busco atributos
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t" ) {
						// no hago nada :)
					} else if ($symbol == '>') {
						$parse[] = $token;
						$state = 0;
					} else {
						$attribute_key .= $symbol;
						$state = 8;
					}
					break;
				case 4:
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t" ) {
						// no hago nada
					} else if ($symbol == "'") {
						$attribute_value .= $symbol;
						$state = 5;
					} else if ($symbol == '"') {
						$attribute_value .= $symbol;
						$state = 6;
					} else if ($symbol == '>') {
						$parse[] = $token;
						$state = 0;
					} else {
						$attribute_value .= $symbol;
						$state = 7;
					}
					break;
				case 5:
					if ($symbol == "'") {
						$attribute_value .= $symbol;
						$token['attributes'][] = array('key'=>$attribute_key,'value'=>$attribute_value);
						$attribute_key = '';
						$attribute_value = '';
						$state = 3;
					} else {
						$attribute_value .= $symbol;
					}
					break;
				case 6:
					if ($symbol == '"') {
						$attribute_value .= $symbol;
						$token['attributes'][] = array('key'=>$attribute_key,'value'=>$attribute_value);
						$attribute_key = '';
						$attribute_value = '';
						$state = 3;
					} else {
						$attribute_value .= $symbol;
					}
					break;
				case 7:
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t") {
						$token['attributes'][] = array('key'=>$attribute_key,'value'=>$attribute_value);
						$attribute_key = '';
						$attribute_value = '';
						$state = 3;
					} else if($symbol == '>') {
						$token['attributes'][] = array('key'=>$attribute_key,'value'=>$attribute_value);
						$attribute_key = '';
						$attribute_value = '';
						$parse[] = $token;
						$state = 0;
					} else {
						$attribute_value .= $symbol;
					}
					break;
				case 8:
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t") {
						$state = 9;
					} else if ($symbol == '=') {
						$state = 4;						
					} else if ($symbol == '>') {
						$token['attributes'][] = array('key'=>$attribute_key);
						$attribute_key = '';
						$parse[] = $token;
						$state = 0;
					} else {
						$attribute_key .= $symbol;
					}
					break;
				case 9:
					if ($symbol == ' ' || $symbol == "\n" || $symbol == "\t") {
						// No hago nada
					} else if ($symbol == '=') {
						$state = 4;
					} else if ($symbol == '>') {
						$token['attributes'][] = array('key'=>$attribute_key);
						$attribute_key = '';
						$state = 0;
					} else {
						$token['attributes'][] = array('key'=>$attribute_key);
						$attribute_key = $symbol;
						$state = 8;
					}
					break;
				case 10:
					if ($symbol == '?') {
						$state = 11;
					} else {
						$word .= $symbol;
					}
					break;
				case 11:
					if ($symbol == '>') {
						$token['inner'] = $word;
						$parse[] = $token;
						$word = '';
						$state = 0;
					} else {
						$word .= '?>';
						$state = 10;
					}
					break;
				
			}
			$i++;
		}
		$parse[] = array('type'=>'text', 'inner'=>$word);
		return $parse;
	}

	public static function colorizeHTML($code) {
		$parse = self::parse($code);
		$s = '';
		$only_text = true;
		$textile = new  Textile();
		foreach ($parse as $p) {
			if ($p['type'] == 'text') {
				$text = str_replace(array('  ', "\t", "\n"), array('&nbsp; ','&nbsp;&nbsp;&nbsp;&nbsp; ','<br>'), $p['inner']);
				if ($only_text) {
					$s .= '<div>'.$textile->TextileThis($text).'</div>';
				} else {
					$s .= '<span style="font-family:monospace;">'.$text.'</span>';
				}
			} else {
				if ($only_text && $p['type'] != 'text') {
					$only_text = false;
					$s .= '<code style="padding:16px; margin-top:16px; border:dashed silver 1px; display:block; overflow-x:auto;">';
				}
				
				if ($p['type'] == '?' || strtoupper($p['type']) == '?PHP') {
					
					$s .= highlight_string('<'.$p['type'].' '.$p['inner'].'?>', true);
				} else {
					$s .= '<span style="color:blue; font-family:monospace;">&lt;'.$p['type'].'</span>';
					
					$attributes = &$p['attributes'];
					foreach ($attributes as $a) {
						$s .= ' <span style="color:purple; font-family:monospace;">'.$a['key'].'</span>';
						if (isset($a['value']))
							$s .= '<span style="font-family:monospace;">=</span><span style="color:navy; font-family:monospace;">'.$a['value'].'</span>';
					}
					
					$s .= '<span style="color:blue; font-family:monospace;">&gt;</span>';
					
				}
			}
			
		}
		
		if (!$only_text) $s .= '</code>';
		return $s;
	}

}