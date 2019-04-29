<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class GrantPrizeController extends BaseController {
    //获取发放奖品列表
    public function getGrantPrizeList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $grantPrizeTable=M('grant_prize');
        $map['username']=array('like',"%$select_word%");
        $list=$grantPrizeTable->join("wechat_admin_user ON wechat_grant_prize.adminuserid=wechat_admin_user.id")->join("wechat_user ON wechat_grant_prize.wechatuserid=wechat_user.id")->join("wechat_prize ON wechat_grant_prize.prizeid=wechat_prize.id")->where($map)->field("wechat_grant_prize.id,wechat_grant_prize.wechatuserid,wechat_grant_prize.prizeid,wechat_grant_prize.datetime,wechat_admin_user.username,wechat_user.nickName,wechat_user.tel,wechat_prize.prize")->order('wechat_grant_prize.datetime desc')->page($pageIndex.",$number")->select();
        foreach ($list as $k=>$v){
            if($list[$k]['status']==1){
                $list[$k]['statusMsg']="未领奖";
            }else if($list[$k]['status']==2){
                $list[$k]['statusMsg']="领奖中";
            }if($list[$k]['status']==3){
                $list[$k]['statusMsg']="已领奖";
            }
        }
        $count=$grantPrizeTable->where($map)->count();// 查询满足要求的总记录数
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
    //发放奖品
    public function saveGrantPrize(){
        $grantPrizeTable=M('grant_prize');
        $smasheggsrecordTable=M('smasheggsrecord');
        $wechatuserid=I('wechatuserid');
        $prizeid=I('prizeid');
        $data=array(
            'adminuserid' => I('adminuserid'),
            'wechatuserid' => $wechatuserid,
            'prizeid' => $prizeid,
            'datetime' => date('Y-m-d H:i:s',time())
        );
        $add=$grantPrizeTable->add($data);
        $sdata=array(
            'userid' => $wechatuserid,
            'prizeid' => $prizeid,
            'status' => 1,
            'datetime' => date('Y-m-d H:i:s',time())
        );
        $sadd=$smasheggsrecordTable->add($sdata);
        if($add && $sadd){
            $result=array(
                'success'=>true,
                'msg'=>'发放成功',
                'data' => $add
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'发放失败',
                'data' => ''
            );
        }

        $this->ajaxReturn($result);
    }
    //获取奖品列表
    public function getPrizeList(){
        $prizeTable=M('prize');
        $prizeList=$prizeTable->field("id,prize")->order("datetime desc")->select();
        if($prizeList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $prizeList
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
        $userTable=M('user');
        $userList=$userTable->field("id,nickName")->order("datetime desc")->select();
//        $adminUserTable=M('admin_user');
//        foreach ($userList as $k=>$v){
//            $count=$adminUserTable->where("wechatid='{$v['id']}'")->count();
//            if($count>0){
//                $userList[$k]['temp']='(已被绑定)';
//            }else{
//                $userList[$k]['temp']='';
//            }
//        }
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
}