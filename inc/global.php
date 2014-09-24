<?php
define("TOKEN", "wx_test");
define(APP_PATH, realpath(dirname(dirname(__FILE__))));
define(MODEL_PATH, APP_PATH."/model/");
require_once(APP_PATH.'/inc/global.php');
require_once(APP_PATH.'/inc/functions.php');

// Add your class dir to include path
set_include_path(get_include_path().PATH_SEPARATOR.MODEL_PATH);
// You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
spl_autoload_extensions('.php');
// Use default autoload implementation
spl_autoload_register();