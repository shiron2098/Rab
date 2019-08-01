<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('WorkerandsendRabbitDAWS/WorkerReceiver1.php');
require_once('VendmaxAndNayaxAndConnectDAWS/DbConnectToDAWS.php');
require_once('VendmaxAndNayaxAndConnectDAWS/VendmaxCommands.php');
require_once('VendmaxAndNayaxAndConnectDAWS/NayaxCommands.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\WorkerReceiver1;

/*'SELECT pro.code,pro.description FROM Products pro'
'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2'*/

class RabbitMqSendMessageDAWS extends WorkerReceiver1
{
    const NameFile = "data";

    protected $DataOperators;
    private  $DataSql;
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
                            $responseJson = json_decode($array);
                            if (!empty($responseJson) && isset($responseJson)) {
                                $responseDATAMYSQL = $this->DataFromOperators($responseJson->Code->operatorid);
								switch($responseJson->Code->software_provider){
									case 'Vendmax':
											$commands = new VendmaxCommands($responseJson->Code->Jobsid,$responseJson->Code->operatorid,$responseJson->Code->command,$responseJson->Code->software_provider);
                                            break;

                                    case 'Nayax':
                                            $commands = new NayaxCommands($responseJson->Code->Jobsid,$responseJson->Code->operatorid,$responseJson->Code->command,$responseJson->Code->software_provider);
                                            break;
								}

                                
                                switch($responseJson->Code->command){
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


                                /*sleep(7);*/
                                if (!empty($response) && isset($response)) {
                                    $_SESSION['Zapros'] = false;
                                    $this->TextOK = $text = '[Job id #' . $this->DataOperators['Jobsid'] . ']' . 'Result was delivered to Data queue successfully.';
                                    $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
                                    $this->CreateExchange(self::exchange, self::type);
                                    $this->CreateQueue(self::NameConfigDAWS . $responseDATAMYSQL['operatorid'], false, false, false, $responseDATAMYSQL['name'], false);
                                    $this->MessageOut($response, $responseJson);
                                    $this->logtext($text);
                                } else {
                                    $text = '[Job id #' . $responseJson->Code->Jobsid . ']' . 'Provider returned no data';
                                    $this->logDB($responseJson->Code->Jobsid, $this->time(), self::statusERROR,$text);
                                    $this->logtext($text);
                                }
                            } else {
                                $text = 'MYSQL is not responding';
                                $this->logDB($responseJson->Code->Jobsid, $this->time(), self::statusERROR,$text);
                                throw new Exception($text);
                            }
                        }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logDB($responseJson->Code->Jobsid, $this->time(), self::statusERROR,$e->getMessage());
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
