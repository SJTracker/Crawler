<?php

namespace SJTracker\Crawler\Test;

use Illuminate\Filesystem\Filesystem;

class DataLoader
{
    /**
     * @param $name
     *
     * @return mixed
     */
    public static function load($name)
    {
        $filesystem = new Filesystem();

        return $filesystem->get(__DIR__.'/../tests/data/'.$name.'.html');
    }
}
