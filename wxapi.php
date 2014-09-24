<?php
/**
 * wechat php test
 */

require_once('inc/global.php');
access_control_header();
spl_autoload('WechatRecieve');echo "string";
$wechatObj = new WechatRecieve();
var_dump($wechatObj->type_arr);
//$wechatObj->valid();
$wechatObj->responseMsg();
?>
