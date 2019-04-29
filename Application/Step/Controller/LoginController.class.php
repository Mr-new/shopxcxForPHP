<?php
namespace Step\Controller;
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
        $data['openid']=$arr['openid'];
        $data['unionid']=$arr['unionid'];
        //如果topId存在则说明此用户是通过别人的分享进入的小程序，如果topId不存在则说明该用户通过其他途径进入小程序
        if(empty($topId)){
            $data['topId']=0;
        }else{
            $data['topId']=$topId;
        }
        $data['step']=0;
        $data['datetime']=date('Y-m-d H:i:s',time());
        $userTable=M('step_user');
        $isUser=$userTable->where("openid='{$data['openid']}'")->find();
        if($isUser){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'userId' => $isUser['id'],
                )
            );
        }else{
            $add=$userTable->add($data);  //当前进入小程序用户id;
            if($add){
                if(!empty($topId)) {
                    $stepRecordTable = M('step_record');
                    $recordData['step'] = 5000;
                    $recordData['userid'] = $topId;
                    $recordData['date'] = null;
                    $stepRecordTable->add($recordData);
                }
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
        $userTable=M('step_user');
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
        $userTable=M('step_user');
        $beforeTel=$userTable->where("openid='$openid'")->getField('tel');
        if(!$beforeTel){
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
    //获取用户运动步数
    public function getUserStep(){
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
        $data = json_decode($data);  //用户运动步数信息
        $currentDate=date('Y-m-d');  //当前日期
        $currentStep=end($data->stepInfoList)->step;  //获取用户过去一个月步数中今天的步数
        $stepUserTable=M('step_user');
        $stepRecordTable=M('step_record');
        $find=$stepUserTable->where("openid='$openid'")->find();
        if($find){
            //此时说明查找用户信息成功
            $stepFind=$stepRecordTable->where("userid='{$find['id']}' and date='$currentDate'")->find();
            $stepData['step']=$currentStep;
            if($stepFind){
                //此时说明用户今天的步数已经被记录，应该执行更新用户当前步数操作
                $stepRecordTable->where("userid='{$find['id']}' and date='$currentDate'")->save($stepData);
                //返回用户步数信息
                $temp=$this->getSumStep($find['id']);

                $result=array(
                    'success'=>true,
                    'msg'=>'更新用户步数成功',
                    'data' => array(
                        'todayStep' => $currentStep,
                        'sumSetp' => $temp['sumSetp'],
                        'syStep' => $temp['syStep'],
                        'isLuckDraw' => $temp['isLuckDraw']
                    )
                );
            }else{
                //此时说明用户今天的步数没有被记录，应该执行添加用户今天步数操作
                $stepData['userid']=$find['id'];
                $stepData['date']=$currentDate;
                $add=$stepRecordTable->add($stepData);
                if($add){
                    //返回用户步数信息
                    $temp=$this->getSumStep($find['id']);
                    $result=array(
                        'success'=>true,
                        'msg'=>'添加用户步数成功',
                        'data' => array(
                            'todayStep' => $currentStep,
                            'sumSetp' => $temp['sumSetp'],
                            'syStep' => $temp['syStep'],
                            'isLuckDraw' => $temp['isLuckDraw']
                        )
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'添加用户步数失败',
                        'data' => ''
                    );
                }
            }
        }else{
            //此时未找到用户信息
            $result=array(
                'success'=>false,
                'msg'=>'没有找到用户信息',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取用户总步数
    public function getSumStep($userid){
        $stepRecordTable=M('step_record');
        $sumUserStep=$stepRecordTable->where("userid=$userid")->getField('step',true);
        $sumStep=0;
        for ($i=0;$i<count($sumUserStep);$i++) {
            $sumStep += $sumUserStep[$i];
        }
        if($sumStep<10000){
            $syStep=10000-$sumStep;
            $isLuckDraw=false;
        }else if($sumStep>=10000 && $sumStep<20000){
            $syStep=20000-$sumStep;
            $isLuckDraw=1;
        }else if($sumStep>=20000 && $sumStep<30000){
            $syStep=30000-$sumStep;
            $isLuckDraw=2;
        }else if($sumStep>=30000 && $sumStep<40000){
            $syStep=40000-$sumStep;
            $isLuckDraw=3;
        }else if($sumStep>=40000){
            $stepSatisfyTable=M('step_satisfy');
            $find=$stepSatisfyTable->where("userid=$userid")->find();
            if(!$find){
                $data['userid']=$userid;
                $data['datetime']=date('Y-m-d');  //当前日期
                $stepSatisfyTable->add($data);
            }
            $syStep=0;
            $isLuckDraw=4;
        }
        return array(
            'sumSetp' => $sumStep,
            'syStep' => $syStep,
            'isLuckDraw' => $isLuckDraw
        );
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