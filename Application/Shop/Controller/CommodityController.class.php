<?php
namespace Shop\Controller;
use Think\Controller;
class CommodityController extends Controller {
    //获取商品分类列表
    public function getCommodityMenuList(){
        $categoryTable=M('shop_commodity_category');
        $categoryList=$categoryTable->field("id,title")->order("sort desc")->select();
        if($categoryList){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $categoryList
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
    //获取商品列表
    public function getCommodityList(){
        $commodityTable=M('shop_commodity');
        $categoryid=I("categoryid");
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $searchValue=I("searchValue")?I("searchValue"):null;
        if(!empty($categoryid)){
            $map['categoryid']= $categoryid;
        }
        $map['name']= array('like',"%$searchValue%");  //查找关键字
        $map['isup']=1;  //查找已上架的商品
        $commodityList=$commodityTable->where($map)->field("id,name,title,thumbnail,beforeprice")->order("sort desc")->page($pageIndex.",$number")->select();
        $count=$commodityTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
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
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $commodityList
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
    //根据商品id获取商品详情
    public function getCommodityDetailsList(){
        $commodityTable=M('shop_commodity');
        $commoditySpecsTable=M('shop_commodity_specs');
        $commodityId=I('id');
        $userid=I('userid');
        $map['id']=$commodityId;
        $commodityDetails=$commodityTable->where($map)->field("id,name,title,thumbnail,swiperimg,beforeprice,salenumber,details")->find();
        if($commodityDetails){
            $commentTable=M('shop_commodity_comment');
            $commodityDetails['comment']=$commentTable->where("shopid='{$commodityDetails['id']}'")->count();
            $images=A('Common/Images');
            //拼接图片路径
            $commodityDetails=$images->getImagesList($commodityDetails,'thumbnail', 'thumbnailUrl');
            //通过逗号分隔id从而查找图片列表
            $commodityDetails=$images->spliceIdGetImgList($commodityDetails,'swiperimg', 'swiperimg');
            $commodityDetails['specslist']=$commoditySpecsTable->where("cid=$commodityId")->field("id,title,price")->order("price desc")->select();
            //查看用户是否收藏此商品
            if(M('shop_commodity_collection')->where("shopid=$commodityId and userid=$userid")->getField("id")){
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
    //根据商品id获取此商品的评论列表
    public function getCommentList(){
        $commentTable=M('shop_commodity_comment');
        $shopid=I('shopid');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $map['shopid']=$shopid;
        $commentList=$commentTable->where($map)->order("datetime desc")->page($pageIndex.",$number")->field("id,userid,xing,content,imglist,datetime")->select();
        $count=$commentTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($commentList){
            $images=A('Common/Images');
            $userTable=M('shop_user');
            $str=A("Api/Index");
            foreach ($commentList as $k=>$v){
                $commentList[$k]=$images->spliceIdGetImgList($commentList[$k],'imglist','imglist');
                $commentList[$k]['userinfo']=$userTable->where("id='{$commentList[$k]['userid']}'")->field("nickName,avatarUrl")->find();
                $commentList[$k]['userinfo']['nickname']=$str->hideStr($commentList[$k]['userinfo']['nickname'],1,1,4);
                $commentList[$k]['xing']=(int)$commentList[$k]['xing'];
            }
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $commentList
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
}