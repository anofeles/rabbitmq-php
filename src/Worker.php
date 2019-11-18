<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

define("RABBITMQ_HOST", "dove-01.rmq.cloudamqp.com");
define("RABBITMQ_PORT", 5672);
define("RABBITMQ_USERNAME", "dove-01.rmq.cloudamqp.com");
define("RABBITMQ_PASSWORD", "4gPbwgbKEKE1YExEgRatrOcR7yR7RnQv");
define("RABBITMQ_QUEUE_NAME", "gurucoder_subscribers");

$host = 'dove-01.rmq.cloudamqp.com';
$port = 5672;
$user = 'kgmunpfq';
$pass = '4gPbwgbKEKE1YExEgRatrOcR7yR7RnQv';
$vhost = 'kgmunpfq';

$exchange = 'subscribers';
$queue = 'gurucoder_subscribers';

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);

$channel = $connection->channel();

# Create the queue if it doesnt already exist.
$channel->queue_declare(
    $queue = RABBITMQ_QUEUE_NAME,
    $passive = false,
    $durable = true,
    $exclusive = false,
    $auto_delete = false,
    $nowait = false,
    $arguments = null,
    $ticket = null
);


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function ($msg) {
    $job = json_decode($msg->body, $assocForm = true);
//    if ($job['sleep_period'] <= 1) {
    echo " [x] Received ", $msg->body, "\n";
    echo " [x] sleep_period ", $job['sleep_period'], "\n";
    sleep($job['sleep_period']);
    echo " [x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//    }
};

$channel->basic_qos(null, 1, null);

$channel->basic_consume(
    $queue = RABBITMQ_QUEUE_NAME,
    $consumer_tag = '',
    $no_local = false,
    $no_ack = false,
    $exclusive = false,
    $nowait = false,
    $callback
);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
