<?php
namespace Potter;

use OPT;
use Potter\Post\Query;
use Potter\Post\QueryModel;
use Potter\Theme\Features;
use Potter\Utils\Metabox;

class Potter
{
    /**
     * @var PotterCore
     */
    private static $potter;
    private static $features;

    /**
     * @param PotterCore $potterCore
     * @param Features   $features
     */
    public function __construct(PotterCore $potterCore, Features $features)
    {
        self::$potter = $potterCore;
        self::$features = $features;

        $this->registerWidgets();
    }

    private function registerWidgets()
    {
        $widgets = self::core()->getWidgets()->toArray();
        self::features()->addWidget($widgets);
    }

    /**
     * @return PotterCore
     */
    public static function core()
    {
        return self::$potter;
    }

    /**
     * @param string $name
     *
     * @return QueryModel
     */
    public static function model($name)
    {
        $_PostType = self::core()->getModels()->get($name);

        $query = new QueryModel($_PostType);

        return $query;
    }

    /**
     * @param string $postType
     * @param array  $args
     *
     * @return Query
     */
    public static function query($postType, $args = array())
    {

        $query = new Query($args, $postType);

        return $query;
    }

    /**
     * @return Features
     */
    public static function features()
    {
        return self::$features;
    }

    /**
     * @param array $attributes
     * @param array $fields
     * @param bool  $autoRegister
     *
     * @return Metabox
     */
    public static function makeMetabox(array $attributes, array $fields = array(), $autoRegister = true)
    {
        return new Metabox($attributes, $fields, $autoRegister);
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return null|string
     */
    public static function OPT($name, $default = null)
    {
        return OPT::get($name, $default);
    }
}