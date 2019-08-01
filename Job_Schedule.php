<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
require_once('Other/Inception.php');
require_once('WorkerandsendRabbit/RabbitMqSendMessageConnect.php');
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
      $DataResponseRunAndCheck = $this->RunAndCheck($response);
       $DataCheckDataAndSendMessage = $this->CheckDataAndSendMessage($DataResponseRunAndCheck);
       return $DataCheckDataAndSendMessage;
    }
    public function create_operator_queue($response){
       $responseSendAndCheckMessageMYSQL = $this->SendAndCheckMessageMYSQL($response);
        $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
        $this->CreateExchange(self::exchange, self::type);
        $this->CreateQueue(self::NameConfig . $this->IDOperators, false, false, false, $this->DataOperators['code'], false);
        return $responseSendAndCheckMessageMYSQL;
    }
    public function send_job_to_queue($ResponseMySQL){
        $this->MessageOut($ResponseMySQL,$this->IDJobs);
        $this->TextOK = $text = '[Job id #' . $this->IDJobs . ']' . 'Result was delivered to Data queue successfully.';
        $this->logtext($text);
        return $text;
    }
}
$jobMYSQL = new Job_Schedule();
$responseValidate_job_schedule = $jobMYSQL->validate_job_schedule($jobMYSQL->get_jobs());
$responsecreate_operator_queue = $jobMYSQL->create_operator_queue($responseValidate_job_schedule);
$jobMYSQL->send_job_to_queue($responsecreate_operator_queue);

$a = $jobMYSQL->idcolumnjob;

$jobDAWS = new RequestProcessor();
$responseread_job_from_queue = $jobDAWS->read_job_from_queue($jobMYSQL->idcolumnjob,$jobMYSQL->IDOperators);
$response_execute_job = $jobDAWS->execute_job($responseread_job_from_queue);
$jobDAWS->save_result_to_queue($response_execute_job);
$responseupdate_to_history = $jobDAWS->update_to_history();
if(!empty($responseupdate_to_history)){
    $jobMYSQL->UpdateOperStreams();
}
