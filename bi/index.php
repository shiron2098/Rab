<?php
include_once __DIR__ . '/../AbstractClass/MYSQL.php';



class index extends MYSQL
{

}
$i = 0;
/*
header('Content-type: text/html; charset=UTF-8');
if (count($_REQUEST)>0){
    require_once 'apiEngine.php';
    foreach ($_REQUEST as $apiFunctionName => $apiFunctionParams) {
        $APIEngine=new APIEngine($apiFunctionName,$apiFunctionParams);
        echo $APIEngine->callApiFunction();
        break;
    }
}
print_r($_REQUEST);*/

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="Charts.js"></script>
</head>
<body>
<div class="container">

                    <select name="date" class="form-control" id="date">
                        <?php
                        for($a = 0;$a<30;$a++)
                        {
                            $time = date ('Ymd' ,time());
                            $finishtime =  date('Ymdhis',strtotime('20030418172538' .'+'.' '. $i . ' ' . 'days'));
                            echo '<option value="'.$finishtime .'">'.$finishtime.'</option>';
                            $i++;
                        }
                        ?>
                    </select>
</div>
</body>
</html>