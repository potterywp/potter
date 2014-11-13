<?php

namespace Potter\Utils;

use Illuminate\Support\Collection;
use RW_Meta_Box;

class Metabox extends Collection
{
    protected $attributes;

    public function __construct(array $attributes, array $fields = array(), $autoRegister = true)
    {
        $this->parseAttributes($attributes);

        $this->addFields($fields);

        if ($autoRegister and is_admin()) add_filter('admin_init', array($this, 'register'));
    }


    /**
     * @param array $attributes
     */
    private function parseAttributes($attributes)
    {
        if (isset($attributes['fields']) && is_array($attributes['fields'])):
            $this->addFields($attributes['fields']);
            unset($attributes['fields']);
        endif;

        $default = array(
            'title'    => __('Data'),
            'pages'    => array('page'),
            'context'  => 'normal',
            'priority' => 'high',
            'autosave' => false,
        );

        $this->attributes = wp_parse_args($attributes, $default);
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $key => $field):
            $this->addField($key, $field);
        endforeach;

        return $this;
    }

    /**
     * @param string $id
     * @param array  $attributes
     *
     * @return $this
     */
    public function addField($id, array $attributes)
    {
        if (is_string($id) and !isset($attributes['id'])):
            $attributes['id'] = $id;
        endif;

        $this->push($attributes);

        return $this;
    }


    /**
     * @return array
     */
    public function getFields()
    {
        return $this->items;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @param array $pages
     *
     * @return $this
     */
    public function setPages()
    {
        $args = func_get_args();

        if (count($args) == 1):
            $pages = (array)$args[0];
        else:
            $pages = $args;
        endif;


        $this->setPages('pages', $pages);

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        return array_get($this->attributes, $key);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $box    = $this->attributes;
        $fields = $this->getFields();

        $box['fields'] = $fields;

        return $box;
    }

    /**
     * @see https://github.com/rilwis/meta-box/blob/master/demo/better-include.php#L60
     * @return bool
     */
    protected function canRegister()
    {
        // Include in back-end only
        if (!defined('WP_ADMIN') || !WP_ADMIN) return false;
        // Always include for ajax
        if (defined('DOING_AJAX') && DOING_AJAX) return true;

        $conditions = $this->get('conditions', []);

        if (empty($conditions)) return true;

        if (isset($_GET['post'])):
            $post_id = intval($_GET['post']);
        elseif (isset($_POST['post_ID'])):
            $post_id = intval($_POST['post_ID']);
        else:
            $post_id = false;
        endif;

        $post_id = (int)$post_id;

        $post = get_post($post_id);

        foreach ($conditions as $cond => $v):

            if (!is_array($v)) $v = array($v); // Catch non-arrays too

            switch ($cond):
                case 'id':
                    if (in_array($post_id, $v)) return true;

                    break;
                case 'parent':
                    $post_parent = $post->post_parent;
                    if (in_array($post_parent, $v)) return true;

                    break;
                case 'slug':
                    $post_slug = $post->post_name;
                    if (in_array($post_slug, $v)) return true;

                    break;
                case 'category': //post must be saved or published first
                    $categories = get_the_category($post->ID);
                    $catslugs   = array();

                    foreach ($categories as $category):
                        array_push($catslugs, $category->slug);
                    endforeach;

                    if (array_intersect($catslugs, $v)) return true;

                    break;
                case 'template':
                    $template = get_post_meta($post_id, '_wp_page_template', true);

                    if (in_array($template, $v)) return true;

                    break;
            endswitch;
        endforeach;

        return false;
    }


    /**
     * @return bool|RW_Meta_Box
     */
    public function register()
    {
        if(!$this->canRegister()) return false;

        $box = $this->toArray();

        return new RW_Meta_Box($box);
    }
}