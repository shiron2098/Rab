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



use PhpAmqpLib\Connection\AMQPStreamConnection;

class WorkerReceiver1
{
    private $response;
    public function Index()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'shir', '1995');
        $channel = $connection->channel();

        $channel->exchange_declare('type', 'direct', false, false, false);

        list($queue_name, ,) = $channel->queue_declare("Operator24", false, true, false, false);

        $channel->queue_bind($queue_name, 'type');

        echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

        $callback = function ($msg) {
           /* echo ' [x] ', $msg->body, "\n";*/
            try {
                if (!empty($msg->body)) {
                    $response = json_decode($msg->body);
                    $this->response=$response;
                } else {
                    Throw new \Exception('Empty message');
                }
            }catch(\Exception $e ){
                $e->getMessage();
            }
        };
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);



        while (count($channel->callbacks)) {
            $channel->wait();
            return $this->response;
        }

        $channel->close();
        $connection->close();
    }
}