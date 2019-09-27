<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class stockouts extends MYSQL_t2s_bi_calendar
{

    const week = '45';
    const month = '180';
    private $upordown;
    private $interval;
    private $int;


    public function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $this->upordown = $this->daily_stockouts_AVG($timemysql,$timemysqlfinishavg);
            $trashhold= $this->daily_stockouts_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'beforeVisitNumberOfProducts' => (string)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (string)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $this->upordown['0'],
                    'afterVisitNumberOfProducts' => (string)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (string)$data['after_percentage'],
                    'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'beforeVisitNumberOfProducts' => (string)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (string)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $this->upordown['0'],
                    'afterVisitNumberOfProducts' => (string)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (string)$data['after_percentage'],
                    'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            }
        }
    }
    private function Months($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_stockouts_and_not_picked($timemysql);
            $dataavg = $this->daily_stockouts_AVG($timemysql,$timemysqlfinishavg);
            $trashhold= $this->daily_stockouts_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'beforeVisitNumberOfProducts' => (string)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (string)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $dataavg['0'],
                    'afterVisitNumberOfProducts' => (string)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (string)$data['after_percentage'],
                    'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'beforeVisitNumberOfProducts' => (string)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (string)$data['before_percentage'],
                    'beforeVisitTrend' => (string) $dataavg['0'],
                    'afterVisitNumberOfProducts' => (string)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (string)$data['after_percentage'],
                    'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $timemysql,
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
$start = new stockouts();
$start->start();
