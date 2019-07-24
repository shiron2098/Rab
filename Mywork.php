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
/*        $link = mysqli_connect(
            '127.0.0.1',
            'ret',
            '123',
            'daws2'
        ) or die (CheckDataMYSQL::NoConnect);
        $result = mysqli_query(
            $link,
            "SELECT * FROM operators"

        );*/
        for($i=0;$i<$this->DataOperators;$i++) {
            exec('php CRON.php > /dev/null 2>/dev/null &');
            sleep(1);
            /*sleep(3);*/
        }/*

        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        print_r($result);
        FOREACH ($result as $row) {
            if ($row['streams'] === null || $row['streams'] == 0) {
                exec('php CRON.php > /dev/null 2>/dev/null &');
                /*exec("php CRON.php > test.txt &");*/

    }

}