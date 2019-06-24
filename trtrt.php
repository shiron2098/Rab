<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'shir', '1995');
$channel = $connection->channel();
$queueName = 'Operator24';
$channel->queue_declare($queueName, false, true, false, false);
$result = $channel->basic_get($queueName);

if(empty($result)){
    $_SESSION['Zapros'] = False;
}
else
{
    $_SESSION['Zapros'] = true;
}

$channel->close();
$connection->close();