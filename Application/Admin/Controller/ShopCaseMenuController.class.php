<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopCaseMenuController extends BaseController {
    //获取日记分类列表
    public function getCaseMenuList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $menuTable=M('shop_casemenu');
        $map['title']=array('like',"%$select_word%");
        $list=$menuTable->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$menuTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $slist=$images->getImagesList($list,'imgid', 'b_image');
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $slist
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
    //修改日记分类
    public function saveCaseMenu(){
        $menuTable=M('shop_casemenu');
        $id=I('id')?I('id'):null;  //主键id
        $imgId=I('imgid')?I('imgid'):null;  //图片id
        $data['title']=I('title');
        $data['sort']=I('sort')?I('sort'):0;
        $data['datetime']=date('Y-m-d H:i:s',time());
        if(empty($id)){
            $data['imgid']=$imgId;
            $add=$menuTable->add($data);
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
        }else{
            $save=$menuTable->where("id=$id")->save($data);
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
    //删除日记分类
    public function deleteCaseMenu(){
        $id=I('id');
        $menuTable=M('shop_casemenu');
        $imgId=$menuTable->where("id=$id")->getField('imgid');
        if($imgId){
            $images=A('Common/Images');
            $images->deleteImage($imgId);
        }
        $delSql=$menuTable->where("id=$id")->delete();
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