<?php

	/**
	 * Class: SimpleText
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SimpleText extends SimpleText_auto {

		public static function INSERT() {
			$row = parent::INSERT();
			return $row;
		}
		
		public function getText() {
			$array = unserialize(parent::getText());
			if (!is_array($array) || !count($array))
				return '';
			
			$language = Router::$language;
			if (array_key_exists($language, $array)) {
				return $array[$language];
			} else {
				reset($array);
				return current($array);
			}
		}
		
		public function setText($value) {
			$language = Router::$language;
				
			$array = unserialize(parent::getText());
			if (!is_array($array)) {
				$array = array();
			}
		
			$array[$language] = $value;
			parent::setText(serialize($array));
		}

		public function toString() {
			return htmlentities($this->getText());
		}

	}
