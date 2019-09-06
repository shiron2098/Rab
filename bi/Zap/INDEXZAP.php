<?php
include_once __DIR__ . '/../AbstractClass/MYSQL.php';
/*require_once 'UsersApi.php';*/



class INDEXZAP extends MYSQL
{

}
print_r($_GET);
header('Content-type: text/html; charset=UTF-8');
if (count($_REQUEST)>0){
    print_r($_REQUEST);
    require_once 'apiEngine.php';
    foreach ($_REQUEST as $apiFunctionName => $apiFunctionParams) {
        $APIEngine=new APIEngine($apiFunctionName,$apiFunctionParams);
        echo $APIEngine->callApiFunction();
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Dynamic Column Chart using PHP Ajax with Google Charts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
</head>
<body>
<br /><br />
<div class="container">
    <!--<h3 align="center">Create Dynamic Column Chart using PHP Ajax with Google Charts</h3>-->
    <br />
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <!--<h3 class="panel-title">Month Wise Profit Data</h3>-->
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-control" id="year">
                        <option value="">Select Year</option>
                        <?php
                        foreach($a as $row)
                        {
                            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div id="chart_area" style="width: 1000px; height: 620px;"></div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(load_monthwise_data);

    function load_monthwise_data(name, title)
    {
/*        var temp_title = title + ' '+id+'';*/
        var temp_title = name;
       var jsondata =   $.ajax({
           url: "fetch.php",
           method: "GET",
           data:{name:name},
           dataType: "JSON",
           success: function (data) {
               drawMonthwiseChart(data, jsondata);
           }
        });
    }

    function drawMonthwiseChart(chart_data, chart_main_title)
    {
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('nubmer', 'totalScheduledStopsNumber');
        data.addColumn('number', 'missedStopsNumber');
        data.addColumn('string', 'missedStopsTrend');
        data.addColumn('number', 'outOfScheduleStopsNumber');
        data.addColumn('string', 'outOfScheduleStopsTrend');

        $.each(jsonData, function(i, jsonData){
            var totalScheduledStopsNumber = jsonData.parseFloat(jsonData.totalScheduledStopsNumber);
           /* var profit = parseFloat($.trim(jsonData.profit));*/
            var missedStopsNumber = parseFloat(jsonData.missedStopsNumber);
            var missedStopsTrend = jsonData.missedStopsTrend;
            var outOfScheduleStopsNumber = jsonData.outOfScheduleStopsNumber;
            var outOfScheduleStopsTrend = jsonData.outOfScheduleStopsTrend;
            data.addRows([[totalScheduledStopsNumber, missedStopsNumber,missedStopsTrend,outOfScheduleStopsNumber,outOfScheduleStopsTrend]]);
        });
        var options = {
            title:chart_main_title,
            hAxis: {
                title: "totalScheduledStopsNumber"
            },
            vAxis: {
                title: 'missedStopsNumber'
            },
            sAxis: {
                title: 'missedStopsTrend'
            },
            dAxis: {
                title: 'outOfScheduleStopsNumber'
            },
            zAxis: {
                title: 'outOfScheduleStopsTrend'
            },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
        chart.draw(data, options);
    }

</script>

<script>

    $(document).ready(function(){

        $('#year').change(function(){
            var year = $(this).val();
            if(year != '')
            {
                load_monthwise_data(year, 'Month Wise Profit Data For');
            }
        });

    });

</script>