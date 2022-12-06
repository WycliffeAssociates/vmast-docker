<?php

namespace App\Providers;

use App\Repositories\Resources\IResourcesRepository;
use App\Repositories\Resources\ResourcesRepository;
use Support\ServiceProvider;

class ResourcesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IResourcesRepository::class,
            ResourcesRepository::class);
    }
}