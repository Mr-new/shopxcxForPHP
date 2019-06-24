<?php
namespace Shop\Controller;
use Think\Controller;
class CollectionController extends Controller {
    //加入商品收藏
    public function addShopCollection(){
        $collectionTable=M("shop_commodity_collection");
        $data['shopid']=I('shopid');
        $data['userid']=I('userid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $add=$collectionTable->add($data);
        if($add){
            $result=array(
                'success'=>true,
                'msg'=>'加入收藏成功',
                'data' => $add
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'加入收藏失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //取消商品收藏
    public function deleteShopCollection(){
        $collectionTable=M("shop_commodity_collection");
        $map['shopid']=I('shopid');
        $map['userid']=I('userid');
        $del=$collectionTable->where($map)->delete();
        if($del){
            $result=array(
                'success'=>true,
                'msg'=>'取消收藏成功',
                'data' => $del
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'取消收藏失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //加入日记收藏
    public function addCaseCollection(){
        $collectionTable=M("shop_case_collection");
        $data['caseid']=I('caseid');
        $data['userid']=I('userid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $add=$collectionTable->add($data);
        if($add){
            $result=array(
                'success'=>true,
                'msg'=>'加入收藏成功',
                'data' => $add
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'加入收藏失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //取消日记收藏
    public function deleteCaseCollection(){
        $collectionTable=M("shop_case_collection");
        $map['caseid']=I('caseid');
        $map['userid']=I('userid');
        $del=$collectionTable->where($map)->delete();
        if($del){
            $result=array(
                'success'=>true,
                'msg'=>'取消收藏成功',
                'data' => $del
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'取消收藏失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取商品收藏列表
    public function getShopCollectionList(){
        $collectionTable=M('shop_commodity_collection');
        $userid=I('userid');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $map['userid']=$userid;
        $shopCollectionList=$collectionTable->join("wechat_shop_commodity ON wechat_shop_commodity_collection.shopid = wechat_shop_commodity.id")->where($map)->field("wechat_shop_commodity.id,wechat_shop_commodity.name,wechat_shop_commodity.title,wechat_shop_commodity.thumbnail")->order("wechat_shop_commodity_collection.datetime desc")->page($pageIndex.",$number")->select();
        $count=$collectionTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($shopCollectionList){
            $specsTable=M('shop_commodity_specs');
            foreach ($shopCollectionList as $k=>$v){
                $shopCollectionList[$k]['specsList']=$specsTable->where("cid='{$shopCollectionList[$k]['id']}'")->field("title,price")->order("price asc")->select();
            }
            $images=A('Common/Images');
            //拼接图片路径
            $shopCollectionList=$images->getImagesList($shopCollectionList,'thumbnail', 'thumbnail');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $shopCollectionList
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到数据',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取日记收藏列表
    public function getCaseCollectionList(){
        $collectionTable=M('shop_case_collection');
        $userid=I('userid');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $map['userid']=$userid;
        $caseCollectionList=$collectionTable->join("wechat_shop_case ON wechat_shop_case_collection.caseid = wechat_shop_case.id")->where($map)->field("wechat_shop_case.id,wechat_shop_case.headerimg,wechat_shop_case.name,wechat_shop_case.imglist,wechat_shop_case.address,wechat_shop_case.casemenuid,wechat_shop_case.looknumber,wechat_shop_case.fabulousnumber,wechat_shop_case.datetime")->order("wechat_shop_case_collection.datetime desc")->page($pageIndex.",$number")->select();
        $count=$collectionTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($caseCollectionList){
            $caseMenuTable=M('shop_casemenu');
            $fabulousTable=M('shop_case_fabulous');
            foreach ($caseCollectionList as $k=>$v){
                $caseCollectionList[$k]['menu']=$caseMenuTable->where("id='{$caseCollectionList[$k]['casemenuid']}'")->getField("title");
                $find=$fabulousTable->where("caseid='{$caseCollectionList[$k]['id']}' and userid=$userid")->getField("id");
                if($find){
                    $caseCollectionList[$k]['isFabulous']=true;
                }else {
                    $caseCollectionList[$k]['isFabulous']=false;
                }
            }
            $images=A('Common/Images');
            //拼接图片路径
            $caseCollectionList=$images->getImagesList($caseCollectionList,'headerimg', 'headerimg');
            //通过逗号分隔id从而查找图片列表
            $caseCollectionList=$images->spliceIdGetImgList($caseCollectionList,'imglist', 'imglist');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $caseCollectionList
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到数据',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}