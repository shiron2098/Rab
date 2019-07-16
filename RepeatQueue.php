<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';
Include_once 'Job.php';


class My extends Thread
{
    function run()
    {
        for ($i = 1; $i < 10; $i++) {
            echo Thread::getCurrentThreadId() . "\n";
            sleep(2);     // <------
        }
    }
}

for ($i = 0; $i < 2; $i++) {
    $pool[] = new My();
}

foreach ($pool as $worker) {
    $worker->start();
}
foreach ($pool as $worker) {
    $worker->join();
}
