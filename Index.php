<?php
require_once __DIR__.'/vendor/autoload.php';
spl_autoload('MysqlDbConnect');


use GO\Scheduler;

class JobScheduler extends MysqlDbConnect
{
    public function Index()

    {

        fopen('rrr','a+');
        function myFunc() {
             return "Hello world from function!";
            }
        $scheduler = new Scheduler([
            'emailFrom' => 'myemail@address.from'
        ]);
        $scheduler->php(__DIR__.'/Index.php')->at('* * * * *')->output(__DIR__.'/cronjobb.log');
        /*$scheduler->php(__DIR__.'/Index.php')->useBin('/usr/bin/php')->at('* * * * *')->output(__DIR__.'/cronjob_bin.log', true);*/
        $scheduler->run();
    }
}
$a = new JobScheduler();
$a->Index();


