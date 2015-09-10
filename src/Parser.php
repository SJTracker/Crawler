<?php

namespace SJTracker\Crawler;

use SJTracker\Crawler\Model\ParserResult;

class Parser
{
    public function parseFromReleaseString($episode)
    {
        preg_match('/^(?<show>.+)\.(?<season>S\d\d)(?<episode>(?:E\d\d){1,2}(?=\.))(?:.*\.(?<quality>720p|1080p)(?=\.))?/', $episode, $matches);

        $show = $this->show($matches);
        $season = $this->season($matches);
        $episode = $this->episode($matches);
        $quality = $this->quality($matches);

        return new ParserResult($show, $season, $episode, $quality);
    }

    private function show($matches)
    {
        return str_replace('.', ' ', $matches['show']);
    }

    private function season($matches)
    {
        return $matches['season'];
    }

    private function episode($matches)
    {
        return $matches['episode'];
    }

    private function quality($matches)
    {
        if (!isset($matches['quality'])) {
            return 'SD';
        }

        return $matches['quality'];
    }
}
