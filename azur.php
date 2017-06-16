<?php

/**
 *
 * Script créé par Mba Quentin pour le webservice paiement mobile mis en place par CFAO
 * Modifié par Capp Andy AKENDENGUE
 *
 */


/* Ajout de Capp Andy AKENDENGUE */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérification de la présence du tag lors de l'envoi
if(isset($_GET['tag'])) {


    // Intialisation des constantes identifants



    define('USERNAME',     'labtest');
    define('SECRET',     'labtest');




    /*
        define("USERNAME",     "bgfidev");
        define("SECRET",     "c8365636-4fd2-11e6-8994-fc15b48e1b50");
    */


    // Initialisation des liens


    $url1 = "http://192.168.2.41:11648/RedkneeSoap_v2_3/services/TransactionService?wsdl";


    /*
    $url1 = "http://89.30.96.178:9050/authentication/sign";
    $url2 = "http://89.30.96.178:9050/payment/prepare";
    $url3 = "http://89.30.96.178:9050/payment/conclude";
    */
    // Enregistrement du tag dans une variable
    $tag = $_GET["tag"];





    // Initialisation de la transaction et récupération du token
    if ($tag == 'init'){
        // Engistrement des informations envoyées par l'utilisateur


        //$phone = $_GET["phone"];
        //$montant = intval($_GET["montant"]);

        // Création d'un tableau à envoyer

        $array = array(

            'Username' => USERNAME,
            'Password' => SECRET);

        $result = sendData1($url1, $array);






        if(isset($result)) {


            // Préparation du payement

            $parse_result1 = json_decode($result, true);



            print_r("ok".$parse_result1);
            var_dump($parse_result1);


        } else {

            $arr = array(

                'message' => "Une erreur s'est produite !",
                'error_code' => 1);
            echo json_encode($arr);

        }



    }


    // Payement du forfait
    else if ($tag == "payment") {

        $token = $_GET['token'];
        $phone = $_GET['phone'];
        $montant = $_GET['montant'];
        $reference = $_GET['reference'];
        $webcode = $_GET['webcode'];

        $array3 = array(

            'reference' => $reference,
            'paymentcode' => $webcode);

        $result3 = sendData3($url3, $array3, $token);

        if (isset($result3)) {

            if (isset($result3['codetransaction'])) {


                // Enregistrement du client
                $postarrayClient = array(
                    'identifiant' => $phone);

                $postClient = json_encode($postarrayClient);

                # Form our options
                $optsClient = array('http' =>
                    array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postClient
                    )
                );
                # Create the context
                $context = stream_context_create($optsClient);
                $resultClient = file_get_contents('http://144.217.80.224/service/api/api.php/client', false, $context);

                $arrClient = array(
                    //'status' => "transaction effectuée avec succès",
                    'message' => $resultClient,
                    'error_code' => 0);
                echo json_encode($arrClient);




                // Enregistrement de la transaction dans la base de données`

                $url = 'http://144.217.80.224/service/api/api.php/client?filter=identifiant,eq,'.$phone;
                $response = file_get_contents($url);

                $response_encode = json_decode($response, true);


                $id_client = $response_encode["client"]["records"][0][0];

                $postarrayTransac = array(
                    'idClient' => $id_client,
                    'idPorte' => "3",
                    'dateTransac' => date("Y-m-d H:m:s"),
                    'montant' => $montant,
                    'token' => $token );

                $postTransac = json_encode($postarrayTransac);




                # Form our options
                $optsTransac = array('http' =>
                    array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postTransac
                    )
                );
                # Create the context
                $context = stream_context_create($optsTransac);
                $result = file_get_contents('http://144.217.80.224/service/api/api.php/transaction', false, $context);

                $arr = array(
                    //'status' => "transaction effectuée avec succès",
                    'message' => $result,
                    'error_code' => 0);
                echo json_encode($arr);


                print_r ($result3);
            } else {
                $arr = array(

                    'message' => $result3['message'],
                    'error_code' => 1);
                echo json_encode($arr);

            }

        } else {
            $arr = array(

                'message' => "Une erreur s'est produite!",
                'error_code' => 1);
            echo json_encode($arr);

        }

    }
    // Le tag envoyé par l'utilisateur n'est pas reconnu
    else {


        $arr = array(

            'message' => "Opération inconnue !",
            'error_code' => 1);
        echo json_encode($arr);

    }







}

// Aucun tag n'a été envoyé par l'interface utilisateur
else {

    $arr = array(

        'message' => "Aucune action n'a été initiée !",
        'error_code' => 1);
    echo json_encode($arr);


}

/* Créé par Quentin Mba */

function sendData1($url1, $data1)
{

    /* Modify By Capp */
    $content = json_encode($data1);
    //$headers = array('Content-Type: application/json', 'Content-Length:'.strlen($content));

    /* Modify By Capp */

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /* Added by Capp */
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    /* Added by Capp */
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $content);



    $result = curl_exec($ch);
    // Will dump a beauty json :3

    $var = json_decode($result, true);
    if ($result === FALSE) {
        echo 'Curl failed: ' . curl_error($ch);
        return FALSE;
    }
    curl_close($ch);

    return $result;
    //return $var;
}



function sendData2($url2, $data2,$token)
{

    $content = json_encode($data2);
    $headers = array('Accept: application/json','Content-Type: application/json','Accept-Encoding: gzip, deflate','Authorization: '.$token,'Proxy-Connection: keep-alive',);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url2);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    /* Added by Capp */
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    /* Added by Capp */
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

    $result = curl_exec($ch);
    // Will dump a beauty json :3

    $var = json_decode($result, true);
    if ($result === FALSE) {
        echo 'Curl failed: ' . curl_error($ch);
        return FALSE;
    }
    curl_close($ch);
    return $var;
}

function sendData3($url3, $data3, $token)
{

    $content = json_encode($data3);
    $headers = array('Accept: application/json','Content-Type: application/json','Accept-Encoding: gzip, deflate','Authorization: '.$token,'Proxy-Connection: keep-alive',);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url3);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    /* Added by Capp */
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    /* Added by Capp */
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

    $result = curl_exec($ch);
    // Will dump a beauty json :3
    $var = json_decode($result, true);
    if ($result === FALSE) {
        echo 'Curl failed: ' . curl_error($ch);
        return FALSE;
    }
    curl_close($ch);
    return $var;
}

function init () {

}

?>