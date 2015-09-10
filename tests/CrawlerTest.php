<?php

namespace SJTracker\Crawler\Test;

use GrahamCampbell\TestBench\AbstractTestCase;
use GuzzleHttp\Client;
use Mockery;
use SJTracker\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerTest extends AbstractTestCase
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

        $episodes = $parser->crawl('url');
        $this->assertCount(64, $episodes);
        $this->assertEquals($filtered, implode("\n", $episodes));
    }
}
