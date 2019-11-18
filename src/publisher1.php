<?php

require dirname(__DIR__).'/vendor/autoload.php';

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

# Create the queue if it does not already exist.
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

$job_id=0;
while (true)
{
    $jobArray = array(
        'id' => $job_id++,
        'task' => 'sleep',
        'email'=> 'kaxam1@gmail.com',
        'sleep_period' => rand(0, 3)
    );

    $msg = new AMQPMessage(
        json_encode($jobArray, JSON_UNESCAPED_SLASHES),
        array('delivery_mode' => 2) # make message persistent
    );
//
//    $channel->basic_publish($msg, '', RABBITMQ_QUEUE_NAME);

    $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
    $channel->queue_bind(RABBITMQ_QUEUE_NAME, $exchange);
//$messageBody = implode(' ', array_slice($argv, 1));
//    $messageBody = json_encode([
//       'email'=> 'kaxam1@gmail.com',
//   'subscribed' => true
//    ]);
//    $message = new AMQPMessage($messageBody, [
//        'content_type' => 'application/json',
//        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
//    ]);
    $channel->basic_publish($msg, $exchange);
//    $channel->close();

    print 'Job created' . PHP_EOL;
    sleep(1);
}
