<?php

namespace SJTracker\Crawler;

use SJTracker\Crawler\Model\ParserResult;

class ParserResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_allows_access_to_the_properties_after_instantiation()
    {
        $result = new ParserResult('The Walking Dead', 'S05', 'E01', '720p');

        $this->assertEquals('The Walking Dead', $result->show);
        $this->assertEquals('S05', $result->season);
        $this->assertEquals('E01', $result->episode);
        $this->assertEquals('720p', $result->quality);
    }

    /**
     * @test
     */
    public function it_adds_a_link_to_a_parser_result()
    {
        $result = new ParserResult('The Walking Dead', 'S05', 'E01', '720p');
        $this->assertCount(0, $result->links);

        $result->addLink('share-online.biz', 'https://shareonline.url');

        $this->assertCount(1, $result->links);
        $this->assertEquals('share-online.biz', $result->links->first()->hoster);
        $this->assertEquals('https://shareonline.url', $result->links->first()->url);
    }

    /**
     * @test
     */
    public function it_adds_two_links_to_a_parser_result()
    {
        $result = new ParserResult('The Walking Dead', 'S05', 'E01', '720p');
        $this->assertCount(0, $result->links);

        $result->addLink('share-online.biz', 'https://shareonline.url');
        $result->addLink('uploaded.net', 'https://uploaded.url');

        $this->assertCount(2, $result->links);
        $this->assertEquals('share-online.biz', $result->links->first()->hoster);
        $this->assertEquals('https://shareonline.url', $result->links->first()->url);
        $this->assertEquals('uploaded.net', $result->links->last()->hoster);
        $this->assertEquals('https://uploaded.url', $result->links->last()->url);
    }
}
