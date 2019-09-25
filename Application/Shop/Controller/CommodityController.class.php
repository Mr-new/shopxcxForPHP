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
    //获取推荐商品列表
    public function getRecomdCommodityList(){
        $commodityTable=M('shop_commodity');
        $map['isrecomd']=1;  //查找商家推荐的商品
        $commodityList=$commodityTable->where($map)->field("id,name,title,thumbnail,beforeprice")->order("sort desc")->select();
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'thumbnail', 'thumbnailUrl');
            $specsTable=M('shop_commodity_specs');
            foreach ($commodityList as $k=>$v){
                $commodityList[$k]['specslist']=$specsTable->where("cid='{$commodityList[$k]['id']}'")->field("id,title,price")->order("price desc")->find();
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
        $commodityDetails=$commodityTable->where($map)->field("id,name,title,thumbnail,swiperimg,beforeprice,salenumber,details,isseckill,seckillprice,startdatetime,enddatetime,isbargain,bargainprice,defaultbargainprice,bargainstartdatetime,bargainenddatetime")->find();
        if($commodityDetails){
            $commodityDetails['isseckill']==1 ? $commodityDetails['isseckill']=true : $commodityDetails['isseckill']=false;
            $commodityDetails['isbargain']==1 ? $commodityDetails['isbargain']=true : $commodityDetails['isbargain']=false;
            $commentTable=M('shop_commodity_comment');
            $commodityDetails['comment']=$commentTable->where("shopid='{$commodityDetails['id']}'")->count();
            $images=A('Common/Images');
            //拼接图片路径
            $commodityDetails=$images->getImagesList($commodityDetails,'thumbnail', 'thumbnailUrl');
            //通过逗号分隔id从而查找图片列表
            $commodityDetails=$images->spliceIdGetImgList($commodityDetails,'swiperimg', 'swiperimg');
            $commodityDetails['specslist']=$commoditySpecsTable->where("cid=$commodityId")->field("id,title,price")->order("price desc")->select();
            if(empty($userid)){
                $commodityDetails['isCollection']=false;
            }else{
                //查看用户是否收藏此商品
                if(M('shop_commodity_collection')->where("shopid=$commodityId and userid=$userid")->getField("id")){
                    $commodityDetails['isCollection']=true;
                }else{
                    $commodityDetails['isCollection']=false;
                }
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
    //获取整外专场、微整专场、美肤专场商品列表
    public function getSpecializedList(){
        $commodityTable=M('shop_commodity');
        $specsTable=M('shop_commodity_specs');
        $images=A('Common/Images');
        $map['isup']=1;  //查找已上架的商品
        $imgurl=C('imgurl');
        //获取整外专场商品列表信息
        $list[0]['title']="整外专场";
        $list[0]['banner']=$imgurl."default/zhengwai.jpg";
        $map['categoryid']=22;
        $list[0]['goodsList']=$commodityTable->where($map)->field("id,name,thumbnail")->order("sort desc")->select();
        if($list[0]['goodsList']){
            $list[0]['goodsList']=$images->getImagesList($list[0]['goodsList'],'thumbnail', 'thumbnailUrl');
            foreach ($list[0]['goodsList'] as $k=>$v){
                $list[0]['goodsList'][$k]['price']=$specsTable->where("cid='{$list[0]['goodsList'][$k]['id']}'")->order("price desc")->getField('price');
            }
        }else{
            $list[0]['goodsList']=array();
        }
//        获取微整专场商品列表信息
        $list[1]['title']="微整专场";
        $list[1]['banner']=$imgurl."default/weizheng.jpg";
        $map['categoryid']=21;
        $list[1]['goodsList']=$commodityTable->where($map)->field("id,name,thumbnail")->order("sort desc")->select();
        if($list[1]['goodsList']){
            $list[1]['goodsList']=$images->getImagesList($list[1]['goodsList'],'thumbnail', 'thumbnailUrl');
            foreach ($list[1]['goodsList'] as $k=>$v){
                $list[1]['goodsList'][$k]['price']=$specsTable->where("cid='{$list[1]['goodsList'][$k]['id']}'")->order("price desc")->getField('price');
            }
        }else{
            $list[1]['goodsList']=array();
        }
//        获取美肤专场商品列表信息
        $list[2]['title']="美肤专场";
        $list[2]['banner']=$imgurl."default/meifu.jpg";
        $map['categoryid']=20;
        $list[2]['goodsList']=$commodityTable->where($map)->field("id,name,thumbnail")->order("sort desc")->select();
        if($list[2]['goodsList']){
            $list[2]['goodsList']=$images->getImagesList($list[2]['goodsList'],'thumbnail', 'thumbnailUrl');
            foreach ($list[2]['goodsList'] as $k=>$v){
                $list[2]['goodsList'][$k]['price']=$specsTable->where("cid='{$list[2]['goodsList'][$k]['id']}'")->order("price desc")->getField('price');
            }
        }else{
            $list[2]['goodsList']=array();
        }
        $result=array(
            'success'=>true,
            'msg'=>'查找成功',
            'data' => $list
        );
        $this->ajaxReturn($result);
    }
}