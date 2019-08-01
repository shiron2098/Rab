<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');
ignore_user_abort(true);


class Inception extends CreateTask
{

    public function Main()
    {
/*                     $this->TointoSoftware_providers();
                        $this->Tointo();
                        $this->TointoCommands();
                        if($this->commands !== '0' && $this->commands !== null) {
                            $this->TointoJob();
                            $this->TointoJob_Schedule();
                        }*/

    $data = $this->SelectToDbOperators();
    $response = $this->JobsOperators($data);
    $responseRunAndCheck = $this->RunAndCheck($response);
           if (!empty($responseRunAndCheck)) {
                    $DAWS = new RabbitMqSendMessageDAWS();
                   $responseDAWS = $DAWS->Connect($this->idcolumnjob,$this->IDOperators);
                   $this->logtext($responseDAWS);
                }else{
               $this->UpdateOperStreams();
           }
        if(!empty($responseDAWS)) {
            $this->UpdateOperStreams();
        }
    }

}
$a = new Inception();
$a->Main();
?>