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

    const userglobalkey = '25634567834DF97345345BNRGH8345235NHWRT8564XZX8DS8234KGDAFGARE89JYTJETBFSGBS';



    private $user;
    private $password;
    protected $pdo;
    private $guild;
    private $lastid;

    public function check($obj)
    {
        if (!empty($obj) || !empty($obj->email) && !empty($obj->password)) {
            try{
            $this->pdo = $this->DbConnectAuthencation();
            $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email=?");
            $stmt->execute(array($obj->email));
                if( !$stmt ){
                    throw new Exception('Query Failed');
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
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
            try{
            $stmt = $this->pdo->prepare("SELECT email,password_hash,user_id FROM users WHERE email=?");
            $stmt->execute(array($json_obj->email));
                if( !$stmt ){
                    throw new Exception('Query Failed');
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
            $DATE = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (!empty($DATE)) {
                foreach ($DATE as $emailandpass) {
                    $password = password_verify($json_obj->password, $emailandpass->password_hash);
                    if ($json_obj->email === $emailandpass->email && $password === true) {
                        /*$token = bin2hex(openssl_random_pseudo_bytes(64));*/
                        if (empty($emailandpass->user_id)) {
                            $this->guild = $this->guid();
                            try{
                            $sql = "INSERT INTO refresh_tokens (id,token_key) VALUES (?,?)";
                            $stmt = $this->pdo->prepare($sql);
                            $stmt->execute([$_SESSION['USERID'], $this->guild]);
                                if( !$stmt ){
                                    throw new Exception('Query Failed');
                                }
                            }
                            catch(Exception $e){
                                echo $e->getMessage();
                            }
                            try{
                            $sql = "update users set user_id=? where email =?";
                            $stmt = $this->pdo->prepare($sql);
                            $stmt->execute([$_SESSION['USERID'], $json_obj->email]);
                                if( !$stmt ){
                                    throw new Exception('Query Failed');
                                }
                            }
                            catch(Exception $e){
                                echo $e->getMessage();
                            }

                        }
                            $jwtRefresh = $this->CreateTokenKeyRefresh($this->guild, $json_obj);
                            $jwtAccess = $this->CreateTokenKeyAcess();
                            if (!empty($jwtRefresh)) {
                                $output = array(
                                    'accessToken' => $jwtAccess,
                                    'refreshToken' => $jwtRefresh,
                                    'userGlobalKey' => (string)$_SESSION['USERID'],
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

    protected function CreateTokenKeyRefresh($guild, $json_obj)
    {
        $this->pdo = $this->DbConnectAuthencation();
        $keymysql = null;
        if(isset($json_obj->email)) {
            $stmt = $this->pdo->prepare("SELECT token_key FROM users u
                                      join refresh_tokens r on r.id = u.user_id
                                      where email=?"
            );
            $stmt->execute(array($json_obj->email));
            $keymysql = $stmt->fetchColumn();
        }else if (isset($json_obj->id)) {
            try {
                $sql = "update refresh_tokens set token_key=? where id =?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($guild, $json_obj->id));
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        if (!empty($keymysql)) {
            return $this->refreshToken($keymysql);
        } else {
            return $this->refreshToken($guild);
        }
    }
     protected function CreateTokenKeyAcess()
     {
         return $this->token(self::userglobalkey);
     }

    protected function guid()
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
        $time = strtotime('+1 min', time());
        $token = array(
            "iss" => $_SERVER['SERVER_NAME'],
            "ext" => $time,
            "id" => $_SESSION['USERID'],
        );
        JWT::$leeway = 3600; // $leeway in seconds
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }
    protected function refreshToken($key){
        $time = strtotime('+24 hour', time());
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



