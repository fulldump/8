<?php

	/**
	 * Descripción: Colección de funciones estáticas que renderizan distintos
	 * elementos.
	 * 
	 * Si una función devuelve un string, significa que el código original
	 * debe corregirse con ese string.
	 * 
	*/


	class RenderToken {
		
		public static function tokenDefault(&$token, &$text) {
			if ($token['type'] == 'text') {
				$text .= $token['data'];
			} else if ($token['type'] == 'token' && strtoupper($token['name']) == 'COMPONENT') {
				$name = $token['data']['name'];
				
				$component = SystemComponent::getComponentByName($name);
				if ($component !== null) {
					$text .= '<?php $data = '.var_export($token['data'], true).'; ?>';
					// Sustituyo esto:
					//$text .= $component->getPHP();
					
					// Por esto otro:
					$html = $component->getPHP();
				    $ctokens = TreeScript::getParse($html)->getTokens();
					$ctext = '';
				    foreach ($ctokens as $ctoken)
						RenderToken::tokenDefault($ctoken, $ctext);
			
					$text .= $ctext;
					// FIN

					
					ControllerPage::requireComponent($name);
				}
			} else if ($token['type'] == 'token' && $token['name'] == '') {
				$text .= '';
			} else {
				//$text .= '';
			}
		}
		
		public static function tokenToString(&$token) {
			$result = '[['.$token['name'];
			
			$data =& $token['data'];
			foreach ($data as $D=>$d) {
				if (strpos($D, ' ') !== false) $D = "'".$D."'";
				if (strpos($d, ' ') !== false) $d = "'".$d."'";
				$result .= ' '.$D.'='.$d;
			}
			
			$result .= ']]';
			
			return $result;
		}
		
	}
