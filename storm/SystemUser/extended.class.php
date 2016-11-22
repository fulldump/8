<?php

	/**
	 * Class: SystemUser
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemUser extends SystemUser_auto {

		public function setPassword($value) {
			return parent::setPassword(md5($value));
		}

		public function inGroup($group) {
			$groups = json_decode($this->getGroups(), true);
			if (false === $groups) {
				return false;
			}

			if (in_array('*', $groups)) {
				return true;
			}

			return in_array($group, $groups);
		}
		
		public static function validate($login, $password) {
			sleep(Config::get('LOGIN_DELAY'));
			$list = self::SELECT("`Login`='".Database::escape($login)."' AND `Password`='".Database::escape(md5($password))."'");
			if (count($list)>0) {
				return $list[0];
			} else {
				return null;
			}
		}
		
		public static function recoverPassword($login) {
			$login = trim($login);
			if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
				$list = self::SELECT("`Login`='".Database::escape($login)."'");
				if (count($list)==0) {
					return 'El email no está registrado';
				} else {
					$password = base_convert(rand(10e16, 10e20), 10, 36);
					$user = $list[0];
					$user->setPassword($password);
					
					$email_contacto = $login;
					$to      = $login;
					$subject = 'Recuperar contraseña en Ovillo';
					$message = '<div style="padding:3px; background-color:#185787; color:white;">Recuperar contraseña</div>
						<div style="padding:20px; border:solid #185787 1px; background-color:white;">
						<div style="float:right;">'.date("r").'</div>
						<h2 style="margin:0; padding:0;">'.$_POST['email'].'</h2><br>
						Hola '.$user->getNombre().',<br>
						<br>
						Tu nueva contraseña es <b>'.$password.'</b>.<br>
						<br>
						Si tú no has pedido la recuperación de contraseña ponte en contacto con el administrador.
						<br>
						Saludos del equipo Ovillo
						</div>';
					
					$headers = "MIME-Version: 1.0\n" ;
					$headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
					$headers .= "Reply-To: ".$login."\n";
					$headers .= "From: ".$login."\n";
					$headers .= "X-Priority: 1 (Higuest)\n";
					$headers .= "X-MSMail-Priority: High\n";
					$headers .= "Importance: High\n";
					
					$ok = mail($login, $subject, $message, $headers);
					
					return '';
				}
			} else {
				return 'El email no es válido';
			}
		}
		
		public static function register($name, $login) {
			// Compruebo si el login es un email válido
			if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
				// Compruebo si el email ya estaba
				$list = self::SELECT("`Login`='".Database::escape($login)."'");
				if (count($list)==0) {
					$password = base_convert(rand(10e16, 10e20), 10, 36);
					$new_user = self::INSERT();
					$new_user->setNombre($name);
					$new_user->setLogin($login);
					$new_user->setPassword($password);
					$new_user->setNumLogins(0);
					
					$email_contacto = $login;
					$to      = $login;
					$subject = 'Registro en Ovillo';
					$message = '<div style="padding:3px; background-color:#185787; color:white;">Formulario de registro</div>
						<div style="padding:20px; border:solid #185787 1px; background-color:white;">
						<div style="float:right;">'.date("r").'</div>
						<h2 style="margin:0; padding:0;">'.$_POST['email'].'</h2><br>
						Hola '.$name.',<br>
						<br>
						Te has registrado en Ovillo con el email '.$login.'.<br>
						Tu clave provisional es <b>'.$password.'</b> recuerda cambiarla cuando entres al sistema.<br>
						<br>
						Saludos del equipo Ovillo
						</div>';
					
					$headers = "MIME-Version: 1.0\n" ;
					$headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
					$headers .= "Reply-To: ".$login."\n";
					$headers .= "From: ".$login."\n";
					$headers .= "X-Priority: 1 (Higuest)\n";
					$headers .= "X-MSMail-Priority: High\n";
					$headers .= "Importance: High\n";
					
					$ok = mail($login, $subject, $message, $headers);
					
					return $ok;
				} else {
					return false;
				}
			} else {
				return false;
			}
			
		}
		
		public function toString() {
			return $this->getName();
		}

	}
