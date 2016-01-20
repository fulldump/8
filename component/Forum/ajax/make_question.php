<?php

	$text = $_POST['text'];
	
	$pregunta = Forum::makeQuestion($text);

	$pregunta->drawQuestion();
	
	ob_end_flush();

	$user = Session::getUser();
	$login = $user->getLogin();
	$email_contacto = $login;
	$to      = 'ovillo@lists.ovillo.org';
	$subject = $user->getName().' ha preguntado algo en el foro';
	
	$date = $pregunta->getTimestamp();
	$autor = $pregunta->getUser();
	if ($autor != null)
		$autor_txt =  '<div class="autor">por <em>'.htmlentities($autor->getName(), ENT_COMPAT, 'utf-8').'</em></div>';

	$message = '
		<div style="    border: 1px solid silver;    border-radius: 8px 8px 8px 8px;    margin: 8px;    overflow: auto;">
			<div class="fecha" title="Hora: '.date('H',$date).':'.date('i',$date).'" style="    color: gray;    float: left;    font-weight: bold;    text-align: center;    width: 48px;">
				<div class="dia" style="font-size: 28px;">'.date('d',$date).'</div>
				<div class="mes" style="">'.date('M',$date).'</div>
				<div class="ano" style="font-size: 10px;">'.date('Y',$date).'</div>
			</div>
			<div class="botones margen" style="clear: right; float: right; padding: 16px;">
				<a href="http://'.$_SERVER['HTTP_HOST'].Config::get('FORUM_URL').'?pregunta='.$pregunta->getQuestion()->getId().'#q'.$pregunta->getId().'" class="shadow-button shadow-button-blue" style="    border: 1px solid silver;    border-radius: 3px 3px 3px 3px;    color: white;    cursor: pointer;    font-size: 12px;    font-weight: bold;    margin: 0;    padding: 7px;    text-transform: uppercase; background-color: #4D90FE; text-decoration:none;">Responder</a>
			</div>
			<div style="margin-left: 48px; padding: 16px;">
				<div class="pie" style="">
					'.$autor_txt.'
				</div>
				'.Lib::colorizeHTML($pregunta->getText()).'
			</div>
		</div>
		<br>
		<br>
		Enlace para responder <a href="http://'.$_SERVER['HTTP_HOST'].Config::get('FORUM_URL').'?pregunta='.$pregunta->getQuestion()->getId().'#q'.$pregunta->getId().'">http://'.$_SERVER['HTTP_HOST'].Config::get('FORUM_URL').'?pregunta='.$pregunta->getQuestion()->getId().'#q'.$pregunta->getId().'</a>
		';
	
	
	
	
	
	$headers = "MIME-Version: 1.0\n" ;
	$headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
	$headers .= 'Message-ID: <'.md5($pregunta->getQuestion()->getId()).'@ibentus.treeweb.es>'."\n";
	$headers .= "Reply-To: ".$login."\n";
	$headers .= "From: ".Config::get('FORUM_SENDER_EMAIL')."\n";
	$headers .= "X-Priority: 1 (Higuest)\n";
	$headers .= "X-MSMail-Priority: High\n";
	$headers .= "Importance: High\n";
	
	$usuarios = SystemUser::SELECT();
	foreach ($usuarios as $u)
		$ok = mail($u->getLogin(), $subject, $message, $headers);

?>