<?php
/**
 * Created by PhpStorm.
 * User: Capp
 * Date: 16/02/2017
 * Time: 14:30
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);


// model
class Book
{
    public $name;
    public $year;
}

// create instance and set a book name
$book      =new Book();
$book->name='test 2';

// initialize SOAP client and call web service function
$client=new SoapClient('http://144.217.80.224/service/soap/server.php?wsdl',['trace'=>1,'cache_wsdl'=>WSDL_CACHE_NONE]);
$resp  =$client->bookYear($book);

// dump response
var_dump($resp);