<?php

namespace SJTracker\Crawler\Test;

use SJTracker\Crawler\Model\ParserResult;
use SJTracker\Crawler\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_parses_a_release_string()
    {
        $parser = new Parser();

        $result = $parser->parseFromReleaseString('The.Walking.Dead.S05E01.720p.WEB-DL.DD5.1.H.264-Cyphanix');

        $this->assertInstanceOf(ParserResult::class, $result);
        $this->assertEquals('The Walking Dead', $result->show);
        $this->assertEquals('S05', $result->season);
        $this->assertEquals('E01', $result->episode);
        $this->assertEquals('720p', $result->quality);
    }

    /**
     * @test
     */
    public function it_parses_a_double_episode_release_string()
    {
        $parser = new Parser();

        $result = $parser->parseFromReleaseString('The.Walking.Dead.S05E01E02.720p.WEB-DL.DD5.1.H.264-Cyphanix');

        $this->assertEquals('The Walking Dead', $result->show);
        $this->assertEquals('S05', $result->season);
        $this->assertEquals('E01E02', $result->episode);
        $this->assertEquals('720p', $result->quality);
    }

    /**
     * @test
     */
    public function it_parses_a_release_string_containing_additional_text_between_the_epiode_and_the_quality()
    {
        $parser = new Parser();

        $result = $parser->parseFromReleaseString('Die.Simpsons.S26E03.Super.Franchise.Me.GERMAN.DUBBED.DL.1080p.WebHD.x264-TVP');

        $this->assertEquals('Die Simpsons', $result->show);
        $this->assertEquals('S26', $result->season);
        $this->assertEquals('E03', $result->episode);
        $this->assertEquals('1080p', $result->quality);

        $result = $parser->parseFromReleaseString('Die.Simpsons.S26E03E04.Super.Franchise.Me.GERMAN.DUBBED.DL.1080p.WebHD.x264-TVP');

        $this->assertEquals('Die Simpsons', $result->show);
        $this->assertEquals('S26', $result->season);
        $this->assertEquals('E03E04', $result->episode);
        $this->assertEquals('1080p', $result->quality);
    }

    /**
     * @test
     */
    public function it_sets_the_quality_to_SD_if_it_cant_find_a_quality()
    {
        $parser = new Parser();

        $result = $parser->parseFromReleaseString('The.Walking.Dead.S05E01E02.HDTV.WEB-DL.DD5.1.H.264-Cyphanix');

        $this->assertEquals('SD', $result->quality);
    }
}
