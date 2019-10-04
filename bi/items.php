<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class items extends MYSQL_t2s_bi_calendar
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

    private function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->daily_items_AVG($timemysql,$timemysqlfinishavg);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $trashhold =  $this->daily_items_CALENDAR($date);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (string) $data['not_picked'],
                    'percentOfProducts'=> (string) $data['after_not_picked'],
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'numberOfProducts' => (string) $data['not_picked'],
                    'percentOfProducts'=> (string) $data['after_not_picked'],
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            }
        }
    }
    public function Months($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->daily_items_AVG($timemysql,$timemysqlfinishavg);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $trashhold =  $this->daily_items_CALENDAR($date);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (string) $data['not_picked'],
                    'percentOfProducts'=> (string) $data['after_not_picked'],
                    'trend' => (string) $this->upordown['0'],
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $trashhold['date'],
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'numberOfProducts' => (string) $data['not_picked'],
                    'percentOfProducts'=> (string) $data['after_not_picked'],
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $trashhold['date'],
                    'threndIntervalComparer' => static::month,
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
$start = new items();
$start->AUT();