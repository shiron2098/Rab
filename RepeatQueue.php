<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';
Include_once 'Job.php';

class RepeatQueue extends Job
{

    public function __construct()
    {

        $this->Timestamp = strtotime(MysqlDbConnect::Time);
        $this->DateForMYSQL = date('l', strtotime('now'));

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
    public function RepeatIndex()
    {
            if ($file = !file_exists(self::FileRepeatToTask)) {
                fopen(self::FileRepeatToTask, 'a+');
            }
            if (!empty(file_get_contents(self::FileRepeatToTask))) {
                $fileRepeat = file_get_contents(self::FileRepeatToTask);
                $this->IdJobScheduler = current(explode(PHP_EOL, $fileRepeat));
                $this->RepeatData($this->IdJobScheduler);
                $rowOfDb = $this->DataFromVendmax($this->IdJobScheduler);
                if ($this->TimeTableDate($this->IDDataTableRows) !== null) {
                    $response = $this->CheckDataAndSendMessage($rowOfDb);
                    if (!empty($response)) {
                        $this->DeleteRepeat($rowOfDb['id']);
                        $text = 'Delete Repeat complete #' . $rowOfDb['id'];
                        $this->log($text);
                    }
                } else {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
}
$new = new RepeatQueue();
$new->RepeatIndex();