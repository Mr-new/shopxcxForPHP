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
        $List=$Table->field("id,doctor_name,doctor_title,doctor_face,doctor_card,browse,praise,follow")->order("sort desc")->page($pageIndex.",$number")->select();
        $count=$Table->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($List){
            $praiseTable=M('shop_doctor_praise');
            foreach ($List as $k=>$v){
                if(empty($userid)){
                    $List[$k]['isFabulous']=false;
                }else{
                    $find=$praiseTable->where("doctor_id='{$List[$k]['id']}' and userid=$userid")->getField("id");
                    if($find){
                        $List[$k]['isFabulous']=true;
                    }else {
                        $List[$k]['isFabulous']=false;
                    }
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
        $Details=$Table->where($map)->field("id,doctor_name,doctor_title,year,ordernum,score,goodat,doctor_face,doctor_card,browse,praise,follow,content,cert,project,linkcase")->find();
        if($Details){
            $Table->where($map)->setInc("browse");
            $Details['browse']=$Table->where($map)->getField("browse");
            if(empty($userid)){
                $Details['isFabulous']=false;
                $Details['isFollow']=false;
            }else{
                //检测此用户是否点赞
                $praiseTable=M('shop_doctor_praise');
                $find=$praiseTable->where("doctor_id=$doctorId and userid=$userid")->getField("id");
                if($find){
                    $Details['isFabulous']=true;
                }else {
                    $Details['isFabulous']=false;
                }
                //检测用户是否关注此医生
                $followTable=M('shop_follow');
                $followFind=$followTable->where("doctorid=$doctorId and userid=$userid")->getField("id");
                $followFind ? $Details['isFollow']=true : $Details['isFollow']=false;
            }
            $images=A('Common/Images');
            //拼接图片路径
            $Details=$images->getImagesList($Details,'doctor_face', 'doctor_face');
            $Details=$images->getImagesList($Details,'doctor_card', 'doctor_card');
            $Details['score']=(int)$Details['score'];
            //查找此医生所关联的日记列表
            if(!empty($Details['linkcase'])){
                $caseTable=M('shop_case');
                $fabulousTable=M('shop_case_fabulous');
                $tempArr=array();
                $goodsIdArr = explode(',',$Details['linkcase']);
                foreach ($goodsIdArr as $key => $value){
                    $temp=$caseTable->where("id='{$value}'")->field("id,name,imglist,fabulousnumber")->find();
                    if($temp){
                        if(empty($userid)){
                            $temp['isFabulous']=false;
                        }else{
                            $find=$fabulousTable->where("caseid='{$temp['id']}' and userid=$userid")->getField("id");
                            if($find){
                                $temp['isFabulous']=true;
                            }else {
                                $temp['isFabulous']=false;
                            }
                        }

                        $images=A('Common/Images');
                        //通过逗号分隔id从而查找图片列表
                        $temp=$images->spliceIdGetImgList($temp,'imglist', 'imglist');
                    }
                    array_push($tempArr,$temp);
                }
                $Details['caseList']=$tempArr;
            }else{
                $Details['caseList']="";
            }
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
    //关注医生操作
    public function addFollow(){
        $Table=M('shop_follow');
        $data['doctorid']=I('doctorid');
        $data['userid']=I('userid');
        //查看该用户是否关注过该医生
        $find=$Table->where($data)->find();
        if($find){
            $result=array(
                'success'=>false,
                'msg'=>'您已经关注过Ta了哟！',
                'data' => $find
            );
        }else{
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                //该医生粉丝+1
                $doctTable=M('shop_doctor');
                $doctTable->where("id='{$data['doctorid']}'")->setInc('follow');
                //获取该医生粉丝数量
                $follow=$doctTable->where("id='{$data['doctorid']}'")->getField("follow");
                $result=array(
                    'success'=>true,
                    'msg'=>'关注成功',
                    'data' => $follow
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'关注失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //取消关注医生操作
    public function delFollow(){
        $Table=M('shop_follow');
        $map['doctorid']=I('doctorid');
        $map['userid']=I('userid');
        $del=$Table->where($map)->delete();
        if($del){
            //该医生粉丝-1
            $doctTable=M('shop_doctor');
            $doctTable->where("id='{$map['doctorid']}'")->setDec('follow');
            //获取该医生粉丝数量
            $follow=$doctTable->where("id='{$map['doctorid']}'")->getField("follow");
            $result=array(
                'success'=>true,
                'msg'=>'取消关注成功',
                'data' => $follow
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'取消关注失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }



}