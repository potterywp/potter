<?php namespace Potter\Utils\Form\Fields;

use HtmlObject\Element;
use HtmlObject\Traits\Tag;
use Illuminate\Support\Collection;

class Checkable extends Tag
{

    /**
     * @var null|string|array
     */
    protected $selected;

    /**
     * @var string
     */
    protected $type = 'checkbox';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    protected $isMultiple = true;

    /**
     * @var Collection
     */
    protected $choices;

    public function __construct($type, $name = null, $selected = null, $attributes = array())
    {
        $this->choices  = new Collection();
        $this->selected = $selected;
        $this->name     = ($this->isMultiple) ? $name . '[]' : $name;
        $this->id       = (isset($attributes['id'])) ? $attributes['id'] : uniqid('checkable-');

        if (isset($attributes['choices'])) $this->parseChoices($attributes['choices']);
        if (isset($attributes['label'])) $this->label = $attributes['label'];

        array_forget($attributes, ['value', 'type', 'name', 'choices', 'label']);

        $this->setTag('p', null, $attributes);

        $this->removeClass('widefat');
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

        if (!isset($atts['id'])) $atts['id'] = $this->id . '-' . $this->choices->count();

        $choice                 = new Input($this->type, $this->name, $value, $atts);
        $choice->inputCheckable = true;

        $choice->setAttribute('type', $this->type);

        if ($this->isSelected($value)) $choice->setAttribute('checked', 'checked');

        $choice->addClass($this->type);
        $choice->setLabel($text);
        $choice->setContainer(null);

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

        if (!empty($this->label)):
            $label = Element::create('label', $this->label);
            $this->nest($label, 'label');
            $this->nest('<br>');
        endif;

        $this->nest($value, 'choices');

        return parent::render();
    }

}