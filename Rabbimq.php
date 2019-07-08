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
    const logfile = '/file.log';
    const FileRepeatToTask =  __DIR__ . '/Repeat.log';

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
                    'Timestamp read from DAWS' => date('d.m.Y H:i:s'),
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
        print_r($ResponseToDb);
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
    public function CheckRabbit()
    {
        $this->AMQPConnect('localhost','5672','Shiro','1995','/');
        $this->CreateExchange('Type','direct');
        $this->CreateQueue('Operator24',false, false ,false,'operator333',false);
        /*$this->channel->queue_declare($this->queue, false, false, false, false);*/
        $result = $this->channel->basic_get($this->queue);
        $this->channel->close();
        $this->connection->close();
        if (isset($_SESSION['Zapros']) === true) {
            return $_SESSION['Zapros'];
        }
        if (empty($result->body)) {

            $_SESSION['Zapros'] = False;
        } else {
            $_SESSION['Zapros'] = true;
        }
    }
    public function log ($text){
        file_put_contents(__DIR__ . Rabbimq::logfile,date('Y-m-d H:i:s', strtotime('now')) ." ". $text . PHP_EOL,FILE_APPEND);
    }
    protected function SearchRepeat($row)
    {
        $file = self::FileRepeatToTask; // Файл
        $string = trim($row . PHP_EOL); // Строка для записи
        $data_file = file($file); // Считываем данные из файла

        // Функция для применения к массиву
/*        function my_trim(&$item)
        {
            $item = trim($item);
        }*/

        // Тут убираем пробелы по краям записей
        /*array_walk($data_file, "my_trim");*/

        // Смотрим, есть ли такая запись
        if (!in_array($row, $data_file)) {
            $_SESSION['String']= false;

        } else {
            $_SESSION['String'] = true;
        }
    }



}