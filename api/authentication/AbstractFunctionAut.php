<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../AbstractClass/MYSQL.php';
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use \Firebase\JWT\JWT;

abstract class AbstractFunctionAut extends MYSQL
{
    private $user;
    private $password;
    protected $pdo;
    private $lastid;

    public function check($obj)
    {
        if (!empty($obj) || !empty($obj->email) && !empty($obj->password)) {
            $this->pdo = $this->DbConnectAuthencation();
            $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email=?");
            $stmt->execute(array($obj->email));
            $userid = $stmt->fetchColumn();
            if (!empty($userid)) {
                $_SESSION['USERID']=$userid;
                $this->CheckEmailPassword($obj);
            } else {
                $this->CheckEmailPassword($obj);
            }
        } else {
            /*            $_SESSION['USERID'] = rand(1, 10000);
                        $this->CheckEmailPassword($obj);*/

            http_response_code(401);
        }
    }

    public function CheckEmailPassword($json_obj)
    {
        if (isset($json_obj->email) && !empty($json_obj->email) && isset($json_obj->password) && !empty($json_obj->password)) {
            $this->pdo = $this->DbConnectAuthencation();
            $stmt = $this->pdo->prepare("SELECT email,password_hash,user_id FROM users WHERE email=?");
            $stmt->execute(array($json_obj->email));
            $DATE = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (!empty($DATE)) {
                foreach ($DATE as $emailandpass) {
                    $password = password_verify($json_obj->password, $emailandpass->password_hash);
                    if ($json_obj->email === $emailandpass->email && $password === true) {
                        /*$token = bin2hex(openssl_random_pseudo_bytes(64));*/
                        if ($emailandpass->user_id === null) {
                            $guild = $this->guid();
                            $sql = "INSERT INTO refresh_tokens (id,token_key) VALUES (?,?)";
                            $stmt = $this->pdo->prepare($sql);
                            $stmt->execute([$_SESSION['USERID'], $guild]);
                            $sql = "update users set user_id=? where email =?";
                            $stmt = $this->pdo->prepare($sql);
                            $stmt->execute([$_SESSION['USERID'], $json_obj->email]);
                        }
                        $jwt = $this->CreateTokenKey($guild, $json_obj);
                        if (!empty($jwt)) {
                            $output = array(
                                'access_token' => $jwt,
                                'userGlobalKey' =>(string)$_SESSION['USERID'],
                            );
                            echo json_encode($output);
                        } else {
                            header('http/1.0 401 Unauthorized');
                        }
                    } else {
                        header('http/1.0 401 Unauthorized');
                    }
                }
            }else{
                header('http/1.0 403 Forbidden');
            }
        }
    }

    protected function CreateTokenKey($guild, $json_obj)
    {
        $stmt = $this->pdo->prepare("SELECT token_key FROM users u
                                      join refresh_tokens r on r.id = u.user_id
                                      where email=?"
        );
        $stmt->execute(array($json_obj->email));
        $keymysql = $stmt->fetchColumn();
        if (!empty($keymysql)) {
            return $this->token($keymysql);
        } else {
            return $this->token($guild);
        }
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

    protected function token($key)
    {

        $time = strtotime('+1 hour', time());
        $token = array(
            "iss" => $_SERVER['SERVER_NAME'],
            "ext" => $time,
            "id " => $_SESSION['USERID'],
        );
        JWT::$leeway = 3600; // $leeway in seconds
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

}



