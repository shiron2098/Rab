<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('CreateOperator/CreateTask.php');

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