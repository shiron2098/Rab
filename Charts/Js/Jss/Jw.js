$ (document).ready(function () {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawChart);

    function load_monthwise_data(id, title) {
        /*        var temp_title = title + ' '+id+'';*/
        var temp_title = id;
        $.ajax({
            url: "fetch.php",
            method: "GET",
            data: {id: id},
            dataType: "JSON",
            success: function (data) {
                drawMonthwiseChart(data, temp_title);
            }
        });
    }

    function drawMonthwiseChart(chart_data, chart_main_title) {
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Profit');
        $.each(jsonData, function (i, jsonData) {
            var month = jsonData.month;
            var profit = parseFloat($.trim(jsonData.profit));
            data.addRows([[month, profit]]);
        });
        var options = {
            title: chart_main_title,
            hAxis: {
                title: "Months"
            },
            vAxis: {
                title: 'Profit'
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
        chart.draw(data, options);
    }
}
