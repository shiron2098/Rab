<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';


class stopsPosCollection  extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';
    private $interval;
    private $int;

    public function Week($date, $int, $offset, $count, $sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_array_TableStops($timemysql, $offset, $count, $sort);
            $dataCount = $this->daily_count_POS($timemysql);
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

    private function Months($date, $int, $offset, $count, $sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtime = strtotime($date);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_array_TableStops($timemysql, $offset, $count, $sort);
            $dataCount = $this->daily_count_POS($timemysql);
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
                    echo json_encode("no data");
                }
            }
        }
    }

    public function start($post)
    {
        if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date) && isset($post->offset) && isset($post->count)) {
            $this->interval = $post->trendIntervalComparer;
            switch ($this->interval) {
                case '45':
                    $this->Week($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting);
                    break;
                case '180':
                    $this->Months($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting);
                    break;
            }
        } else {
            echo json_encode('no correct data for P5235423232323');
        }
    }
}
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);

$start = new stopsPosCollection();
/*$start->Week('20030508',45,0,20,$sorting = ['pos_id','ascending']);*/
$start->start($json_obj);