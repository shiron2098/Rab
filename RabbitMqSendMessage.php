<?php
namespace app\RabbitMqSendMessage;
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMqSendMessage
{
     const NameFile = "data";

     protected $channel;
     protected $connection;
     protected $ReponseFromMessage;
     protected $queue;
     protected $Exchange;
     protected $routing_key;



    public function AMQPConnect($host,$port,$username,$password,$vhost)
    {
        $connection = new AMQPStreamConnection($host, $port, $username,$password,$vhost);
        $channel = $connection->channel();
        $this->channel = $channel;
        $this->connection = $connection;

    }

    public function CreateExchange($Exchange,$type,$queue,$routing_key)
    {
        $this->queue =$queue;
        $this->routing_key = $routing_key;
        $this->Exchange = $Exchange;
        return $this->channel->exchange_declare($Exchange,$type,false,false,false);
    }
    public function CreateQueue()
    {
       $this->channel->queue_declare($this->queue,false,true,false,false);
        $this->channel->queue_bind($this->queue,$this->Exchange, $this->routing_key);
    }

    /** @return array for the message */
    public function MassivMessageTODAWS($ResponseToDb)
    {
        if(isset($ResponseToDb) && !empty($ResponseToDb)){
            if($ResponseToDb['ToMessage'] === 0){
                return $this->ReponseFromMessage;

            }
            else if($ResponseToDb['ToMessage'] === 1){
                $filaname = rabbitMqSendMessage::NameFile;
                $Read = file_get_contents($filaname);
                $file=[
                    'timestamp' => $ResponseToDb['timestamp'],
                    'code' => $Read,
                ];
                unlink($filaname);
                return $file;
            }
        }
    }
    public function MessageToArrayTODAWS($massivData){
        $messageBody = json_encode([
            'Timestamp read from DAWS' => date('d.m.Y H:i:s',$massivData['timestamp']),
            'timestamp sent to Rabbit' =>date('d.m.Y H:i:s',strtotime('now')),
            'Code' => $massivData['code'],
        ]);
        return $messageBody;
    }

    public function MessageOut($ResponseToDb)
    {
        $this->ReponseFromMessage = $ResponseToDb;
        $responseOfMassivMessage =  $this->MassivMessageTODAWS($this->ReponseFromMessage);
        $msg = new AMQPMessage($this->MessageToArrayTODAWS($responseOfMassivMessage),array('content_type' => 'text/json',
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($msg,$this->Exchange,$this->routing_key);
        $this->channel->close();
        $this->connection->close();

    }
/*    public function process(AMQPMessage $msg)
    {

        $this->generatePdf()->sendEmail();
    }*/



}