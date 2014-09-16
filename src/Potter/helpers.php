<?php

use Potter\Theme\Options;

class OPT
{
    /**
     * @var Options
     */
    private static $instance = null;

    public static function setInstanse(Options $instance)
    {
        self::$instance = $instance;
    }

    /**
     * @param string $option
     * @param string $default
     *
     * @return null|string
     */
    public static function get($option, $default = null)
    {
        return ot_get_option($option, $default);
    }

    /**
     * @param string $option
     * @param string $default
     *
     * @return void
     */
    public static function _get($option, $default = null)
    {
        echo self::get($option, $default);
    }

    /**
     * @param        $option
     * @param string $default
     *
     * @return string
     */
    public static function get_nl2br($option, $default = null)
    {
        return nl2br(self::get($option, $default));
    }

    /**
     * @param string $option
     * @param null   $default
     *
     * @return void
     */
    public static function _get_nl2br($option, $default = null)
    {
        echo self::get_nl2br($option, $default);
    }

    /**
     * @param   string $option
     * @param   string $size
     * @param string   $default
     *
     * @return null|string
     */
    public static function get_optImg($option, $size, $default = null)
    {
        $id = self::get($option, $default);
        if (empty($id)):
            return null;
        endif;

        $image = wp_get_attachment_image_src($id, $size);

        return array_get($image, 0, null);
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return null|string
     */
    public static function __callStatic($name, $args)
    {
        $default = (empty($args)) ? null : $args[0];

        return self::get($name, $default);
    }

    /**
     * @return Options
     */
    public static function getInstance()
    {
        return self::$instance;
    }

}

/**
 * @param string $str
 *
 * @return string
 */
function cleanURI($str)
{
    return preg_replace('/([^:])(\/{2,})/', '$1/', $str);
}


/**
 * @param null $path
 *
 * @return string
 */
function theme_url($path = null)
{
    return cleanURI(THEME_URL . $path);
}