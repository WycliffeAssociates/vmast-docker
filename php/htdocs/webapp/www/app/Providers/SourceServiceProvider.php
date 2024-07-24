<?php


namespace App\Providers;


use App\Repositories\Project\IProjectRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Source\ISourceRepository;
use App\Repositories\Source\SourceRepository;
use Support\ServiceProvider;

class SourceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ISourceRepository::class,
            SourceRepository::class);
    }

}