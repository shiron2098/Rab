<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('Inception.php');
require_once('WorkerandsendRabbitDAWS/RabbitMqSendMessageDAWS.php');
ignore_user_abort(true);

class Job_Schedule extends Inception
{
    public function get_jobs(){
        $data = $this->SelectToDbOperators();
        $response = $this->JobsOperators($data);
        return $response;
    }
    public function validate_job_schedule($response){
      $DataResponseRunAndCheck = $this->RunAndCheck($response);
       $DataCheckDataAndSendMessage = $this->CheckDataAndSendMessage($DataResponseRunAndCheck);
        $this->SendAndCheckMessageMYSQL($DataCheckDataAndSendMessage);
    }
    public function create_operator_queue(){

    }
}
$job = new Job_Schedule();
$job->validate_job_schedule($job->get_jobs());
