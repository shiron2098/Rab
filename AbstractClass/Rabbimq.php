<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../log/class/Log.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


abstract class Rabbimq extends Log
{
    const Time = 'now';
    const hostrabbit = 'localhost';
    const port = '5672';
    const username = 'shir';
    const passwordrabbit = '1995';
    const vhost = '/';
    const exchange = 'Type';
    const type = 'direct';
    const NameConfig = 'JobOperator#';
    const NameConfigDAWS = 'ResponseOperator#';


    protected $TextOK;
    private $channel;
    private $connection;
    private $queue;
    private $Exchange;
    private $routing_key;
    private $int;
    private $idJobHistory;
    protected $jsonresponse;
    protected $IDOperators;
    protected $IDJobs;
    protected $IDJob_Scheduler;
    protected $checktrabbitmsg;


    public function AMQPConnect($host, $port, $username, $password, $vhost)
    {
        $connection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);
        $channel = $connection->channel();
        $this->channel = $channel;
        $this->connection = $connection;




    }

    public function CreateExchange($Exchange, $type)
    {
        $this->Exchange = $Exchange;
        return $this->channel->exchange_declare($Exchange, $type, false, false, false);
    }

    public function CreateQueue($queen, $passive, $durable, $exclusive, $routing_key, $Auto_Delete)
    {
        $this->routing_key = $routing_key;
        $this->queue = $queen;
        $this->channel->queue_declare($this->queue, $passive, $durable, $exclusive, $Auto_Delete);
        $this->channel->queue_bind($this->queue,$this->Exchange,$this->routing_key);
    }
    public function MessageToArray($massivData)
    {
        try {
            if (!empty($massivData)&& !empty($massivData['time'])&& isset($massivData['time'])) {
                $messageBody = json_encode([
                    'Timestamp read from MYSQL' => date('d.m.Y H:i:s' , $massivData['time'] ),
                    'timestamp sent to Rabbit' => date('d.m.Y H:i:s', strtotime('now')),
                    'Code' => $massivData['code'],
                    'IdColumnJobHistory' => $massivData['idcolumnjob'],
                ]);
                return $messageBody;
            } else {
                throw new Exception('Error message from RabbitMYSQL');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }

    /////////////////////////////
    public function MessageToDaws($Massiv,$dataOperators){
        try {
            if (!empty($Massiv['timestamp'])&& isset($Massiv['timestamp']) && !empty($dataOperators) && isset($dataOperators)) {
                $messageBody = json_encode([
                    'Timestamp read from DAWS' => date('d.m.Y H:i:s',$Massiv['timestamp']),
                    'timestamp sent to Rabbit' => date('d.m.Y H:i:s', strtotime('now')),
                    'Code' => [
                        'operatorid' => $dataOperators->Code->operatorid,
                        'jobsid' => $dataOperators->Code->Jobsid,
                        'command' => $dataOperators->Code->command,
                        'software_provider' => $dataOperators->Code->software_provider,
                        'filepath' => $Massiv['code'],
                        ]
                ]);
                return $messageBody;
            } else {
                throw new Exception('Error message from DAWSRabbit');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    /////////////////////////////
    public function MessageOut($ResponseToDb,$data)
    {
        if (isset($_SESSION['FileZip']) && !empty($_SESSION['FileZip']) === true || isset($ResponseToDb['timestamp']) && !empty($ResponseToDb['timestamp'])) {
            $ReponseFromMessage = $this->MassivMessageTODAWS($ResponseToDb);
            if(!empty($ReponseFromMessage)) {
                $msg = new AMQPMessage($this->MessageToDaws($ReponseFromMessage,$data), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                $this->channel->basic_publish($msg, $this->Exchange, $this->routing_key);
              /*      $responseLOG = $this->logDB($data->Code->Jobsid, $this->time(), self::statusOK,$this->TextOK);*/
                $this->channel->close();
                $this->connection->close();
/*                if(!empty($responseLOG)) {
                    $results = print_r($responseLOG, true);
                    if (!empty($results)) {
                        $responseTableDate = $this->UpdateJobs();
                        $this->logtext($responseTableDate);


                        return $responseTableDate;
                    }
                }*/


            }
        } else {
            $msg = new AMQPMessage($this->MessageToArray($ResponseToDb), array('content_type' => 'text/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $this->channel->basic_publish($msg, $this->Exchange, $this->routing_key);
            $this->channel->close();
            $this->connection->close();
        }

    }
    public function UpdateOperStreams($id,$bool){
        if(isset($bool) && !empty($bool) == 0) {
            $result = mysqli_query(
                $this->linkConnect,
                "UPDATE operators SET streams = 0 WHERE id=$id"
            );
        }else{
            $result = mysqli_query(
                $this->linkConnect,
                "UPDATE operators SET streams_response = 0 WHERE id=$id"
            );
        }
        if($result !== false){
            $text = 'Update to complete streams 0 #' . $id;
            $this->logtext($text);
        }else{
            $text = 'Update error streams';
            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
        }
    }
    public function UpdateOperStreamsUp($idstreams,$idoper,$configid){
        if(!empty($idstreams) && isset($idstreams)&& !empty($idstreams)&& isset($idoper)) {
            if(isset($configid)&& !empty($configid) == 0) {
                $result = mysqli_query(
                    $this->linkConnect,
                    "UPDATE operators SET streams = $idstreams WHERE id=$idoper"
                );
                $text = 'Update to complete streams #' . $idstreams . 'in #' . $idoper;
            }else{
                $result = mysqli_query(
                    $this->linkConnect,
                    "UPDATE operators SET streams_response = $idstreams WHERE id=$idoper"
                );
                $text = 'Update to complete streams_response #' . $idstreams . 'in #' . $idoper;
            }
                if ($result !== false) {
                    $this->logtext($text);
                } else {
                    $text = 'update error streams' . $idoper;
                    $this->logDB($this->IDJobs, $this->time(), self::statusERROR, $text);
                }
        }
    }

    public function MassivMessageTODAWS($ResponseToDb)
    {
        try {
            if (isset($ResponseToDb) && !empty($ResponseToDb)) {
                if ($ResponseToDb['ToMessage'] === 0) {
                    return $ResponseToDb;

                } else if ($ResponseToDb['ToMessage'] === 1) {
                    if(!empty($ResponseToDb['PathToFile']) && $ResponseToDb['PathToFile'] !== 1) {
                        if (file_exists($ResponseToDb['PathToFile'])) {
                            $filaname = $ResponseToDb['PathToFile'] . RequestProcessor::NameFile;
                            $this->logtext(__DIR__ . '/' . $ResponseToDb['PathToFile']);
                           $perem = Date('Y-m-d H:i:s', time());
                            $renameFile = rename($filaname,$ResponseToDb['PathToFile'] . RequestProcessor::NameFile . $perem);
                            $renameFile = $ResponseToDb['PathToFile'] . RequestProcessor::NameFile . $perem;
                            $arrayResponseFromProvider = [
                                'timestamp' => $ResponseToDb['timestamp'],
                                'code' => $renameFile,
                            ];
/*                            unlink($filaname);
                            rmdir(__DIR__ . '/' . $ResponseToDb['PathToFile']);*/
                            return $arrayResponseFromProvider;
                        } else {
                            $text = '[Job id #' . $this->IDJobs . ']'. 'Result data unpack failed ';
                            $this->logDB($this->IDJobs, $this->time(), self::statusERROR, $text);
                            throw new Exception($text);


                        }
                    }
                }
            }
        }catch(Exception $e ){
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }
    public function CheckRabbit($Mysql)
    {

       $this->DataOperators= $this->DataFromOperators($this->IDOperators);
        if(!empty($this->DataOperators['operatorid'])) {
            $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit,self::vhost);
            $this->CreateExchange(self::exchange, self::type);
            $this->CreateQueue(self::NameConfig . $this->DataOperators['operatorid'], false, false, false, $this->DataOperators['code'], false);
            /*$this->channel->queue_declare($this->queue, false, false, false, false);*/
/*            foreach ($this->channel->basic_get($this->queue) as $res) {
                   print_r($res);
            }*/$this->int=0;
                while($this->int === 0) {
                    $this->checktrabbitmsg = $result = $this->channel->basic_get($this->queue);
                    if (!empty($result->body)) {
                        $this->jsonresponse = json_decode($result->body);
                        if (!empty($Mysql['code']['command']) && !empty($Mysql['code']['Jobsid']) && !empty($this->jsonresponse->Code->command) && !empty($this->jsonresponse->Code->Jobsid)) {
                            if ($this->jsonresponse->Code->command !== $Mysql['code']['command'] && $this->jsonresponse->Code->Jobsid !== $Mysql['code']['Jobsid']) {
                                if (empty($this->jsonresponse)) {
                                    $this->channel->close();
                                    $this->connection->close();
                                    return $_SESSION['Check'] = false;
                                }
                                $_SESSION['Check'] = false;
                            } else {
                                $_SESSION['Check'] = true;
                                $this->int = 1;
                                $this->channel->close();
                                $this->connection->close();

                            }
                        }else{
                            $text= 'MYSQL code and command null or Rabbitmq Body code and command null';
                            $this->logtext($text);
                            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                            return $_SESSION['Check']= true;
                        }
                    }else{
                        $this->channel->close();
                        $this->connection->close();
                        return $_SESSION['Check'] = false;
                    }
                }


/*                if (isset($_SESSION['Check']) === true) {
                    return $_SESSION['Check'];
                }*/
            }else{
            $text= 'Operator not found';
            $this->logtext($text);
            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
        }
    }
    protected function SearchRepeat($row)
    {
        $file = self::FileRepeatToTask; // file
        $string = trim($row . PHP_EOL); // string for write
        $data_file = file($file); // Read data from file

        // Function from application to array
/*        function my_trim(&$item)
        {
            $item = trim($item);
        }*/

        // We remove spaces around the edges of the record
        /*array_walk($data_file, "my_trim");*/

        // See if there is such a record
        if (!in_array($row, $data_file)) {
            $_SESSION['String']= false;

        } else {
            $_SESSION['String'] = true;
        }
    }



}