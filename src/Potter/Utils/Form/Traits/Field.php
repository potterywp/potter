<?php namespace Potter\Utils\Form\Traits;

use HtmlObject\Element;

trait Field
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $container = 'p';

    /**
     * @var bool
     */
    public $inputCheckable = false;

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
     * @param $value
     *
     * @return $this
     */
    public function setLabel($value)
    {
        $this->label = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setContainer($value)
    {
        $this->container = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $label = Element::label($this->label)->for($this->id);

        if ($this->inputCheckable):
            $p = Element::create($this->container)->nest(
                [
                    'field' => parent::render(),
                    'label' => $label,
                    'close' => '<br>'
                ]
            );

        else:
            $p = Element::create($this->container)->nest(
                [
                    'label' => $label,
                    'field' => parent::render()
                ]
            );

        endif;

        return $p->render();
    }
}