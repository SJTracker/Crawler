<?php

namespace SJTracker\Crawler\Test;

use GuzzleHttp\Client;
use Mockery;
use SJTracker\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_crawls_all_episodes()
    {
        $raw = DataLoader::load('sj-season-raw');
        $filtered = DataLoader::load('sj-season-filtered');

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->with('url')->andReturnSelf()->once();
        $client->shouldReceive('getBody')->andReturn($raw)->once();

        $crawler = new DomCrawler();

        $parser = new Crawler($client, $crawler);

        $this->assertCount(64, $parser->crawl('url'));
        $this->assertEquals($filtered, implode("\n", $parser->crawl('url')));
    }
}
