<?php namespace Potter\Utils\Form;

use Illuminate\Support\Collection;

class Widget
{
    protected static $fieldsInput    = ['url', 'password', 'text', 'tel', 'number', 'date'];
    protected static $fieldsTextarea = ['textarea'];
    protected static $fieldsSelect   = ['select', 'multi-select'];
    protected static $fieldsCheckbox = ['checkbox'];
    protected static $fieldsRadio    = ['radio'];

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

    /**
     * @param $id
     * @param $options
     *
     * @return $this
     */
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
     * @return \HtmlObject\Traits\Tag
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
        $options['id']   = $this->widget->get_field_id($options['id']);

        $type = $options['type'];

        $value = $this->values->get($id, $options['value']);

        if (in_array($type, self::$fieldsInput)):
            $field = new Fields\Input($type, $options['name'], $value, $options);
        elseif (in_array($type, self::$fieldsTextarea)):
            $field = new Fields\Textarea($type, $options['name'], $value, $options);
        elseif (in_array($type, self::$fieldsSelect)):
            $field = new Fields\Select($type, $options['name'], $value, $options);
        elseif (in_array($type, self::$fieldsCheckbox)):
            $field = new Fields\Checkable($type, $options['name'], $value, $options);
        elseif (in_array($type, self::$fieldsRadio)):
            $field = new Fields\Radio($type, $options['name'], $value, $options);
        else:
            $field = new Fields\Input($type, $options['name'], $value, $options);
        endif;

        return $field;
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

    /**
     * @return string
     */
    public function  __toString()
    {
        return $this->render();
    }
}