<?php


namespace App\Providers;


use App\Repositories\Event\EventRepository;
use App\Repositories\Event\IEventRepository;
use Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IEventRepository::class,
            EventRepository::class);
    }

}