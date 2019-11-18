<?php

require dirname(__DIR__).'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;


$host = 'dove-01.rmq.cloudamqp.com';
$port = 5672;
$user = 'kgmunpfq';
$pass = '4gPbwgbKEKE1YExEgRatrOcR7yR7RnQv';
$vhost = 'kgmunpfq';

$exchange = 'subscribers';
$queue = 'gurucoder_subscribers';

$connection = new AMQPStreamConnection($host, $port, $user, $pass,$vhost);
$channel = $connection->channel();

$channel->exchange_declare('logs', 'direct', false, false, false);
$channel->exchange_declare('logs1', 'direct', false, false, false);
//$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

//$channel->queue_declare($queue, false, true, false, false);
//$channel->queue_bind($queue, $exchange);

$data = implode(' ', array_slice($argv, 1));
//$data1 = implode(' ', array_slice($argv, 1));
if(empty($data)) $data = "info: Hello World!111111111111";
if(empty($data1)) $data1 = "info: Hello World!1111111111112";
$msg = new AMQPMessage($data);
$msg1 = new AMQPMessage($data1);

$channel->basic_publish($msg, 'logs');
$channel->basic_publish($msg1, 'logs1');

echo " [x] Sent ", $data, "\n";
echo " [x] Sent ", $data1, "\n";

$channel->close();
$connection->close();

?>
