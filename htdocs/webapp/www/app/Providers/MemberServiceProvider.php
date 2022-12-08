<?php


namespace App\Providers;


use App\Repositories\Member\IMemberRepository;
use App\Repositories\Member\MemberRepository;
use Support\ServiceProvider;

class MemberServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IMemberRepository::class,
            MemberRepository::class);
    }

}