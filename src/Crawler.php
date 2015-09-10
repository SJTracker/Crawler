<?php

namespace SJTracker\Crawler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    /**
     * Http Client.
     *
     * @var Client
     */
    private $client;

    /**
     * Dom Crawler.
     *
     * @var Crawler
     */
    private $crawler;

    /**
     * @param Client     $client
     * @param DomCrawler $crawler
     */
    public function __construct(Client $client, DomCrawler $crawler)
    {
        $this->client = $client;
        $this->crawler = $crawler;
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
        $episodes = $this->filterEpisodes($paragraphs);

        return $episodes;
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
     *
     * @return array
     */
    private function filterEpisodes(DomCrawler $paragraph)
    {
        $episodes = $paragraph->reduce(function (DomCrawler $node) {
            return $this->isEpisode($node);
        })->each(function (DomCrawler $node) {
            return $node->html();
        });

        return $episodes;
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
