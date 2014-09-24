<?php
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
