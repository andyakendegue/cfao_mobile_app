<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
$url = "https://ilink-app.com/api/api.php/membres";
$response = file_get_contents($url);

$capp = json_decode($response, true);



$phone = $capp["membres"]["records"][0][2];
$solde = $capp["membres"]["records"][0][3];
$mdp = $capp["membres"]["records"][0][4];

echo 'Votre numero '.$phone.' votre mot de passe '.$mdp.' votre solde '.$solde.'';

?>
