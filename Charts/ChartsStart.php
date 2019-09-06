<?php
include_once __DIR__ . '/../AbstractClass/MYSQL.php';



class ChartsStart extends MYSQL
{

}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="\bootstrap\css\bootstrap.css"
    <link rel="stylesheet" href="..\js\jquery-ui.css">
    <link rel="stylesheet" href="..\css\Style.css">
    <script src="/js/jss/jquery-3.3.1.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/jss/ru.js"></script>
    <script src="/js/jss/sorttable.js"></script>
    <script src="/js/jss/Jw.js"></script>

</head>
<body>

</body>
</html>
<?php
$class = new ChartsStart();
$class->Dbconnect();
$a = $class->test();
print_R($a);
?>
