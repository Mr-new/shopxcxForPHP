<?php
namespace Api\Controller;
use Think\Controller;
class TempController extends Controller{
    public function temp(){

        //1.获取到微信推送过来post数据（xml格式）
        //$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];//php7以上不能用
        $postArr = file_get_contents("php://input");
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //$postObj->ToUserName = '';
        //$postObj->FromUserName = '';
        //$postObj->CreateTime = '';
        //$postObj->MsgType = '';
        //$postObj->Event = '';
        // gh_e79a177814ed
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                $this->getUserInfo($postObj->FromUserName);
            }
                //回复用户消息(纯文本格式)
//                $toUser   = $postObj->FromUserName;
//                $fromUser = $postObj->ToUserName;
//                $time     = time();
//                $msgType  =  'text';
//                //$content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
//                $content  = '欢迎关注放哥的微信公众账号';
//                $template = "<xml>
//                            <ToUserName><![CDATA[%s]]></ToUserName>
//                            <FromUserName><![CDATA[%s]]></FromUserName>
//                            <CreateTime>%s</CreateTime>
//                            <MsgType><![CDATA[%s]]></MsgType>
//                            <Content><![CDATA[%s]]></Content>
//                            </xml>";
//                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
//                echo $info;
        }
//        $echoStr = $_GET["echostr"];
//        if($this->checkSignature()){
//            echo $echoStr;
//            exit;
//        }
        //验证服务器地址有效性,验证通过后把代码注销
        //1. 将timestamp , nonce , token 按照字典排序
//        $timestamp = $_GET['timestamp'];
//        $nonce = $_GET['nonce'];
//        $token = "yestar";
//        $signature = $_GET['signature'];
//        $array = array($timestamp,$nonce,$token);
//        sort($array);
//
////2.将排序后的三个参数拼接后用sha1加密
//        $tmpstr = implode('',$array);
//        $tmpstr = sha1($tmpstr);
//
////3. 将加密后的字符串与 signature 进行对比, 判断该请求是否来自微信
//        if($tmpstr == $signature)
//        {
//            echo $_GET['echostr'];
//            exit;
//        }

    }
    public function getUserInfo($openid){
        $APPID = "wx50f1e5dc0e001628";
        $APPSECRET =  "547e7c0dbf676bbe2274b17813bce583";
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
        $access_msg = json_decode(file_get_contents($access_token));
//        echo '<pre>';
//        print_r($access_msg);
//        exit();
        $token = $access_msg->access_token;
//        $userTable=M('user');
//        $openid="oN2FP0Qhszreg_9sc5OtHZB3S45Q";
        $subscribe_msg = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid";
        $subscribe = json_decode(file_get_contents($subscribe_msg));
        $publicnumberTable=M('publicnumber');
        $data['openid']=$subscribe->openid;
        $data['unionid']=$subscribe->unionid;
        $data['subscribe']=$subscribe->subscribe;
        $map['openid']=$data['openid'];
        $find=$publicnumberTable->where($map)->field("id")->find();
        if(!$find){
            $publicnumberTable->add($data);
//            echo 1;
        }
    }
    public function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = "yestar1992";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }
}