<?php
namespace Potter;

use Potter\Post\QueryModel;

class Potter
{
    /**
     * @var PotterCore
     */
    private static $potter;

    /**
     * @param PotterCore $potterCore
     */
    public function __construct(PotterCore $potterCore)
    {
        self::$potter = $potterCore;
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
}