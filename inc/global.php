<?php
define(TOKEN, "wx_test");
define(APP_PATH, realpath(dirname(dirname(__FILE__))));
define(MODEL_PATH, APP_PATH."/model");

require_once(APP_PATH.'/inc/config.php');
require_once(APP_PATH.'/inc/db.php');
require_once(APP_PATH.'/inc/functions.php');

//require model classes
require_once(MODEL_PATH."/AutoReply.php");
//require_once(MODEL_PATH."/Database.php");