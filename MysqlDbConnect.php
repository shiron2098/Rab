<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
include_once 'Rabbimq.php';


class MysqlDbConnect extends Rabbimq
{

    const NoConnect = 'No connect';
    const Time = 'now';
    const FileResponseName = __DIR__ . 'Response';


    private $Operatorid;
    protected $Timestamp;
    protected $linkConnect;
    protected $idOperator;
    protected $timestamp;
    protected $TableName;

    public function __construct()
    {

        $this->Timestamp = strtotime(MysqlDbConnect::Time);
        $this->Dbconnect();


    }

    public function Dbconnect()
    {
        $link = mysqli_connect(
            self::host,
            self::user,
            self::password,
            self::database
        ) or die (MysqlDbConnect::NoConnect);
        $this->linkConnect = $link;

    }

    public function SelectDb()
    {

        $row = $this->RowsDataTable();
        if(!empty($row) && isset($row)){
        foreach($row as $task) {
            $this->TableName = $task['TABLE_NAME'];
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT * FROM $this->TableName"
            );
            $row = mysqli_fetch_assoc($result);
            $results = print_r($row, true);
            $a = new MysqlDbConnect();
            $a->log($results);
            $timeNow = time();
            $time = strtotime('+1minutes', $row['TimeTask']) . PHP_EOL;
            $timeProduct = strtotime('+2minutes', $row['TimeTask']) . PHP_EOL;
            /*           print_r(date('Y-m-d H:i:s',$timeNow . PHP_EOL));
                        print_r(date('Y-m-d H:i:s',$time) . PHP_EOL);*/

            /*|| $timeNow>= $timeProduct*/
            try {
                if ($timeNow >= $time) {
                    $Rabbi = new RabbiSendSqlTakeInDbMYSQL();
                    $rabbitResponse = $Rabbi->index($row);
                    $results = print_r($rabbitResponse, true);
                    if(!empty($results)) {
                        $a->log($results);
                        $response = $a->UpdateBaseMYSQL($row['DBNAME'], $row['id']);
                        $a->log($response);
                    }
                } else {
                    throw new Exception('TIme is not come');

                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $a->log($e->getMessage());
            }
        }
    }
        exit();
        /*            $this->Operatorid = $row['operatorid'];*/
        /*            if (!empty($result)) {
                        mysqli_query(
                            $this->linkConnect,
                            "UPDATE Operator SET TimeInput=$this->Timestamp WHERE id=$this->Operatorid"
                        );
                    }*/
/*        $row = mysqli_fetch_assoc($result);
        $results = print_r($row, true);*/
        /*$results = print_r($row, true);
            if (file_exists($row['Name'])) {
                try {
                    $_SESSION['Zapros'] = false;
                    throw new Exception('message is allready sent ');
                }catch(Exception $e) {
                   echo $e->getMessage();
                }
            }
            file_put_contents($row['Name'], $results);
            $_SESSION['FileZip'] = false;*/
        $file = ['code' => $row,
            'timestamp' => $this->Timestamp];
        $f = fopen(__DIR__ . '/fileoo.log', 'a+');
        fwrite($f, 'error' . $results);
        fclose($f);
        return $file;

        /*                $file = ['code' => $row,
                            'timestamp' => $this->Timestamp];
                        $_SESSION['FileZip']=false;
                        return $file;*/

        /*                if ($f = fopen(MysqlDbConnect::FileResponseName, 'a+')) {
                            fwrite($f, $row);
                            fclose($f);*/

    }
    protected function UpdateBaseMYSQL($nameTable,$UserId){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE $nameTable SET TimeTask = $this->Timestamp WHERE id=$UserId"
        );
        $a = 'Update complete timestamp to Database MYSQL' . PHP_EOL;
        return $a;
    }
    protected function RowsDataTable(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='daws'"
        );
        foreach ($result as $res){
            $MYSQLdbname[]= $res;
        }
        return $MYSQLdbname;
    }
}
?>