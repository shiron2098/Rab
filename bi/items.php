<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class items extends MYSQL_t2s_bi_avg
{

    private $upordown;
    private $interval;

    public function Week($date)
    {
        if (!empty($date) && isset($date)) {
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-42 days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG - '-42 days');
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->daily_items_AVG($timemysql,$timemysqlfinishavg);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (int) $data['not_picked'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $time,
                    'threndIntervalComparer' => 'LastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stockout)");
            }
        }
    }
    public function Months($date)
    {
        if (!empty($date) && isset($date)) {
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-182 days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG - '-182 days');
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->daily_items_AVG($timemysql,$timemysqlfinishavg);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (int) $data['not_picked'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $time,
                    'threndIntervalComparer' => 'LastMonth',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stockout)");
            }
        }
    }
    public function start()
    {
        if (isset($_GET['interval']) && !EMPTY($_GET['interval']) && isset($_GET['date']) && !empty($_GET['date'])) {
            $this->interval = $_GET['interval'];
            switch ($this->interval) {
                case 'lastWeek':
                    $this->Week($_GET['date']);
                    break;
                case 'lastMonth':
                    $this->Months($_GET['date']);
                    break;
            }
        }

    }
}
$start = new items();
$start->start();