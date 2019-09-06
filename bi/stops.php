<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_data.php';


class stops extends MYSQL_t2s_bi_data
{
    public function index()
    {
        if (!empty($_GET['date']) && isset($_GET['date'])) {
            $time = date('Ymdhis', time());
            $unixtime = strtotime($_GET['date']);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_missed_stops($timemysql);
            if ($data !== null) {
                $output[] = array(
                    'totalScheduledStopsNumber' => (int)$data['scheduled_stops'],
                    'missedStopsNumber' => (int)$data['missed_stops'],
                    'missedStopsTrend' => (string)'up',
                    'outOfScheduleStopsNumber' => (int)$data['out_of_schedule_stops'],
                    'outOfScheduleStopsTrend' => (string)'down',
                );
                $output[] = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'lastWeek',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stops)");
            }
        }
    }
}
$start = new stops();
$start->index();