<?php

	/**
	 * Class: NotesEntry
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class NotesEntry extends NotesEntry_auto {

		public static function INSERT() {
			$row = parent::INSERT();
			$row->setAuthor(Session::getUser());
			$row->setTitle(Label::INSERT());
			$row->setContent(SimpleText::INSERT());
			$row->setCreation(time());
			$row->setPublication(time()+365*24*3600*3);
			return $row;
		}
		
		public function isPublished() {
			return time() > $this->getPublication();
		}
		
		public function publish() {
			$this->setPublication(time());
		}

	}
