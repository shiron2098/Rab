<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../AbstractClass/MYSQL_t2s_bi_calendar.php';


class distribution extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';

    private $upordown;
    private $interval;
    private $int;


    public function AUT(){
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if($_SESSION['AUT'] === true){
            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str);
            $this->start($json_obj);
        }else
        {
            http_response_code(403);
        }
    }

    public function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_collection_distribution($timemysql);
            $this->upordown = $this->daily_distribution_AVG($timemysql, $timemysqlfinishavg);
            $this->daily_distribution_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'salesDistributionCollection' => $this->upordown
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'salesDistributionCollection' => array()
                );
                echo json_encode($output);
            }
        }
    }
    private function Months($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_collection_distribution($timemysql);
            $this->upordown = $this->daily_distribution_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'salesDistributionCollection' => $this->upordown
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'salesDistributionCollection' => array()
                );
                echo json_encode($output);
            }
        }
    }
    public function start()
    {
        if (isset($_GET['trendIntervalComparer']) && !EMPTY($_GET['trendIntervalComparer']) && isset($_GET['date']) && !empty($_GET['date'])) {
            $this->interval = $_GET['trendIntervalComparer'];
            switch ($this->interval) {
                case '45':
                    $this->Week($_GET['date'],$_GET['trendIntervalComparer']);
                    break;
                case '180':
                    $this->Months($_GET['date'],$_GET['trendIntervalComparer']);
                    break;
            }
        }
    }
}
$start = new distribution();
$start->AUT();