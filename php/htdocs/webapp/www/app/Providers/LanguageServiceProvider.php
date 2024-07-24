<?php


namespace App\Providers;


use App\Repositories\Language\ILanguageRepository;
use App\Repositories\Language\LanguageRepository;
use Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ILanguageRepository::class,
            LanguageRepository::class);
    }

}