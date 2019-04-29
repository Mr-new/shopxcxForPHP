<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ConsultationController extends BaseController {
    //获取咨询用户列表
    public function getConsultationList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $userTable=M('admin_user');
        $wechatUserTable=M('user');
        $map['username']=array('like',"%$select_word%");
        $map['roleid']=8;
        $map['_logic'] = 'and';
        $list=$userTable->join("wechat_user ON wechat_admin_user.wechatid=wechat_user.id")->where($map)->field("wechat_admin_user.id,wechat_admin_user.username,wechat_admin_user.wechatid,wechat_admin_user.datetime,wechat_user.nickname,wechat_user.avatarurl,wechat_user.tel")->order('wechat_admin_user.datetime desc')->page($pageIndex.",$number")->select();
        foreach ($list as $k=>$v){
            $list[$k]['num']=$wechatUserTable->where("topid={$v['wechatid']}")->count();
            $list[$k]['sub']=$wechatUserTable->where("topid={$v['wechatid']}")->order("datetime desc")->select();
            foreach ($list[$k]['sub'] as $key=>$value){
                if($list[$k]['sub'][$key]['gender']==1){
                    $list[$k]['sub'][$key]['sex']="男";
                }else if($list[$k]['sub'][$key]['gender']==2){
                    $list[$k]['sub'][$key]['sex']="女";
                }else{
                    $list[$k]['sub'][$key]['sex']="未知";
                }

            }
        }
        $count=$userTable->where($map)->count();// 查询满足要求的总记录数
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

}