<?php
include_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_calendar.php';



class calendar extends MYSQL_t2s_bi_calendar
{

    private $datefrom;
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

    public function Week($array,$int)
    {
        if (!empty($array) && isset($array)) {

            foreach ($array as $date) {
                $arraydate= [];
                $this->DeleteArrayFile($arraydate);
                $this->int = $int;
                $this->datefrom = $date;
                $arraydate[] = $this->daily_missed_stops_CALENDAR($this->datefrom);
                $arraydate[] = $this->daily_items_CALENDAR($this->datefrom);
                $arraydate[] = $this->daily_avg_CALENDAR($this->datefrom);
                $arraydate[] = $this->daily_stockouts_CALENDAR($this->datefrom);
                $arraydate[] = $this->daily_distribution_CALENDAR($this->datefrom);
                foreach($arraydate as $dateOKANDALLERT) {
                    if (!empty($arraydate['0'])&&isset($arraydate)) {
                        if($dateOKANDALLERT['value'] === 'ok'){
                            $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                            );
                        }elseif($dateOKANDALLERT['value'] === 'alert'){
                            $finishdate = array(
                                'date' => $dateOKANDALLERT['date'],
                                'levelDateByThreshold' => $dateOKANDALLERT['value'],
                            );
                            break;
                        }
                    }else{
                        $finishdate = array(
                            'date' => $date,
                            'levelDateByThreshold' => 'nodata',
                        );
                        break;
                    }
                }
                $arrayjson[]=$finishdate;
            }
            $date = array(
                'threndIntervalComparer' => '45',
                'dateCollection' => $arrayjson,
            );
            echo json_encode($date);
        }
    }
    public function Months($array,$int)
    {
/*                $array = array(
            '20030420',
            '20030421',
            '20030422',
            '20030423',
            '20030424',
            '20030425',
            '20030426',
        );*/

        if (!empty($array) && isset($array)) {

            if (!empty($array) && isset($array)) {

                foreach ($array as $date) {
                    $arraydate= [];
                    $this->DeleteArrayFile($arraydate);
                    $this->int = $int;
                    $this->datefrom = $date;
                    $arraydate[] = $this->daily_missed_stops_CALENDAR($this->datefrom);
                    $arraydate[] = $this->daily_items_CALENDAR($this->datefrom);
                    $arraydate[] = $this->daily_avg_CALENDAR($this->datefrom);
                    $arraydate[] = $this->daily_stockouts_CALENDAR($this->datefrom);
                    $arraydate[] = $this->daily_distribution_CALENDAR($this->datefrom);
                    foreach($arraydate as $dateOKANDALLERT) {
                        if (!empty($arraydate['0'])&&isset($arraydate)) {
                            if($dateOKANDALLERT['value'] === 'ok'){
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                            }elseif($dateOKANDALLERT['value'] === 'alert'){
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                                break;
                            }
                        }else{
                            $finishdate = array(
                                'date' => $date,
                                'levelDateByThreshold' => 'nodata',
                            );
                            break;
                        }
                    }
                    $arrayjson[]=$finishdate;
                }
                $date = array(
                    'threndIntervalComparer' => '180',
                    'dateCollection' => $arrayjson,
                );
                echo json_encode($date);
            }
        }
    }
    public function start()
    {
        if (isset($_GET['trendIntervalComparer']) && !EMPTY($_GET['trendIntervalComparer']) && isset($_GET['dateCollection']) && !empty($_GET['dateCollection'])) {
            $this->interval = $_GET['trendIntervalComparer'];
            switch ($this->interval) {
                case '45':
                    $this->Week($_GET['dateCollection'],$_GET['trendIntervalComparer']);
                    break;
                case '180':
                    $this->Months($_GET['dateCollection'],$_GET['trendIntervalComparer']);
                    break;
            }
        }
    }


}
$start = new calendar();
$start->AUT();
