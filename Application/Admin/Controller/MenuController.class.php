<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class MenuController extends BaseController {
    //获取菜单列表
    public function getMenuList(){
        $userId=I('userId');
        $jurisdictionTable=M('admin_jurisdiction');
        $jurisdictionStr=$jurisdictionTable->where("userid=$userId")->getField("menuid");  //获取菜单id
        if($jurisdictionStr){
            $jurisdictionIdList = explode(',',$jurisdictionStr);
            $menuTable=M("admin_menu");
            $arr=array();
            foreach ($jurisdictionIdList as $k=>$v){
                $find=$menuTable->where("id=$v")->order("id asc")->find();
                if(!empty($find)){
                    array_push($arr,$find);
                }
            }
            $arr=$this->getMenuSub($arr);  //递归查询子菜单
//            foreach ($arr as $k=>$v){
//                $subs=$menuTable->where("pid={$v['id']}")->select();
//                if(!empty($subs)) {
//                    foreach ($subs as $kk=>$vv){
//                        if(in_array($subs[$kk]['id'], $jurisdictionIdList)){
//                            $arr[$k]['subs'][$kk]=$subs[$kk];
//                        }
//                    }
//                }
//                foreach ($arr[$k]['subs'] as $key=>$val){
//                    $subsThree=$menuTable->where("pid={$val['id']}")->select();
//                    if(!empty($subsThree)){
//                        foreach ($subsThree as $kkk=>$vvv){
//                            if(in_array($subsThree[$kkk]['id'], $jurisdictionIdList)){
//                                $arr[$k]['subs'][$key]['subs'][$kkk]=$subsThree[$kkk];
//                            }
//                        }
//                    }
//                }
//            }
            if($arr){
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => $arr
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => ''
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有查询到此用户的权限',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //递归查找子级菜单
    private function getMenuSub($data, $id=0) {
        $list = array();
        foreach($data as $v) {
            if($v['pid'] == $id) {
                $v['subs'] = $this->getMenuSub($data, $v['id']);
                if(empty($v['subs'])) {
                    unset($v['subs']);
                }
                array_push($list, $v);
            }
        }
        return $list;
    }
}