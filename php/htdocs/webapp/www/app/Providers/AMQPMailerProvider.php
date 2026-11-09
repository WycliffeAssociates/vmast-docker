<?php

namespace App\Providers;

use App\Domain\AMQPMailer;
use Support\ServiceProvider;

class AMQPMailerProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->bindShared('amqpmailer', function($app)
        {
            return new AMQPMailer();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('amqpmailer');
    }
}