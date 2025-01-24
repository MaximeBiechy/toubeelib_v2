<?php
require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$queue = 'mail_queue';
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
$channel = $connection->channel();
$callback = function (AMQPMessage $msg) {
    $msg_body = json_decode($msg->getBody(), true);
    print "[x] message reçu : \n";
    print_r($msg_body);
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};
$channel->basic_consume($queue,
    ''
    , false, false, false, false, $callback);
try {
    $channel->consume();
} catch (Exception $e) {
    print $e->getMessage();
}
$channel->close();
$connection->close();