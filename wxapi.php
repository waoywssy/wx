<?php
/**
 * wechat php test
 */

require_once('inc/global.php');
access_control_header();
require_once(MODEL_PATH."/WechatRecieve.php");
$wechatObj = new WechatRecieve();
//$wechatObj->valid();
$wechatObj->responseMsg();
?>
