<?php
namespace app;
require_once __DIR__ . '/vendor/autoload.php';
/*use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'Shiro', '1995');
$channel = $connection->channel();
$queueName = 'Operator24';
$channel->queue_declare($queueName, false, true, false, false);
$result = $channel->basic_get($queueName);
$a =json_decode($result->body);

$channel->close();
$connection->close();*/
include_once('CheckDataMYSQL.php');


use PhpAmqpLib\Connection\AMQPStreamConnection;

class WorkerReceiver1 extends \CheckDataMYSQL
{
    private $response;
    protected $file;

    public function Index($id)
    {
        $connection = new AMQPStreamConnection(self::hostrabbit, self::port, self::username, self::passwordrabbit,self::vhost);
        $channel = $connection->channel();
        $responseOper = $this->SelectToDbOperatorsDAWS($id);
        if (!empty($responseOper) && isset($responseOper)) {
            foreach ($responseOper as $rabbitmq) {
                $channel->exchange_declare(self::exchange, self::type, false, false, false);

                list($queue_name, ,) = $channel->queue_declare(self::NameConfig . $rabbitmq['id'], false, false, false, false);

                $channel->queue_bind($queue_name, self::exchange, $rabbitmq['code']);

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
                            $file[] = $msg->body;
                        }
                    }
                        else
                        {
                            break;
                        }
                }
            }
        }
        if(!empty($file)) {
            return $file;
        }else{
            $text = '$file array WorkerReceiver null';
            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
            $this->logtext($text);
        }
    }
}