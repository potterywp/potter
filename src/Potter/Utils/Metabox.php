<?php

namespace Potter\Utils;

use Illuminate\Support\Collection;
use RW_Meta_Box;

class Metabox extends Collection
{
    protected $attributes;

    public function __construct(array $attributes, array $fields = array(), $autoRegister = true)
    {
        $this->parseAttributes($attributes);

        $this->addFields($fields);

        if ($autoRegister and is_admin()) add_filter('admin_init', array($this, 'register'));
    }


    /**
     * @param array $attributes
     */
    private function parseAttributes($attributes)
    {
        if (isset($attributes['fields']) && is_array($attributes['fields'])):
            $this->addFields($attributes['fields']);
            unset($attributes['fields']);
        endif;

        $default = array(
           'title'    => __('Data'),
           'pages'    => array('page'),
           'context'  => 'normal',
           'priority' => 'high',
           'autosave' => false,
        );

        $this->attributes = wp_parse_args($attributes, $default);
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $key => $field):
            $this->addField($key, $field);
        endforeach;

        return $this;
    }

    /**
     * @param string $id
     * @param array  $attributes
     *
     * @return $this
     */
    public function addField($id, array $attributes)
    {
        if (is_string($id) and !isset($attributes['id'])):
            $attributes['id'] = $id;
        endif;

        $this->push($attributes);

        return $this;
    }


    /**
     * @return array
     */
    public function getFields()
    {
        return $this->items;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @param array $pages
     *
     * @return $this
     */
    public function setPages()
    {
        $args = func_get_args();

        if (count($args) == 1):
            $pages = (array)$args[0];
        else:
            $pages = $args;
        endif;


        $this->setPages('pages', $pages);

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        return array_get($this->attributes, $key);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $box    = $this->attributes;
        $fields = $this->getFields();

        $box['fields'] = $fields;

        return $box;
    }

    /**
     * @return RW_Meta_Box
     */
    public function register()
    {
        $box = $this->toArray();

        return new RW_Meta_Box($box);
    }
}