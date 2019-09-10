<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class revenue extends MYSQL_t2s_bi_avg
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
            $data = $this->daily_revenue_per_collection($timemysql);
            $datarevenue = $this->daily_array_revenue($timemysql);
            $this->upordown = $this->daily_revenue_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => (int)$data['date_num'],
                    'averageRevenue' => (int)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (int)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (int)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    /**
                     * 7 last day and value
                     */
                    'averageRevenueCollection' => [
                        'date' => $_GET['date'],
                        'series' => $datarevenue,
                    ],
                    'date' => $time,
                    'threndIntervalComparer' => 'lastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(revenue)");
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
            $data = $this->daily_revenue_per_collection($timemysql);
            $datarevenue = $this->daily_array_revenue($timemysql);
            $this->upordown = $this->daily_revenue_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => (int)$data['date_num'],
                    'averageRevenue' => (int)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (int)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (int)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],

                    /**
                     * 7 last day and value
                     */
                    'averageRevenueCollection' => [
                        'date' => $_GET['date'],
                        'series' => $datarevenue,
                    ],
                    'date' => $time,
                    'threndIntervalComparer' => 'lastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(revenue)");
            }
        }
    }
    public function start()
    {
        if (isset($_GET['trendIntervalComparer']) && !EMPTY($_GET['trendIntervalComparer']) && isset($_GET['date']) && !empty($_GET['date'])) {
            $this->interval = $_GET['trendIntervalComparer'];
            switch ($this->interval) {
                case '45':
                    $this->Week($_GET['date']);
                    break;
                case '180':
                    $this->Months($_GET['date']);
                    break;
            }
        }

    }
}
$start = new revenue();
$start->start();