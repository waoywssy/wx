<?php
class AutoReply{
	public $type_arr = array(
        'text' , 'image' ,'voice' , 'video' , 'location' , 'link'
    );
    public function valid(){
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $r_msgType = trim($postObj->MsgType);   //收到的消息类型
            switch ($r_msgType) {
                case 'text':
                    $resultStr = $this->textHandle($postObj);
                    break;
                case 'event':
                    $resultStr = $this->eventHandle($postObj);
                    break;
                default:
                    $resultStr = "Unknow msg type: ".$r_msgType;
                    break;
            }
            echo $resultStr;
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
    /**
     * 处理文字信息
     */
    private function textHandle($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                </xml>";
        if(!empty( $keyword )){
            switch ($keyword) {
                case '程序猿':
                    $contentStr = "世界上最傻逼的职业";
                    break;
                case '你好':
                    $contentStr = "大家好，才是真的好";
                    break;
                case '挖掘机技术哪家强':
                    $contentStr = "中国山东找蓝翔！";
                    break;
                case '测试':
                    $contentStr = "测你妹啊";
                    break;
                case '你是谁':
                    $contentStr = "我是你爸爸";
                    break;
                default:
                    $contentStr = "你TM说的什么玩意儿，我听不懂！";
                    break;
            }
            $msgType = "text";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
        }else{
            return "Input something...";
        }
    }
    /**
     * 处理文字信息
     */
    private function eventHandle($postObj){
        $contentStr = "";
        switch ($postObj->Event){
            case "subscribe":
                $contentStr = "感谢您关注【鸟哥的测试账号】"."\n"."微信号：gh_4626b28cbfd8"."\n"."你可以跟我问好，可以问我你是谁，更可以问我挖掘机技术哪家强哦~";
                break;
            default :
                $contentStr = "Unknow Event: ".$postObj->Event;
                break;
        }
        $resultStr = $this->responseText($postObj, $contentStr);
        return $resultStr;
    }

    public function responseText($object, $content, $flag=0){
        $textTpl = "<xml>
            <ToUserName><!--[CDATA[%s]]--></ToUserName>
            <FromUserName><!--[CDATA[%s]]--></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><!--[CDATA[text]]--></MsgType>
            <Content><!--[CDATA[%s]]--></Content>
            <FuncFlag>%d</FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
}
