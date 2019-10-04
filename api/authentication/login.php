<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/AbstractFunctionAut.php';
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use \Firebase\JWT\JWT;

class login extends AbstractFunctionAut
{

    public function start($obj)
    {
        $this->check($obj);
    }
}

$arr =(object)['email' =>'repik@mail.ru','password'=>'q1w2e3'];
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$_SESSION['AUT'] = false;
$a= new login();
$a->check($json_obj);