<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_data.php';


class distribution extends MYSQL_t2s_bi_data
{
    public function index()
    {
        if (!empty($_GET['date']) && isset($_GET['date'])) {
            $time = date('Ymdhis', time());
            $unixtime = strtotime($_GET['date']);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_collection_distribution($timemysql);
            if ($data !== null) {
                $output[] = array(
                    'mintTresholdSales' => (int)$data['less_50'],
                    'maxTresholdSales' => (int)$data['more_150'],
                    'numberOfPos' => (int)$data['less_50'],
                    'trend' => (string)'down',
                );
                $output[] = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'lastMonth',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stops)");
            }
        }
    }
}
$start = new distribution();
$start->index();