<?php
if(isset($_GET['name'])) {
    $con = mysqli_connect("127.0.0.1", "ret", "123", 'daws2') or die("Failed to connect with database!!!!");
    $result = mysqli_query(
        $con,
        /*$connect = " SELECT * FROM operators  WHERE name ='" . $_GET["name"] . "'"*/
        $connect = " SELECT * FROM operators  WHERE name = 'CLASS2'"
    );
    foreach ($result as $row1) {
        $id = $row1['id'];
    }

    $result = mysqli_query(
        $con,
        "SELECT * FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  join commands on commands.id = jobs.command_id
                  WHERE operators.id = $id"
    );
    /*            $connect = " SELECT * FROM commands WHERE id = '" . $_GET["id"] . "' ORDER BY id ASC "*/
    foreach ($result as $row2) {
    }

    if ($_GET['name'] == '20190903151038') {
        $output[] = array(
            'totalScheduledStopsNumber' => (int) '2',
            'missedStopsNumber' => '321',
            'missedStopsTrend' => (string) 'Trend',
            'outOfScheduleStopsNumber' => (int) '234555',
            'outOfScheduleStopsTrend' => (string) 'srtee',
        );
    }
    if ($_GET['name'] == '20190903151037') {
        $output[] = array(
            'totalScheduledStopsNumber' => (int) '4444',
            'missedStopsNumber' =>  '5555',
            'missedStopsTrend' => (string) 'bbbbb',
            'outOfScheduleStopsNumber' => (int)  '3333',
            'outOfScheduleStopsTrend' => (string) 'vvvvv',
        );
    }
    echo json_encode($output);
}
?>