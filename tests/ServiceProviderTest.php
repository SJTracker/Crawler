<?php

namespace SJTracker\Crawler\Test;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use SJTracker\Crawler\Crawler;
use SJTracker\Crawler\CrawlerServiceProvider;

class ServiceProviderTest extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return CrawlerServiceProvider::class;
    }

    public function testCrawlerIsInjectable()
    {
        $this->assertIsInjectable(Crawler::class);
    }
}
