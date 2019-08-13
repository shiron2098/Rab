<?php
namespace app;
require_once __DIR__ . '/../vendor/autoload.php';
/*use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'Shiro', '1995');
$channel = $connection->channel();
$queueName = 'Operator24';
$channel->queue_declare($queueName, false, true, false, false);
$result = $channel->basic_get($queueName);
$a =json_decode($result->body);

$channel->close();
$connection->close();*/
require_once __DIR__ . '/../CheckAndSendRabbitMYSQL/CheckDataMYSQL.php';


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class WorkerReceiver1 extends \CheckDataMYSQL
{
     protected $IDOperators;
     protected $IDJobs;
     Protected $IDJob_Scheduler;

    public function Index($idoper,$idconfig)
    {
        $connection = new AMQPStreamConnection(self::hostrabbit, self::port, self::username, self::passwordrabbit,self::vhost);
        $channel = $connection->channel();
            $responseOper = $this->SelectToDbOperatorsDAWS($idoper['id']);
        if (!empty($responseOper) && isset($responseOper)) {
            foreach ($responseOper as $rabbitmq) {
                $this->IdOperatorsFull($rabbitmq['id']);
                $channel->exchange_declare(self::exchange, self::type, false, false, false);
                if(isset($idconfig)&&!empty($idconfig) == 0) {
                    list($queue_name, ,) = $channel->queue_declare(self::NameConfig . $rabbitmq['id'], false, false, false, false);
                    $channel->queue_bind($queue_name, self::exchange, $rabbitmq['code']);
                }else {
                    list($queue_name, ,) = $channel->queue_declare(self::NameConfigDAWS . $rabbitmq['id'], false, false, false, false);
                    $channel->queue_bind($queue_name, self::exchange, $rabbitmq['name']);
                }

                $channel->basic_qos(
                    null,
                    1,
                    null
                );


               /* echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";*/

                /*      $callback = function ($msg) {
                          echo ' [x] ', $msg->body, "\n";
                          try {
                              if (!empty($msg->body)) {
                                  $response = json_decode($msg->body);
                                  $this->response = $file[] = $response;
                                  return $this->response;
                              } else {
                                  Throw new \Exception('Empty message');
                              }
                          } catch (\Exception $e) {
                              $e->getMessage();
                          }
                      };
                      $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

                      print_r($callback);

                      while (count($channel->callbacks)) {
                          $channel->wait();
                          return $this->response;
                      }
                  }
              }*/
                while (true) {
                    $msg = $channel->basic_get($queue_name, true);
                    if (!empty($msg->body) && isset($msg->body)) {
                        if ($msg->body !== null) {
                            $arrayMessageFromRabbit[] = $msg->body;
                        }
                    }
                        else
                        {
                            break;
                        }
                }
            }
        }
        if(!empty($arrayMessageFromRabbit)) {
            return $arrayMessageFromRabbit;
        }else{
            $text = '$file array WorkerReceiver null';
            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
            $this->UpdateOperStreams($rabbitmq['id'],$this->bool);
            $this->logtext($text);
        }
    }

}