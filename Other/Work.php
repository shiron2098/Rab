<?php

include_once('CheckAndSendRabbitMYSQL/CheckDataMYSQL.php');
include_once('AbstractClass/MYSQL.php');
include_once('AbstractClass/Rabbimq.php');


class Work extends Threaded
{

    protected $DataOperators;
    public function __construct($row)
    {
        $this->DataOperators = $row;
    }

    public function run()
    {
        $value = null;

        $provider = $this->worker->getProvider();

        // Синхронизируем получение данных
        $provider->synchronized(function ($provider) use (&$value) {
            $value = $provider->getNext();
        }, $provider);

        for($i=0;$i<$this->DataOperators;$i++) {
            exec('php Job_Schedule.php > /dev/null 2>/dev/null &');
            sleep(1);
        }


    }

}