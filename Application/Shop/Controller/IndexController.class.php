<?php
namespace Shop\Controller;
use Think\Controller;
class IndexController extends Controller {
    //获取首页热门爆品列表
    public function getHotCommodityList(){
        $commodityTable=M('shop_commodity');
        $map['hot'] = 1;
        $map['isup'] = 1;
        $commodityList=$commodityTable->where($map)->field("id,name,title,thumbnail")->order("sort desc")->select();
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'thumbnail', 'thumbnailUrl');
            $specsTable=M('shop_commodity_specs');
            foreach ($commodityList as $k=>$v){
                $commodityList[$k]['specslist']=$specsTable->where("cid='{$commodityList[$k]['id']}'")->field("id,title,price")->order("price desc")->select();
            }
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityList
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
    //获取首页日记列表
    public function getHomeCaseList(){
        $caseTable=M("shop_case");
        $map['ishome'] = 1;
        $caseList=$caseTable->where($map)->field("id,headerimg,name,imglist,remarks,doctor")->order("sort desc")->select();
        if($caseList){
            $images=A('Common/Images');
            $caseList=$images->getImagesList($caseList,'headerimg', 'headerimg');
            //通过逗号分隔id从而查找图片列表
            $caseList=$images->spliceIdGetImgList($caseList,'imglist', 'imglist');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $caseList
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