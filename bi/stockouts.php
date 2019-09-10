<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class stockouts extends MYSQL_t2s_bi_avg
{

    private $upordown;
    private $interval;


    public function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-'.$int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG - '-' . $int . 'days');
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $this->upordown = $this->daily_stockouts_AVG($timemysql,$timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'beforeVisitNumberOfProducts' => (int)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (int)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $this->upordown['0'],
                    'afterVisitNumberOfProducts' => (int)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (int)$data['after_percentage'],
                    'date' => $time,
                    'threndIntervalComparer' => 'lastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stockout)");
            }
        }
    }
    public function Months($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $time = date('Ymdhis', time());
            $unixtimeAVG = strtotime($date . '-'.$int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG - '-' . $int . 'days');
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $dataavg = $this->daily_stockouts_AVG($timemysql,$timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'beforeVisitNumberOfProducts' => (int)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (int)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $dataavg['0'],
                    'afterVisitNumberOfProducts' => (int)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (int)$data['after_percentage'],
                    'date' => $time,
                    'threndIntervalComparer' => 'lastMonth',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stockout)");
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
$start = new stockouts();
$start->start();
