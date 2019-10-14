<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_Collection.php';


class routes extends MYSQL_t2s_bi_Collection
{
    public function AUT()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if (!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $this->start();
            } else {
                http_response_code(403);
            }
        } else {
            http_response_code(401);
        }
    }

    public function start()
    {
        $this->connectT2S_dashboard();
        $result = mysqli_query(
            MYSQLConnect::$linkConnectT2S,
            "select rte_code,rte_description
                      from routes"
        );
        if($result == true) {
            foreach($result as $row){
                $array [] = $row;
            }
            echo json_encode($array);
        }else {
            http_response_code(501);
        }

    }

}
$start = new routes();
$start->AUT();