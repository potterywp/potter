<?php

namespace Potter;

use Illuminate\Support\Collection;
use Potter\Theme\OptionsEmpty;
use Super_CPT_Loader;

class PotterCore
{
    /**
     * @var array
     */
    protected $autoloadFolders = array('app/models');
    /**
     * @var string
     */
    protected $widgetsFolder = 'app/widgets';
    /**
     * @var array
     */
    protected $autolaodFiles = array();
    /**
     * @var string
     */
    protected $themeDIR;
    /**
     * @var string
     */
    protected $themeURL;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $models;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $widgets;

    /**
     * @var \Potter\Theme\Options
     */
    protected $optionsInstance;

    public function __construct()
    {
        $this->themeDIR = THEME_DIR;
        $this->themeURL = THEME_URL;
        $SCPT_PLUGIN_URL = $this->themeURL . 'vendor/potterywp/super-cpt/';
        $SCPT_PLUGIN_DIR = $this->themeDIR . 'vendor/potterywp/super-cpt';

        Super_CPT_Loader::load($SCPT_PLUGIN_URL, $SCPT_PLUGIN_DIR);

        $this->models = new Collection();
        $this->widgets = new Collection();
    }

    /**
     * Load files
     * @return void
     */
    public function autoload()
    {
        $folders = apply_filters('potter_autoload_folders', $this->autoloadFolders);
        $files = apply_filters('potter_autoload_files', $this->autolaodFiles);

        // Files
        foreach ($files as $file) {
            $file = cleanURI($this->themeDIR . $file);
            $this->loadFile($file);
        }

        // Folders
        foreach ($folders as $folder):
            $pattern = $this->themeDIR . $folder . "/*.php";
            foreach (glob($pattern) as $file):
                $this->loadFile($file);
            endforeach;
        endforeach;

        $this->loadWidgets();
        $this->loadThemeOptions();
    }


    /**
     * @param $file
     *
     * @return mixed
     */
    public function  loadFile($file)
    {
        $file = cleanURI($file);
        if (!file_exists($file)) return false;;
        // Include
        require_once($file);

        $name = basename($file, '.php');

        // File class
        if (class_exists($name)):
            $class = new $name;
            //Model Class
            if (is_subclass_of($name, 'Potter\Post\Type')):
                $this->registerModel($class);
            endif;

            return $class;
        endif;

        return true;
    }

    /**
     * Load and register widgets
     */
    protected function loadWidgets()
    {
        $widgets_folder = apply_filters('potter_widgets_folder', $this->widgetsFolder);

        $pattern = $this->themeDIR . $widgets_folder . "/*.php";

        foreach (glob($pattern) as $file): // Load File
            require_once($file);

            $widget = basename($file, '.php');

            // Register
            if (class_exists($widget)):
                $this->widgets->push($widget);
            endif;
        endforeach;
    }

    /**
     * Load end register theme options
     */
    private function loadThemeOptions()
    {
        $file = cleanURI($this->themeDIR . 'app/ThemeOptions.php');
        $file = apply_filters('potter_autoload_themeoptions', $file);

        $class = $this->loadFile($file);

        if (!$class):
            $class = new OptionsEmpty();
            add_action('admin_menu', array($this, 'remove_ot_theme_options_page'), 999);
        endif;

        $this->optionsInstance = $class;
    }

    public function remove_ot_theme_options_page()
    {
        remove_submenu_page('themes.php', 'ot-theme-options');
    }

    /**
     * @return Collection
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @return Collection
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * @return Theme\Options
     */
    public function getOptionsInstance()
    {
        return $this->optionsInstance;
    }

    /**
     * @param Post\Type $postType
     */
    private function registerModel(Post\Type $postType)
    {
        $type = $postType->getPostType();

        $this->models->put($type, $postType);
    }
}