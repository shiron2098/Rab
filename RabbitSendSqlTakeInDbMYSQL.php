<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('Rabbimq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class RabbitSendSqlTakeInDbMYSQL extends Rabbimq
{

    protected $ResponseMySQL;

    public function SendAndCheckMessageMYSQL($response)
    {
        if (!empty($response['code'] && !empty($response['time'])) && isset($response['code'])&& isset($response['time'])) {
            $this->ResponseMySQL = $response;
/*            $results = print_r($this->ResponseMySQL,
                true);*/

            try {
                $this->CheckRabbit($this->ResponseMySQL);
                if (isset($_SESSION['Zapros'])) {
                    if (!empty($this->ResponseMySQL['code']['command']) && !empty($this->ResponseMySQL['code']['Jobsid'])){
                    if ($_SESSION['Zapros'] !== true) {
                        $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
                        $this->CreateExchange(self::exchange, self::type);
                        $this->CreateQueue(self::NameConfig . $this->IDOperators, false, false, false, $this->DataOperators['code'], false);
                        $this->MessageOut($this->ResponseMySQL,$this->IDJobs);
                        $text = 'message delivery is complete Rabbit #' . $this->IDJobs;
                        $this->logtext($text);
                        /*                   include_once('RabbitMqSendMessageDAWS.php');*/
                        return $text;
                    } else {
                        $this->logDB($this->IDJobs,$this->timestamp,self::statusERROR);
                        throw new Exception('error download into rabbit because the message exists MYSQL #' . $this->IDJobs);

                        }
                    } else {
                        $this->logDB($this->IDJobs,$this->timestamp,self::statusERROR);
                        throw new Exception('Response mysql code and id null');
                    }
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->logtext($e->getMessage());
            }
        }
    }
}