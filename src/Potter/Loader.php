<?php
namespace Potter;

use Potter\Theme\Features;
use RWMB_Loader;

require_once('helpers.php');

add_filter('ot_theme_mode', '__return_true');

define('THEME_DIR', trailingslashit(get_template_directory()));
define('THEME_URL', trailingslashit(get_template_directory_uri()));

$RWMB_URL = THEME_URL . 'vendor/rilwis/meta-box/';
$RWMB_DIR = THEME_DIR . 'vendor/rilwis/meta-box/';

RWMB_Loader::load($RWMB_URL, $RWMB_DIR);

// Init potter
global $potter, $features;

$core = new PotterCore();
$core->autoload();

$features = new Features();
\OPT::setInstanse($core->getOptionsInstance());

$potter = new Potter($core, $features);
