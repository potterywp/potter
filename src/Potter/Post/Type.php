<?php

namespace Potter\Post;

use Illuminate\Support\Str;
use Potter\Utils\Metabox;

abstract class Type
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var array
     */
    protected $args = array();
    /**
     * @var bool
     */
    protected $autoRegister = true;
    /**
     * @var array
     */
    protected $taxonomies = array();
    /**
     * @var array
     */
    protected $labels = array();
    /**
     * @var string
     */
    protected $singular;
    /**
     * @var string
     */
    protected $plural;
    /**
     * @var array
     */
    protected $capabilities = array();
    /**
     * @var array
     */
    protected $supports = array('title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'page-attributes');
    /**
     * @see https://developer.wordpress.org/resource/dashicons/
     * @var string
     */
    protected $icon = 'dashicons-admin-post';
    /**
     * @var string
     */
    protected $capability_type = 'page';
    /**
     * @var boolean
     */
    protected $public = true;
    /**
     * @var boolean
     */
    protected $show_ui = true;
    /**
     * @var bool
     */
    protected $has_archive = true;
    /**
     * @var int
     */
    protected $menu_position = 5;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $route;
    /**
     * @var array
     */
    protected $queryArgs = array();
    /**
     * @var bool
     */
    protected $hierarchical = false;
    /**
     * @var array
     */
    protected $meta_boxes = array();

    public function __construct()
    {
        $this->setSingular();
        $this->setPlural();

        if (!empty($this->meta_boxes)) $this->parseMetaboxes();

        if ($this->autoRegister) add_action('init', array($this, '_register'));
    }

    /**
     * @param string $singular
     *
     * @return $this
     */
    public function setSingular($singular = null)
    {
        if (!empty($singular)) $this->singular = $singular;

        if (empty($this->singular)) $this->singular = Str::title($this->getPostType());

        return $this;
    }

    /**
     * @param string $plural
     *
     * @return $this
     */
    public function setPlural($plural = null)
    {
        if (!empty($plural)) $this->plural = $plural;

        if (empty($this->plural)) $this->plural = Str::plural($this->singular);

        return $this;
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        if (empty($this->type)):
            $class      = get_class($this);
            $this->type = str_replace('Model', '', $class);
            $this->type = str_replace('Type', '', $this->type);
            $this->type = Str::snake($this->type);
        endif;

        return $this->type;
    }

    /**
     * @return array
     */
    protected function parseMetaboxes()
    {
        $default = array(
            'title'    => __('Data'),
            'pages'    => array($this->getPostType()),
            'context'  => 'normal',
            'priority' => 'high',
            'fields'   => array()
        );

        foreach ($this->meta_boxes as $key => $mbox):
            $default['id'] = $key;
            $attributes    = wp_parse_args($mbox, $default);

            new Metabox($attributes);

        endforeach;
    }

    /**
     * @param array $customArgs
     *
     * @return array
     */
    protected function getArgs($customArgs = [])
    {
        $args = [
            'label'         => $this->plural,
            'description'   => $this->description,
            'labels'        => $this->getLabels(),
            'supports'      => $this->supports,
            'taxonomies'    => $this->taxonomies,
            'hierarchical'  => $this->hierarchical,
            'public'        => $this->public,
            'show_ui'       => $this->show_ui,
            'menu_position' => $this->menu_position,
            'has_archive'   => $this->has_archive,
            'menu_icon'     => $this->icon ? $this->icon : false,
        ];

        if (!empty($this->route)):
            $args['rewrite'] = array('slug' => $this->route, 'with_front' => true);
        endif;

        $args = wp_parse_args($args, $customArgs);

        return wp_parse_args($this->args, $args);
    }

    /**
     * @return array
     */
    private function getLabels()
    {
        return wp_parse_args(
            $this->labels,
            array(
                'name'               => __($this->plural),
                'singular_name'      => __($this->singular),
                'menu_name'          => __($this->plural),
                'parent_item_colon'  => __('Parent Item:'),
                'all_items'          => __($this->plural),
                'view_item'          => __('View') . ' ' . __($this->singular),
                'add_new_item'       => __('Add') . ' ' . __($this->singular),
                'add_new'            => __('Add') . ' ' . __($this->singular),
                'edit_item'          => __('Edit') . ' ' . __($this->singular),
                'update_item'        => __('Update') . ' ' . __($this->singular),
                'search_items'       => __('Search') . ' ' . __($this->singular),
                'not_found'          => __('Not found'),
                'not_found_in_trash' => __('Not found in Trash'),
            )
        );
    }

    /**
     * @return array
     */
    public function getQueryArgs()
    {
        return $this->queryArgs;
    }

    /**
     * Extra commands to be executed before registration.
     */
    protected function beforeRegister()
    {
        // Do what you want.
    }

    /**
     * Register
     */
    public function _register()
    {
        $this->beforeRegister();

        $args = $this->getArgs();

        register_post_type($this->type, $args);
    }
}