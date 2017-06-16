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

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

//Verifie que le token est renseigné
if(isset($_POST['token'])) {

//$ok = "ok";
//if ($ok) {

$token = $_POST['token'];

$webcode = $_POST['webcode'];



// Vérfie si le token existe

	$url = 'https://ilink-app.com/api/api.php/connections?filter=token,eq,'.$token;
	$response = file_get_contents($url);

	$response_encode = json_decode($response, true);

	
	$token_remote = $response_encode["connections"]["records"][0][1];
	$client = $response_encode["connections"]["records"][0][2];

	
	// Vérifie que le token est bon
	if ($token == $token_remote) {

	

	$url = 'https://ilink-app.com/api/api.php/webcode?filter=webcode,eq,'.$webcode;
	$response = file_get_contents($url);

	$response_encode = json_decode($response, true);

	
	$webcode_remote = $response_encode["webcode"]["records"][0][1];

		// Vérifie que le phone est le même
		if ($webcode == $webcode_remote) {

			$arr = array( 
			'status' => "transaction effectuée avec succès",
			'error_code' => 0);
			echo json_encode($arr);



		}

		// Le phone ne correspond pas

		else {
		$arr = array(
		'status' => "webcode invalide",
		'error_code' => 1);

		echo json_encode($arr);
		//echo $response_encode["connections"]["records"][0][2];

		}

	}

	// Le token ne correspond pas
	else {

	$arr = array(
	'status' => "token invalide",
	'error_code' => 1);

	echo json_encode($arr);
	}


}

// le token n'a pas été renseigné

else {

$arr = array(
'status' => "token not set",
'error_code' => 1);

echo json_encode($arr);

}

?>
