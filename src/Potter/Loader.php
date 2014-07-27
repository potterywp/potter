<?php
namespace Potter;

use Potter\Theme\Features;

require_once('helpers.php');
add_filter('ot_theme_mode', '__return_true');
define('THEME_DIR', trailingslashit(get_template_directory()));
define('THEME_URL', trailingslashit(get_template_directory_uri()));
// Init potter
global $potter, $features, $OPT;

$core = new PotterCore();
$core->autoload();

$features = new Features();
$OPT      = new \OPT($core->getOptionsInstance());

$potter = new Potter($core, $features);