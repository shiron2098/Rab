<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../AbstractClass/MYSQL_t2s_bi_calendar.php';


class revenue extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';

    private $upordown;
    private $interval;
    private $int;

    public function AUT(){
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if(!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $this->start();
            } else {
                http_response_code(403);
            }
        }else{
            http_response_code(401);
        }
    }

    public function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'. $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_revenue_per_collection($timemysql);
            $datarevenue = $this->daily_array_revenue($timemysql);
            $this->upordown = $this->daily_revenue_AVG($timemysql, $timemysqlfinishavg);
            $trashhold = $this->daily_avg_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => $datarevenue,
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    /*'date' => $trashhold['date'],*/
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => array(),
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    /*'date' => $trashhold['date'],*/
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
            $unixtimeAVG = strtotime($date . '-'. $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_revenue_per_collection($timemysql);
            $datarevenue = $this->daily_array_revenue($timemysql);
            $this->upordown = $this->daily_revenue_AVG($timemysql, $timemysqlfinishavg);
            $trashhold = $this->daily_avg_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => $datarevenue,
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    /*'date' => $trashhold['date'],*/
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'maxRevenue' => (string)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => array(),
                    'levelAverageRevenueByThreshold' => array(),
                    /*'date' => $trashhold['date'],*/
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
$start = new revenue();
$start->AUT();