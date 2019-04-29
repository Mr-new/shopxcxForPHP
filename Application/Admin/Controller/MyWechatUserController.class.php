<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class MyWechatUserController extends BaseController {
    //获取微信用户列表
    public function getWechatUserList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $adminuserid=I('adminuserid');
        $userTable=M('admin_user');
        $wechatId=$userTable->where("id=$adminuserid")->getField("wechatid");
        $wechatUserTable=M('user');
        $map['topId']=$wechatId;
        $map['_logic'] = 'and';
        if($select_word){
            $map['nickName']=array('like',"%$select_word%");
        }
        $list=$wechatUserTable->where($map)->order('datetime desc')->page($pageIndex.",$number")->field("id,nickName,province,city,gender,avatarurl,tel,datetime")->select();
        $count=$wechatUserTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        foreach ($list as $k=>$v){
            if($list[$k]['gender']==0){
                $list[$k]['sex']="未知";
            }else if($list[$k]['gender']==1){
                $list[$k]['sex']="男";
            }else if($list[$k]['gender']==2){
                $list[$k]['sex']="女";
            }
        }
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $list,
                    'count' => $count,
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
    //获取角色列表
    public function getRoleList(){
        $roleTable=M('admin_role');
        $roleList=$roleTable->order("datetime desc")->select();
        if($roleList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $roleList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => '没有找到您需要的数据哟'
            );
        }
        $this->ajaxReturn($result);
    }
    //获取菜单列表
    public function getMenuList(){
        $adminMenuTable=M('admin_menu');
        $menuList=$adminMenuTable->field("id,title,pid")->select();
        if($menuList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $menuList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => '没有找到您需要的数据哟'
            );
        }
        $this->ajaxReturn($result);
    }
    //获取微信用户列表
    public function wechatUserList(){
        $userTable=M('user');
        $userList=$userTable->field("id,nickName")->order("datetime desc")->select();
        $adminUserTable=M('admin_user');
        foreach ($userList as $k=>$v){
            $count=$adminUserTable->where("wechatid='{$v['id']}'")->count();
            if($count>0){
                $userList[$k]['temp']='(已被绑定)';
            }else{
                $userList[$k]['temp']='';
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
                'msg'=>'请求失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }

    //修改用户信息
    public function saveAdminUser(){
        $userTable=M('admin_user');
        $adminJurisdictionTable=M('admin_jurisdiction');
        $id=I('id')?I('id'):null;  //主键id
        $imgId=I('picid')?I('picid'):null;  //图片id
        $username=I('username');
        $weChatId=I('weChatId')?I('weChatId'):null;
        $password=I('password');
        $roleid=I('roleid');
        $menuId=I('menuId');
        if(empty($username)){
            $result=array(
                'success'=>false,
                'msg'=>'添加失败',
                'data' => '用户名不能为空'
            );
            $this->ajaxReturn($result);
        }else if(empty($password)){
            $result=array(
                'success'=>false,
                'msg'=>'添加失败',
                'data' => '密码不能为空'
            );
            $this->ajaxReturn($result);
        }else if(empty($roleid)){
            $result=array(
                'success'=>false,
                'msg'=>'添加失败',
                'data' => '角色不能为空'
            );
            $this->ajaxReturn($result);
        }else{
            $userData['username']=$username;
            $userData['password']=$password;
            $userData['picid']=$imgId;
            $userData['roleid']=$roleid;
            $userData['wechatid']=$weChatId;
            $userData['datetime']=date('Y-m-d H:i:s',time());
            if(empty($id)){
                //添加
                $add=$userTable->add($userData);
                if($add){
                    $Jdata['userid']=$add;
                    $Jdata['menuid']='';
                    for($i=0;$i<count($menuId);$i++){
                        if(!empty($Jdata['menuid'])){
                            $Jdata['menuid']=$Jdata['menuid'].','.$menuId[$i];
                        }else{
                            $Jdata['menuid']=$Jdata['menuid'].$menuId[$i];
                        }
                    }
                    $Jdata['datetime']=date('Y-m-d H:i:s',time());
                    $adminJurisdictionTable->add($Jdata);
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
                $save=$userTable->where("id=$id")->save($userData);
                if($save){
                    $JDdata['menuid']='';
                    for($i=0;$i<count($menuId);$i++){
                        if(!empty($JDdata['menuid'])){
                            $JDdata['menuid']=$JDdata['menuid'].','.$menuId[$i];
                        }else{
                            $JDdata['menuid']=$JDdata['menuid'].$menuId[$i];
                        }
                    }
                    $JDdata['datetime']=date('Y-m-d H:i:s',time());
                    $adminJurisdictionTable->where("userid=$id")->save($JDdata);
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
        }
        $this->ajaxReturn($result);
    }
    //删除管理员用户
    public function deleteAdminUser(){
        $id=I('id');
        $adminUserTable=M('admin_user');
        $adminJurisdiction=M('admin_jurisdiction');
        $imgId=$adminUserTable->where("id=$id")->getField('picid');
        $images=A('Common/Images');
        $images->deleteImage($imgId);
        $delSql=$adminUserTable->where("id=$id")->delete();
        $delJDSql=$adminJurisdiction->where("userid=$id")->delete();
        if($delSql && $delJDSql){
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