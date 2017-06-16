<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 28/02/2017
 * Time: 15:33
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
$baseurl = 'http://127.0.0.1:1401/send';

$params = '?username=capp';
$params.= '&password=musobase';
$params.= '&to='.urlencode('03454400');
$params.= '&content='.urlencode('Hello world !');

$response = file_get_contents($baseurl.$params);
$response_encode = json_decode($response, true);


print_r('cool'.$response);
*/



require_once 'lib/smppclient.class.php';
require_once 'lib/gsmencoder.class.php';
require_once 'lib/sockettransport.class.php';
define('USERNAME',     'systemsms');
define('PASSWORD',     'sms');

// Construct transport and client
$transport = new SocketTransport(array('154.118.248.45'),5019);
$transport->setSendTimeout(10000);
$transport->setRecvTimeout(10000);
$smpp = new SmppClient($transport);

// Activate binary hex-output of server interaction
$smpp->debug = true;
$transport->debug = true;

// Open the connection
$transport->open();
$smpp->bindTransmitter(USERNAME,PASSWORD);

// Optional connection specific overrides
//SmppClient::$sms_null_terminate_octetstrings = false;
//SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
//SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;

// Prepare message
$message = 'H€llo world';
$encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
$from = new SmppAddress('SMPP Test',SMPP::TON_ALPHANUMERIC);
$to = new SmppAddress(03454400,SMPP::TON_INTERNATIONAL,SMPP::NPI_E164);

// Send
$smpp->sendSMS($from,$to,$encodedMessage,$tags);

// Close connection
$smpp->close();


/*
include 'lib/class.smpp.php';

$src  = "24103454400"; // or text
$dst  = "24103454400";
$message = "Test Message";

$s = new smpp();
$s->debug=1;

// $host,$port,$system_id,$password
$s->open("192.168.2.201", 5019, "systemsms", "sms");

// $source_addr,$destintation_addr,$short_message,$utf=0,$flash=0
$s->send_long($src, $dst, $message);

/* To send unicode
$utf = true;
$message = iconv('Windows-1256','UTF-16BE',$message);
$s->send_long($src, $dst, $message, $utf);
*/
/*
$s->close();
*/