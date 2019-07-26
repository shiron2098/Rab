<?php

include_once('CheckDataMYSQL.php');
include_once('MYSQL.php');
include_once('Rabbimq.php');


class MyWork extends Threaded
{

    protected $timestamp;
    protected $IDOperators;
    protected $IDJobs;

    public $linkConnect;
    const host = '127.0.0.1';
    const user = 'ret';
    const password = '123';
    const database = 'daws2';

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
            exec('php CRON.php > /dev/null 2>/dev/null &');
            sleep(1);
        }


    }

}