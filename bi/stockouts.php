<?php
header('Content-type: application/json');
require_once __DIR__ . '/../AbstractClass/MYSQL_t2s_bi_data.php';


class stockouts extends MYSQL_t2s_bi_data
{
    public function index()
    {
        if (!empty($_GET['date']) && isset($_GET['date'])) {
            $time = date('Ymdhis', time());
            $unixtime = strtotime($_GET['date']);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->daily_stockouts_and_not_pickedhide($timemysql);
            if ($data !== null) {
                $output[] = array(
                    'beforeVisitNumberOfProducts' => (int)$data['before_stockouts'],
                    'beforeVisitPercentOfProducts' => (int)$data['before_percentage'],
                    'beforeVisitTrend' => (string)'up',
                    'afterVisitNumberOfProducts' => (int)$data['after_stockouts'],
                    'afterVisitPercentOfProducts' => (int)$data['after_percentage'],
                );
                $output[] = array(
                    'date' => $time,
                    'threndIntervalComparer' => 'lastMonth',
                );
                echo json_encode($output);
            } else {
                echo json_encode("no correct date(stockout)");
            }
        }
    }
}
$start = new stockouts();
$start->index();
