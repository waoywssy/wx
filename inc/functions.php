<?php

//允许QQ跨域访问
function access_control_header () {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $pattern = ';^(https?://)?(([a-zA-Z][a-zA-Z0-9\-]*\.)+[a-zA-Z][a-zA-Z0-9\-]*);';
        $hostname = $_SERVER['HTTP_REFERER'];
        $flag = preg_match($pattern, $hostname, $matches);
        if ($flag) {
            if ($matches[1]) {
                $hostname = $matches[0];
            } else {
                $hostname = 'http://' + $matches[0];
            }
            if (preg_match(';.+\.(qq)\.(com|test);i', $hostname, $m)) {
                header("Access-Control-Allow-Origin:".$hostname,TRUE,NULL);
                header("Access-Control-Allow-Methods:POST",TRUE,NULL);
            }
        }
    }
}

//获取config配置
function get_config($key){
    if($key == 'db'){
        return false;
    }
    global $config;
    return $config[$key];
}

//获取数据库配置
function get_db_config($key){
    global $config;
    return $config['db'][$key];
}

//获取微信access_token
function get_access_token(){
    if(isset($_COOKIE['wx_access_token'])){
        return $_COOKIE['wx_access_token'];
    }
    $appid = get_config('WX_APPID');
    $appsecrete = get_config('WX_APPSECRET');
    $wx_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecrete;
    $curl = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl,CURLOPT_URL,$wx_url);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($curl);
    $info = json_decode($result);
    setcookie('wx_access_token',$info->access_token,time()+intval($info->expires_in));
    return $info->access_token;
}