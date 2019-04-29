<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ParticipantController extends BaseController {
    //获取咨询用户列表
    public function getParticipantList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $userTable=M('user');
        $map['nickName']=array('like',"%$select_word%");
        $map['province']=array('like',"%$select_word%");
        $map['city']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
        $map['_logic'] = 'or';
        if($select_word){
            $list=$userTable->where($map)->order('datetime desc')->page($pageIndex.",$number")->select();
            $count=$userTable->where($map)->count();// 查询满足要求的总记录数
        }else{
            $list=$userTable->order('datetime desc')->page($pageIndex.",$number")->select();
            $count=$userTable->count();// 查询满足要求的总记录数
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
        $userTable=M('user');
        $userList=$userTable->order("datetime desc")->field("id,nickname,gender,province,city,tel,datetime")->select();
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