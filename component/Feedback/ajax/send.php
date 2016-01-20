<?php

$message = $_POST['message'];

$feedback = Feedback::INSERT();
$feedback->setMessage($message);
$feedback->setUser(Users::getLogged());


// Send email
$to      = 'gerardooscarjt@gmail.com';
$subject = 'Wikaan - Feedback';
$message = htmlentities($message);

$headers = "MIME-Version: 1.0\n" ;
$headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
$headers .= "Reply-To: ".'noreply@metric.com'."\n";
$headers .= "X-Priority: 1 (Higuest)n";
$headers .= "X-MSMail-Priority: High\n";
$headers .= "Importance: High\n";

mail(trim($to), $subject, $message, $headers);



?>