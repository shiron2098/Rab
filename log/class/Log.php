<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../AbstractClass/MYSQL.php';


class Log extends MYSQL
{

    const statusOK = 'OK';
    const statusERROR = 'ERROR';
    const statusRUN = 'RUN';
    const logfile = '/file.log';
    const FileRepeatToTask = __DIR__ . '/Repeat.log';

    protected $timestamp;
    protected $timetasklogstart;
    public $idcolumnjob;
    private $operratorname;
    private $commandname;
    private $software_provider;
    protected $IDJobs;

    protected function logDB($id, $timelog, $status, $text)
    {
        $this->LogData($id);
        $this->IDJobs = $id;
        if (!empty($id) && !empty($timelog) && !empty($status) && !empty($text) && !empty($this->operratorname) && !empty ($this->commandname) && !empty($this->software_provider)) {
            if ($status === self::statusRUN) {
                $result = mysqli_query(
                    $this->linkConnect,
                    "insert into job_history (job_id,command_name,operator_id,operator_name,software_provider,execute_start_time_dt,status,description) 
                               values ('" . $id . "','" . $this->commandname . "','" . $this->IDOperators . "','" . $this->operratorname . "','" . $this->software_provider . "','" . $timelog . "','" . $status . "','" . $text . "')"
                );
              $id= $this->Insertidrows();
              $this->idcolumnjob = $id;
                if ($status === self::statusRUN) {
                    $text = 'log downloads complete in DATABASE MYSQL #' . $this->idcolumnjob;
                    $this->logtext($text);
                    return $text;
                } else {
                    return null;
                }
            }
            if ($status === self::statusOK || $status === self::statusERROR) {
                $this->time();
                if (!empty($this->idcolumnjob)) {
                    $result = mysqli_query(
                        $this->linkConnect,
                        "UPDATE job_history SET execute_end_time_dt = '" . $this->timestamp . "',status = '" . $status . "',description = '" . $text . "' WHERE id=$this->idcolumnjob"
                    );
                }
            }
            if ($status === static::statusOK) {
                $text = 'log update complete in DATABASE MYSQL #' . $this->idcolumnjob;
                $this->logtext($text);
                return $text;
            }
            if ($result === false || $status === self::statusERROR) {
                $text = 'Log error downloads to DATABASE MYSQL #' . $this->idcolumnjob;
                $this->logtext($text);

            }
        } else {
            return null;
        }
    }
    public static function logtext($text)
    {
        file_put_contents(__DIR__ . Rabbimq::logfile, date('Y-m-d H:i:s', strtotime('now')) . " " . $text . PHP_EOL, FILE_APPEND);
    }


    public static function logtextL2D($text,$name)
    {
        file_put_contents(__DIR__ . '/' . $name . '.log', date('Y-m-d H:i:s', strtotime('now')) . " " . $text . PHP_EOL, FILE_APPEND);
    }
    private function LogData($id){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT operators.id as Operatorsid,operators.name as OperatorName,commands.code as CodeName,software_providers.code as Softwareprovider FROM operators
                JOIN jobs on jobs.operator_id =  operators.id
                join software_providers on software_providers.id = operators.software_provider_id
                join commands on commands.id = jobs.command_id
                WHERE jobs.id = $id"
        );
        try {
            if (!empty($result)) {
                foreach ($result as $row) {
                    $this->IDOperators = $row['Operatorsid'];
                    $this->operratorname = $row['OperatorName'];
                    $this->commandname = $row ['CodeName'];
                    $this->software_provider = $row['Softwareprovider'];
                }
            } else {
                throw new Exception('error no date ID MYSQL');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }
}