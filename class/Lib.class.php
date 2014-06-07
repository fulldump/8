<?php

	/**
	 * Clase: Lib
	 * Ubicación: /class/Lib.class.php
	 * Descripción: Funciones varias
	 *
	 * autor: gerardooscarjt@gmail.com
	 * fecha: 2011/08/04
	*/
	
	class Lib {
		
		public static function parseHTML($code) {
			
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
			$parse = self::parseHTML($code);
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
		
		public static function urlFriendize($nombre) {
			$n = $nombre;
			
			$s = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','à','è','ì','ò','ù','À','È','Ì','Ò','Ù','â','ê','î','ô','û','Â','Ê','Î','Ô','Û','ä','ë','ï','ö','ü','Ä','Ë','ï','Ö','Ü','ñ','Ñ','ý','ỳ','ÿ','ŷ','Ý','Ỳ','Ÿ','Ŷ','/');
			$r = array('a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','n','N','y','y','y','y','Y','Y','Y','Y','  ');
			
			$n = str_replace($s, $r, $n);
			
            $n = str_replace(' ', '-', $n);
            $n = preg_replace('[^a-zA-Z0-9-_]','', $n);
            
            $palabras = explode('-', $n);
            foreach ($palabras as $P=>$p)
                if (strlen($p)<1) unset($palabras[$P]);
            $n = implode('-', $palabras);
            
            return $n;
		}
		
		public static function is_md5($md5) {
			return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
		}
		
		public static function editingMode() {
			return array_key_exists('edit', $_GET) && Session::isLoggedIn();
		}
		
		public static function humanSize($size) {
			$prefix = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
			$i = 0;
			while ($size > 1024) {
				$size /= 1024;
				$i++;
			}
			return number_format($size, 2).' '.$prefix[$i];
		}

		/**
		 * Documentar este tipo de menu
		*/
		public static function menu1($node, $show_parent=false, $l=1) {

			/* Cada llamada imprime el nombre de los hijos */
	

			$children = $node->getChildren();
			if (count($children)) {
				echo '<ul class="menu menu-'.$l.'">';
				if ($show_parent) {
					$selected = '';
					if (ControllerPage::$page->getId() == $node->getReference())
						$selected = ' selected';
				
					echo '<li class="'.$selected.'">';
					echo '<a class="'.$selected.'" href="'.$node->getPath().'">'.$node->getTitle().'</a>';
					echo '</li>';
				}
				foreach ($children as $n) {
					$n_url = $n->getUrl();
					if ($n_url != 'adminx' && $n_url != 'admin' && $n_url != 'profile') {
						$selected = '';
						if (ControllerPage::$page->getId() == $n->getReference())
							$selected = ' selected';
						
						$path = $n->getPath();
						if (array_key_exists('edit', $_GET))
							$path .= '?edit';
						echo '<li class="'.$selected.'">';
						echo '<a class="'.$selected.'" href="'.$path.'">'.$n->getTitle().'</a>';
						self::menu1($n, false, $l+1);
						echo '</li>';
					}
				}
				echo '</ul>';
			}
		}
		
		
		/**
		 * Documentar este tipo de menu
		*/
		public static function menu2($node, $max_id = 1,$show_parent=false, $sel_id=0) {
			if ($node->getId() == $max_id) {
				$array = array(
					'level'=>1
				);
			} else {
				$array = Lib::menu2($node->getParent(), $max_id, $show_parent, $node->getId());
				$array['level']++;
			}

			$children = $node->getChildren();
			if (count($children)) {
				echo '<ul class="menu menu-'.$array['level'].'">';
				if ($node->getId() == $max_id && $show_parent)
					echo '<li><a class="menu-e'.$node->getId().$selected.'" href="'.$node->getPath().'">'.$node->getTitle().'</a></li>';

				foreach($children as $n) {
					$n_url = $n->getUrl();
					if ($n_url != 'adminx' && $n_url != 'admin' && $n_url != 'profile') {
						$selected = $n->getReference() == $sel_id ? ' selected' : '';
						echo '<li><a class="'.$selected.'" href="'.$n->getPath().'">'.$n->getTitle().'</a></li>';
					}
				}
				echo '</ul>';
			}

			return $array;
		}		
	}
