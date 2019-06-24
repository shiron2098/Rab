<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
spl_autoload('DbConnectToDAWS');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMqSendMessageDAWS extends Rabbimq
{
    const NameFile = "data";




    public function Connect(){
        $WorkerOfDb = new \app\WorkerReceiver1();
        $responseOfMYSQL = $WorkerOfDb->Index();
       if(!empty($responseOfMYSQL)&& isset($responseOfMYSQL)) {
           $DAWS = new DbConnectToDAWS($responseOfMYSQL->Code->SQL_Zapros,$responseOfMYSQL->Code->connection_string);
           $response = $DAWS->ResponseOfDbToLogFile();
           $rabbi=new RabbitMqSendMessageDAWS();
           $rabbi->AMQPConnect('localhost', '5672', 'Shiro', '1995', '/');
           $rabbi->CreateExchange($responseOfMYSQL->Code->operatorname, 'direct');
           $rabbi->CreateQueue($responseOfMYSQL->Code->operatorname, false, true, false, 'oper23', false);
           try {
               $rabbi->CheckRabbit();
               if ($_SESSION['Zapros'] !== true)
               {
                   $rabbi->MessageOut($response);
                   echo 'message delivery is complete DAWS';
               }
               else
               {

                   throw new Exception('error download into rabbit because the message exists DAWS');

               }
           }catch (Exception $e) {
               echo $e->getMessage();
           }
       }
    }
    /*    public function AMQPConnect($host,$port,$username,$password,$vhost)
        {+
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
        }*/

    /** @return array for the message */
    /*    public function MassivMessageTODAWS($ResponseToDb)
        {
            if(isset($ResponseToDb) && !empty($ResponseToDb)){
                if($ResponseToDb['ToMessage'] === 0){
                    return $this->ReponseFromMessage;

                }
                else if($ResponseToDb['ToMessage'] === 1){
                    $filaname = RabbitMqSendMessageDAWS::NameFile;
                    $Read = file_get_contents($filaname);
                    $file=[
                        'timestamp' => $ResponseToDb['timestamp'],
                        'code' => $Read,
                    ];
                    unlink($filaname);
                    return $file;
                }
            }
        }*/

    /*    public function MessageOut($ResponseToDb)
        {
            if(isset($ResponseToDb) && empty($ResponseToDb)) {
                $this->Connect();
                $this->ReponseFromMessage = $ResponseToDb;
            }
            $responseOfMassivMessage =  $this->MessageToArray($this->ReponseFromMessage);
            $msg = new AMQPMessage($this->MessageToArray($responseOfMassivMessage),array('content_type' => 'text/json',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $this->channel->basic_publish($msg,$this->Exchange,$this->routing_key);
            $this->channel->close();
            $this->connection->close();

        }*/
    /*    public function process(AMQPMessage $msg)
        {

            $this->generatePdf()->sendEmail();
        }*/

}