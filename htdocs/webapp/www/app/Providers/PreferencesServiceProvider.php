<?php

namespace App\Providers;

use App\Repositories\Preferences\IPreferenceRepository;
use App\Repositories\Preferences\PreferenceRepository;
use Support\ServiceProvider;

class PreferencesServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind(IPreferenceRepository::class, PreferenceRepository::class);
    }
}