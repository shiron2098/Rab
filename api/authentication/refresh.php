<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/AbstractFunctionAut.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class refresh extends AbstractFunctionAut
{
    public function fieldToken($jsonObj)
    {
        if (!empty($jsonObj->refreshToken) && isset($jsonObj->refreshToken)) {
           $acess = $jsonObj->accessToken;
            $decodedacess = JWT::decode($acess, self::userglobalkey, array('HS256'));
            $key = $this->tokenKey($decodedacess->id);
            /*            $arr = explode(" ", $jsonObj->refreshToken);
                        $jwt = $arr[1];*/
            try {
                $decoded = JWT::decode($jsonObj->refreshToken, $key, array('HS256'));
                if ($decoded->ext > time()) {
                    $newAccess = $this->CreateTokenKeyAcess();
                    $newRefresh = $this->CreateTokenKeyRefresh($this->guid(),$decodedacess);
                    if (!empty($newRefresh)&&!empty($newAccess)) {
                        $output = array(
                            'accessToken' => $newAccess,
                            'refreshToken' => $newRefresh,
                            'userGlobalKey' => (string)$decodedacess->id,
                        );
                        echo json_encode($output);
                    }
                }else {
                    http_response_code(403);
                }
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                ));
            }
        }
    }
        public function tokenKey($id)
        {
            $pdo = $this->DbConnectAuthencation();
            try {
                $stmt = $pdo->prepare("SELECT token_key FROM users u
                                      join refresh_tokens r on r.id = u.user_id
                                      where user_id=?"
                );
                $stmt->execute(array($id));
                return $keymysql = $stmt->fetchColumn();

            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
}

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$a= new refresh();
$a->fieldToken($json_obj);