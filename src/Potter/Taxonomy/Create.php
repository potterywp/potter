<?php namespace Potter\Taxonomy;

use Illuminate\Support\Str;
use Potter\Post\Type;

class Create
{

   /**
    * @var string
    */
   protected $name;

   /**
    * @var string
    */
   protected $singular;

   /**
    * @var string
    */
   protected $plural;
   /**
    * @var string
    */
   protected $route;

   /**
    * @var bool
    */
   protected $hierarchical = false;

   /**
    * @var array
    */
   protected $labels = [];

   /**
    * @var array
    */
   protected $post_types = [];

   /**
    * @var array
    */
   protected $tax = [];

   /**
    * @var array
    */
   protected $args = [];

   /**
    * @param string $name
    * @param string $singular
    * @param string $plural
    * @param string $hierarchical
    * @param array  $args
    * @param bool   $autoRegister
    */
   public function __construct($name = null, $singular = null, $plural = null, $hierarchical = null, $args = [], $autoRegister = true)
   {
      $this->setTaxName($name);
      $this->setSingular($singular);
      $this->setPlural($plural);

      if (is_bool($hierarchical)) $this->hierarchical = (bool)$hierarchical;

      $this->setArgs($args);

      if ($autoRegister) add_action('init', array($this, 'registerTaxonomy'));
   }

   /**
    * @param $name
    *
    * @return $this
    */
   public function setTaxName($name)
   {
      if (!empty($name)) $this->name = $name;

      if (empty($this->name)):
         $class      = get_class($this);
         $this->name = str_replace('Tax', '', $class);
         $this->name = str_replace('Taxonomy', '', $this->name);
         $this->name = Str::snake($this->name);
      endif;

      return $this;
   }

   /**
    * @param $singular
    *
    * @return $this
    */
   public function setSingular($singular)
   {
      if (!empty($singular)) $this->singular = $singular;

      if (empty($this->singular)) $this->singular = Str::title($this->name);

      return $this;
   }

   /**
    * @param $plural
    *
    * @return $this
    */
   public function setPlural($plural)
   {
      if (!empty($plural)) $this->plural = $plural;

      if (empty($this->plural)) $this->plural = Str::plural($this->singular);

      return $this;
   }

   /**
    * @param array $customizations
    *
    * @return $this
    */
   public function setArgs($customizations = array())
   {
      $defaults = [
         'label'        => $this->plural,
         'labels'       => $this->getLabels(),
         'hierarchical' => $this->hierarchical,
      ];

      if (!empty($this->route)):
         $defaults['rewrite'] = ['slug' => $this->route, 'with_front' => true];
      endif;

      $defaults = wp_parse_args($customizations, $defaults);

      $this->args = wp_parse_args($this->args, $defaults);

      return $this;
   }

   /**
    * @param array|string|Type $post_types
    *
    * @return $this
    */
   public function assignPostType($post_types)
   {
      if (!is_array($post_types)) $post_types = [$post_types];

      foreach ($post_types as &$post_type):
         if ($post_type instanceof Type) $post_type = $post_type->getPostType();
      endforeach;

      $this->post_types = array_merge($this->post_types, $post_types);

      return $this;
   }

   /**
    * @return array
    */
   protected function getLabels()
   {
      $labels = [
         'name'                       => __($this->plural),
         'singular_name'              => __($this->singular),
         'search_items'               => __('Search') . ' ' . $this->plural,
         'popular_items'              => __('Popular') . ' ' . $this->plural,
         'all_items'                  => __('All') . ' ' . $this->plural,
         'parent_item'                => __('Parent') . ' ' . $this->singular,
         'parent_item_colon'          => __('Parent') . ' ' . $this->singular . ':',
         'edit_item'                  => __('Edit') . ' ' . $this->singular,
         'update_item'                => __('Update') . ' ' . $this->singular,
         'add_new_item'               => __('Add') . ' ' . $this->singular,
         'new_item_name'              => __('New') . ' ' . $this->singular . ' Name',
         'separate_items_with_commas' => __('Separate') . ' ' . strtolower($this->plural) . ' ' . __('with commas'),
         'add_or_remove_items'        => __('Add or remove') . ' ' . strtolower($this->plural),
         'choose_from_most_used'      => __('Choose from the most used') . ' ' . strtolower($this->plural),
         'menu_name'                  => __($this->plural)
      ];

      return $this->labels = wp_parse_args($this->labels, $labels);
   }

   public function registerTaxonomy()
   {
      register_taxonomy($this->name, $this->post_types, $this->args);
   }
}