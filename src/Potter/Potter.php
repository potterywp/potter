<?php
namespace Potter;

use Potter\Post\QueryModel;
use Potter\Theme\Features;

class Potter
{
    /**
     * @var PotterCore
     */
    private static $potter;
    private static $features;

    /**
     * @param PotterCore $potterCore
     */
    public function __construct(PotterCore $potterCore, Features $features)
    {
        self::$potter   = $potterCore;
        self::$features = $features;
    }

    /**
     * @return PotterCore
     */
    public static function core()
    {
        return self::$potter;
    }

    /**
     * @param $name
     * @return QueryModel
     */
    public static function model($name)
    {
        $_PostType = self::core()->getModels()->get($name);

        $query = new QueryModel($_PostType);

        return $query;
    }

    /**
     * @return Features
     */
    public static function feature()
    {
        return self::$features;
    }
}