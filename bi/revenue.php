<?php
header('Content-type: application/json');
if(!empty($_GET['date'])&&isset($_GET['date'])){
    $time = date('Ymdhis',time());
    if ($_GET['date'] == '2019-09-04') {
        $output[] = array(
            'date' => (int) '333',
            'averageRevenue' => (int) '321',
            'beforeVisitTrend' => (string) 'up',
            'averageRevenueTrend' => (int) '1999',
            'minRevenue' => (int) '2095',
            'minAverageRevenueTrend' => (int) '333',
            'maxRevenue' => (int) '321',
            'maxAverageRevenueTrend' => (string) 'down',
            'averageRevenueCollection' => [
                'date' => '2019-08-30',
                'series' => 'number',
            ]
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastYear',
        );
    }
    if ($_GET['date'] == '2019-09-05') {
        $output[] = array(
            'date' => (int) '33325',
            'averageRevenue' => (int) '666',
            'beforeVisitTrend' => (string) 'down',
            'averageRevenueTrend' => (int) '4242',
            'minRevenue' => (int) '997',
            'minAverageRevenueTrend' => (int) '8646',
            'maxRevenue' => (int) '1235',
            'maxAverageRevenueTrend' => (string) 'up',
            'averageRevenueCollection' => [
                'date' => '2019-08-30',
                'series' => 'number',
            ]
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastYear',
        );
    }
    echo json_encode($output);
}
