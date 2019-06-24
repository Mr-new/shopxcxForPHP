<?php
namespace Shop\Controller;
use Think\Controller;
class BannerController extends Controller {
    //获取banner轮播图列表
    public function getBannerList(){
        $bannerTable=M("shop_banner");
        $bannerList=$bannerTable->order("sort desc")->select();
        if($bannerList){
            $images=A('Common/Images');
            $bannerList=$images->getImagesList($bannerList,'imgid', 'imgUrl');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $bannerList
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
    //根据轮播图id获取轮播图详情内容
    public function getBannerDetails(){
        $bannerTable=M("shop_banner");
        $id=I('id');
        $Details=$bannerTable->where("id=$id")->field("id,title,details")->find();
        if($Details){
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
}