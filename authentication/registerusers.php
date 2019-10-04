<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../AbstractClass/MYSQL.php';

class registerusers extends MYSQL
{
    public function __construct()
    {
        if(empty($_SESSION['USERID'])&&!isset($_SESSION['USERID'])) {
           ECHO $_SESSION['USERID'] = rand(1, 10000);
        }
    }

    public function RegisterData($json_obj){
        if(isset($json_obj->email)&&!empty($json_obj->email)&&isset($json_obj->password)&&!empty($json_obj->password)){
          $hashed_password = password_hash( $json_obj->password, PASSWORD_DEFAULT );
            $pdo=$this->DbConnectAuthencation();
            $data = [
                'email' => $json_obj->email,
                'password_hash' => $hashed_password,
            ];
            $sql = "INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)";
            $stmt= $pdo->prepare($sql);
            $stmt->execute($data);
        /*   echo $token = bin2hex(openssl_random_pseudo_bytes(64));*/
        }
    }
}
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$a= new registerusers();
$a->RegisterData($json_obj);
