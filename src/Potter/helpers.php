<?php

use Potter\Theme\Options;

class OPT
{
    /**
     * @var Options
     */
    private static $instance = null;

    public function __construct(Options $instance)
    {
        self::$instance = $instance;
    }

    /**
     * @param string $option
     * @param string $default
     *
     * @return null|string
     */
    public function get($option, $default = null)
    {
        return ot_get_option($option, $default);
    }

    /**
     * @param string $option
     * @param string $default
     *
     * @return void
     */
    public function _get($option, $default = null)
    {
        echo $this->get($option, $default);
    }

    /**
     * @param        $option
     * @param string $default
     *
     * @return void
     */
    public function _nl2br_get($option, $default = null)
    {
        echo nl2br($this->get($option, $default));
    }

    /**
     * @param   string $option
     * @param   string $size
     * @param string $default
     *
     * @return null|string
     */
    public function get_optImg($option, $size, $default = null)
    {
        $id = $this->get($option, $default);
        if (empty($id)):
            return null;
        endif;

        $im = wp_get_attachment_image_src($id, $size);
        if (isset($im[0])):
            return $im[0];
        endif;

        return null;
    }

    public function __get($var)
    {
        return $this->get($var);
    }

    public function __call($name, $args)
    {
        array_unshift($args, $name);
        return call_user_func_array(array($this, 'get'), $args);
    }

    /**
     * @return \Options
     */
    public static function getInstance()
    {
        return self::$instance;
    }

}

/**
 * @return string|mixed|OPT
 */
function OPT($key = null, $default = null)
{
    global $OPT;
    if (empty($key)):
        return $OPT;
    endif;

    return $OPT->get($key, $default);
}

/**
 * @param string $str
 * @return string
 */
function cleanURI($str)
{
    return preg_replace('#/+#', '/', $str);
}
