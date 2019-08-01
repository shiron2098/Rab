<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
spl_autoload('MysqlDbConnect');
require_once('Rabbimq.php');
require_once('MysqlDbConnect.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class RabbiSendSqlTakeInDbMYSQL extends MysqlDbConnect
{
    public $ResponseMySQL;
    public $TimeAndTaskDolg;

    public function index($response)
    {
        if (!empty($response['code'] && !empty($response['time'])) && isset($response['code'])&& isset($response['time'])) {
            $this->ResponseMySQL = $response;
/*            $results = print_r($this->ResponseMySQL,
                true);*/

            try {
                $this->CheckRabbit();
                if (isset($_SESSION['Check'])) {
                    if (!empty($this->ResponseMySQL['code']['operatorid']) && !empty($this->ResponseMySQL['code']['Jobsid'])){
                    if ($_SESSION['Check'] !== true) {
                            $this->AMQPConnect('localhost', '5672', 'shir', '1995', '/');
                        $this->CreateExchange('Type', 'direct');
                        $this->CreateQueue('ConfigOperator#' . $this->ResponseMySQL['code']['operatorid'], false, false, false, $this->ResponseMySQL['code']['code'], false);
                        $this->MessageOut($this->ResponseMySQL);
                        $text = 'message delivery is complete Rabbit #' . $this->ResponseMySQL['code']['Jobsid'];
                        $_SESSION['Check'] = true;
                        $this->log($text);
                        /*                   include_once('RabbitMqSendMessageConnect.phpphp');*/
                        return $text;
                    } else {
/*                            $this->SearchRepeat($this->ResponseMySQL['code']['Jobsid'] . PHP_EOL);
                            if (!empty($_SESSION['String']) === true && isset($_SESSION['String']) === true) {*/
                                throw new Exception('error download into rabbit because the message exists MYSQL #' . $this->ResponseMySQL['code']['Jobsid']);
                /*            } else {
                                file_put_contents(self::FileRepeatToTask, $this->ResponseMySQL['code']['Jobsid'] . PHP_EOL, FILE_APPEND);
                                $text = 'Put in line for addition #' . $this->ResponseMySQL['code']['Jobsid'];
                                $this->log($text);
                            }*/
                        }
                    }
                    else
                    {
                        throw new Exception('Response mysql code and id null');
                    }
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->log($e->getMessage());
            }
        }
    }
}