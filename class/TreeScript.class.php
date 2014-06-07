<?php

	/**
	 * Documentar esto
	 *
	 * autor: gerardooscarjt@gmail.com
	 * fecha: 13/04/2011
	 * Uso típico:
	 * 		$parse = TreeScript::getParse($codigo);
	 * 		print_r($parse->getTokens());
	*/
	
	class TreeScript {
		
		
		// TODO: limpiar cosas que no se usan:
		private $encoding;
		private $pos;
		private $len;
		private $code;
		private $tokens = array();
		private $token = null;
		private $last_key = null;
		private $equal = false;
		private $errors = array();
		private $line = 1;
		
		/**
		 * Documentar esto
		*/
		private function __construct($code, $encoding='UTF-8') {
			$this->encoding = $encoding;
			$this->pos = 0;
			$this->code = $code;
			$this->len = mb_strlen($code, $this->encoding);
			$this->parse();
		}
		
		/**
		 * Documentar esto
		*/
		public static function getParse($code) {
			return new TreeScript($code);
		}
		
		/**
		 * Documentar esto
		*/
		
		private function parse() {
		
			mb_internal_encoding('UTF-8');
		
			$text = $this->code;
			$l = mb_strlen($text);
			$i = 0;
			$token = array();
			$tokens = array();
			$state = 0;

			$attribute = '';
			$value = '';

			while ($i<$l) {
				switch ($state) {
					case 0:
						// Estado inicial, busco un inicio de token '[['
						$p = mb_strpos($text,'[[',$i);
						if ($p===false) {
						    $p = $l;
						}
						
						$tokens[] = array('type'=>'text', 'data'=>mb_substr($text,$i, $p-$i));
						$i = $p;
						
						$state = 1;
						break;
					case 1:
						$i+=2; // Me salto los dos corchetes;
						
						$token = array(
						    'type'=>'token',
						    'data'=>array(),
						    'name'=>''
						);
						// Busco el primer espacio o la primera pareja de corchetes que cierran
						$p1 = mb_strpos($text, ']]', $i);
						$p2 = mb_strpos($text, ' ', $i);
						if ($p2===false || $p1<$p2) {
						    $token['name'] = mb_substr($text, $i, $p1-$i);
						    $tokens[] = $token;
						    $i = $p1+2;
						    $state = 0;
						} else {
						    $token['name'] = mb_substr($text, $i, $p2-$i);
						    $i = $p2;
						    $state = 2;
						}
						break;
					case 2:
						// Empiezo a buscar atributos
						// Tarea: si hay un espacio, lo quito
						$c = mb_substr($text, $i, 1);
						$i++;
						switch ($c) {
						    case ' ':
						        // No hago nada
						        $state = 2;
						        break;
						    case ']':
						        // Voy a buscar otro corchete de cierre
						        $state = 3;
						        break;
						    default:
						        // Comienzo a guardar el nombre del primer atributo
						        $attribute = $c;
						        $state = 4;
						} 
						break;
					case 3:
						// Busco otro corchete de cierre
						$c = mb_substr($text, $i, 1);
						$i++;
						if ($c == ']') {
						    $tokens[] = $token;
						}
						$state = 0;
						break;
					case 4:
						// Estoy buscando el nombre del atributo
						$c = mb_substr($text, $i, 1);
						$i++;
						switch ($c) {
						    case ' ':
						        // Voy a buscar un operador de asignación
						        $state = 5;
						        break;
						    case '=':
						    case ':':
						        // Ya he encontrado la asignación!! :) voy a buscar el valor
						        $state = 6;
						        break;
						    case ']':
						        // Cancelo todo y voy a buscar otro corchete de cierre
						        $state = 3;
						        break;
						    default:
						        // Completo el nombre
						        $attribute .= $c;
						    
						}
						break;
					case 5:
						// Empiezo a buscar un operador de asignación
						$c = mb_substr($text, $i, 1);
						$i++;
						switch ($c) {
						    case ' ':
						        // Sigo buscando el operador
						        break;
						    case ':':
						    case '=':
						        // He encontrado el operador! voy a buscar el valor:
						        $state = 6;
						        break;
						    case ']':
						        // Cancelo todo y voy a buscar otro corchete de cierre
						        $state = 3;
						        break;
						    default:
						        $attribute = $c;
						        // esto se produce cuando encuentro un atributo, luego un espacio
						        // y luego empieza a aparecer otro atributo.
						    
						}
						break;
					case 6:
						// Empiezo a buscar un valor de atributo
						$c = mb_substr($text, $i, 1);
						$i++;
						switch($c) {
						    case ' ':
						        // Sigo buscando el primer caracter valido
						        break;
						    case '"':
						        // Empiezo a procesar comillas dobles :P
						        $state = 8;
						        $value = '';
						        break;
						    case '\'':
						        // Empiezo a procesar comillas simples
						        $value = '';
						        $state = 9;
						        break;
						    case ']':
						        // Cancelo todo y voy a buscar otro corchete de cierre
						        $state = 3;
						        break;
						    default:
						        // Empiezo a procesar valores sin comillas:
						        $value = $c;
						        $state = 7;
						}
						break;
					case 7:
						$c = mb_substr($text, $i, 1);
						$i++;
						switch($c) {
						    case ' ':
						        // He terminado de procesar el valor sin comillas :)
						        // Lo añado al data del token y sigo procesando atributos
						        $token['data'][$attribute] = $value;
						        $state = 2;
						        break;
						    case ']':
						        // He terminado de procesar el valor sin comillas :)
						        // Lo añado al data y voy a buscar otro corchete de cierre
						        $token['data'][$attribute] = $value;
						        $state = 3;
						        break;
						    default:
						        $value .= $c;
						    
						}
						break;
					case 8:
						// TODO: esto se puede optimizar con mb_strpos
						// Acumulo valor hasta que encuentre una comilla doble de cierre
						$c = mb_substr($text, $i, 1);
						$i++;
						switch ($c) {
						    case '"':
						        // He terminado de procesar el valor con comillas simples,
						        // Lo añado al data del token y sigo procesando atributos
						        $token['data'][$attribute] = $value;
						        $state = 2;
						        break;
						    default:
						        $value .= $c;
						}
						break;
					case 9:
						// TODO: esto se puede optimizar con mb_strpos
						// Acumulo valor hasta que encuentre una comilla simple de cierre
						$c = mb_substr($text, $i, 1);
						$i++;
						switch ($c) {
						    case '\'':
						        // He terminado de procesar el valor con comillas simples,
						        // Lo añado al data del token y sigo procesando atributos
						        $token['data'][$attribute] = $value;
						        $state = 2;
						        break;
						    default:
						        $value .= $c;
						}
						break;
				}
			}
			
			$this->tokens = $tokens;
		}
		
		/**
		 * Documentar esto
		*/
		public function &getErrors() {
			if (count($this->errors))
				return $this->errors;
			return false;
		}
		/**
		 * Documentar esto
		*/
		public function &getTokens() {
			return $this->tokens;
		}
	}
