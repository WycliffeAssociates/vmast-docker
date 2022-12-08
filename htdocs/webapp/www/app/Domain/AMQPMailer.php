<?php

namespace App\Domain;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Support\Facades\View;

class AMQPMailer
{
    private $connection;
    private $channel;

    public function __construct() {
        try {
            $this->connection = new AMQPStreamConnection(
                $_ENV["RABBITMQ_HOST"],
                $_ENV["RABBITMQ_PORT"],
                $_ENV["RABBITMQ_USER"],
                $_ENV["RABBITMQ_PASS"]
            );
            $this->channel = $this->connection->channel();
            $this->channel->queue_declare($_ENV["RABBITMQ_QUEUE"], false, true, false, false);
        } catch (Exception $e) {
            //pr($e->getTraceAsString(),1);
        }
    }

    public function sendHtml($html, $emails, $subject, $replyTo = null) {
        if ($this->channel) {
            $message = [
                "emails" => $emails,
                "subject" => $subject,
                "message" => $html
            ];

            if ($replyTo != null) {
                $message["replyTo"] = $replyTo;
            }

            $msg = new AMQPMessage(json_encode($message));
            $this->channel->basic_publish($msg, '', $_ENV["RABBITMQ_ROUTING"]);
        }
    }

    public function sendView($view, $data, $emails, $subject, $replyTo = null) {
        $html = View::make($view, $data)->render();
        $this->sendHtml($html, $emails, $subject, $replyTo);
    }
}