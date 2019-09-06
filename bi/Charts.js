$(document).ready(function(){
    $('#date').change(function(){
        var date = $(this).val();
        if(date != '')
        {
            load_monthwise_data(date, 'Month Wise Profit Data For');
            load_monthwise_data2(date, 'Month Wise Profit Data For');
            load_monthwise_data3(date, 'Month Wise Profit Data For');
            load_monthwise_data4(date, 'Month Wise Profit Data For');
            load_monthwise_data5(date, 'Month Wise Profit Data For');
        }
    });
    function load_monthwise_data(date, title) {
        var jsondata = $.ajax({
            url: "stops.php",
            method: "GET",
            data: {date: date},
            contentType: 'application/json; charset=utf-8',
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                data.forEach(function (o) {
                    data2 = o;
                    console.log(data2);
                });
            }
        });
    }
    function load_monthwise_data2(date, title) {
        var jsondata = $.ajax({
            url: "stockouts.php",
            method: "GET",
            contentType: 'application/json; charset=utf-8',
            data: {date: date},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                data.forEach(function (o) {
                    data2 = o;
                });
                console.log(data2);
            }
        });
    }
    function load_monthwise_data3(date, title) {
        var jsondata = $.ajax({
            url: "revenue.php",
            method: "GET",
            contentType: 'application/json; charset=utf-8',
            data: {date: date},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                data.forEach(function (o) {
                    data2 = o;
                });
                console.log(data2);
            }
        });
    }
    function load_monthwise_data4(date, title) {
        var jsondata = $.ajax({
            url: "items.php",
            method: "GET",
            contentType: 'application/json; charset=utf-8',
            data: {date: date},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                data.forEach(function (o) {
                    data2 = o;
                });
                console.log(data2);
            }
        });
    }
    function load_monthwise_data5(date, title) {
        var jsondata = $.ajax({
            url: "distribution.php",
            method: "GET",
            contentType: "application/json",
            data: {date: date},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                data.forEach(function (o) {
                    data2 = o;
                });
                console.log(data2);
            }
        });
    }
});