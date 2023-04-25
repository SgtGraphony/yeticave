<?php

require_once("models.php");
require_once("functions.php");
require_once ("vendor/autoload.php");

$transport = (new Swift_SmtpTransport())
 ->setUsername('')
 ->setPassword('')
 ;

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Subject'))
 ->setFrom(['mail' => 'name'])
 ->setTo(['reciever' => 'name'])
 ->setBody('Message')
 ;

$result = $mailer->send($message); 