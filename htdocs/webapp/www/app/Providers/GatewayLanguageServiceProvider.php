<?php


namespace App\Providers;


use App\Repositories\GatewayLanguage\GatewayLanguageRepository;
use App\Repositories\GatewayLanguage\IGatewayLanguageRepository;
use Support\ServiceProvider;

class GatewayLanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IGatewayLanguageRepository::class,
            GatewayLanguageRepository::class);
    }

}