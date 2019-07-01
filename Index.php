<?php

/*class JobScheduler
{
    public function Index()
    {
        $scheduler = new Scheduler([

        ]);*/
        /*$scheduler->php(__DIR__.'/trtrt.php')->at('* * * * *');*/
        /*$scheduler->php(__DIR__.'/Index.php')->useBin('/usr/bin/php')->at('* * * * *')->output(__DIR__.'/cronjob_bin.log', true);*/
/*        $scheduler->php('trtrt.php')->everyMinute();
        $scheduler->run();
        print_r($scheduler);
    }
}*/
/*$scheduler = new Scheduler([

]);*/
/*$scheduler->php(__DIR__.'/trtrt.php')->at('* * * * *');*/
/*$scheduler->php('trtrt.php')->everyMinute();*/
/*$scheduler->php(__DIR__.'/Index.php')->useBin('/usr/bin/php')->at('* * * * *')->output(__DIR__.'/cronjob_bin.log', true);*/
/*$scheduler->run();
print_r($scheduler);*/
$time =date( 'H:i:s', strtotime('now')) . PHP_EOL;
$b =fopen(__DIR__ . '/rte.txt','a+');
fwrite($b,$time);
