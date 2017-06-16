<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 20/02/2017
 * Time: 15:40
 */
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

//$rest_json = file_get_contents("php://input");
//$_POST = json_decode($rest_json, true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// API access key from Google API's Console
//define( 'API_ACCESS_KEY', 'ENTER_PUBLIC_API_ACCESS_KEY_FROM_API_CONSOLE' );
define( 'API_ACCESS_KEY', 'AAAA8l5H83w:APA91bGdmiWUGK8N6bCbuCO3KUir5QeSIsOa6A5mnN03PNB2VS0K7kocQQ0vy9eOA2Ae0p5uMexf-GZe-HEuGcMJ8uXAFYyFrd8PlZxs09BsPqiEvTJheiD8hAiYKYL9rd58teIty9VA' );

$registrationIds = array( $_GET['id'] );

// prep the bundle
$msg = array
(
    'message' 	=> 'New Message',
    'title'		=> 'Title',
    'subtitle'	=> 'Subtitle',
    'tickerText'	=> 'Ticker Text',
    'vibrate'	=> 1,
    'sound'		=> 1,
    'largeIcon'	=> 'large_icon',
    'smallIcon'	=> 'small_icon'
);

$fields = array
(
    //'registration_ids' 	=> $registrationIds,
    'to' => 'awcf',
    'data'			=> $msg
);

$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result;

?>