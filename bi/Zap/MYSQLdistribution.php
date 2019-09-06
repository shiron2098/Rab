<?php
header('Content-type: application/json');
if(!empty($_GET['date'])&&isset($_GET['date'])){
    $time = date('Ymdhis',time());
    if ($_GET['date'] == '2019-09-04') {
        $output[] = array(
            'mintTresholdSales' => (int) '90',
            'maxTresholdSales' => (int) '91',
            'numberOfPos' => (int) '92',
            'trend' => (string) 'up',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastYear',
        );
    }
    if ($_GET['date'] == '2019-09-05') {
        $output[] = array(
            'mintTresholdSales' => (int) '95',
            'maxTresholdSales' => (int) '97',
            'numberOfPos' => (int) '999',
            'trend' => (string) 'down',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastYear',
        );
    }
    echo json_encode($output);
}