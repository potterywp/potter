<?php

namespace Potter\Post;

class QueryModel extends Query
{
    private $postType;
    private $_typeObject;

    public function __construct(Type &$_typeObject)
    {
        $this->_typeObject = $_typeObject;
        $this->postType    = $_typeObject->getPostType();

        $args = $_typeObject->getQueryArgs();

        parent::__construct($args, $this->postType);
    }
}