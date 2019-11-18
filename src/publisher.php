<?php
//include(__DIR__ . '/config.php');
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

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();
/*
    The following code is the same both in the consumer and the producer.
    In this way we are sure we always have a queue to consume from and an
        exchange where to publish messages.
*/
/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$channel->queue_declare($queue, false, true, false, false);
/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/
$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
$channel->queue_bind($queue, $exchange);
//$messageBody = implode(' ', array_slice($argv, 1));
$messageBody = json_encode([
   'email'=> 'kaxam1@gmail.com',
   'subscribed' => true
]);
$message = new AMQPMessage($messageBody, [
    'content_type' => 'application/json',
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
]);
$channel->basic_publish($message, $exchange);
$channel->close();
$connection->close();
