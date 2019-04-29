<?php
namespace Step\Controller;
use Think\Controller;
class IndexController extends Controller {
    //获取门槛奖品列表
    public function getPrizeList(){
        $stepPrizeTable=M('step_prize');
        $list=$stepPrizeTable->order("id asc")->select();
        $images=A('Common/Images');
        $slist=$images->getImagesList($list,'imgid', 'imageUrl');
        if($slist){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $slist
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取我的卡券列表
    public function getMyCardList(){
        $userid=I('userid');
        $stepPrizeRecordTable=M('step_prize_record');
        $list=$stepPrizeRecordTable->join("wechat_step_prize ON wechat_step_prize_record.prizeid=wechat_step_prize.id")->where("wechat_step_prize_record.userid=$userid")->field("wechat_step_prize_record.id,wechat_step_prize.title")->order("wechat_step_prize_record.datetime desc")->select();
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $list
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //领取优惠券
    public function receivePrize(){
        $userid=I('userid');
        $prizeid=I('prizeid');
        $stepPrizeRecordTable=M('step_prize_record');
        $map['userid']=$userid;
        $map['prizeid']=$prizeid;
        $map['_logic']='and';
        $find=$stepPrizeRecordTable->where($map)->find();
        if($find){
            //此时说明用户已经领取过该券了，不能重复领取
            if($prizeid==4){
                $result = array(
                    'success' => false,
                    'msg' => '奖品都领取完了哟，恭喜您达成40000步获得抽奖机会!',
                    'data' => true
                );
            }else {
                $result = array(
                    'success' => false,
                    'msg' => '您已经领取过了哟，请继续健步达成下个目标再来领奖吧！',
                    'data' => ''
                );
            }
        }else{
            $data['userid']=$userid;
            $data['prizeid']=$prizeid;
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$stepPrizeRecordTable->add($data);
            if($add){
                $stepPrizeTable=M('step_prize');
                $prize=$stepPrizeTable->where("id=$prizeid")->getField("title");
                if($prizeid==4){
                    $result=array(
                        'success'=>true,
                        'msg'=>'恭喜您获得'.$prize."恭喜您达成40000步获得抽奖机会!",
                        'data' => true
                    );
                }else{
                    $result=array(
                        'success'=>true,
                        'msg'=>'恭喜您获得'.$prize,
                        'data' => ''
                    );
                }
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'领取失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //获取抽奖页面初始化数据
    public function getGiftDefaultData(){
        //获取礼品列表
        $giftList=M('step_gift')->order("id asc")->select();
        //获取达成目标人数及百分比
        $num=M('step_user')->count();  //总人数
        $stepSatisfyTable=M('step_satisfy');
        $satisfy=$stepSatisfyTable->count();  //达成目标人数
        $percentage=round($satisfy/$num*100,2)."%";  //百分比
        //获取达成目标人员信息
        $userList=$stepSatisfyTable->join("wechat_step_user ON wechat_step_satisfy.userid=wechat_step_user.id")->field("wechat_step_user.nickName,wechat_step_satisfy.datetime")->order('wechat_step_satisfy.id desc')->limit(20)->select();
        //获取中奖名单人员信息及奖品信息：如果没有查询到说明未开奖，查询到说明已开奖
        $inYesUser=M('step_ingift')->join("wechat_step_user ON wechat_step_ingift.userid=wechat_step_user.id")->join("wechat_step_gift ON wechat_step_ingift.giftid=wechat_step_gift.id")->field("wechat_step_user.nickName,wechat_step_ingift.datetime,wechat_step_gift.gift")->order('wechat_step_ingift.giftid asc')->select();
        if($inYesUser){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'giftList' => $giftList,
                    'satisfy' => $satisfy,
                    'percentage' => $percentage,
                    'userList' => $userList,
                    'inYesUser' => $inYesUser
                )
            );
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'giftList' => $giftList,
                    'satisfy' => $satisfy,
                    'percentage' => $percentage,
                    'userList' => $userList,
                    'inYesUser' => false
                )
            );
        }
        $this->ajaxReturn($result);
    }
    //获取规则信息
    public function getStepRules(){
        //获取规则列表信息
        $rulesTable=M('step_rules');
        $rulesList=$rulesTable->field('id,content')->find();
        if($rulesList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $rulesList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有查找到规则',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}