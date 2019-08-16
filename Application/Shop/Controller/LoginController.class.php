<?php
namespace Shop\Controller;
use Think\Controller;
class LoginController extends Controller {
    //执行登陆操作并且绑定用户信息
    public function doLogin(){
        $APPID = C('APPID');
        $AppSecret = C('AppSecret');
        $code =$_POST['code'];
        $encryptedData = $_POST['encryptedData'];
        $iv = $_POST['iv'];
        $userTable=M('shop_user');
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID."&secret=".$AppSecret."&js_code=".$code."&grant_type=authorization_code";
        $Request=A('Common/Request');
        $arr = $Request->vget($url);  // 一个使用curl实现的get方法请求
        $arr = json_decode($arr,true);
        $session_key = $arr['session_key'];
        Vendor('weChat.wxBizDataCrypt');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $pc->decryptData($encryptedData, $iv, $data);
        $data = json_decode($data);
        $tempArr['nickName']=$data->nickName;
        $tempArr['language']=$data->language;
        $tempArr['gender']=$data->gender;
        $tempArr['city']=$data->city;
        $tempArr['province']=$data->province;
        $tempArr['country']=$data->country;
        $tempArr['avatarUrl']=$data->avatarUrl;
        $tempArr['openid']=$data->openId;
        $tempArr['unionid']=$data->unionId;
        $tempArr['integral']=0;
        $tempArr['datetime']=date('Y-m-d H:i:s',time());
        if($tempArr['openid']){
            $isUser=$userTable->where("openid='{$tempArr['openid']}'")->find();
            if($isUser){
                unset($tempArr['integral']);
                unset($tempArr['datetime']);
                $userTable->where("openid='{$tempArr['openid']}'")->save($tempArr);
                S($isUser['id'], $session_key);  //记录redis缓存
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => array(
                        'userId' => $isUser['id'],
                    )
                );
            }else{
                $add=$userTable->add($tempArr);
                if($add){
                    S($add, $session_key);  //记录redis缓存
                    $result=array(
                        'success'=>true,
                        'msg'=>'请求成功',
                        'data' => array(
                            'userId' => $add,
                        )
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'授权失败，请重试!',
                        'data' => ''
                    );
                }
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'授权失败，请重试!',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}