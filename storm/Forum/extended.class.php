<?php
	
	/**
	 * Clase: Forum
	 * Ubicación: storm/Forum/extended.class.php
	 * Fecha: Sat, 27 Oct 2012 16:19:55 +0200
	 * 
	*/
	
	class Forum extends Forum_auto {
		
		public static function INSERT() {
			$row = parent::INSERT();
			
			$row->setText($text);
			$row->setTimestamp(time());
			$row->setUser(Session::getUser());
			
			return $row;
		}		
		
		public static function makeQuestion($text) {
			$row = self::INSERT();
			$row->setText($text);
			
			return $row;
		}
		
		public function answer($text) {
			$row = self::INSERT();
			
			$row->setText($text);
			$row->setResponseTo($this);
			
			$this->incNumResponses();
			
			return $row;
		}		
		
		public function incNumResponses() {
			$this->setNumResponses($this->getNumResponses() + 1);
			$mo = $this->getResponseTo();
			if ($mo != null)
				$mo->incNumResponses();
		}
		
		public function getResponses() {
			$where = "ResponseTo = '".$this->getId()."' ORDER BY Timestamp";
			return parent::SELECT($where);
		}		
		
		public function drawQuestion() {
			echo '<div class="foro-elem" id="q'.$this->getId().'">';
				$date = $this->getTimestamp();
				echo '<div class="fecha" title="Hora: '.date('H',$date).':'.date('i',$date).'">';
					echo '<div class="dia">'.date('d',$date).'</div>';
					echo '<div class="mes">'.date('M',$date).'</div>';
					echo '<div class="ano">'.date('Y',$date).'</div>';
				echo '</div>';
				echo '<div class="botones margen">';
					echo '<a class="shadow-button shadow-button-blue" href="?pregunta='.$this->getId().'">Ver</a>';
				echo '</div>';
				echo '<div class="margen texto">';
					echo '<div class="pie">';
						$autor = $this->getUser();
						if ($autor != null)
							echo '<div class="autor">por <em>'.htmlentities($this->getUser()->getName(), ENT_COMPAT, 'utf-8').'</em></div>';
						// TODO: Contar y escribir comentarios:
						echo '<div class="comentarios">'.$this->getNumResponses().' respuestas</div>';
					echo '</div>';
					echo Lib::colorizeHTML($this->getText());
				echo '</div>';
			echo '</div>';
		}
		
		public function drawAnswers() {
			$classname = 'foro-elem';
			if (Session::isLoggedIn() && $this->getUser()->getId() == Session::getUser()->getId())
				$classname .= ' foro-elem-user';
			echo '<div class="'.$classname.'" id="q'.$this->getId().'">';
				$date = $this->getTimestamp();
				echo '<div class="fecha" title="Hora: '.date('H',$date).':'.date('i',$date).'">';
					echo '<div class="dia">'.date('d',$date).'</div>';
					echo '<div class="mes">'.date('M',$date).'</div>';
					echo '<div class="ano">'.date('Y',$date).'</div>';
				echo '</div>';
				echo '<div class="botones margen">';
					echo '<button id="answer-button1-'.$this->getId().'" class="shadow-button shadow-button-blue" onclick="botonResponderClick(\''.$this->getId().'\'); this.style.display=\'none\'">Responder</button>';
				echo '</div>';
				echo '<div class="margen texto">';
					echo '<div class="pie">';
						$autor = $this->getUser();
						if ($autor != null)
							echo '<div class="autor">por <em>'.htmlentities($this->getUser()->getName(), ENT_COMPAT, 'utf-8').'</em></div>';
						
						// TODO: Contar y escribir comentarios:
						echo '<div class="comentarios">'.$this->getNumResponses().' respuestas</div>';
					echo '</div>';
					echo Lib::colorizeHTML($this->getText());
	 			echo '</div>';
				echo '<div id="answer'.$this->getId().'" class="margen" style="background-color:silver; display:none;">fasdfasfdffdfdfdfafsd';
				echo '</div>';
			echo '</div>';
			echo '<div id="hijos'.$this->getId().'" class="foro-hijos">';
			$hijos = $this->getResponses();
			foreach ($hijos as $h)
				$h->drawAnswers();
			echo '</div>';			
		}
		
		public function getQuestion() {
			$question = $this;
			while($question->getResponseTo() != null)
				$question = $question->getResponseTo();
			return $question;
		}		
	}
