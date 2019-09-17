<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class POS  extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';
    private $interval;
    private $int;

    public function Week($date, $int, $offset, $count)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_array_TableStops($timemysql,$offset,$count);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'items' => $data,
                    'totalCount' => $data['number'],
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stops)");
            }
        }
    }

    private function Months($date, $int, $offset, $count)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtime = strtotime($date);
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_array_TableStops($timemysql,$offset,$count);
            if ($data !== null) {
                if ($data !== null) {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $data['number'],
                    );
                    echo json_encode($output);
                } else {
                    echo json_encode("no correct date(stops)");
                }
            }
        }
    }

    public function start()
    {

        if (isset($_GET['trendIntervalComparer']) && !EMPTY($_GET['trendIntervalComparer']) && isset($_GET['date']) && !empty($_GET['date']) && isset($_GET['offset']) && isset($_GET['count'])) {
            $this->interval = $_GET['trendIntervalComparer'];
            switch ($this->interval) {
                case '45':
                    echo json_encode($_GET);
                    $this->Week($_GET['date'], $_GET['trendIntervalComparer'], $_GET['offset'], $_GET['count']);
                    break;
                case '180':
                    $this->Months($_GET['date'], $_GET['trendIntervalComparer'], $_GET['offset'], $_GET['count']);
                    break;
            }
        }else{
            echo json_encode('no correct data for POS');
        }
    }
}
$start = new POS();
/*$start->Week('20040202',45,20,30);*/
$start->start();
