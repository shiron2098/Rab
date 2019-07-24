<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');
require_once 'MyWorker.php';
require_once 'Mywork.php';
require_once 'MyDataProvider.php';


class CRON extends CreateTask
{

    public function index()
    {
/*                     $this->TointoSoftware_providers();
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
               if ($responseRunAndCheck !== null) {
                 /*  sleep(2);*/
                    $DAWS = new RabbitMqSendMessageDAWS();
                    $DAWS->Connect($this->timetasklogstart,$this->IDOperators);
                }
        $this->UpdateOperStreams();
    }

}
$a = new CRON();
$a->index();
?>