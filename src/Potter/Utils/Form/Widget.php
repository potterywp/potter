<?php namespace Potter\Utils\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class Widget
{
    /**
     * @var Collection
     */
    protected $fields;
    /**
     * @var \Potter\Utils\Widget
     */
    protected $widget;

    /**
     * @var Collection
     */
    protected $values;

    public function __construct(&$widget, $fields, $values)
    {
        $this->widget = $widget;
        $this->fields = new Collection();
        $this->values = new Collection($values);

        $this->parseFields($fields);

    }

    /**
     * @param $fields
     *
     * @return $this
     */
    public function parseFields($fields)
    {
        foreach ($fields as $id => $options):
            $field = $this->makeField($id, $options);
            $this->fields->put($id, $field);
        endforeach;

        return $this;
    }

    public function addField($id, $options)
    {
        $field = $this->makeField($id, $options);
        $this->fields->put($id, $field);

        return $this;
    }

    /**
     * @param $id
     * @param $options
     *
     * @return Field
     */
    protected function makeField($id, $options)
    {
        $options = wp_parse_args(
            $options, array(
                'label' => $id,
                'type'  => 'text',
                'id'    => $id,
                'name'  => $id,
                'value' => null,
                'class' => 'widefat'
            )
        );

        $options['name'] = $this->widget->get_field_name($options['name']);
        $options['id'] = $this->widget->get_field_name($options['id']);

        $value = $this->values->get($id, $options['value']);

        $filed = new Field($options['type'], $options['name'], $value, $options);

        return $filed;
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = [];
        foreach ($this->fields as $field):
            $html[] = $field->render();
        endforeach;

        return implode("\n", $html);
    }

    public function  __toString()
    {
        return $this->render();
    }
}