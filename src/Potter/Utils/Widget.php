<?php
namespace Potter\Utils;

abstract class Widget extends \WP_Widget
{
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
}

