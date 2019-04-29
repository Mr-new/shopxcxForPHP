<?php
namespace Step\Controller;
use Think\Controller;
class QrcodeController extends Controller {
    //生成小程序二维码
    public function createQrcode(){
        $userId=I('userId');
        $APPID = C('APPID');
        $APPSECRET =  C('AppSecret');
        $imgUrl=C('imgurl');
        $file="Public/uploadImages/stepQrcode/$userId.png";
        if(file_exists($file)){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $imgUrl.'stepQrcode/'.$userId.'.png',
                'userid' => $userId
            );
        }else{
            //获取access_token
            $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
            $tokenResult=json_decode($this->httpRequest($access_token));
            $ACCESS_TOKEN=$tokenResult->access_token;
            $qcode ="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$ACCESS_TOKEN";
            $param = json_encode(array("scene"=>$userId,"page"=>"pages/step/step","width"=> 150));
            //POST参数
            $result = $this->httpRequest( $qcode, $param,"POST");
            //生成二维码
            $res=file_put_contents("Public/uploadImages/stepQrcode/$userId.png", $result);
            if($res){
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => $imgUrl.'stepQrcode/'.$userId.'.png',
                    'userid' => $userId
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => "二维码生成失败"
                );
            }
        }
        $this->ajaxReturn($result);
    }


    //把请求发送到微信服务器换取二维码
    function httpRequest($url, $data='', $method='GET'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}