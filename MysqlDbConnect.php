<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');


class MysqlDbConnect extends Rabbimq
{

    const NoConnect = 'No connect';
    const Time = 'now';
    const FileResponseName = __DIR__ . 'Response';


    private $Operatorid;
    protected $Timestamp;
    protected $linkConnect;

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

        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM Operator"
        );
        /*            $this->Operatorid = $row['operatorid'];*/
        /*            if (!empty($result)) {
                        mysqli_query(
                            $this->linkConnect,
                            "UPDATE Operator SET TimeInput=$this->Timestamp WHERE id=$this->Operatorid"
                        );
                    }*/

        $row = mysqli_fetch_assoc($result);
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
        return $file;

        /*                $file = ['code' => $row,
                            'timestamp' => $this->Timestamp];
                        $_SESSION['FileZip']=false;
                        return $file;*/

        /*                if ($f = fopen(MysqlDbConnect::FileResponseName, 'a+')) {
                            fwrite($f, $row);
                            fclose($f);*/


        if (empty($row)) {
            $f = fopen('file.log', 'a+');
            fwrite($f, 'error' . $f);
            fclose($f);
        }

    }
}


/**
 * mixed object_from_file
 *
 * @param string filename
 * @return mixed
 *
 */
/*    function object_from_file($filename)
    {
        if(isset($filename) && !empty($filename)) {
            $file = file_get_contents($filename);
            $value = unserialize($file);
            return $value;
        }
    }*/