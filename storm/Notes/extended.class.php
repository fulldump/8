<?php

	/**
	 * Class: Notes
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class Notes extends Notes_auto {

		public static function INSERT() {
			$row = parent::INSERT();
			$row->setCreation(time());
			$row->setConfiguration(serialize(array(
				'entries-per-page'=>'3',
				'default-title'=>'Título',
				'default-content'=>'Nota',
			)));
			$row->makeEntry();
			return $row;
		}
		
		public function makeEntry() {
			$configuration = unserialize($this->getConfiguration());
			$entry = NotesEntry::INSERT();
			$entry->setNotes($this);
			$entry->getTitle()->setText($configuration['default-title']);
			$entry->getContent()->setText($configuration['default-content']);
			return $entry;
		}
		
		public function getEntries($page = 0, $all = false) {
		
			if ($all) {
				$sql_published = "";
			} else {
				$sql_published = " AND '".time()."' > Publication";
			}
			
			$configuration = unserialize($this->getConfiguration());
			$entries_per_page = $configuration['entries-per-page'];
			
			$sql = "Notes='".$this->getId()."' ".$sql_published." ORDER BY Creation DESC LIMIT ".($page*$entries_per_page).", ".($entries_per_page+1);
			
			$entries = NotesEntry::SELECT($sql);
			
			$has_prev = false;
			if ($page > 0)
				$has_prev = true;
			
			$has_next = false;
			if (count($entries)>$entries_per_page) {
				$has_next = true;
				array_pop($entries);
			}

			return array($entries, $has_prev, $has_next);
		}

	}
