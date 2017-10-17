<?php
# !/usr/bin/php
// Pour envoyer un mail HTML, l en-tête Content-type doit être défini
$headers = "MIME-Version: 1.0" . "\n";
$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
 
// En-têtes additionnels
$headers .= 'From: Serveur Azur-Wifly <no-reply@azur-wifly.com>' . "\r\n";

// Destinataire
$to = "andymigoumbi@gmail.com";
// Sujet
$subject = 'Renouvellement des certificats de sécurité';
 
// Message
$message = '
<html>
  <head>
    <title>Certificats de sécurité</title>
  </head>
  <body>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td align="center">
          <p>
            Les certificats de sécurité arriveront à expiration dans une semaine !
	<br>
	Veuillez, vous connecter au serveur et effectuer le renouvellement des certificats.
          </p>
          <p>
            Le serveur Azur-Wifly
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
';

// Envoie
      
$resultat = mail($to, $subject, $message, $headers);

echo $resultat;



?>
