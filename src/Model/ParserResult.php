<?php

namespace SJTracker\Crawler\Model;

use Illuminate\Support\Collection;

class ParserResult
{
    public $show;
    public $season;
    public $episode;
    public $quality;
    public $links;

    /**
     * ParserResult constructor.
     *
     * @param $show
     * @param $season
     * @param $episode
     * @param $quality
     */
    public function __construct($show, $season, $episode, $quality)
    {
        $this->show = $show;
        $this->season = $season;
        $this->episode = $episode;
        $this->quality = $quality;
        $this->links = new Collection();
    }

    public function addLink($hoster, $url)
    {
        $this->links->push(new Link($hoster, $url));
    }
}
