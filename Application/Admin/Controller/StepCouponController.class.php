<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepCouponController extends BaseController {
    //获取领券人员名单
    public function getStepPrizeList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $stepPrizeRecordTable=M('step_prize_record');
        $map['nickName']=array('like',"%$select_word%");
        $map['province']=array('like',"%$select_word%");
        $map['city']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
        $map['title']=array('like',"%$select_word%");
        $map['_logic'] = 'or';
        if($select_word){
            $list=$stepPrizeRecordTable->join("wechat_step_user ON wechat_step_prize_record.userid=wechat_step_user.id")->join("wechat_step_prize ON wechat_step_prize_record.prizeid=wechat_step_prize.id")->where($map)->order('wechat_step_prize_record.datetime desc')->field("wechat_step_prize_record.id,wechat_step_prize_record.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel,wechat_step_user.avatarurl,wechat_step_prize.title")->page($pageIndex.",$number")->select();
            $count=$stepPrizeRecordTable->join("wechat_step_user ON wechat_step_prize_record.userid=wechat_step_user.id")->join("wechat_step_prize ON wechat_step_prize_record.prizeid=wechat_step_prize.id")->where($map)->count();// 查询满足要求的总记录数
        }else{
            $list=$stepPrizeRecordTable->join("wechat_step_user ON wechat_step_prize_record.userid=wechat_step_user.id")->join("wechat_step_prize ON wechat_step_prize_record.prizeid=wechat_step_prize.id")->order('wechat_step_prize_record.datetime desc')->field("wechat_step_prize_record.id,wechat_step_prize_record.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel,wechat_step_user.avatarurl,wechat_step_prize.title")->page($pageIndex.",$number")->select();
            $count=$stepPrizeRecordTable->count();// 查询满足要求的总记录数
        }
        foreach ($list as $k=>$v){
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
        $stepPrizeRecordTable=M('step_prize_record');
        $userList=$stepPrizeRecordTable->join("wechat_step_user ON wechat_step_prize_record.userid=wechat_step_user.id")->join("wechat_step_prize ON wechat_step_prize_record.prizeid=wechat_step_prize.id")->order('wechat_step_prize_record.datetime desc')->field("wechat_step_prize_record.id,wechat_step_prize_record.datetime,wechat_step_user.nickName,wechat_step_user.gender,wechat_step_user.province,wechat_step_user.city,wechat_step_user.tel,wechat_step_user.avatarurl,wechat_step_prize.title")->select();
        foreach ($userList as $k=>$v){
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