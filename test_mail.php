<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 16/04/2017
 * Time: 14:55
 */

$mail = "andymigoumbi@gmail.com";
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====Définition du sujet.
$sujet = "Hey mon ami !";
//=========


//==========
$headers = 'From: webmaster@azur-wifly.com' . "\r\n" .
    'Reply-To: webmaster@azur-wifly.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

//=====Envoi de l'e-mail.
mail("andymigoumbi@gmail.com",$Subject,$body,$headers);
//==========

$envoi = mail("andymigoumbi@gmail.com",$Subject,$body,$headers);