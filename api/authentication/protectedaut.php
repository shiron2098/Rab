<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/AbstractFunctionAut.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class protectedaut extends AbstractFunctionAut
{
    private $secret_key;
    public $pdo;
    private $jwt = null;

    public function selectkey($auttoken)
    {
        $_SESSION['AUT'] = false;
        $data = $this->tokenkey();
        if ($data === true) {
            $this->checktoken($auttoken);
        }
    }

    private function tokenkey()
    {
        $this->pdo = $this->DbConnectAuthencation();
        $stmt = $this->pdo->prepare("SELECT token_key FROM users u
                                      join refresh_tokens r on r.id = u.user_id
                                      where user_id=?"
        );
        $stmt->execute(array($_SESSION['USERID']));
        $this->secret_key = $stmt->fetchColumn();
        if (!empty($this->secret_key)) {
            return true;
        } else {
            http_response_code(401);
        }
    }

    private function checktoken($token)
    {


        $arr = explode(" ", $token);
        $jwt = $arr[1];
        try {
            $decoded = JWT::decode($jwt, $this->secret_key, array('HS256'));
            if ($decoded->ext > time()) {
                $_SESSION['AUT'] = true;
            } else {
                $_SESSION['AUT'] = false;
                http_response_code(401);
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