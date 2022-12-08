<?php

namespace App\Support\Facades;

use Support\Facades\Facade;

class AMQPMailer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'amqpmailer'; }
}