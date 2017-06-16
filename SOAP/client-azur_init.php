<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 16/02/2017
 * Time: 14:30
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = 'labtest';
$password = 'labtest';

$xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header>
      <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
         <wsu:Timestamp wsu:Id="TS-12">
            <wsu:Created>date("M d Y H:i:s", mktime(0, 0, 0, 1, 1, 1998))</wsu:Created>
           </wsu:Timestamp>
         <wsse:UsernameToken wsu:Id="UsernameToken-11">
            <wsse:Username>$username</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">$password</wsse:Password>
            <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">WBsAFJ1cVnowrJf5xkB+0A==</wsse:Nonce>
            <wsu:Created>date("M d Y H:i:s", mktime(0, 0, 0, 1, 1, 1998))</wsu:Created>
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
            <ns14:accountID xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1500452</ns14:accountID>
            <ns14:mobileNumber xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">24103001011</ns14:mobileNumber>
            <ns14:spid xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:spid>
            <ns14:subscriptionType xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:subscriptionType>
            <ns14:state xmlns:ns14="http:/ /soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:state>
         </ns17:subscriptionRef>
         <ns17:request>
            <ns6:adjustmentType xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">25000</ns6:adjustmentType>
            <ns6:amount xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">1000000</ns6:amount>
            <ns6:subscriptionType xmlns:ns6="http://soap.crmapi.util.redknee.com/transactions/xsd/2010/11">1</ns6:subscriptionType>
         </ns17:request>
      </ns17:createSubscriptionTransaction>
   </soapenv:Body>
</soapenv:Envelope>
XML;
/*
// create instance and set a book name
$book      =new Book();
$book->name='test 2';

// initialize SOAP client and call web service function
$client=new SoapClient('http://192.168.2.41:11648/RedkneeSoap_v2_3/services/TransactionService?wsdl',['trace'=>1,'cache_wsdl'=>WSDL_CACHE_NONE]);
$resp  =$client->createSubscriptionTransaction($book);

// dump response
var_dump($resp);
*/
class MySoapClient extends SoapClient {

    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);
        $this->server = new SoapServer($wsdl, $options);
    }
    public function __doRequest($request, $location, $action, $version)
    {
        $result = parent::__doRequest($request, $location, $action, $version);
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
    echo "Last request:<pre>" . htmlentities($client->__getLastRequest()) . "</pre>";
    exit();
}

//header('Content-type: text/xml');

$xml = simplexml_load_string($request, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
$ns = $xml->getNamespaces(true);
$soap = $xml->children($ns['env']);
$res = $soap->Body->children($ns['ns2']);

foreach ($res->LookupResponse as $item) {
    echo $item->Name.PHP_EOL;
}


print_r($soapResponse);
