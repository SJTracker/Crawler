<?php

namespace SJTracker\Crawler;

use Illuminate\Support\ServiceProvider;

class CrawlerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('crawler', function () {
            return $this->app->make(Crawler::class);
        });
    }
}
