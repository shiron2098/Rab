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
include_once ('Job.php');



use PhpAmqpLib\Connection\AMQPStreamConnection;

class WorkerReceiver1 extends \Job
{
    private $response;
    protected $file;
    public function Index()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'shir', '1995');
        $channel = $connection->channel();
        $responseOper = $this->SelectToDbOperators();
        if(!empty($responseOper['id']&& isset($responseOper['id']))) {
            $channel->exchange_declare('Type', 'direct', false, false, false);

            list($queue_name, ,) = $channel->queue_declare("ConfigOperator#" . $responseOper['id'], false, false, false, false);

            $channel->queue_bind($queue_name, 'Type',$responseOper['code']);

            $channel->basic_qos(
                null,   #размер предварительной выборки - размер окна предварительнйо выборки в октетах, null означает “без определённого ограничения”
                1,    #количество предварительных выборок - окна предварительных выборок в рамках целого сообщения
                null    #глобальный - global=null означает, что настройки QoS должны применяться для получателей, global=true означает, что настройки QoS должны применяться к каналу
            );

            echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

/*            $callback = function ($msg) {
                /echo ' [x] ', $msg->body, "\n";
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
            $a = $channel->basic_consume($queue_name, 'ConfigForOperator#1', false, true, false, false, $callback);

            print_r($callback);

            while (count($channel->callbacks)) {
                    $channel->wait();
            }*/
            while(true) {
                $msg = $channel->basic_get($queue_name, true);
                if(!empty($msg->body)&& isset($msg->body)) {
                    if ($msg->body !== null) {
                        $file[] = $msg->body;
                    }
                }
                else
                {
                    return $file;
                }
            }
            $channel->close();
            $connection->close();
        }
    }

}