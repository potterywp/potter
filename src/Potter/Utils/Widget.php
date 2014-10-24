<?php
namespace Potter\Utils;

use Potter\Utils\Form\Widget as Form;

abstract class Widget extends \WP_Widget
{
    /**
     * @var array
     */
    protected $fields = array();

    public function __construct()
    {
        // ID Base
        if (empty($this->id_base)):
            $this->id_base = preg_replace('/(wp_)?widget_/', '', strtolower(get_class($this)));
        endif;
        // Name
        if (empty($this->name)):
            $this->name = $this->id_base;
        endif;
        // Option name
        if (empty($this->option_name)):
            $this->option_name = 'widget_' . $this->id_base;
        endif;
        // Widget Options
        if (empty($this->widget_options)):
            $this->widget_options = array('classname' => $this->option_name);
        endif;
        // Control Options
        if (empty($this->control_options)):
            $this->control_options = array('id_base' => $this->id_base);
        endif;
    }

    /**
     * @param array $instanse
     *
     * @return string|void
     */
    public function form($instanse)
    {
        $form = $this->makeForm($instanse);

        echo $form->render();
    }

    /**
     * @param array $instanse
     *
     * @return Form
     */
    protected function makeForm(array $instanse = array())
    {
        return new Form($this, $this->fields, $instanse);
    }
}