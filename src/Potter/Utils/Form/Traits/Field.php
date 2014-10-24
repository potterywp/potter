<?php namespace Potter\Utils\Form\Traits;

use HtmlObject\Element;

trait Field
{
    public $label;

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function replaceAttributes($attributes)
    {
        if (isset($attributes['label'])):
            $this->label = $attributes['label'];
            unset($attributes['label']);
        else:
            $this->label = $this->name;
        endif;

        $this->attributes = (array)$attributes;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $label = Element::label($this->label)->for($this->id);

        $p = Element::p()->nest(
            [
                'label' => $label,
                'field' => parent::render()
            ]
        );

        return $p->render();
    }
}