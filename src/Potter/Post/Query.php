<?php

namespace Potter\Post;

use Illuminate\Support\Collection;
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
        if (!empty($postType)) $this->PostType($postType);

    }

    /**
     * @param $key
     * @param $value
     * @return Query;
     */
    private function put($key, $value)
    {
        $this->args->put($key, $value);

        return $this;
    }

    /**
     * @param $type
     * @return Query
     */
    public function postType($type)
    {
        return $this->put('post_type', $type);
    }

    public function perPage($perPage)
    {
        $this->put('posts_per_page', $perPage);
    }

    /**
     * @param $key
     * @param $value
     * @return Query
     */
    public function __set($key, $value)
    {
        return $this->put($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->args->get($key);
    }

    /**
     * @return WP_Query
     */
    public function exe()
    {
        $_query = new WP_Query($this->args->all());

        return $_query;
    }
}