<?php

namespace App\Providers;

use App\Repositories\Translation\ITranslationRepository;
use App\Repositories\Translation\TranslationRepository;
use Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind(ITranslationRepository::class,
            TranslationRepository::class);
    }
}