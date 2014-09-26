<?php
/**
 * wechat php test
 */
require_once('inc/global.php');
access_control_header();
$wechatObj = new AutoReply();
//$wechatObj->valid();
$wechatObj->responseMsg();
?>