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

	public static function getCurrentUrlScheme() {
		$server_protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
		return strtolower($server_protocol[0]);
	}

	public static function getCurrentUrlHost() {
		return $_SERVER['HTTP_HOST'];
	}

	public static function getCurrentUrl() {
		$scheme = self::getCurrentUrlScheme();
		$host = self::getCurrentUrlHost();
		$request_uri = $_SERVER['REQUEST_URI'];
		return "{$scheme}://{$host}{$request_uri}";
	}

	public static function doRequest($method, $url, $headers, $body) {

		$opts = array('http' =>
			array(
			'method'  => $method,
			'header'  => implode("\n", $headers),
			'content' => $body,
			)
		);

		$context  = stream_context_create($opts);

		return file_get_contents($url, false, $context);
	}

	public static function doPostForm($url, $headers, $body) {

		$headers[] = 'Content-type: application/x-www-form-urlencoded';
		$headers = array_unique($headers);

		return Lib::doRequest('POST', $url, $headers, http_build_query($body));
	}

	public static function json_encode($data) {
		return json_encode($data, Config::get('JSON_ENCODE_OPTIONS'));
	}

	public static function json_decode($data) {
		return json_decode($data);
	}

	/**
	 * Documentar este tipo de menu
	*/
	public static function menu1($node, $show_parent=false, $l=1) {
		$children = $node->children;
		if (count($children)) {
			echo '<ul class="menu menu-'.$l.'">';
			if ($show_parent) {
				$selected = '';
				if (Router::$node->id == $node->id) {
					$selected = ' selected';
				}
				echo '<li class="'.$selected.'">';
				echo '<a class="'.$selected.'" href="'.Router::getNodeUrl($node).'">'.$node->getProperty('title').'</a>';
				echo '</li>';
			}
			foreach ($children as $n) {
				$selected = '';
				if (Router::$node->id === $n->id) {
					$selected = ' selected';
				}

				echo '<li class="'.$selected.'">';
				echo '<a class="'.$selected.'" href="'.Router::getNodeUrl($n).'">'.$n->getProperty('title').'</a>';
				self::menu1($n, false, $l+1);
				echo '</li>';
			}
			echo '</ul>';
		}
	}
	

	private static function print_menu2_item($node) {
		$selected = (Router::$node->id === $node->id) ? 'selected' : '';
		$href = Router::getNodeUrl($node);
		$title = $node->getProperty('title');
		echo "<li class='$selected'><a class='$selected' href='$href'>$title</a></li>";
	}
	
	/**
	 * Documentar este tipo de menu
	*/
	public static function menu2($node, $show_parent=false) {

		if ($node->id === Router::$root->id) {
			$level = self::menu2(Router::$root->getById(Config::get('DEFAULT_PAGE')), $show_parent);
			return -1;
		} else if ($node->id === Config::get('DEFAULT_PAGE')) {
			$level = 0;
		} else {
			$level = self::menu2($node->parent, $show_parent);
		}

		if (-1 === $level) {
			return -1;
		}

		if (count($node->children)) {
			echo "<ul class='menu menu-$level'>";
			if (0 === $level && $show_parent) {
				self::print_menu2_item($node);
			}
			foreach ($node->children as $child) {
				self::print_menu2_item($child);
			}
			echo "</ul>";
		}

		return $level + 1;
	}		
}
