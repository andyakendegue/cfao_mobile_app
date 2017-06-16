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

// Vérifie que le nom d'utilisateur a été renseigné
if (isset($_POST["username"])) {

//$ok ="cfao";
//if ($ok) {


	//Vérifie que le mot de passe a été renseigné

	if(isset($_POST["mdp"])) {

	//if($ok) {

	$username = $_POST["username"];
	$mdp = $_POST["mdp"];

	$url = 'https://ilink-app.com/api/api.php/users?filter=username,eq,'.$username;
	$response = file_get_contents($url);

	$response_encode = json_decode($response, true);

	$user_mdp = $response_encode["users"]["records"][0][3];

		// Verifie que les variables sont remplies
		if(!empty($user_mdp) && !empty($mdp)) {

			// Compare les variables
			if($user_mdp == $mdp) {

			$token=uniqid('', true);

			
			$postarrayString = array(
			
			'token' => $token,
			'client' => $username,
			'date' => date("Y-m-d H:m:s"));

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
			
			$result = file_get_contents('https://ilink-app.com/api/api.php/connections', false, $context);

			$arr = array(
			
			'status' => "succès", 
			'token' => $token,
				'error_code' => 0);
			echo json_encode($arr);


			
			} 
			// Les variables ont des données différentes
			else {

			$arr = array(

			'status' => "An error occured, wrong password", 
			'token' => 0, 
			'error_code' => 1);

			echo json_encode($arr);


			}


		}
		// Les variables ne sont pas bien remplies
		 else {

		$arr = array(
		'status' => "An error occured, password doesn't have values", 
		'token' => 0, 
		'error_code' => 1);

		echo json_encode($arr);


		}

	//echo $user_mdp;
	


	} 
	// Le mot de passe n'a pas été renseigné
	else {

	$arr = array(
	'status' => "password not set", 
	'token' => 0, 
	'error_code' => 1);

	echo json_encode($arr);



	}



} 
// Le nom d'utilisateur n'a pas été renseigné
else {

$arr = array(
'status' => "username not set", 
'token' => 0, 
'error_code' => 1);

echo json_encode($arr);


}


?>
