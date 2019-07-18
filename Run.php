<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');

class Run extends CreateTask
{

    public function index()
    {
/*        $this->TointoSoftware_providers();
        $this->Tointo();
        $this->TointoCommands();
        if($this->commands !== '0' && $this->commands !== null) {
            $this->TointoCommandDetails();
            $this->TointoJob();
            $this->TointoJob_Schedule();
        }*/
      $data = $this->SelectToDbOperators();
        $response = $this->JobsOperators($data);
        $responseRunAndCheck = $this->RunAndCheck($response);
       if($responseRunAndCheck !== null) {
            $DAWS = new RabbitMqSendMessageDAWS();
            $DAWS->Connect($this->timetasklogstart);
        }

    }

}
$a = new Run();
$a->index();
?>