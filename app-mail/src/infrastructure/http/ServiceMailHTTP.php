<?php
require __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use toubeelib\core\services\mail\MailService;

$exchange_name = 'direct_mail';
$queue_name = 'mail_queue';
$routing_key = 'mail_routing_key';
try {
    echo "En attente de message";
    $mailService = new MailService();

    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
    $channel = $connection->channel();

    $channel->exchange_declare($exchange_name, 'direct', false, true, false);
    $channel->queue_declare($queue_name, false, true, false, false);
    $channel->queue_bind($queue_name, $exchange_name, $routing_key);

    $channel->queue_purge($queue_name);

    $callback = function (AMQPMessage $msg) {
        global $mailService;
        $msg_body = json_decode($msg->getBody(), true);

        $mailPraticien = $msg_body['mailPraticien'];
        $mailPatient = $msg_body['mailPatient'];


        $mailService->sendMail($msg_body['from'], $mailPraticien, $msg_body['subject'], $msg_body['text'], $msg_body['html'], $msg_body['smtp']);
        $mailService->sendMail($msg_body['from'], $mailPatient, $msg_body['subject'], $msg_body['text'], $msg_body['html'], $msg_body['smtp']);

        print "[x] message reçu : \n";
        print_r($msg_body);
        $msg->getChannel()->basic_ack($msg->getDeliveryTag());
    };
    $channel->basic_consume($queue_name,
        ''
        , false, false, false, false, $callback);
    try {
        $channel->consume();
    } catch (Exception $e) {
        print $e->getMessage();
    }
    $channel->close();
    $connection->close();

} catch (Exception $e) {
    print $e->getMessage();
}
