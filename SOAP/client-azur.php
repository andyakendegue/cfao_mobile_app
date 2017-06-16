<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 16/02/2017
 * Time: 14:30
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
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

$username = 'labtest';
$password = 'labtest';
$jour = date('Y-m-d');
$heure = date('H:i:s.U');
$time_stamp = $jour.'T'.$heure.'Z';

$amount = $_GET['montant']*100;
$phone = $_GET['phone'];
$debit = '25000';
$credit = '21000';
if($_GET['transaction']=='debit') {
    $transaction = $debit;
} else if ($_GET['transaction']=='credit'){
    $transaction = $credit;
}





$xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header>
      <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
         <wsu:Timestamp wsu:Id="TS-12">
            <wsu:Created>$time_stamp</wsu:Created>
           </wsu:Timestamp>
         <wsse:UsernameToken wsu:Id="UsernameToken-11">
            <wsse:Username>$username</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">$password</wsse:Password>
            <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">WBsAFJ1cVnowrJf5xkB+0A==</wsse:Nonce>
            <wsu:Created>$time_stamp</wsu:Created>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <ns17:createSubscriptionTransaction xmlns:ns17="http://soap.crmapi.util.redknee.com/transactions/xsd/Transactions-v1.2">
         <ns17:header>
            <ns3:password xmlns:ns3="http://soap.crmapi.util.redknee.com/common/xsd/2008/08">$username</ns3:password>
            <ns3:username xmlns:ns3="http://soap.crmapi.util.redknee.com/common/xsd/2008/08">$password</ns3:username>
         </ns17:header>
         <ns17:subscriptionRef>
            <ns14:mobileNumber xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">241$phone</ns14:mobileNumber>
            <ns14:subscriptionType xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:subscriptionType>
            <ns14:state xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:state>
         </ns17:subscriptionRef>
         <ns17:request>
            <ns6:adjustmentType xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">$transaction</ns6:adjustmentType>
            <ns6:amount xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">$amount</ns6:amount>
            <ns6:subscriptionType xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">1</ns6:subscriptionType>
         </ns17:request>
      </ns17:createSubscriptionTransaction>
   </soapenv:Body>
</soapenv:Envelope>
XML;

class MySoapClient extends SoapClient {

    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);
        $this->server = new SoapServer($wsdl, $options);
    }
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $result = parent::__doRequest($request, $location, $action, $version, $one_way = 0);
        return $result;
    }
    function __myDoRequest($call, $params) {
        $location = 'http://192.168.2.41:11648/RedkneeSoap_v2_3/services/TransactionService?wsdl';
        $action = 'http://192.168.2.41:11648/RedkneeSoap_v2_3/services/TransactionService?wsdl'.$call;
        $request = $params;
        $result =$this->__doRequest($request, $location, $action, '1');
        return $result;
    }
}

$wsdl = 'http://192.168.2.41:11648/RedkneeSoap_v2_3/services/TransactionService?wsdl';
$client = new MySoapClient($wsdl, array(
    'cache_wsdl'    => WSDL_CACHE_NONE,
    'cache_ttl'     => 86400,
    'trace'         => true,
    'exceptions'    => true,
));

// Make the request
try {
    $request = $client->__myDoRequest('getTransaction', $xml);
} catch (SoapFault $e ){
    echo "Last request :<pre>" . htmlentities($client->__getLastRequest()) . "</pre>";
    exit();
}





//header('Content-type: text/json');
//var_dump($request);

$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $request);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArray = json_decode($json,true);

echo $json;