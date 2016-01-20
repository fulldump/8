<?php

	/**
	 * Class: GraphicSlideshowItem
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class GraphicSlideshowItem extends GraphicSlideshowItem_auto {

		public static function INSERT() {
			$row = parent::INSERT();

			$row->setBackground(Image::INSERT('resources/default-image.png'));
			$row->setTitle(Label::INSERT());
			$row->setText(SimpleText::INSERT());
			$row->setEnabled(1);

			return $row;
		}



	}
