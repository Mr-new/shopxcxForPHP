<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepParticipantController extends BaseController {
    //获取参与者用户信息
    public function getStepParticipantList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $userTable=M('step_user');
        $map['nickName']=array('like',"%$select_word%");
        $map['province']=array('like',"%$select_word%");
        $map['city']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
        $map['_logic'] = 'or';
        if($select_word){
            $list=$userTable->where($map)->order('datetime desc')->page($pageIndex.",$number")->select();
            $count=$userTable->where($map)->count();// 查询满足要求的总记录数
        }else{
            $list=$userTable->where("nickName!=''")->order('datetime desc')->page($pageIndex.",$number")->select();
            $count=$userTable->where("nickName!=''")->count();// 查询满足要求的总记录数
        }
        $stepRecordTable=M('step_record');
        foreach ($list as $k=>$v){
            $stepRecordList=$stepRecordTable->where("userid='{$list[$k]['id']}'")->getField("step",true);
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
        $userTable=M('step_user');
        $userList=$userTable->order("datetime desc")->field("id,nickname,gender,province,city,tel,datetime")->select();
        $stepRecordTable=M('step_record');
        foreach ($userList as $k=>$v){
            $stepRecordList=$stepRecordTable->where("userid='{$userList[$k]['id']}'")->getField("step",true);
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