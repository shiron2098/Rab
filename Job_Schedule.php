<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('Inception.php');
require_once('RabbitMqSendMessageDAWS.php');
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
       $this->CheckDataAndSendMessage($DataResponseRunAndCheck);

    }
}
$this->validate_job_schedule($this->get_jobs());
