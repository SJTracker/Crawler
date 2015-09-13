<?php

namespace SJTracker\Crawler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    /**
     * The raw crawled episodes.
     *
     * @var array
     */
    protected $episodes;

    /**
     * Http Client.
     *
     * @var Client
     */
    private $client;

    /**
     * Dom Crawler.
     *
     * @var DomCrawler
     */
    private $crawler;

    /**
     * @var Parser.
     */
    private $parser;

    /**
     * @param Client     $client
     * @param DomCrawler $crawler
     * @param Parser     $parser
     */
    public function __construct(Client $client, DomCrawler $crawler, Parser $parser)
    {
        $this->client = $client;
        $this->crawler = $crawler;
        $this->parser = $parser;
    }

    /**
     * Parse the given Serienjunkies URL.
     *
     * @param string $url
     *
     * @return string
     */
    public function crawl($url)
    {
        $body = $this->getHtml($url);

        $this->crawler->add($body);

        $paragraphs = $this->filterPosts();

        $this->filterEpisodes($paragraphs);

        return $this;
    }

    public function raw()
    {
        return $this->episodes;
    }

    /**
     * Parse the crawler result.
     *
     * @return Model\ParserResult
     */
    public function parse()
    {
        return $this->parser->parseFromReleaseString($this->episodes);
    }

    /**
     * @param $url
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    private function getHtml($url)
    {
        $res = $this->client->get($url);

        $body = $res->getBody();

        return $body;
    }

    /**
     * @param DomCrawler $paragraph
     */
    private function filterEpisodes(DomCrawler $paragraph)
    {
        $episodes = $paragraph->reduce(function (DomCrawler $node) {
            return $this->isEpisode($node);
        })->each(function (DomCrawler $node) {
            return $node->html();
        });

        $this->episodes = $episodes;
    }

    /**
     * @param DomCrawler $node
     *
     * @return int
     */
    private function isEpisode(DomCrawler $node)
    {
        return (bool) preg_match('#^<strong>.+?</strong>.+?<strong>Download:</strong>.+?<a#', $node->html());
    }

    /**
     * @return DomCrawler
     */
    private function filterPosts()
    {
        return $this->crawler->filter('#content .post-content')
            ->first()
            ->filter('p');
    }
}
