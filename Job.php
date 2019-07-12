<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once ('MysqlDbConnect.php');
include_once ('RabbiSendSqlTakeInDbMYSQL.php');
use GO\Scheduler;

class Job extends MysqlDbConnect
{

    const NameTable = 'JobScheduler';
    const Schedule = "*/5 * * * 4,5 /usr/bin/php7.3 /var/www/html/rab/index.php >/dev/null 2>&1";
    const SQL = 'SELECT * FROM Product';
    const columnname = 'Taskid';

    protected $Userid;
    protected $TimeForScheduler;
    protected $DateForMYSQL;

    protected function SelectToDbOperators()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $row = mysqli_fetch_assoc($result);
        $this->Userid = $row['id'];
        return $row;
    }
    protected function SchedulerSingle()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM jobs WHERE operator_id = $this->Userid"
        );
        if (!empty($result)) {
            foreach ($result as $date)
                $file[] = $date;
            return $file;
        } else {
            $a = 'error empty response';
            return $a;
        }
    }

}

