<?php

namespace SJTracker\Crawler;

use GuzzleHttp\Client;
use Mockery;
use SJTracker\Crawler\Crawler;
use SJTracker\Crawler\Model\ParserResult;
use SJTracker\Crawler\Parser;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_crawls_all_episodes()
    {
        $filtered = DataLoader::load('sj-season-filtered');

        $crawler = $this->getCrawler();

        $rawData = $crawler->crawl('url')->raw();

        $this->assertCount(64, $rawData);
        $this->assertEquals($filtered, implode("\n", $rawData));
    }

    /**
     * @test
     */
    public function it_parses_the_crawler_result()
    {
        $crawler = $this->getCrawler();

        $result = $crawler->crawl('url')->parse();

        $this->assertInstanceOf(ParserResult::class, $result);
    }

    /**
     * Get the client mock.
     *
     * @return Mockery\MockInterface
     */
    protected function getClient()
    {
        $raw = DataLoader::load('sj-season-raw');

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->with('url')->andReturnSelf()->once();
        $client->shouldReceive('getBody')->andReturn($raw)->once();

        return $client;
    }

    /**
     * Get the Parser mock.
     *
     * @return Mockery\MockInterface|Parser
     */
    private function getParser()
    {
        $parser = Mockery::mock(Parser::class);
        $parser->shouldReceive('parseFromReleaseString')
            ->andReturn(new ParserResult('The Walking Dead', 'S05', 'E01', '720p'))
            ->once();

        return $parser;
    }

    /**
     * Get the crawler instance.
     *
     * @return Mockery\MockInterface|Crawler|Parser
     */
    protected function getCrawler()
    {
        $client = $this->getClient();
        $crawler = new DomCrawler();
        $parser = $this->getParser();

        $parser = new Crawler($client, $crawler, $parser);

        return $parser;
    }
}
