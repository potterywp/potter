<?php namespace Potter\Utils\Form\Fields;

use HtmlObject\Element;
use HtmlObject\Traits\Tag;
use Illuminate\Support\Collection;
use Potter\Utils\Form\Traits\Field;

class Select extends Tag
{
    use Field {
        Field::render as realRender;
    }

    protected $element = 'select';

    /**
     * @var null|string|array
     */
    protected $selected;

    /**
     * @var Collection
     */
    protected $choices;

    public function __construct($type, $name = null, $selected = null, $attributes = array())
    {
        $this->selected = $selected;
        $this->choices  = new Collection();

        if ($type != 'select') $attributes['type'] = $type;

        if (isset($attributes['choices'])):
            $this->parseChoices($attributes['choices']);

            unset($attributes['choices']);
        endif;

        if (isset($attributes['value'])) unset($attributes['value']);

        $attributes['name'] = $name;

        $this->setTag('select', null, $attributes);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setSelected($value)
    {
        $this->selected = $value;

        return $this;
    }

    /**
     * @param array $choices
     *
     * @return $this
     */
    public function parseChoices(array $choices)
    {
        foreach ($choices as $value => $choice):
            $this->addChoice($choice, $value);
        endforeach;

        return $this;
    }

    /**
     * @param string|array $_choice
     * @param string       $_value
     *
     * @return $this
     */
    public function addChoice($_choice, $_value = null)
    {
        if (is_array($_choice)):
            $text  = array_get($_choice, 'label');
            $value = array_get($_choice, 'value');
            $atts  = array_except($_choice, ['label']);
        else:
            $text  = $_choice;
            $value = (is_null($_value)) ? $_choice : $_value;
            $atts  = ['value' => $value];
        endif;

        $choice = Element::create('option', $text);
        $choice->setAttributes($atts);

        if ($this->isSelected($value)) $choice->setAttribute('selected', 'selected');

        $this->choices->push($choice);

        return $this;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function isSelected($value)
    {
        if (is_array($this->selected)):
            return in_array($value, $this->selected);
        endif;

        return $this->selected == $value;
    }

    /**
     * @return string
     */
    public function render()
    {
        $value = $this->choices->all();

        $this->setValue($value);

        return $this->realRender();
    }

}