<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../AbstractClass/MYSQL.php';

use \Firebase\JWT\JWT;

class Login extends MYSQL
{
    private $user;
    private $password;
    private $pdo;

    public function __construct()
    {
        if (empty($_SESSION['USERID']) && !isset($_SESSION['USERID'])) {
            $_SESSION['USERID'] = rand(1, 10000);
        }
    }

    public function CheckEmailPassword($json_obj)
    {
        if (isset($json_obj->email) && !empty($json_obj->email) && isset($json_obj->password) && !empty($json_obj->password)) {
            $this->pdo = $this->DbConnectAuthencation();
            $stmt = $this->pdo->prepare("SELECT email,password_hash,user_id FROM users WHERE email=?");
            $stmt->execute(array('repik@mail.ru'));
            $DATE = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($DATE as $emailandpass) {
                $password = password_verify($json_obj->password, $emailandpass->password_hash);
                if ($json_obj->email === $emailandpass->email && $password === true) {
                    /*$token = bin2hex(openssl_random_pseudo_bytes(64));*/
                    if($emailandpass->user_id === null) {
                    $guild = $this->guid();
                    $sql = "INSERT INTO refresh_tokens (token_key) VALUES (?)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$guild]);
                    $last = $this->pdo->lastInsertId();
                    $sql = "update users set user_id=? where email =?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$last, $json_obj->email]);
                    }
                    $jwt = $this->CheckTokenKey($guild);
                    if(!empty($jwt)){
                        $output = array(
                            'access_token' => $jwt,
                        );
                        echo json_encode($output);
                    }else{
                        header('http/1.0 401 Unauthorized');
                    }
                } else {
                    header('http/1.0 401 Unauthorized');
                }
            }
        }
    }
    private function CheckTokenKey($guild){
        $key = $guild;
        $time = strtotime('+1 hour',time());
        $token = array(
            "iss" => $_SERVER['SERVER_NAME'],
            "ext" => strtotime($time)
        );
/*                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $decoded_array = (array) $decoded;*/
                JWT::$leeway = 3600; // $leeway in seconds
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }
    private function guid()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}

/*$token = array(
    "iss" => $_SERVER['SERVER_NAME'],
    "ext" => strtotime('+1 hour',time())
);
$jwt = JWT::encode($token, '78c125d6-5466-4f2b-b753-637d27760230');
$decoded = JWT::decode($jwt, '78c125d6-5466-4f2b-b753-637d27760230', array('HS256'));
$decoded_array = (array) $decoded;
JWT::$leeway = 3600; // $leeway in seconds
print_r($decoded);
exit();*/


$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$a= new Login();
$a->CheckEmailPassword($json_obj);