<?php


namespace App\Providers;


use App\Repositories\BookInfo\BookInfoRepository;
use App\Repositories\BookInfo\IBookInfoRepository;
use Support\ServiceProvider;

class BookInfoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IBookInfoRepository::class,
            BookInfoRepository::class);
    }

}