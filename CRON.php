<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');
require_once 'MyWorker.php';
require_once 'Mywork.php';
require_once 'MyDataProvider.php';
ignore_user_abort(true);


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
            sleep(2);
            if ($responseRunAndCheck !== null) {
                    $DAWS = new RabbitMqSendMessageDAWS();
                   $responseDAWS = $DAWS->Connect($this->timetasklogstart,$this->IDOperators);
                   $this->logtext($responseDAWS);
                }
                      sleep(2);
               if(!empty($responseDAWS)) {
                   $this->UpdateOperStreams();
               }
    }

}
$a = new CRON();
$a->index();
?>