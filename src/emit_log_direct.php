<?php

require dirname(__DIR__).'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = 'dove-01.rmq.cloudamqp.com';
$port = 5672;
$user = 'kgmunpfq';
$pass = '4gPbwgbKEKE1YExEgRatrOcR7yR7RnQv';
$vhost = 'kgmunpfq';

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = $argv[1];
if(empty($severity)) $severity = "info";

$data = implode(' ', array_slice($argv, 2));
if(empty($data)) $data = "Hello World!";

$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'direct_logs', $severity);

echo " [x] Sent ",$severity,':',$data," \n";

$channel->close();
$connection->close();

?>
