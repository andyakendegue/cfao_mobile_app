<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 23/02/2017
 * Time: 15:10
 */
$jour = date('Y-m-d');
$heure = date('H:i:s.U');
$time_stamp = $jour.'T'.$heure.'Z';

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
            <ns14:mobileNumber xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">24103454400</ns14:mobileNumber>
            <ns14:subscriptionType xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:subscriptionType>
            <ns14:state xmlns:ns14="http://soap.crmapi.util.redknee.com/subscriptions/xsd/2009/04">1</ns14:state>
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

print_r($time_stamp);