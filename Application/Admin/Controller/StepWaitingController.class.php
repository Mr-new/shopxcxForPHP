<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepWaitingController extends BaseController {
    //获取等待抽奖用户信息
    public function getStepWaitingList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $satisfyTable=M('step_satisfy');
        $map['nickName']=array('like',"%$select_word%");
        $map['province']=array('like',"%$select_word%");
        $map['city']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
        $map['_logic'] = 'or';
        if($select_word){
//            $list=$stepInGiftTable->join("wechat_step_user ON wechat_step_ingift.userid=wechat_step_user.id")->join("wechat_step_gift ON wechat_step_ingift.giftid=wechat_step_gift.id")->field("wechat_step_ingift.id,wechat_step_ingift.datetime,wechat_step_user.nickName,wechat_step_user.tel,wechat_step_user.avatarUrl,wechat_step_user.city,wechat_step_user.province,wechat_step_gift.gift")->order('wechat_step_ingift.datetime desc')->select();
            $list=$satisfyTable->join("wechat_step_user ON wechat_step_satisfy.userid=wechat_step_user.id")->where($map)->order('wechat_step_satisfy.datetime desc')->field("wechat_step_satisfy.id,wechat_step_satisfy.userid,wechat_step_satisfy.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel,wechat_step_user.avatarurl")->page($pageIndex.",$number")->select();
            $count=$satisfyTable->join("wechat_step_user ON wechat_step_satisfy.userid=wechat_step_user.id")->where($map)->count();// 查询满足要求的总记录数
        }else{
            $list=$satisfyTable->join("wechat_step_user ON wechat_step_satisfy.userid=wechat_step_user.id")->order('wechat_step_satisfy.datetime desc')->field("wechat_step_satisfy.id,wechat_step_satisfy.userid,wechat_step_satisfy.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel,wechat_step_user.avatarurl")->page($pageIndex.",$number")->select();
            $count=$satisfyTable->count();// 查询满足要求的总记录数
        }
        $stepRecordTable=M('step_record');
        foreach ($list as $k=>$v){
            $stepRecordList=$stepRecordTable->where("userid='{$list[$k]['userid']}'")->getField("step",true);
            $temp=0;
            foreach ($stepRecordList as $key=>$value){
                $temp+=$stepRecordList[$key];
            }
            $list[$k]['sumStep']=$temp;  //用户总运动步数
            if($list[$k]['gender']==1){
                $list[$k]['sex']="男";
            }else if($list[$k]['gender']==2){
                $list[$k]['sex']="女";
            }else{
                $list[$k]['sex']="未知";
            }
        }
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $list,
                    'count' => $count
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取导出表格数据
    public function exportExcel(){
        $satisfyTable=M('step_satisfy');
        $userList=$satisfyTable->join("wechat_step_user ON wechat_step_satisfy.userid=wechat_step_user.id")->order('wechat_step_satisfy.datetime desc')->field("wechat_step_satisfy.id,wechat_step_satisfy.userid,wechat_step_satisfy.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel")->select();
        $stepRecordTable=M('step_record');
        foreach ($userList as $k=>$v){
            $stepRecordList=$stepRecordTable->where("userid='{$userList[$k]['userid']}'")->getField("step",true);
            $temp=0;
            foreach ($stepRecordList as $key=>$value){
                $temp+=$stepRecordList[$key];
            }
            $userList[$k]['sumStep']=$temp;  //用户总运动步数
            if($userList[$k]['gender']==1){
                $userList[$k]['sex']="男";
            }else if($userList[$k]['gender']==2){
                $userList[$k]['sex']="女";
            }else{
                $userList[$k]['sex']="未知";
            }
        }
        if($userList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $userList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }

}