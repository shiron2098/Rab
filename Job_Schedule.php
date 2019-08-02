<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('Other/Inception.php');
require_once('RequestProcessor.php');
ignore_user_abort(true);

class Job_Schedule extends Inception
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
sleep(5);
$jobDAWS = new RequestProcessor();
$responseread_job_from_queue = $jobDAWS->read_job_from_queue($jobMYSQL->idcolumnjob,$jobMYSQL->IDOperators);
sleep(2);
if($responseread_job_from_queue == true){
    $jobMYSQL->UpdateOperStreams();
}