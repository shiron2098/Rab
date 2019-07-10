<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';
Include_once 'Job.php';

class RepeatQueue extends Job
{


    public function __construct()
    {
        parent::__construct();
    }
    public function RepeatIndex()
    {
            if ($file = !file_exists(self::FileRepeatToTask)) {
                fopen(self::FileRepeatToTask, 'a+');
            }
            if (!empty(file_get_contents(self::FileRepeatToTask))) {
                $fileRepeat = file_get_contents(self::FileRepeatToTask);
                $this->IDOperators = current(explode(PHP_EOL, $fileRepeat));
                $this->RepeatData($this->IDOperators);
                $rowOfDb = $this->DataFromVendmax($this->IDJobs);
                if ($this->TimeTableDate($this->IDJobs) !== null) {
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