<?php
namespace Shop\Controller;
use Think\Controller;
class CaseController extends Controller {
    //获取日记菜单列表
    public function getCaseMenuList(){
        $caseMenuTable=M("shop_casemenu");
        $caseMenuList=$caseMenuTable->order("sort desc")->field("id,imgid,title")->select();
        if($caseMenuList){
            $images=A('Common/Images');
            $caseMenuList=$images->getImagesList($caseMenuList,'imgid', 'imgUrl');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $caseMenuList
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
    //获取日记列表
    public function getCaseList(){
        $caseTable=M('shop_case');
        $casemenuid=I("casemenuid");  //类别id
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $searchValue=I("searchValue")?I("searchValue"):null;  //搜索内容
        $userid=I('userid');
        if(!empty($casemenuid)){
            $map['casemenuid']= $casemenuid;
        }
        $map['name']= array('like',"%$searchValue%");  //查找关键字
        $caseList=$caseTable->join("wechat_shop_casemenu ON wechat_shop_case.casemenuid = wechat_shop_casemenu.id")->where($map)->field("wechat_shop_case.id,wechat_shop_case.name,wechat_shop_case.headerimg,wechat_shop_casemenu.title,wechat_shop_case.imglist,wechat_shop_case.address,wechat_shop_case.looknumber,wechat_shop_case.fabulousnumber,wechat_shop_case.datetime")->order("wechat_shop_case.sort desc")->page($pageIndex.",$number")->select();
        $count=$caseTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($caseList){
            $fabulousTable=M('shop_case_fabulous');
            foreach ($caseList as $k=>$v){
                $find=$fabulousTable->where("caseid='{$caseList[$k]['id']}' and userid=$userid")->getField("id");
                if($find){
                    $caseList[$k]['isFabulous']=true;
                }else {
                    $caseList[$k]['isFabulous']=false;
                }
            }
            $images=A('Common/Images');
            //通过图片id查找图片地址
            $caseList=$images->getImagesList($caseList,'headerimg', 'headerimg');
            //通过逗号分隔id从而查找图片列表
            $caseList=$images->spliceIdGetImgList($caseList,'imglist', 'imglist');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $caseList
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
    //根据日记id获取日记详情
    public function getCaseDetails(){
        $caseTable=M('shop_case');
        $caseId=I('id');
        $userid=I('userid');
        $map['id']=$caseId;
        $caseDetails=$caseTable->where($map)->field("id,headerimg,name,looknumber,fabulousnumber,remarks,details,datetime")->find();
        if($caseDetails){
            $caseTable->where($map)->setInc("looknumber");
            $caseDetails['looknumber']=$caseTable->where($map)->getField("looknumber");
            $images=A('Common/Images');
            //拼接图片路径
            $commodityDetails=$images->getImagesList($caseDetails,'headerimg', 'headerimg');
            //查看用户是否收藏此日记
            if(M('shop_case_collection')->where("caseid=$caseId and userid=$userid")->getField("id")){
                $commodityDetails['isCollection']=true;
            }else{
                $commodityDetails['isCollection']=false;
            }
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityDetails
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
    public function addfabulous(){
        $data['userid']=I('userid');
        $data['caseid']=I('caseid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $fabulousTable=M('shop_case_fabulous');
        $add=$fabulousTable->add($data);
        if($add){
            $caseTable=M("shop_case");
            $caseTable->where("id='{$data['caseid']}'")->setInc('fabulousnumber');
            $fabulousnumber=$caseTable->where("id='{$data['caseid']}'")->getField("fabulousnumber");
            $result=array(
                'success'=>true,
                'msg'=>'点赞成功',
                'data' => $fabulousnumber
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
    public function delfabulous(){
        $fabulousTable=M('shop_case_fabulous');
        $map['caseid']=I('caseid');
        $map['userid']=I('userid');
        $del=$fabulousTable->where($map)->delete();
        if($del){
            $caseTable=M("shop_case");
            $caseTable->where("id='{$map['caseid']}'")->setDec('fabulousnumber');
            $fabulousnumber=$caseTable->where("id='{$map['caseid']}'")->getField("fabulousnumber");
            $result=array(
                'success'=>true,
                'msg'=>'取消点赞成功',
                'data' => $fabulousnumber
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