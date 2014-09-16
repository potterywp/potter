<?php

namespace Potter\Post;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use WP_Query;

/**
 * Class Query
 * @package Potter\Post
 */
class Query
{
    private $args;

    public function __construct(array $args = array(), $postType = null, $perPage = null)
    {
        $this->args = new Collection($args);
        if (!empty($postType)) $this->postType($postType);
        if (!empty($perPage)) $this->perPage($perPage);

    }

    /**
     * @param $type
     *
     * @return Query
     */
    public function postType($type)
    {
        return $this->put('post_type', $type);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return Query;
     */
    private function put($key, $value)
    {
        $this->args->put($key, $value);

        return $this;
    }

    /**
     * @param $perPage
     *
     * @return Query
     */
    public function perPage($perPage)
    {
        return $this->put('posts_per_page', $perPage);
    }

    /**
     * @param $value
     *
     * @return Query
     */
    public function order($value)
    {
        return $this->put('order', $value);
    }

    /**
     * @param $value
     *
     * @return Query
     */
    public function orderBy($value)
    {
        return $this->put('orderby', $value);
    }

    /**
     * @param int   $limit
     * @param array $args
     *
     * @return WP_Query
     */
    public function get($limit, $args = array())
    {
        if (is_numeric($limit)):
            return $this->perPage($limit)->exe($args);
        else:
            return $this->exe($args);
        endif;
    }

    /**
     * @param $id
     *
     * @return WP_Query
     */
    public function byParent($id)
    {
        return $this->put('post_parent', $id)->exe();
    }

    /**
     * @param $ids
     *
     * @return WP_Query
     */
    public function byParents($ids)
    {
        $ids = (array)$ids;

        return $this->put('post_parent__in', $ids)->exe();
    }

    /**
     * @param $ids
     *
     * @return Query
     */
    public function exclude($ids)
    {
        $ids = (array)$ids;

        return $this->put('post__not_in', $ids);
    }

    /**
     * @param $id
     *
     * @return WP_Query
     */
    function byID($id)
    {
        return $this->put('p', $id)->exe();
    }

    /**
     * @param $ids
     *
     * @return WP_Query
     */
    function byIDs($ids)
    {
        return $this->put('post__in', $ids)->exe();
    }

    /**
     * @param array|int $id
     *
     * @return WP_Query
     */
    public function notID($id)
    {
        return $this->notIDs($id);
    }

    /**
     * @param array $ids
     *
     * @return WP_Query
     */
    public function notIDs($ids)
    {
        return $this->exclude($ids)->exe();
    }

    /**
     * @return WP_Query
     */
    public function all()
    {
        return $this->perPage(-1)->exe();
    }

    /**
     * @return WP_Query
     */
    public function exe($args = array())
    {
        $_query = wp_parse_args($this->args->all(), $args);
        $_query = new WP_Query($_query);

        return $_query;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->args->get($key);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return Query
     */
    public function __set($key, $value)
    {
        return $this->put($key, $value);
    }

    /**
     * @param $name
     * @param $args
     *
     * @return Query
     */
    public function __call($name, $args)
    {
        $name = Str::snake($name);

        switch (count($args)):
            case 0:
                $this->__set($name, true);
                break;
            case 1:
                $this->__set($name, $args[0]);
                break;
            default:
                $this->__set($name, $args);
                break;
        endswitch;

        return $this;
    }
}