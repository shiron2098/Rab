<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_Collection.php';


class stopsPosCollection  extends MYSQL_t2s_bi_Collection
{
    const week = '45';
    const month = '180';
    private $interval;
    private $int;

    public function AUT(){
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if($_SESSION['AUT'] === true){
            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str);
            $this->start($json_obj);
        }else
        {
            http_response_code(403);
        }
    }


    public function Week($date, $int, $offset, $count, $sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_array_stops_collection($timemysql, $offset, $count, $sort);
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
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            }
        }
    }

    private function Months($date, $int, $offset, $count, $sort)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->daily_array_stops_collection($timemysql, $offset, $count, $sort);
            $dataCount = $this->daily_count_POS($timemysql);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            }
        }
    }

    public function start($post)
    {
        try {
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
                $text = 'Data not correct';
                throw new Exception($text);
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}

$start = new stopsPosCollection();
/*$start->Week('20030508',45,0,20,$sorting = ['pos_id','ascending']);*/
$start->AUT();