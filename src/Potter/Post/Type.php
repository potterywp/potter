<?php

namespace Potter\Post;

use Illuminate\Support\Str;
use Super_Custom_Post_Type;
use Potter\Utils\Metabox;

abstract class Type extends Super_Custom_Post_Type
{

    /**
     * @var array
     */
    protected $taxonomies = array();
    /**
     * @var array
     */
    protected $labels = array();
    /**
     * @var array
     */
    protected $capabilities = array();
    /**
     * @var array
     */
    protected $args = array();
    /**
     * @var array
     */
    protected $supports = array();
    /**
     * @var string
     */
    public $icon = 'dashicons-admin-post';
    /**
     * @var string
     */
    protected $capability_type = 'page';
    /**
     * @var boolean
     */
    protected $public;
    /**
     * @var boolean
     */
    protected $show_ui;
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

    protected $meta_boxes = array();

    public function __construct()
    {
        $type     = $this->getPostType();
        $singular = $this->singular;
        $plural   = $this->plural;

        if (!empty($this->meta_boxes)) $this->parseMetaboxes();

        parent::__construct($type, $singular, $plural);
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

    public function register_post_type($customizations = array())
    {
        if (is_bool($this->public)) $customizations['public'] = $this->public;
        if (is_bool($this->show_ui)) $customizations['show_ui'] = $this->show_ui;
        if (!empty($this->supports)) $customizations['supports'] = $this->supports;
        if (!empty($this->taxonomies)) $customizations['taxonomies'] = $this->taxonomies;
        if (!empty($this->capabilities)) $customizations['capabilities'] = $this->capabilities;
        if (!empty($this->capability_type)) $customizations['capability_type'] = $this->capability_type;
        if (!empty($this->description)) $customizations['description'] = $this->description;
        if (!empty($this->route)):
            $customizations['rewrite'] = array('slug' => $this->route, 'with_front' => true);
        endif;
        $customizations['labels'] = $this->getLabels();

        $customizations = wp_parse_args($this->args, $customizations);

        if (!empty($this->icon)) $this->set_icon($this->icon);

        parent::register_post_type($customizations);
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
              'view_item'          => __('View') . ' ' . __($this->plural),
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
}