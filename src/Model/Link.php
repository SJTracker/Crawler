<?php

namespace SJTracker\Crawler\Model;

class Link
{
    public $hoster;
    public $url;

    /**
     * Link constructor.
     *
     * @param string $hoster
     * @param string $url
     */
    public function __construct($hoster, $url)
    {
        $this->hoster = $hoster;
        $this->url = $url;
    }
}
