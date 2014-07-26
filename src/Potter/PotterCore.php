<?php

namespace Potter;
use Super_CPT_Loader;
class PotterCore
{
    public function __construct()
    {
        $SCPT_PLUGIN_URL = get_template_directory_uri().'/potter/vendor/potterywp/super-cpt/';
        $SCPT_PLUGIN_DIR = get_template_directory().'/potter/vendor/potterywp/super-cpt';
        Super_CPT_Loader::load($SCPT_PLUGIN_URL,$SCPT_PLUGIN_DIR);
    }
}