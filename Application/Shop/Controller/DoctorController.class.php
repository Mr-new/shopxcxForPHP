<?php
namespace Shop\Controller;
use Think\Controller;
class DoctorController extends Controller {
    //获取医生列表
    public function getDoctorList(){
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $userid=I('userid');
        $Table=M('shop_doctor');
        $List=$Table->field("id,doctor_name,doctor_title,doctor_face,doctor_card,browse,praise")->order("sort desc")->page($pageIndex.",$number")->select();
        $count=$Table->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($List){
            $praiseTable=M('shop_doctor_praise');
            foreach ($List as $k=>$v){
                $find=$praiseTable->where("doctor_id='{$List[$k]['id']}' and userid=$userid")->getField("id");
                if($find){
                    $List[$k]['isFabulous']=true;
                }else {
                    $List[$k]['isFabulous']=false;
                }
            }
            $images=A('Common/Images');
            $List=$images->getImagesList($List,'doctor_face', 'doctor_face');
            $List=$images->getImagesList($List,'doctor_card', 'doctor_card');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $List
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到所需数据',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //根据医生id获取医生详情
    public function getDoctorDetails(){
        $Table=M('shop_doctor');
        $doctorId=I('id');
        $userid=I('userid');
        $map['id']=$doctorId;
        $Details=$Table->where($map)->field("id,doctor_name,doctor_title,doctor_face,doctor_card,browse,praise,content")->find();
        if($Details){
            $Table->where($map)->setInc("browse");
            $Details['browse']=$Table->where($map)->getField("browse");
            //检测此用户是否点赞
            $praiseTable=M('shop_doctor_praise');
            $find=$praiseTable->where("doctor_id=$doctorId and userid=$userid")->getField("id");
            if($find){
                $Details['isFabulous']=true;
            }else {
                $Details['isFabulous']=false;
            }

            $images=A('Common/Images');
            //拼接图片路径
            $Details=$images->getImagesList($Details,'doctor_face', 'doctor_face');
            $Details=$images->getImagesList($Details,'doctor_card', 'doctor_card');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $Details
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到所需数据',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //点赞
    public function addPraise(){
        $data['userid']=I('userid');
        $data['doctor_id']=I('doctorid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $praiseTable=M('shop_doctor_praise');
        $add=$praiseTable->add($data);
        if($add){
            $doctorTable=M("shop_doctor");
            $doctorTable->where("id='{$data['doctor_id']}'")->setInc('praise');
            $praiseNumber=$doctorTable->where("id='{$data['doctor_id']}'")->getField("praise");
            $result=array(
                'success'=>true,
                'msg'=>'点赞成功',
                'data' => $praiseNumber
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'点赞失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //取消点赞
    public function delPraise(){
        $praiseTable=M('shop_doctor_praise');
        $map['doctor_id']=I('doctorid');
        $map['userid']=I('userid');
        $del=$praiseTable->where($map)->delete();
        if($del){
            $doctorTable=M("shop_doctor");
            $doctorTable->where("id='{$map['doctor_id']}'")->setDec('praise');
            $praiseNumber=$doctorTable->where("id='{$map['doctor_id']}'")->getField("praise");
            $result=array(
                'success'=>true,
                'msg'=>'取消点赞成功',
                'data' => $praiseNumber
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'取消点赞失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }



}