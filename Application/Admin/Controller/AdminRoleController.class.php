<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class AdminRoleController extends BaseController {
    //获取角色列表
    public function getRoleList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $adminRoleTable=M('admin_role');
        $map['title']=array('like',"%$select_word%");
        $list=$adminRoleTable->where($map)->order('datetime desc')->page($pageIndex.",$number")->select();
        $count=$adminRoleTable->where($map)->count();// 查询满足要求的总记录数
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
    //修改角色信息
    public function saveAdminRole(){
        $adminRoleTable=M('admin_role');
        $id=I('id')?I('id'):null;  //主键id
        $title=I('title');
        $roleData['title']=$title;
        $roleData['datetime']=date('Y-m-d H:i:s',time());
        if(empty($id)){
            //添加
            $add=$adminRoleTable->add($roleData);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $add,
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'添加失败',
                    'data' => ''
                );
            }
        }else{
            //修改
            $save=$adminRoleTable->where("id=$id")->save($roleData);
            if($save){
                $result=array(
                    'success'=>true,
                    'msg'=>'修改成功',
                    'data' => $id
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'修改失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //删除角色
    public function deleteAdminRole(){
        $id=I('id');
        $adminRoleTable=M('admin_role');
        $delSql=$adminRoleTable->where("id=$id")->delete();
        if($delSql){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $delSql
            );
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}