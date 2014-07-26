<?php

namespace Potter;

use Super_Custom_Post_Type;

abstract class Model extends Super_Custom_Post_Type
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

    public function __construct()
    {
        $type     = $this->getPostType();
        $singular = $this->singular;
        $plural   = $this->plural;

        parent::__construct($type, $singular, $plural);
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        if (empty($this->type)):
            $this->type = strtolower(str_replace('Model', '', get_class($this)));
        endif;

        return $this->type;
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
            $args['rewrite'] = array('slug' => $this->route, 'with_front' => true);
        endif;
        $customizations['labels'] = $this->getLabels();


        $customizations = wp_parse_args($this->args, $customizations);

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
}