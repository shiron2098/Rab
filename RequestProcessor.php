<?php

use app\WorkerReceiver1;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/VendmaxAndNayaxAndConnect/VendmaxCommandsToProvider.php';
require_once __DIR__ . '/Worker/WorkerReceiver1.php';
require_once __DIR__ . '/VendmaxAndNayaxAndConnect/VendmaxCommandsToProvider.php';
require_once __DIR__ . '/VendmaxAndNayaxAndConnect/NayaxCommandsToProvider.php';
require_once __DIR__ . '/VendmaxAndNayaxAndConnect/DbConnectProvider.php';
/*require_once('Worker/RabbitMqSendMessageConnect.php');*/

class RequestProcessor extends WorkerReceiver1
{

    const NameFile = "data";


    protected $responseDATAMYSQL;
    protected $responseJson;
    private $responseData;
    private $command;
    public $idcolumnjob;
    public $bolleanUpdateStreams = false;
    public $bool = 0;
    public function read_job_from_queue($Operator)
    {
        if(!empty($Operator) && isset($Operator)) {
            $WorkerOfDb = new WorkerReceiver1();
            $responseOfRabbit = $WorkerOfDb->Index($Operator,$this->bool);

            if (!empty($responseOfRabbit)) {
                foreach ($responseOfRabbit as $array) {

                    $response = null;
                    $this->responseData = null;
                    $this->responseJson = json_decode($array);
                    $this->idcolumnjob = $this->responseJson->IdColumnJobHistory;
                    $this->execute_job($this->responseJson);
                }
            } else {
                $text = '$Operator variable data null #' . $Operator['id'];
                Log::logtext($text);
                return $this->UpdateOperStreams($Operator['id'],$this->bool);
            }
        }

        if($this->bolleanUpdateStreams === true) {
            $this->UpdateOperStreams($Operator['id'],$this->bool);
        } else {
            $text = 'successfully finish processing' . $Operator['id'];
            Log::logtext($text);
        }
    }
    public function execute_job($json)
    {
        try {
            $this->responseJson = $json;
            if (!empty($this->responseJson) && isset($this->responseJson)) {
                $this->responseDATAMYSQL = $this->DataFromOperators($this->responseJson->Code->operatorid);
                switch ($this->responseJson->Code->software_provider) {
                    case 'Vendmax':
                        $this->command = new VendmaxCommands($this->responseJson->Code->Jobsid, $this->responseJson->Code->operatorid, $this->responseJson->Code->command, $this->responseJson->Code->software_provider, $this->responseDATAMYSQL['name']);
                        break;

                    case 'Nayax':
                        $this->command = new NayaxCommands($this->responseJson->Code->Jobsid, $this->responseJson->Code->operatorid, $this->responseJson->Code->command, $this->responseJson->Code->software_provider);
                        break;
                }


                switch ($this->responseJson->Code->command) {
                    case 'get_products':
                        $this->responseData =  $this->command->get_products();
                        break;

                    case 'get_customers':
                        $this->responseData =  $this->command->get_customers();
                        break;

                    case 'get_points_of_sale':
                        $this->responseData =  $this->command->get_pointsofsale();
                        break;
                    case 'get_locations':
                        $this->responseData =  $this->command->get_locations();
                        break;
                    case 'get_non_vending_equipment':
                        $this->responseData =  $this->command->get_non_vending_equipment();
                        break;
                    case 'get_equipment':
                        $this->responseData =  $this->command->get_equipment();
                        break;
                    case 'get_items':
                        $this->responseData =  $this->command->get_items();
                        break;
                    case 'get_pos_bi':
                        $this->responseData =  $this->command->get_t2s_exportPos();
                        break;
                    case 'get_pro_bi':
                        $this->responseData =  $this->command->get_t2s_exportPRO();
                        break;
                    case 'get_vvs_bi':
                        $this->responseData =  $this->command->get_t2s_exportVisits();
                        break;
                    case 'export_pro_wbstore':
                        $this->responseData =  $this->command->get_t2s_export_pro_wbstore();
                        break;
                    case 'export_pos_wbstore':
                        $this->responseData =  $this->command->get_t2s_export_pos_wbstore();
                        break;

                }
                $this->save_result_to_queue($this->responseData);

            }else{
                $text = 'Connector is not responding';
                throw new Exception($text);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logDB($this->responseJson->Code->Jobsid, $this->time(), self::statusERROR,$e->getMessage());
            $this->logtext($e->getMessage());
            }
    }

    public function save_result_to_queue($response)
    {
        if (!empty($response) && isset($response)) {
            $_SESSION['Check'] = false;
            $this->TextOK = $text = '[Job id #' . $this->responseJson->Code->Jobsid . ']' . 'Result was delivered to Data queue successfully.';
            $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
            $this->CreateExchange(self::exchange, self::type);
            $this->CreateQueue(self::NameConfigDAWS .$this->responseDATAMYSQL['operatorid'], false, false, false, $this->responseDATAMYSQL['name'], false);
            $this->MessageOut($response, $this->responseJson);
            $this->logtext($text);
            $this->update_to_history($this->TextOK);
            $this->bolleanUpdateStreams = true;
        } else {
            $text = '[Job id #' . $this->responseJson->Code->Jobsid . ']' . 'Provider returned no data';
            $this->logDB($this->responseJson->Code->Jobsid, $this->time(), self::statusERROR, $text);
            $this->logtext($text);
            $this->bolleanUpdateStreams = true;
        }
    }
    public function update_to_history($text)
    {
        $responseLOG = $this->logDB($this->responseJson->Code->Jobsid, $this->time(), self::statusOK, $text);
        if (!empty($responseLOG)) {
            $results = print_r($responseLOG, true);
            if (!empty($results)) {
                $responseTableDate = $this->UpdateJobs();
                $this->logtext($responseTableDate);
                return $responseTableDate;

            }
        }
        $text ='Error update job_history';
        return $text;
    }
}
/*$start = new RequestProcessor();
$start->read_job_from_queue();*/