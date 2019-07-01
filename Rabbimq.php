<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('MysqlDbConnect');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


abstract class Rabbimq
{
    const PASSIVETrue = TRUE;
    const passivefalse = False;
    const host = 'localhost';
    const user = 'root';
    const password = '';
    const database = 'daws';
    const logfile = 'file.log';
    protected $Quire;
    protected $channel;
    protected $connection;
    protected $ReponseFromMessage;
    protected $queue;
    protected $Exchange;
    protected $routing_key;



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
            if (!empty($massivData)) {
                $messageBody = json_encode([
                    'Timestamp read from DAWS' => date('d.m.Y H:i:s', $massivData['TimeTask']),
                    'timestamp sent to Rabbit' => date('d.m.Y H:i:s', strtotime('now')),
                    'Code' => $massivData,
                ]);
                return $messageBody;
            } else {
                throw new Exception('Error message from DAWS');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }

    public function MessageOut($ResponseToDb)
    {

        if (isset($_SESSION['FileZip']) && !empty($_SESSION['FileZip']) === true) {
            $ReponseFromMessage = $this->MassivMessageTODAWS($ResponseToDb);
            if(!empty($ReponseFromMessage)) {
                $msg = new AMQPMessage($this->MessageToArray($ReponseFromMessage), array('content_type' => 'text/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                $this->channel->basic_publish($msg, $this->Exchange, $this->routing_key);
                $this->channel->close();
                $this->connection->close();
            }
        } else {

            $msg = new AMQPMessage($this->MessageToArray($ResponseToDb), array('content_type' => 'text/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $this->channel->basic_publish($msg, $this->Exchange, $this->routing_key);
            $this->channel->close();
            $this->connection->close();
        }
    }



    public function MassivMessageTODAWS($ResponseToDb)
    {
        try {
            if (isset($ResponseToDb) && !empty($ResponseToDb)) {
                if ($ResponseToDb['ToMessage'] === 0) {
                    return $this->ReponseFromMessage;

                } else if ($ResponseToDb['ToMessage'] === 1) {
                    if (file_exists(RabbitMqSendMessageDAWS::NameFile)) {
                        $filaname = RabbitMqSendMessageDAWS::NameFile;
                        $Read = file_get_contents($filaname);
                        $file = [
                            'timestamp' => $ResponseToDb['timestamp'],
                            'code' => $Read,
                        ];
                        unlink($filaname);
                        return $file;
                    } else {
                        throw new Exception('File not create in Vendmax');
                    }
                }
            }
        }catch(Exception $e ){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    public function CheckRabbit(){
        $this->channel->queue_declare($this->queue, false, true, false, false);
        $result = $this->channel->basic_get($this->queue);

        if(empty($result)){
            $_SESSION['Zapros'] = False;
        }
        else
        {
            $_SESSION['Zapros'] = true;
        }
    }
    public function log ($text){
        file_put_contents(Rabbimq::logfile,date('Y-m-d H:i:s', strtotime('now')) ." ". $text . PHP_EOL,FILE_APPEND);
    }



}