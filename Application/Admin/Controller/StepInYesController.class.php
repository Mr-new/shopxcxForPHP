<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepInYesController extends BaseController {
    //获取中奖列表
    public function getInGiftList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $stepInGiftTable=M('step_ingift');
        $map['nickName']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
//        $map['code']=array('like',"%$select_word%");
        $map['gift']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$stepInGiftTable->join("wechat_step_user ON wechat_step_ingift.userid=wechat_step_user.id")->join("wechat_step_gift ON wechat_step_ingift.giftid=wechat_step_gift.id")->where($map)->field("wechat_step_ingift.id,wechat_step_ingift.datetime,wechat_step_user.nickName,wechat_step_user.tel,wechat_step_user.avatarUrl,wechat_step_user.city,wechat_step_user.province,wechat_step_gift.gift")->order('wechat_step_ingift.datetime desc')->page($pageIndex.",$number")->select();
        $count=$stepInGiftTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $list
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
    //导出为excel表格
    public function exportExcel(){
        $stepInGiftTable=M('step_ingift');
        $list=$stepInGiftTable->join("wechat_step_user ON wechat_step_ingift.userid=wechat_step_user.id")->join("wechat_step_gift ON wechat_step_ingift.giftid=wechat_step_gift.id")->field("wechat_step_ingift.id,wechat_step_ingift.datetime,wechat_step_user.nickName,wechat_step_user.tel,wechat_step_user.avatarUrl,wechat_step_user.city,wechat_step_user.province,wechat_step_gift.gift")->order('wechat_step_ingift.datetime desc')->select();
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
    //删除中奖名单操作
    public function deleteInYes(){
        $id=I('id');
        $stepInGiftTable=M('step_ingift');
        $del=$stepInGiftTable->where("id=$id")->delete();
        if($del){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $del
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取礼品列表
    public function getGiftList(){
        $giftTable=M('step_gift');
        $giftList=$giftTable->field("id,gift")->order("datetime desc")->select();
        if($giftList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $giftList
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
    //获取微信用户列表
    public function wechatUserList(){
        $userTable=M('step_user');
        $userList=$userTable->where("nickName!=''")->field("id,nickName")->order("datetime desc")->select();
        if($userList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $userList
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
    //添加中奖名单
    public function AddInGift(){
        $data['userid']=I('userid');
        $data['giftid']=I('giftid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $stepInGiftTable=M('step_ingift');
        $add=$stepInGiftTable->add($data);
        if($add){
            $result=array(
                'success'=>true,
                'msg'=>'添加成功',
                'data' => $add
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'添加失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }

}