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
				echo '<a class="'.$selected.'" href="'.Router::getNodeUrl($node, true).'">'.$node->getProperty('title').'</a>';
				echo '</li>';
			}
			foreach ($children as $n) {
				$selected = '';
				if (Router::$node->id === $n->id) {
					$selected = ' selected';
				}

				echo '<li class="'.$selected.'">';
				echo '<a class="'.$selected.'" href="'.Router::getNodeUrl($n, true).'">'.$n->getProperty('title').'</a>';
				self::menu1($n, false, $l+1);
				echo '</li>';
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
