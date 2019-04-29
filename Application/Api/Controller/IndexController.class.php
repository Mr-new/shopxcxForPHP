<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    //初始化数据：奖品列表、中奖名单、砸蛋次数、
    public function getDefaultData(){
        $prizeTable=M('prize');
        //获取奖品列表
        $prizeList=$prizeTable->where("type=1")->field('id,prize,remarks,imgid')->order("id asc")->select();
        $images=A('Common/Images');
        $prizeList=$images->getImagesList($prizeList,'imgid', 'imgUrl');
        foreach ($prizeList as $k=>$v){
            if($prizeList[$k]['remarks']==null){
                $prizeList[$k]['remarks']='';
            }
        }
        //获取规则列表信息
        $rulesTable=M('activity_rules');
        $rulesList=$rulesTable->field('id,content')->select();
        //获取中奖名单列表
        $smasheggsrecordTable=M('smasheggsrecord');
        $list=$smasheggsrecordTable->limit(20)->order('datetime desc')->select();
        $prizeTable=M('prize');
        $userTable=M('user');
        foreach ($list as $k=>$v){
            $list[$k]['prize']=$prizeTable->where("id='{$v['prizeid']}'")->getField('prize');
            $list[$k]['nickName']=$userTable->where("id='{$v['userid']}'")->getField('nickName');
            if(empty($list[$k]['nickName'])){
                unset($list[$k]);
            }else{
                $list[$k]['nickName']=$this->hideStr($list[$k]['nickName'],1,1,4);
                //仅显示日期，不显示时间
                $list[$k]['datetime']=date("Y-m-d",strtotime($list[$k]['datetime']));
            }

            unset($list[$k]['userid']);
            unset($list[$k]['prizeid']);
        }
        $result=array(
            'success'=>true,
            'msg'=>'请求成功',
            'data' => array(
                'prizeList' => $prizeList,
                'rulesList' => $rulesList,
                'winPrizeList' => $list
            )
        );
        $this->ajaxReturn($result);
    }
    //获取用户获奖记录
    public function getUserPrizeRecord(){
        $userId=I('userId');
        $smasheggsrecordTable=M('smasheggsrecord');
        $list=$smasheggsrecordTable->where("userid='$userId'")->order('datetime desc')->select();
        $prizeTable=M('prize');
        foreach ($list as $k=>$v){
            $list[$k]['prize']=$prizeTable->where("id='{$v['prizeid']}'")->getField('prize');
            $remarks=$prizeTable->where("id='{$v['prizeid']}'")->getField('remarks');
            if(!empty($remarks)){
                $list[$k]['remarks']=$remarks;
            }

            $list[$k]['imgId']=$prizeTable->where("id='{$v['prizeid']}'")->getField("imgid");
            unset($list[$k]['userid']);
            unset($list[$k]['prizeid']);
        }
        $images=A('Common/Images');
        $slist=$images->getImagesList($list,'imgId', 'imgUrl');
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $slist
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'暂无记录',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //砸蛋操作
    public function smashEggs(){
//        $prize_arr = array(
//            '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1),
//            '1' => array('id'=>2,'prize'=>'数码相机','v'=>5),
//            '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
//            '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12),
//            '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
//            '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
//        );
        $userId=I('userId');
        $userTable=M('user');
        $frequency=$userTable->where("id=$userId")->getField("frequency");
        if($frequency==0){
            $result=array(
                'success'=>false,
                'msg'=>'请求成功',
                'data' => '您的砸蛋次数暂时用完了'
            );
        }else{
            $prizeTable=M('prize');
            $prize_arr=$prizeTable->order("id asc")->select();
            foreach ($prize_arr as $key => $val) {
                $arr[$val['id']] = $val['v'];
            }
            $rid = $this->get_rand($arr); //根据概率获取奖项id
            $res['yes'] = array(
                'id' => $prize_arr[$rid-1]['id'],
                'type' => $prize_arr[$rid-1]['type'],
                'prize' => $prize_arr[$rid-1]['prize'] //中奖项
            );
            unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项
            shuffle($prize_arr); //打乱数组顺序
            for($i=0;$i<count($prize_arr);$i++){
                $pr[] = $prize_arr[$i]['prize'];
            }
            $res['no'] = $pr;
            $recordTable=M('smasheggsrecord');
            $data['userid']=$userId;
            $data['prizeid']=$res['yes']['id'];
            $data['status']=1;
            $data['code']=$this->rand(4);
            $data['datetime']=date('Y-m-d H:i:s',time());
            if($res['yes']['type']!=2) {
                //中奖
                $add=$recordTable->add($data);
                if($add){
                    $userTable->where("id=$userId")->setDec('frequency');  //减少一次砸蛋机会
                    $currentFrequency=$userTable->where("id=$userId")->getField("frequency");
                    $res['yes']['recordId']=$add;
                    $res['yes']['code']=$data['code'];
                    $result=array(
                        'success'=>true,
                        'msg'=>'请求成功',
                        'data' => array(
                            'yes' => $res['yes'],
                            'currentFrequency' => $currentFrequency,
                        )
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'请求失败',
                        'data' => '数据库写入失败'
                    );
                }
            }else{
                //未中奖
                $userTable->where("id=$userId")->setDec('frequency');  //减少一次砸蛋机会
                $currentFrequency=$userTable->where("id=$userId")->getField("frequency");
                $result=array(
                    'success'=>false,
                    'msg'=>'请求成功',
                    'data' => array(
                        'yes' => $res['yes'],
                        'currentFrequency' => $currentFrequency,
                    )
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //生成不重复字符串
    function rand($len=4){
        return substr(md5(microtime(true)), 0, $len);
    }
    //概率计算方法
    public function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
    //用户点击立即领奖操作：将当前中奖记录状态改为2
    public function receivePrizes(){
        $recordId=I('recordId');
        $smasheggsrecordTable=M('smasheggsrecord');
        $save=$smasheggsrecordTable->where("id=$recordId")->setField('status',2);
        if($save){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => '正在领取中'
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => '数据库写入错误'
            );
        }
        $this->ajaxReturn($result);
    }


    /**
    +----------------------------------------------------------
     * 将一个字符串部分字符用*替代隐藏
    +----------------------------------------------------------
     * @param string    $string   待转换的字符串
     * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string    $glue     分割符
    +----------------------------------------------------------
     * @return string   处理后的字符串
    +----------------------------------------------------------
     */
    public function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@") {
        if (empty($string))
            return false;
        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", $array);
        } else if ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", array_reverse($array));
        } else if ($type == 2) {
            $array = explode($glue, $string);
            $array[0] = hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
        } else if ($type == 3) {
            $array = explode($glue, $string);
            $array[1] = hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
        } else if ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i]))
                    $tem[] = $i >= $left ? "*" : $array[$i];
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                $tem[] = $array[$i];
            }
            $string = implode("", $tem);
        }
        return $string;
    }
    //内部码使用
    public function userInCode(){
        $inCode=I('inCode');
        $userId=I('userId');
        $recordTable=M('code_record');
        $find=$recordTable->where("content='$inCode'")->field("id,number,status")->find();
        if($find['status']==1){
            $userTable=M('user');
            $userSet=$userTable->where("id=$userId")->setInc('frequency',$find['number']);
            if($userSet){
                $data['status']=2;
                $data['enddatetime']=date('Y-m-d H:i:s',time());
                $save=$recordTable->where("content='$inCode'")->save($data);
                if($save){
                    $frequency=$userTable->where("id=$userId")->getField("frequency");
                    $addFrequencyRecordTable=M('add_frequency_record');
                    $msg['type']=4;
                    $msg['userId']=$userId;
                    $msg['date']=date('Y-m-d');
                    $addFrequencyRecordTable->add($msg);
                    $result=array(
                        'success'=>true,
                        'msg'=>"请求成功",
                        'data' => "恭喜您成功兑换{$find['number']}次砸蛋机会",
                        'frequency' => $frequency
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'请求失败',
                        'data' => '更新兑换码失败'
                    );
                }
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => '更新砸蛋次数失败'
                );
            }
        }else if($find['status']==2){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => '您输入的内部码已经被使用'
            );
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => '您输入的内部码无效'
            );
        }
        $this->ajaxReturn($result);
    }
    //添加砸蛋次数
    //userid:用户id
    //type:增加次数的类型：1每天一次机会，2关注公众号，3分享朋友，4内部码，5分享朋友圈
    public function addFrequency($userId,$type,$subId){
        if($type==3){
            $number=1;
            $data['subid']=$subId;
        }else if($type==2){
            $number=2;
        }else if($type==5){
            $number=5;
        }
        $userTable=M('user');
        $setFrequency=$userTable->where("id=$userId")->setInc('frequency',$number);
        $addFrequencyRecordTable=M('add_frequency_record');
        $data['type']=$type;
        $data['userId']=$userId;
        $data['date']=date('Y-m-d');
        $add=$addFrequencyRecordTable->add($data);
        if($setFrequency && $add){
            $frequency=$userTable->where("id=$userId")->getField('frequency');
            return $frequency;
        }else{
            return false;
        }
    }
    //分享给朋友
//    public function shareFriends(){
//        $userId=I('userId');
//        $addFrequencyRecordTable=M('add_frequency_record');
////        $currentDate=date('Y-m-d');
//        $count=$addFrequencyRecordTable->where("userId=$userId and type=3")->count();
//        if($count<10){
//            $res=$this->addFrequency($userId,3);
//            if($res){
//                $result=array(
//                    'success'=>true,
//                    'msg'=>'请求成功',
//                    'data' => '恭喜您获得一次砸蛋机会',
//                    'frequency' => $res
//                );
//            }else{
//                $result=array(
//                    'success'=>false,
//                    'msg'=>'请求失败',
//                    'data' => '数据库写入错误'
//                );
//            }
//
//        }else{
//            $result=array(
//                'success'=>true,
//                'msg'=>'请求成功',
//                'data' => '分享给朋友最多可以获得10次机会哟'
//            );
//        }
//        $this->ajaxReturn($result);
//    }
    //分享到朋友圈
    public function shareFriendCircle(){
        $userId=I('userId');
        $addFrequencyRecordTable=M('add_frequency_record');
        $count=$addFrequencyRecordTable->where("userId=$userId and type=5")->count();
        if($count<1){
            $res=$this->addFrequency($userId,5);
            if($res){
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => '恭喜您获得5次砸蛋机会',
                    'frequency' => $res
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => '数据库写入错误'
                );
            }

        }else{
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => '您已经分享过朋友圈了哟'
            );
        }
        $this->ajaxReturn($result);
    }
    public function isPublicAddress(){
//        $userId=I('userId');
//        $APPID = "wx50f1e5dc0e001628";
//        $APPSECRET =  "547e7c0dbf676bbe2274b17813bce583";
//        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
//        $access_msg = json_decode(file_get_contents($access_token));
//        $token = $access_msg->access_token;
//        $userTable=M('user');
//        $openid=$userTable->where("id=$userId")->getField("openid");
//        $subscribe_msg = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid";
//        $subscribe = json_decode(file_get_contents($subscribe_msg));
//        $gzxx = $subscribe->subscribe;
//        echo '<pre>';
//        print_r($subscribe);
//        if($gzxx === 1){
//            echo "已关注";
//        }else{
//            echo "未关注";
//        }
        $userId=I('userId');
        $userTable=M('user');
        $unionid=$userTable->where("id=$userId")->getField("unionid");
        $addFrequencyRecordTable=M('add_frequency_record');
        $count=$addFrequencyRecordTable->where("userId=$userId and type=2")->count();
        if($unionid){
            $tempTable=M('publicnumber');
            $find=$tempTable->where("unionid='$unionid'")->find();
            if($find){
                if($count<1){
                    $res=$this->addFrequency($userId,2);
                    if($res){
                        $result=array(
                            'success'=>true,
                            'msg'=>'请求成功',
                            'data' => '恭喜您获得2次砸蛋机会',
                            'frequency' => $res
                        );
                    }else{
                        $result=array(
                            'success'=>false,
                            'msg'=>'请求失败',
                            'data' => '数据库写入错误'
                        );
                    }
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'请求失败',
                        'data' => '您已经领取过了哟'
                    );
                }
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => '您还没有关注公众号哟，关注公众号后即可领取2此次砸蛋机会'
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => '您还没有关注公众号哟，关注公众号后即可领取2此次砸蛋机会'
            );
        }
        $this->ajaxReturn($result);
    }
    //获取用户砸蛋次数
    public function getUserFrequency(){
        $userId=I('userId');
        $userTable=M('user');
        $frequency=$userTable->where("id=$userId")->getField("frequency");
        if($frequency){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $frequency,
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => 0,
            );
        }
        $this->ajaxReturn($result);
    }
}