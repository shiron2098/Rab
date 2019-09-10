<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class stops extends MYSQL_t2s_bi_avg
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
            $this->upordown = $this->daily_stopsAVG($timemysql, $timemysqlfinishavg);

            $data = $this->daily_missed_stops($timemysql);
            if ($data !== null) {
                $output = array(
                    'totalScheduledStopsNumber' => (int)$data['scheduled_stops'],
                    'missedStopsNumber' => (int)$data['missed_stops'],
                    'missedStopsTrend' => (string)$this->upordown['0'],
                    'outOfScheduleStopsNumber' => (int)$data['out_of_schedule_stops'],
                    'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                    'date' => $time,
                    'threndIntervalComparer' => 'lastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stops)");
            }
        }
    }
        public function Months($date)
        {
            if (!empty($date) && isset($date)) {
                $time = date('Ymdhis', time());
                $unixtime = strtotime($date);
                $unixtimeAVG = strtotime($date . '-182 days');
                $threndinterval = date('Ymd', $unixtimeAVG);
                $timemysql = date('Ymd', $unixtime);
                $this->upordown = $this->dayly_stopsAVG($timemysql, $threndinterval);
                $data = $this->daily_missed_stops($timemysql);
                if ($data !== null) {
                    $output[] = array(
                        'totalScheduledStopsNumber' => (int)$data['scheduled_stops'],
                        'missedStopsNumber' => (int)$data['missed_stops'],
                        'missedStopsTrend' => (string)$this->upordown['0'],
                        'outOfScheduleStopsNumber' => (int)$data['out_of_schedule_stops'],
                        'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                        'date' => $time,
                        'threndIntervalComparer' => 'LastMonth',
                    );
                    echo json_encode($output);
                } else {
                    echo json_encode("no correct date(stops)");
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
$start = new stops();
$start->start();