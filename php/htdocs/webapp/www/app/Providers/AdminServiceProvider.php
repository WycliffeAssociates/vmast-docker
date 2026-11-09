<?php


namespace App\Providers;


use App\Repositories\Admin\IAdminRepository;
use App\Repositories\Admin\AdminRepository;
use Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IAdminRepository::class,
            AdminRepository::class);
    }

}