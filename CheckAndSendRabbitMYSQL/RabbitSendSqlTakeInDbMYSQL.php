<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('AbstractClass/Rabbimq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class RabbitSendSqlTakeInDbMYSQL extends Rabbimq
{

    protected $ResponseMySQL;
    protected $TextOK;

    public function SendAndCheckMessageMYSQL($response)
    {
        if (!empty($response['code'] && !empty($response['time'])) && isset($response['code'])&& isset($response['time'])) {
            $this->ResponseMySQL = $response;
/*            $results = print_r($this->ResponseMySQL,
                true);*/

            try {
                $this->CheckRabbit($this->ResponseMySQL);
                if (isset($_SESSION['Zapros'])) {
                    if (!empty($this->ResponseMySQL['code']['command']) && !empty($this->ResponseMySQL['code']['Jobsid'])&& !empty($this->ResponseMySQL['code']['operatorid'])){
                    if ($_SESSION['Zapros'] !== true) {
/*                        $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
                        $this->CreateExchange(self::exchange, self::type);
                        $this->CreateQueue(self::NameConfig . $this->IDOperators, false, false, false, $this->DataOperators['code'], false);
                        $this->MessageOut($this->ResponseMySQL,$this->IDJobs);
                        $this->TextOK = $text = '[Job id #' . $this->IDJobs . ']' . 'Result was delivered to Data queue successfully.';
                        $this->logtext($text);
                        return $text;*/
                        return $this->ResponseMySQL;
                    } else {
                        $text = '[Job id #' . $this->IDJobs . ']' . 'Command Execution is already in progress';
                        $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                        throw new Exception($text . $this->IDJobs);
                        }
                    } else {
                        $text='Code and ID are NULL';
                        $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                        throw new Exception($text);
                    }
                }
            } catch (Exception $e){
                echo $e->getMessage();
                $this->logtext($e->getMessage());
            }
        }

    }
}