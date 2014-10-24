<?php namespace Potter\Utils\Form\Fields;

use HtmlObject\Traits\Tag;
use Potter\Utils\Form\Traits\Field;

class Textarea extends Tag
{
    use Field;

    protected $element = 'textarea';

    public function __construct($type, $name = null, $value = null, $attributes = array())
    {
        if ($type != 'textarea') $attributes['type'] = $type;

        $attributes['name'] = $name;

        $this->setTag('textarea', $value, $attributes);
    }
}