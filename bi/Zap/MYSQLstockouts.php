<?php
header('Content-type: application/json');
if(!empty($_GET['date'])&&isset($_GET['date'])){
    $time = date('Ymdhis',time());
    if ($_GET['date'] == '2019-09-04') {
        $output[] = array(
            'beforeVisitNumberOfProducts' => (int) '333',
            'beforeVisitPercentOfProducts' => (int) '321',
            'beforeVisitTrend' => (string) 'up',
            'afterVisitNumberOfProducts' => (int) '1999',
            'afterVisitPercentOfProducts' => (int) '2095',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastMonth',
        );
    }
    if ($_GET['date'] == '2019-09-05') {
        $output[] = array(
            'beforeVisitNumberOfProducts' => (int) '4444',
            'beforeVisitPercentOfProducts' => (int) '888',
            'beforeVisitTrend' => (string) 'down',
            'afterVisitNumberOfProducts' => (int)  '3333',
            'afterVisitPercentOfProducts' => (int) '7777',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastMonth',
        );
    }
    echo json_encode($output);
}