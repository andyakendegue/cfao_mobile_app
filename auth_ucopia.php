<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 22/12/2016
 * Time: 11:09
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

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

//Verifie que le phone est renseigné
if(isset($_POST['phone']) && isset($_POST['mdp'])) {





    $phone = $_POST['phone'];
    $mdp = $_POST['mdp'];


    /*
    $token = "58413954963d60.81830334";
    $phone = "04645969";
    $mdp = "capp";
    $produit = "forfait";
    $montant = "1000";
    $user = "cfao";
    */

// Vérfie si le phone existe

    $url = 'http://144.217.80.224/service/api/api.php/users_ucopia?filter=phone,eq,'.$phone;
    $response = file_get_contents($url);

    $response_encode = json_decode($response, true);


    $phone_remote = $response_encode["users_ucopia"]["records"][0][3];
    $mdp_remote = $response_encode["users_ucopia"]["records"][0][5];


    // Vérifie que le phone n'existe pas
    if ($phone_remote) {


            if($mdp === $mdp_remote) {

                $arr = array(

                    'message' => "transaction effectuée avec succès",
                    'error_code' => 0);
                echo json_encode($arr);


            } else {

                $arr = array(
                    'status' => $result,
                    'message' => "le mot de passe ne correspond pas",
                    'error_code' => 1);
                echo json_encode($arr);

            }




    }

    //
    else {

        $arr = array(
            'status' => "Le phone n'existe pass",
            'error_code' => 1);

        echo json_encode($arr);

    }


}

// le phone n'a pas été renseigné

else {

    $arr = array(
        'status' => "un ou plusieurs champs sont vides",
        'error_code' => 1);

echo json_encode($arr);



}

?>
