<?php
header('Content-type: application/json');
if(!empty($_GET['date'])&&isset($_GET['date'])){
    $time = date('Ymdhis',time());
    if ($_GET['date'] == '2019-09-04') {
        $output[] = array(
            'numberOfProducts' => (int) '333',
            'trend' => (string) 'up',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastWeek',
        );
    }
    if ($_GET['date'] == '2019-09-05') {
        $output[] = array(
            'numberOfProducts' => (int) '545',
            'trend' => (string) 'down',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastWeek',
        );
    }
    echo json_encode($output);
}