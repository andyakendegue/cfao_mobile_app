<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 23/12/2016
 * Time: 14:39
 */

/*
$url = 'http://144.217.80.224/service/api/api.php/users_ucopia?filter=phone';

$response = file_get_contents($url);

$response_encode = json_decode($response, true);

//echo $response_encode["users_ucopia"]["records"][0][1];

echo $response_encode["users_ucopia"]["records"][0][3];
*/
$string = $_GET['number'];
$arr1 = str_split($string);
$arr2 = str_split($string, 3);

if($arr1[3]=="0"){
    $bon_num = $string;
} else {
    $bon_num = "2410".$arr1[3].$arr1[4].$arr1[5].$arr1[6].$arr1[7].$arr1[8].$arr1[9];
}

echo $bon_num;