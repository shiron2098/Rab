<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_avg.php';


class distribution extends MYSQL_t2s_bi_avg
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
            $data = $this->daily_collection_distribution($timemysql);
            $this->upordown = $this->daily_distribution_AVG($timemysql, $timemysqlfinishavg);
            if ($data !== null) {
                $output = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'LastWeek',
                    'salesDistributionCollection' => [
                        'minTresholdSales' => '0',
                        'maxTresholdSales' => 'less 50',
                        'numberOfPos' => 'avg',
                        'trend' => $this->upordown,
                    ]
                );
            }
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(distribution)");
            }
        }
}
$start = new distribution();
$start->Week('20030420');