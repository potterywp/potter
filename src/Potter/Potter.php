<?php
namespace Potter;

class Potter
{
    private static $potter;

    public function __construct(PotterCore $potterCore)
    {
        self::$potter = $potterCore;
    }
}