<?php

namespace Potter\Post;

class QueryModel
{
    private $postType;
    private $_instanse;
    /**
     * @var Query;
     */
    private $query;

    public function __construct(Type &$_instanse)
    {
        $this->_instanse = $_instanse;
        $this->postType  = $_instanse->getPostType();

        $args = $_instanse->getQueryArgs();

        $this->newQuery($args);
    }

    public function newQuery($args = array())
    {
        $this->query = new Query($args, $this->postType);
    }

    /**
     * @return \WP_Query
     */
    public function exe()
    {
        return $this->query->exe();
    }
}