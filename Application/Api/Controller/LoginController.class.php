<?php
namespace Api\Controller;
use Think\Controller;
class LoginController extends Controller {
    //小程序登陆操作
    public function login(){
        //开发者使用登陆凭证 code 获取 session_key 和 openid
        $APPID = C('APPID');
        $AppSecret = C('AppSecret');
        $code=I('code');
        $topId=I('topId');  //上级id
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID."&secret=".$AppSecret."&js_code=".$code."&grant_type=authorization_code";
        $arr = $this->vget($url);  // 一个使用curl实现的get方法请求
        $arr = json_decode($arr,true);
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];
        $session3rd  = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for($i=0;$i<16;$i++){
            $session3rd .=$strPol[rand(0,$max)];
        }
        S($session3rd, $openid.$session_key);  //记录redis缓存
        $data['openid']=$openid;
        $data['threevalue']=$openid.$session_key;
        $data['unionid']=$arr['unionid'];
        $addFrequencyRecordTable=M('add_frequency_record');
        //如果topId存在则说明此用户是通过别人的分享进入的小程序，如果topId不存在则说明该用户通过其他途径进入小程序
        if(empty($topId)){
            $data['topId']=0;
        }else{
            $data['topId']=$topId;
        }
        $data['datetime']=date('Y-m-d H:i:s',time());
        $userTable=M('user');

        $isUser=$userTable->where("openid='$openid'")->find();
        if($isUser){
            $currentDate=date('Y-m-d');
            $number=$addFrequencyRecordTable->where("userId={$isUser['id']} and date='$currentDate' and type=1")->count();
            if($number==0){
                $setFrequency=$userTable->where("id={$isUser['id']}")->setInc('frequency');
                if($setFrequency){
                    $msg['type']=1;
                    $msg['userId']=$isUser['id'];
                    $msg['date']=$currentDate;
                    $addFrequencyRecordTable->add($msg);
                }
            }
            $currentUserInfo=$userTable->where("id={$isUser['id']}")->find();
            if($isUser['threevalue']!=$openid.$session_key) {
                $save=$userTable->where("openid='$openid'")->setField('threevalue',$openid.$session_key);
                if($save){
                    $result=array(
                        'success'=>true,
                        'msg'=>'请求成功',
                        'data' => array(
                            'session3rd' => $session3rd,
                            'userId' => $isUser['id'],
                            'frequency' => $currentUserInfo['frequency'],
                            'number' => $number,
                            'arr' => $arr
                        )
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'请求失败',
                        'data' => '更新用户threevalue失败'
                    );
                }
            }else{
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => array(
                        'session3rd' => $session3rd,
                        'userId' => $isUser['id'],
                        'frequency' => $currentUserInfo['frequency'],
                        'number' => $number,
                        'arr' => $arr
                    )
                );
            }
        }else{
            $add=$userTable->add($data);  //当前进入小程序用户id;
            if($add){
                if(!empty($topId)){
                    $count=$addFrequencyRecordTable->where("userId=$topId and type=3 ")->count();
                    if($count<10){
                        $friendNumber=$addFrequencyRecordTable->where("userId=$topId and type=3 and subid=$add")->count();
                        if($friendNumber<=0) {
                            $index = A("Index");
                            $index->addFrequency($topId, 3,$add);
                        }
                    }
                }
                $adata=array(
                    'type' => 1,
                    'userId' => $add,
                    'date' => date('Y-m-d')
                );
                $addFrequencyRecordTable->add($adata);
                $frequency=$userTable->where("id=$add")->getField('frequency');
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => array(
                        'session3rd' => $session3rd,
                        'userId' => $add,
                        'frequency' => $frequency
                    )
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => '用户添加失败'
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //获取用户信息并绑定用户
    public function getUserInfo(){
        $userInfo=json_decode(htmlspecialchars_decode(I('userInfo')));
        $userId=I('userId');
        $userTable=M('user');
        $data['nickName']=$userInfo->nickName;
        $data['language']=$userInfo->language;
        $data['gender']=$userInfo->gender;
        $data['city']=$userInfo->city;
        $data['province']=$userInfo->province;
        $data['country']=$userInfo->country;
        $data['avatarUrl']=$userInfo->avatarUrl;
        $save=$userTable->where("id='$userId'")->save($data);
        if($save){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $userInfo
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求成功',
                'data' => "用户信息更新失败"
            );
        }
        $this->ajaxReturn($result);

    }
    //获取用户手机号
    public function getUserTel(){
        $APPID = C('APPID');
        $AppSecret = C('AppSecret');
        $code =I('code');
        $encryptedData = I('encryptedData');
        $iv = I('iv');
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID."&secret=".$AppSecret."&js_code=".$code."&grant_type=authorization_code";
        $arr = $this->vget($url);  // 一个使用curl实现的get方法请求
        $arr = json_decode($arr,true);
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];
        Vendor('weChat.wxBizDataCrypt');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $pc->decryptData($encryptedData, $iv, $data);
        $data = json_decode($data);
        $userTable=M('user');
        $beforeTel=$userTable->where("openid='$openid'")->getField('tel');
        if($beforeTel!=$data->phoneNumber){
            $arr['tel']=$data->phoneNumber;
            $arr['countryCode']=$data->countryCode;
            $save=$userTable->where("openid='$openid'")->save($arr);
            if($save){
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => $save
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => "数据库写入失败"
                );
            }
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => "当前手机号重复"
            );
        }
        $this->ajaxReturn($result);

    }

    //发起get请求
    public function vget($url){
        $info=curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info,CURLOPT_URL,$url);
        $output= curl_exec($info);
        curl_close($info);
        return $output;
    }
}