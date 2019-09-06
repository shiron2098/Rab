<?php
header('Content-type: application/json');
if(!empty($_GET['date'])&&isset($_GET['date'])) {
    $time = date('Ymdhis',time());
    if ($_GET['date'] == '2019-09-04') {
        $output[] = array(
            'totalScheduledStopsNumber' => (int)'2',
            'missedStopsNumber' => (int)'321',
            'missedStopsTrend' => (string)'up',
            'outOfScheduleStopsNumber' => (int)'234555',
            'outOfScheduleStopsTrend' => (string)'srtee',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastWeek',
        );
    }
    if ($_GET['date'] === '2019-09-05') {
        $output[] = array(
            'totalScheduledStopsNumber' => (int)'4444',
            'missedStopsNumber' => (int)'5555',
            'missedStopsTrend' => (string)'down',
            'outOfScheduleStopsNumber' => (int)'3333',
            'outOfScheduleStopsTrend' => (string)'vvvvv',
        );
        $output[] =array(
            'date' => $time,
            'threndIntervalComparer' => 'lastWeek',
        );
    }
    echo json_encode($output);
}