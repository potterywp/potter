<?php

namespace Potter;

use Illuminate\Support\Collection;
use Super_CPT_Loader;

class PotterCore
{
    /**
     * @var array
     */
    protected $autoloadFolders = array('app/models');
    /**
     * @var array
     */
    protected $autolaodFiles = array('app/ThemeOptions.php');
    /**
     * @var string
     */
    protected $themeDIR;
    /**
     * @var string
     */
    protected $themeURI;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $models;

    public function __construct()
    {
        $this->themeDIR  = trailingslashit(get_template_directory());
        $this->themeURI  = trailingslashit(get_template_directory_uri());
        $SCPT_PLUGIN_URL = $this->themeURI . 'vendor/potterywp/super-cpt/';
        $SCPT_PLUGIN_DIR = $this->themeDIR . 'vendor/potterywp/super-cpt';

        Super_CPT_Loader::load($SCPT_PLUGIN_URL, $SCPT_PLUGIN_DIR);

        $this->models = new Collection();
    }

    /**
     * Load files
     * @return void
     */
    public function autoload()
    {
        $folders = apply_filters('potter_autoload_folders', $this->autoloadFolders);
        $files   = apply_filters('potter_autoload_files', $this->autolaodFiles);

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
    }


    /**
     * @param $file
     * @return void
     */
    private function  loadFile($file)
    {
        $file = cleanURI($file);
        if (!file_exists($file)) return;
        // Include
        require_once($file);

        $name = basename($file, '.php');

        // File class
        if (class_exists($name)):
            $class = new $name;
            //Model Class
            if (is_subclass_of($name, 'Potter\Model')):
                $type = $class->getPostType();
                $this->models->put($type, $class);
            endif;
        endif;
    }
}