<?php


namespace App\Providers;


use App\Repositories\Project\IProjectRepository;
use App\Repositories\Project\ProjectRepository;
use Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IProjectRepository::class,
            ProjectRepository::class);
    }

}