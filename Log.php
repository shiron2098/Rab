<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'MYSQL.php';


class Log extends MYSQL
{
    const statusOK = 'OK';
    const statusERROR = 'ERROR';
    const logfile = '/log/file.log';
    const FileRepeatToTask =  __DIR__ . '/log/Repeat.log';

    protected function logDB($id,$time,$status)
    {
        if (!empty($id)&& !empty($time)&&!empty($status)) {
            $result = mysqli_query(
                $this->linkConnect,
                "insert into job_history (job_id,status) values ('" . $id . "','" . $status . "')"
            );
            if($result === false) {
                $text = 'Log error downloads to DATABASE MYSQL';
                $this->logtext($text);
                }
            if($status === static::statusOK){
                $text = 'log downloads complete in DATABASE MYSQL';
                return $text;
            }else{
                return null;
            }
        } else {
            $text = 'Check $id and $time and $status to logDB function';
            $this->logtext($text);
        }
    }
    protected function logtext($text)
    {
        file_put_contents(__DIR__ . Rabbimq::logfile, date('Y-m-d H:i:s', strtotime('now')) . " " . $text . PHP_EOL, FILE_APPEND);
    }
}