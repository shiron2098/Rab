<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('Worker/WorkerReceiver1.php');
require_once('VendmaxAndNayaxAndConnect/DbConnectProvider.php');
require_once('VendmaxAndNayaxAndConnect/VendmaxCommandsToProvider.php');
require_once('VendmaxAndNayaxAndConnect/NayaxCommandsToProvider.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\WorkerReceiver1;

/*'SELECT pro.code,pro.description FROM Products pro'
'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2'*/

class RabbitMqSendMessageConnect extends WorkerReceiver1
{
    const NameFile = "data";


    public $responseDATAMYSQL;
    public $responseJson;
    protected $DataOperators;
    public  $idcolumnjob;

    public function Connect($idcolumnjob_history,$id)
    {
        if (!empty($idcolumnjob_history) && isset($idcolumnjob_history)) {
            $WorkerOfDb = new WorkerReceiver1();
            $this->idcolumnjob = $idcolumnjob_history;
            $responseOfRabbit = $WorkerOfDb->Index($id);
            if (!empty($responseOfRabbit)) {
                try {
                        foreach ($responseOfRabbit as $array) {
                            $response = null;
                            $this->responseJson = json_decode($array);
                            if (!empty($this->responseJson) && isset($this->responseJson)) {
                                $this->responseDATAMYSQL = $this->DataFromOperators($this->responseJson->Code->operatorid);
								switch($this->responseJson->Code->software_provider){
									case 'Vendmax':
											$commands = new VendmaxCommands($this->responseJson->Code->Jobsid,$this->responseJson->Code->operatorid,$this->responseJson->Code->command,$this->responseJson->Code->software_provider,$this->responseDATAMYSQL['name']);
                                            break;

                                    case 'Nayax':
                                            $commands = new NayaxCommands($this->responseJson->Code->Jobsid,$this->responseJson->Code->operatorid,$this->responseJson->Code->command,$this->responseJson->Code->software_provider);
                                            break;
								}

                                
                                switch($this->responseJson->Code->command){
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


                                if (!empty($response) && isset($response)) {
                                    $_SESSION['Check'] = false;
                                    $this->TextOK = $text = '[Job id #' . $this->DataOperators['Jobsid'] . ']' . 'Result was delivered to Data queue successfully.';
                                    $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
                                    $this->CreateExchange(self::exchange, self::type);
                                    $this->CreateQueue(self::NameConfigDAWS . $this->responseDATAMYSQL['operatorid'], false, false, false, $this->responseDATAMYSQL['name'], false);
                                    $this->MessageOut($response, $this->responseJson);
                                    $this->logtext($text);
                                } else {
                                    $text = '[Job id #' . $this->responseJson->Code->Jobsid . ']' . 'Provider returned no data';
                                    $this->logDB($this->responseJson->Code->Jobsid, $this->time(), self::statusERROR,$text);
                                    $this->logtext($text);
                                }
                            } else {
                                $text = 'MYSQL is not responding';
                                throw new Exception($text);
                            }
                        }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logDB($this->responseJson->Code->Jobsid, $this->time(), self::statusERROR,$e->getMessage());
                    $this->logtext($e->getMessage());
                }
            }else{
                return null;
            }
        }
        return $text;
    }
}
?>
