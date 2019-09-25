<?php
namespace Shop\Controller;
use Think\Controller;
class IndexController extends Controller {
    //获取首页热门爆品列表
    public function getHotCommodityList(){
        $commodityTable=M('shop_commodity');
        $map['hot'] = 1;
        $map['isup'] = 1;
//        $map['isseckill'] = 0;  //查找未开启秒杀的商品
        $commodityList=$commodityTable->where($map)->field("id,name,title,thumbnail,salenumber")->order("sort desc")->select();
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'thumbnail', 'thumbnailUrl');
            $specsTable=M('shop_commodity_specs');
            foreach ($commodityList as $k=>$v){
                $commodityList[$k]['specslist']=$specsTable->where("cid='{$commodityList[$k]['id']}'")->field("id,title,price")->order("price desc")->select();
                foreach ($commodityList[$k]['specslist'] as $key=>$value){
                    $commodityList[$k]['specslist'][$key]['price']=(int)$commodityList[$k]['specslist'][$key]['price'];
                }
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
        $userid=I('userid');
        $map['ishome'] = 1;
        $caseList=$caseTable->where($map)->field("id,name,project,imglist,fabulousnumber")->order("sort desc")->select();
        if($caseList){
            $images=A('Common/Images');
            $fabulousTable=M('shop_case_fabulous');
            $processTable=M('shop_case_process');
            foreach ($caseList as $k=>$v){
                //查询最后一次添加的变美记录
                $processList=$processTable->where("caseid='{$caseList[$k]['id']}' and type=1")->order("day desc")->select();
                $temp=$images->spliceIdGetImgList($processList[0],'medialist', 'medialist');
                $caseList[$k]['afterImgUrl']=$temp['medialist'][0];
                if(empty($userid)){
                    $caseList[$k]['isFabulous']=false;
                }else{
                    $find=$fabulousTable->where("caseid='{$caseList[$k]['id']}' and userid=$userid")->getField("id");
                    if($find){
                        $caseList[$k]['isFabulous']=true;
                    }else {
                        $caseList[$k]['isFabulous']=false;
                    }
                }
            }
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