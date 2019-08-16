<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopDoctorController extends BaseController {
    //获取医生列表
    public function getDoctorList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_doctor');
        $map['doctor_name']=array('like',"%$select_word%");
        $map['doctor_title']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $list=$images->getImagesList($list,'doctor_face', 'face');
            $list=$images->getImagesList($list,'doctor_card', 'card');
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
    //修改医生信息
    public function saveDoctor(){
        $Table=M('shop_doctor');
        $id=I('id')?I('id'):null;  //主键id
        $data['doctor_name']=I('doctor_name');
        $data['doctor_title']=I('doctor_title');
        $data['doctor_face']=I('doctor_face')?I('doctor_face'):null;
        $data['doctor_card']=I('doctor_card')?I('doctor_card'):null;
        $data['browse']=I('browse');
        $data['praise']=I('praise');
        $data['content']=$_POST['content'];
        $data['sort']=I('sort')?I('sort'):0;
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
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
            //修改商品信息操作
            $save=$Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $save
            );
        }
        $this->ajaxReturn($result);
    }
    //删除医生
    public function deleteDoctor(){
        $id=I('id');
        $Table=M('shop_doctor');
        $imgId=$Table->where("id=$id")->getField('doctor_face');
        $images=A('Common/Images');
        if($imgId){
            $images->deleteImage($imgId);
        }
        $imgId2=$Table->where("id=$id")->getField('doctor_card');
        if($imgId2){
            $images->deleteImage($imgId2);
        }
        $delSql=$Table->where("id=$id")->delete();
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