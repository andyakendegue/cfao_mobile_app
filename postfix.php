#!/usr/bin/php
<?php 
/** CReated by Capp 12/04/2017
**
**/
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_include_path(implode(PATH_SEPARATOR, array(
    realpath('.'), // Make sure this is the correct path to your library folder
    get_include_path(),
)));

include __DIR__.'/MimeMailParser/Parser.php';
include __DIR__.'/MimeMailParser/Attachment.php';

$parser = new MimeMailParser\Parser();
$parser->setText(file_get_contents('php://stdin'));

$to = $parser->getHeader('to');
$delivered_to = $parser->getHeader('delivered-to');
$from = $parser->getHeader('from');
$subject = $parser->getHeader('subject');
$text = $parser->getMessageBody('text');
$html = $parser->getMessageBody('html');
$attachments = $parser->getAttachments();

// Write attachments to disk
foreach ($attachments as $attachment) {
    $attachment->saveAttachment('/tmp');
}

//parse_str($subject, $output);
$output = explode("&", $subject);
$phoneNumber = explode("=", $output[0]);
$textTosend = explode("=", $output[1]);
$stringNumber = $phoneNumber[1];
$arr1 = str_split($stringNumber);
$bon_num = $stringNumber;
/*
if($arr1[3]=="0"){
    $bon_num = $stringNumber;
} else {
    $bon_num = "2410".$arr1[3].$arr1[4].$arr1[5].$arr1[6].$arr1[7].$arr1[8].$arr1[9];
}
*/

$email = "mbaquent1@gmail.com";


//=====Définition du sujet.
$sujet = "Hey mon ami !";
//=========

$headers = 'From: sms@azur-wifly.com' . "\r\n" .
    'Reply-To: sms@azur-wifly.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

//=====Envoi de l'e-mail.
// read from stdin

mail($email, $sujet, $bon_num.' '.$textTosend[1], $headers);
$url = 'http://azur-wifly.com:13132/cgi-bin/sendsms?username=capp&password=musobase&from=AzurMiddleWare&to='.$bon_num.'&text='.$textTosend[1];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

$result = curl_exec($ch);
$var = json_decode($result, true);
//return $var;

//header('Content-type: text/json');
//print_r($result);
//$response = file_get_contents($url);

return $result;
//==========

?>
