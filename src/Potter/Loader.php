<?php
namespace Potter;

require_once('helpers.php');

// Init potter
global $potter;

$core   = new PotterCore();
$core->autoload();

$potter = new Potter($core);