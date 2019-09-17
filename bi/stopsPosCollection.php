<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class stopsPosCollection  extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';
    private $interval;
    private $int;

    public function Week($date, $int, $offset, $count,$sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_array_TableStops($timemysql,$offset,$count,$sort);
            $dataCount= $this->daily_count_POS($timemysql);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(POS)");
            }
        }
    }

    private function Months($date, $int, $offset, $count,$sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtime = strtotime($date);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_array_TableStops($timemysql,$offset,$count,$sort);
            $dataCount= $this->daily_count_POS($timemysql);
            if ($data !== null) {
                if ($data !== null) {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                } else {
                    echo json_encode("no correct date(POS)");
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
                    $this->Week($_GET['date'], $_GET['trendIntervalComparer'], $_GET['offset'], $_GET['count'],$_GET['sorting']);
                    break;
                case '180':
                    $this->Months($_GET['date'], $_GET['trendIntervalComparer'], $_GET['offset'], $_GET['count'],$_GET['sorting']);
                    break;
            }
        }else{
            echo json_encode('no correct data for POS');
        }
    }
}
$start = new stopsPosCollection();
/*$start->Week('20040202',45,0,20,$sorting = array(['pos_id','descending']));*/
$start->start();
