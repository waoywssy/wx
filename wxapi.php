<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "wx_test");
access_control_header();
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
        public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
                echo $echoStr;
                exit;
        }
    }

    public function responseMsg()
    {
                //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){

                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $r_msgType = trim($postObj->MsgType);   //收到的消息类型
                $time = time();
                $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
                        </xml>";
                if(!empty( $keyword ))
                {
                        if($keyword == 'yhl'){
                                $contentStr = "Hello, World!";
                        }else{
                                $contentStr = "Welcome to wechat world!";
                        }
                        $msgType = "text";
              //        $contentStr = "Welcome to wechat world!";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                }else{
                        echo "Input something...";
                }

        }else {
                echo "";
                exit;
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

                $token = TOKEN;
                $tmpArr = array($token, $timestamp, $nonce);
                sort($tmpArr);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );

                if( $tmpStr == $signature ){
                        return true;
                }else{
                        return false;
                }
        }
}
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
//                    $response = APF::get_instance()->get_response();
                    header("Access-Control-Allow-Origin:".$hostname,TRUE,NULL);
                    header("Access-Control-Allow-Methods:POST",TRUE,NULL);
//                    $response->set_header('Access-Control-Allow-Origin', $hostname);
//                    $response->set_header('Access-Control-Allow-Methods', 'POST');
                }
            }
        }
    }
?>
