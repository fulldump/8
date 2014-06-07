<?php

	/**
	 * Class: SystemPage
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemPage extends SystemPage_auto {

		public function setHTML($html) {
			// TODO: Corregir sintaxis...
			
			$tokens = TreeScript::getParse($html)->getTokens();
			$html = '';
			foreach ($tokens as $token) {
				if ($token['type'] == 'text') {
					$html .= $token['data'];
				} else {
					if (strtoupper($token['name']) == 'COMPONENT') {
						$name = $token['data']['name'];
						$component = SystemComponent::get($name);
						if ($component === null) {
							$token['data']['error'] = 'Component "'.$name.'" does not exists.';
							// El componente no existe :S
						} else {
							self::validateHTML($token, $component->getValidation());
						}
					}
					$html .= RenderToken::tokenToString($token);
				}
				
			}
			
			parent::setHTML($html);
		}
		
		private static function validateHTML(&$token, $code) {
			eval($code);
		}
		
		public function render() {


			$html = $this->getHTML();
            $tokens = TreeScript::getParse($html)->getTokens();
			$text = '';
            foreach ($tokens as $token)
				RenderToken::tokenDefault($token, $text);

			
			ControllerPage::appendHTML($text);

			$template = SystemTemplate::get($this->getTemplate());
			if (null === $template) {
				$template = SystemTemplate::get(Config::get('DEFAULT_TEMPLATE'));
			}
			$template->render();

			ControllerPage::appendCSS($this->getCSS());
			ControllerPage::appendJS($this->getJS());
		}

	}
