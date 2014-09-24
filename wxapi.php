<?php
/**
 * wechat php test
 */

require_once('inc/global.php');
access_control_header();

$wechatObj = new WechatRecieve();
//$wechatObj->valid();
$wechatObj->responseMsg();
?>
