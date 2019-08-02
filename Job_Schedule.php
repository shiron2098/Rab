<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('CreateOperator/CreateTask.php');
require_once('RequestProcessor.php');
ignore_user_abort(true);

class Job_Schedule extends CreateTask
{

    public $idcolumnjob;
    public $IDOperators;


    public function get_jobs(){
        $data = $this->SelectToDbOperators();
        $response = $this->JobsOperators($data);
        return $response;
    }
    public function validate_job_schedule($response){
                   $this->RunAndCheck($response);
    }
}
$jobMYSQL = new Job_Schedule();
$responseValidate_job_schedule = $jobMYSQL->validate_job_schedule($jobMYSQL->get_jobs());