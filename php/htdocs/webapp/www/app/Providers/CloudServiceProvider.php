<?php

namespace App\Providers;

use App\Repositories\Cloud\CloudRepository;
use App\Repositories\Cloud\ICloudRepository;
use Support\ServiceProvider;

class CloudServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ICloudRepository::class,
            CloudRepository::class);
    }
}