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
if(isset($_POST['phone']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email'])) {





    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];


    

// Vérfie si le phone existe

    $url = 'http://144.217.80.224/service/api/api.php/users_ucopia?filter=phone,eq,'.$phone;
    $response = file_get_contents($url);

    $response_encode = json_decode($response, true);


    $phone_remote = $response_encode["users_ucopia"]["records"][0][3];


    // Vérifie que le phone n'existe pas
    if ($phone_remote) {

        $arr = array(
            'status' => "Le phone existe",
            'error_code' => 1);

        echo json_encode($arr);




    }

    // Le token ne correspond pas
    else {
        $postarrayString = array(
            'name' => $name,
            'lastname' => $lastname,
            'phone' => $phone,
            'email' => $email,
            'password' => $phone+$lastname,
            'created_at' => date("Y-m-d H:m:s"));

        $postString = json_encode($postarrayString);




        # Form our options
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postString
            )
        );
        # Create the context
        $context = stream_context_create($opts);
        # Get the response (you can use this for GET)
        $result = file_get_contents('http://144.217.80.224/service/api/api.php/users_ucopia', false, $context);

        $arr = array(
            'status' => $result,
            'message' => "transaction effectuée avec succès",
            'error_code' => 0);
        echo json_encode($arr);


    }


}

// le phone n'a pas été renseigné

else {

    $arr = array(
        'status' => "un ou plusieurs champs sont vides".$_POST['phone'].$_POST['name'].$_POST['lastname'].$_POST['email'],
        'error_code' => 1);

echo json_encode($arr);



}

?>
