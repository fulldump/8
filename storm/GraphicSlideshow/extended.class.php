<?php
	
	/**
	 * Clase: GraphicSlideshow
	 * Ubicación: storm/GraphicSlideshow/extended.class.php
	 * Fecha: Thu, 23 May 2013 22:54:40 +0200
	 * 
	*/
	
	class GraphicSlideshow extends GraphicSlideshow_auto {

		public static function INSERT() {
			$row = parent::INSERT();
			for ($i=0; $i<5; $i++) {
				$row->add();
			}
			return $row;
		}
		
		public function add() {
			$item = GraphicSlideshowItem::INSERT();
			$item->setSlideshow($this);
			return $item;
		}

		public function getAll() {
			$where = "`Slideshow` = '".mysql_real_escape_string($this->getId())."' ORDER BY `Order`";
			return GraphicSlideshowItem::SELECT($where);
		}

		public function toString() {
			return $this->getName();
		}

	}
