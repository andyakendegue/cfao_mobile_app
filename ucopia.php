<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 11/04/2017
 * Time: 15:16
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,Access-Control-Allow-Origin,Access-Control-Allow-Credentials,Access-Control-Max-Age,Access-Control-Allow-Methods");




// get posted data
//$data = json_decode(file_get_contents("php://input"));
$action = htmlspecialchars(strip_tags($_GET['action']));
$deleg_id = "andy";
$deleg_pwd = "andy";



// On teste la variable $action pour savoir quel type d'actio initier

if ($action == "adduser") {


    $phoneNumber = htmlspecialchars(strip_tags($_GET['phoneNumber']));
    $mdp = htmlspecialchars(strip_tags($_GET['mdp']));
    $nom = htmlspecialchars(strip_tags($_GET['nom']));
    $prenom = htmlspecialchars(strip_tags($_GET['prenom']));
    $email = htmlspecialchars(strip_tags($_GET['email']));



    $url = 'http://192.168.104.2/deleg/api_admin_deleg.php?deleg_id='.$deleg_id.'&deleg_pwd='.$deleg_pwd.'&action='.$action.'&user_id='.$phoneNumber.'&user_pwd='.$mdp.'&user_grp=test&user_lastname='.$nom.'&user_firstname='.$prenom.'&user_email='.$email.'&user_phonenumber='.$phoneNumber;



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $result = curl_exec($ch);
    $var = json_decode($result, true);
    //return $var;
header('Content-type: text/json');
    print_r($result);


}

// Récupération des options de rechargement
else if ($action == "get_refill_options") {

    $url = 'http://192.168.104.2/deleg/api_admin_deleg.php?deleg_id='.$deleg_id.'&deleg_pwd='.$deleg_pwd.'&action='.$action.'&reply_format=json';


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $result = curl_exec($ch);
    $var = json_decode($result, true);
    //return $var;

    header('Content-type: text/json');
    print_r($result);
}

// Obtention d'un code de rechargement
else if ($action == "get_refill_code") {


    $forfait = htmlspecialchars(strip_tags($_GET['forfait']));

    $url = 'http://192.168.104.2/deleg/api_admin_deleg.php?deleg_id='.$deleg_id.'&deleg_pwd='.$deleg_pwd.'&action='.$action.'&option_id='.$forfait.'&reply_format=json';


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $result = curl_exec($ch);
    $var = json_decode($result, true);
    //return $var;

    header('Content-type: text/json');
    print_r($result);

}

// Rechargement du compte de l'utilisateur
else if ($action == "set_refill_code_info") {

    $phoneNumber = htmlspecialchars(strip_tags($_GET['phoneNumber']));
    $refill_code = htmlspecialchars(strip_tags($_GET['refill_code']));

    $url = 'http://192.168.104.2/deleg/api_admin_deleg.php?deleg_id='.$deleg_id.'&deleg_pwd='.$deleg_pwd.'&action='.$action.'&refill_code='.$refill_code.'&user_id='.$phoneNumber;



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $result = curl_exec($ch);
    $var = json_decode($result, true);
    //return $var;

    header('Content-type: text/json');
    print_r($result);

} else {

    echo 'L\'action initiée n\'est pas reconnue';

}





