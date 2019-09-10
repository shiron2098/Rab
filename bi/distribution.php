<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class distribution extends MYSQL_t2s_bi_avg
{
    private $upordown;
    private $interval;
    private $int;


    private function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_collection_distribution($timemysql);
            $this->upordown = $this->daily_distribution_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'LastWeek',
                    'salesDistributionCollection' => $this->upordown
                );
            }
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(distribution)");
            }
        }
    private function Months($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_collection_distribution($timemysql);
            $this->upordown = $this->daily_distribution_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'LastWeek',
                    'salesDistributionCollection' => $this->upordown
                );
            }
            echo json_encode($output);
        } else {
            echo json_encode("no correct date(distribution)");
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
$start->start();