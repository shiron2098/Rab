<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class stops extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';
    private $upordown;
    private $interval;
    private $int;

    public function Week($date,$int)
    {
        if (!empty($date) && isset($date)) {
             $this->int=$int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->daily_stopsAVG($timemysql, $timemysqlfinishavg);
            $data = $this->daily_missed_stops($timemysql);
            $trashhold=  $this->daily_missed_stops_CALENDAR($timemysql);
            if ($data !== null) {
                $output = array(
                    'totalScheduledStopsNumber' => (string)$data['scheduled_stops'],
                    'missedStopsNumber' => (string)$data['missed_stops'],
                    'missedStopsTrend' => (string)$this->upordown['0'],
                    'outOfScheduleStopsNumber' => (string)$data['out_of_schedule_stops'],
                    'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                    'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'totalScheduledStopsNumber' => (string)$data['scheduled_stops'],
                    'missedStopsNumber' => (string)$data['missed_stops'],
                    'missedStopsTrend' => (string)$this->upordown['0'],
                    'outOfScheduleStopsNumber' => (string)$data['out_of_schedule_stops'],
                    'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                    'levelMissedStopsNumberByThreshold' => $trashhold['value'],
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
                $this->int=$int;
                $unixtime = strtotime($date);
                $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
                $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
                $timemysql = date('Ymd', $unixtime);
                $this->upordown = $this->daily_stopsAVG($timemysql, $timemysqlfinishavg);
                $data = $this->daily_missed_stops($timemysql);
                $trashhold=  $this->daily_missed_stops_CALENDAR($timemysql);
                if ($data !== null) {
                    $output = array(
                        'totalScheduledStopsNumber' => (string)$data['scheduled_stops'],
                        'missedStopsNumber' => (string)$data['missed_stops'],
                        'missedStopsTrend' => (string)$this->upordown['0'],
                        'outOfScheduleStopsNumber' => (string)$data['out_of_schedule_stops'],
                        'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                        'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                        'date' => $trashhold['date'],
                        'threndIntervalComparer' => static::month,
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'totalScheduledStopsNumber' => (string)$data['scheduled_stops'],
                        'missedStopsNumber' => (string)$data['missed_stops'],
                        'missedStopsTrend' => (string)$this->upordown['0'],
                        'outOfScheduleStopsNumber' => (string)$data['out_of_schedule_stops'],
                        'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                        'levelMissedStopsNumberByThreshold' => $trashhold['value'],
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
$start = new stops();
$start->start();