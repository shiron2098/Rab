<?php
require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../AbstractClass/ForChars.php';




class items extends ForChars
{
    public function start()
    {
        static::$date = $_GET['date'];
        $i = 0; ?>
        <html>
        <head>
            <!--Load the Ajax API-->
            <select name="date" class="form-control" id="date">
                <?php
                for ($a = 0; $a < 30; $a++) {
                    $time = date('Y-m-d', time());
                    $finishtime = date('Y-m-d', strtotime($time . '+' . ' ' . $i . ' ' . 'days'));
                    echo '<option value="' . $finishtime . '">' . $finishtime . '</option>';
                    $i++;
                }?>
            </select>
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <script type="text/javascript"
                    src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
            <script type="text/javascript">
                var data2;
                <?php if (isset(static::$date) && !empty(static::$date)) {?>
                var url_string = window.location.href; // www.test.com?filename=test
                var url = new URL(url_string);
                var paramValue = url.searchParams.get("date");
                if (paramValue != null && typeof paramValue !== "undefined") {
                    var jsondata = $.ajax({
                        url: "MYSQLitems.php",
                        method: "GET",
                        data: {
                            date: paramValue
                        },
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
                <?php }?>
                function load_monthwise_data(date, title) {
                    var jsondata = $.ajax({
                        url: "MYSQLitems.php",
                        method: "GET",
                        data: {
                            date: date
                        },
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
            </script>
            <script type="text/javascript">

                $(document).ready(function () {
                    $('#date').change(function () {
                        var date = $(this).val();
                        if (date != '') {
                            load_monthwise_data(date, 'Month Wise Profit Data For');
                        }
                    });
                });

            </script>
        </head>
        </html>
        <?php
    }
}?>
<?php
$stops = new items();
$stops->start();
