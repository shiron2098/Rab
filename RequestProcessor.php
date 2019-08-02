<?php

use app\WorkerReceiver1;

require_once __DIR__ . '/vendor/autoload.php';
require_once('Other/Inception.php');
require_once('Worker/WorkerReceiver1.php');
require_once ('VendmaxAndNayaxAndConnect/VendmaxCommandsToProvider.php');
require_once('VendmaxAndNayaxAndConnect/NayaxCommandsToProvider.php');
require_once('VendmaxAndNayaxAndConnect/DbConnectProvider.php');
/*require_once('Worker/RabbitMqSendMessageConnect.php');*/

class RequestProcessor extends WorkerReceiver1
{

    const NameFile = "data";


    public $responseDATAMYSQL;
    public $responseJson;
    public $bolleanUpdateStreams = false;
    public function read_job_from_queue($idcolumnjob_history,$id)
    {
        if (!empty($idcolumnjob_history) && isset($idcolumnjob_history)) {
            $WorkerOfDb = new WorkerReceiver1();
            $this->idcolumnjob = $idcolumnjob_history;
            $responseOfRabbit = $WorkerOfDb->Index($id);
            if (!empty($responseOfRabbit)) {
                foreach ($responseOfRabbit as $array) {
                    $response = null;
                    $this->responseJson = json_decode($array);
                   $this->execute_job($this->responseJson);
                }
            }else{
                return $this->bolleanUpdateStreams;
            }

        }
        return $this->bolleanUpdateStreams;
    }
    public function execute_job($json)
    {
        try {
            $this->responseJson = $json;
            if (!empty($this->responseJson) && isset($this->responseJson)) {
                $this->responseDATAMYSQL = $this->DataFromOperators($this->responseJson->Code->operatorid);
                switch ($this->responseJson->Code->software_provider) {
                    case 'Vendmax':
                        $commands = new VendmaxCommands($this->responseJson->Code->Jobsid, $this->responseJson->Code->operatorid, $this->responseJson->Code->command, $this->responseJson->Code->software_provider, $this->responseDATAMYSQL['name']);
                        break;

                    case 'Nayax':
                        $commands = new NayaxCommands($this->responseJson->Code->Jobsid, $this->responseJson->Code->operatorid, $this->responseJson->Code->command, $this->responseJson->Code->software_provider);
                        break;
                }


                switch ($this->responseJson->Code->command) {
                    case 'get_products':
                        $response = $commands->get_products();
                        break;

                    case 'get_customers':
                        $response = $commands->get_customers();
                        break;

                    case 'get_points_of_sale':
                        $response = $commands->get_pointsofsale();
                        break;
                    case 'get_locations':
                        $response = $commands->get_locations();
                }
                $this->save_result_to_queue($response);

            }else{
                $text = 'MYSQL is not responding';
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